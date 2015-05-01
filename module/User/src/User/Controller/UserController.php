<?php

namespace User\Controller;

use Zend\Crypt\Password\Bcrypt;
use Zend\Form\Form;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\View\Model\ViewModel;
use User\Service\User as UserService;
use User\Options\UserControllerOptionsInterface;
use Application\Controller\BaseController;

class UserController extends BaseController
{

    const ROUTE_LOGIN = 'user/login';
    const ROUTE_PROFILE = 'user/profile';
    const ROUTE_REGISTER = 'user/register';

    const CONTROLLER_NAME = 'user';


    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var Form
     */
    protected $loginForm;

    /**
     * @var Form
     */
    protected $registerForm;

    /**
     * @var Form
     */
    protected $changePasswordForm;

    /**
     * @var Form
     */
    protected $changeEmailForm;

    /**
     * @todo Make this dynamic / translation-friendly
     * @var string
     */
    protected $failedLoginMessage = 'Authentication failed. Please try again.';

    /**
     * @var UserControllerOptionsInterface
     */
    protected $options;
    protected $username;

    /**
     * User page
     */
    public function indexAction()
    {
        if($this->isUser()) return $this->isUser();

        return new ViewModel();
    }

    /**
     * Profile User
     */
    public function profileAction()
    {
        if($this->isUser()) return $this->isUser();
        $adverts = $this->em('Advert\Entity\Advert')->getCompanyAdverts($this->getCompanyId());

        return new ViewModel(array(
            'bundlePayments' => $this->em('Company\Entity\BundlePayments')->findOneBy(array('company_id' => $this->getCompanyId()), array('created_at' => 'DESC')),
            'company' => $this->em('Company\Entity\Company')->find($this->getCompanyId()),
            'adverts' => $adverts
        ));
    }

    /**
     * Login form
     */
    public function loginAction()
    {
        $request = $this->getRequest();
        $form = $this->getLoginForm();

        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $request->getQuery()->get('redirect')) {
            $redirect = $request->getQuery()->get('redirect');
        } else {
            $redirect = false;
        }

        if (!$request->isPost()) {
            return array(
                'loginForm' => $form,
                'redirect' => $redirect,
                'enableRegistration' => $this->getOptions()->getEnableRegistration(),
            );
        }

        $post = $request->getPost();

        $post->set('credential', trim($post->get('credential')));
        $form->setData($post);

        if (!$form->isValid()) {
            $this->flashMessenger()->addErrorMessage($this->failedLoginMessage);
            return $this->redirect()->toUrl($this->url()->fromRoute(static::ROUTE_LOGIN) . ($redirect ? '?redirect=' . $redirect : ''));
        }

        // clear adapters
        $this->UserAuthentication()->getAuthAdapter()->resetAdapters();
        $this->UserAuthentication()->getAuthService()->clearIdentity();


        return $this->forward()->dispatch(static::CONTROLLER_NAME, array('action' => 'authenticate'));
    }

    /**
     * Logout and clear the identity
     */
    public function logoutAction()
    {

        $this->UserAuthentication()->getAuthAdapter()->resetAdapters();
        $this->UserAuthentication()->getAuthAdapter()->logoutAdapters();
        $this->getServiceLocator()->get('user_remember')->forgetMe();
        $this->UserAuthentication()->getAuthService()->clearIdentity();

        $sessionManager = new \Zend\Session\SessionManager();
        $sessionManager->forgetMe();

        $redirect = $this->params()->fromPost('redirect', $this->params()->fromQuery('redirect', false));

        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $redirect) {
            return $this->redirect()->toUrl($redirect);
        }

        return $this->redirect()->toRoute($this->getOptions()->getLogoutRedirectRoute());
    }

    /**
     * General-purpose authentication action
     */
    public function authenticateAction()
    {

        if ($this->UserAuthentication()->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }
        $adapter = $this->UserAuthentication()->getAuthAdapter();
        $redirect = $this->params()->fromPost('redirect', $this->params()->fromQuery('redirect', false));

        $result = $adapter->prepareForAuthentication($this->getRequest());

        // Return early if an adapter returned a response
        if ($result instanceof Response) {
            return $result;
        }

        $auth = $this->UserAuthentication()->getAuthService()->authenticate($adapter);

        if (!$auth->isValid()) {
            $this->flashMessenger()->addErrorMessage($this->failedLoginMessage);
            $adapter->resetAdapters();
            return $this->redirect()->toUrl($this->url()->fromRoute(static::ROUTE_LOGIN)
                . ($redirect ? '?redirect=' . $redirect : ''));
        }

        return $this->redirect()->toRoute('user/profile');
    }

    public function changePasswordAction()
    {
        return new ViewModel(array());
    }

    /**
     * Logout and clear the identity
     */
    public function resetAction() {
        if($this->UserAuthentication()->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute('user/profile');
        }

        $returnUri = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $this->url()->fromRoute('user/login');

        $request = $this->getRequest();
        $email = $this->params('email') ? $this->params('email') : $this->params()->fromPost('email');
        $user = $this->em('User\Entity\User')->findOneBy(array('email' => $email));

        if($request->getPost('email')) {
            if(!$user) {
                $this->flashMessenger()->addErrorMessage($email . ' nie istnieje w naszej bazie danych');
                return $this->redirect()->toUrl($this->url()->fromRoute('user/changepassword'));
            }

            $mail = new Message();
            $mail->setFrom('support@recmetals.com', 'RecMetals.com');
            $mail->addTo($user->getEmail(), $user->getFirstName() .' '. $user->getLastName());
            $mail->setEncoding('ISO-8859-2');

            $bcrypt = new Bcrypt();
            $bcrypt->setCost(14);

            $newPassword = $this->generatePassword();

            $user->setPassword($bcrypt->create($newPassword));
            $this->em()->persist($user);
            $this->em()->flush();

            $mail->setSubject('Nowe Hasło na RecMetals.com.');
            $mail->setBody("Witaj " .$user->getFirstName() . "!\n\n" .
                "Twoje hasło zostało zresetowane na Recmetals.com. Wygenerowaliśmy dla Ciebie nowe hasło:\n" .
                $newPassword . "\n Zmień hasło po zalogowaniu.\n\n" .
                "Zespół RecMetals.com!");

            $transport = new SmtpTransport();
            $options = new SmtpOptions(array(
                'host' => 'mymark.nazwa.pl',
                'connection_class' => 'login',
                'connection_config' => array(
                    'username' => 'support@mymark.nazwa.pl',
                    'password' => 'Qwedsazxc123'
                ),
                'port' => 25,
            ));
            $transport->setOptions($options);
            $transport->send($mail);

            $this->flashMessenger()->addSuccessMessage('Twoje nowe hasło zostało wysłane na Twoją skrzynkę pocztą. Prosze sprawdź skrzynkę pocztową.');
        } else {
            return $this->redirect()->toUrl($returnUri);
        }

        return $this->redirect()->toUrl($this->url()->fromRoute('user/login'));
    }

    public function getUserService()
    {
        if (!$this->userService) {
            $this->userService = $this->getServiceLocator()->get('user_user_service');
        }
        return $this->userService;
    }

    public function setUserService(UserService $userService)
    {
        $this->userService = $userService;
        return $this;
    }

    public function getRegisterForm()
    {
        if (!$this->registerForm) {
            $this->setRegisterForm($this->getServiceLocator()->get('user_register_form'));
        }
        return $this->registerForm;
    }

    public function setRegisterForm(Form $registerForm)
    {
        $this->registerForm = $registerForm;
    }

    public function getLoginForm()
    {
        if (!$this->loginForm) {
            $this->setLoginForm($this->getServiceLocator()->get('user_login_form'));
        }
        return $this->loginForm;
    }

    public function setLoginForm(Form $loginForm)
    {
        $this->loginForm = $loginForm;
        $fm = $this->flashMessenger()->setNamespace('user-login-form')->getMessages();
        if (isset($fm[0])) {
            $this->loginForm->setMessages(
                array('identity' => array($fm[0]))
            );
        }
        return $this;
    }

    public function setOptions(UserControllerOptionsInterface $options)
    {
        $this->options = $options;
        return $this;
    }

    public function getOptions()
    {
        if (!$this->options instanceof UserControllerOptionsInterface) {
            $this->setOptions($this->getServiceLocator()->get('user_module_options'));
        }
        return $this->options;
    }

    private function generatePassword($length = 12) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }

        return $result;
    }
}
