<?php

namespace User\Controller;

use Zend\Form\Form;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\Stdlib\Parameters;
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
        $companyId = $this->user()->getIdentity()->getCompany_id();

        return new ViewModel(array(
            'bundlePayments' => $this->em('Company\Entity\BundlePayments')->findOneBy(array('company_id' => $companyId), array('created_at' => 'DESC')),
            'company' => $this->em('Company\Entity\Company')->find($companyId)
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

        $email = $this->params('email') ? $this->params('email') : $this->params()->fromPost('email');
        $hash = $this->params('hash');
        $request = $this->getRequest();
        $hash_exists = false;

        $returnUri = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $this->url()->fromRoute('user/login');

        $host = $this->getRequest()->getServer('HTTP_HOST');

        if($hash) {
            $user = $this->em('User\Entity\User')->findOneBy(array('email' => $email, 'password_recovery_hash' => $hash));
        } else {
            $user = $this->em('User\Entity\User')->findOneBy(array('email' => $email));
        }

        if(count($user) > 0) {
            $hash_exists = true;
        }

        if($hash && $hash_exists) {
            if($request->isPost()) {
                if(strlen(trim($request->getPost("password"))) < 6) {
                    $this->flashMessenger()->addErrorMessage('Your password is too short.');
                    return $this->redirect()->toUrl($returnUri);
                }
                if($request->getPost("password") !== $request->getPost("password2")) {
                    $this->flashMessenger()->addErrorMessage('Passwords don\'t match. Please double check your passwords.');
                    return $this->redirect()->toUrl($returnUri);
                }

                $bcrypt = new Bcrypt;
                $bcrypt->setCost(14);
                $user->password = $bcrypt->create($request->getPost("password"));
                $user->password_recovery_hash = null;
                $this->em()->persist($user);
                $this->em()->flush();

                $request->setPost(new Parameters(array(
                    'identity' => $user->email,
                    'credential' => $request->getPost("password"),
                    'remember' => 1
                )));

                // reseting adapters and eventualy logging out old user
                $this->UserAuthentication()->getAuthAdapter()->resetAdapters();
                $this->UserAuthentication()->getAuthService()->clearIdentity();

                // logging in new user
                $adapter = $this->UserAuthentication()->getAuthAdapter();
                $adapter->prepareForAuthentication($this->getRequest());

                $this->UserAuthentication()->getAuthService()->authenticate($adapter);

                $this->flashMessenger()->addSuccessMessage('Your password has been changed.');
                return $this->redirect()->toUrl($this->url()->fromRoute('membership'));
            }
            return array();
        } elseif(isset($hash) && !$hash_exists) {
            return $this->redirect()->toRoute('user/login');
        }
        if($request->getPost('email')) {
            if(!$user) {
                $this->flashMessenger()->addErrorMessage('Email not found in the database. Please signup first.');
                return $this->redirect()->toUrl($returnUri);
            }

            $mail = new Message();
            $mail->setFrom('info@kwiklearning.com', 'KwikLearning.com');
            $mail->addTo($user->email, "$user->first_name $user->last_name");

            $user->password_recovery_hash = \sha1($user->email . '-' . \time() . '-saltysalty');
            $this->em()->persist($user);
            $this->em()->flush();

            $resetUrl = 'http://' . $host . $this->url()->fromRoute('user/reset', array('email' => $user->email, 'hash' => $user->password_recovery_hash));

            $mail->setSubject('Reset your password.');
            $mail->setBody("Hi $user->first_name!\n\n" .
                "You have requested password reset on KwikLearning.com. To reset, please click this link.:\n\n" .
                $resetUrl .
                "\n\n Kwik Learning Team!");

            $transport = new SmtpTransport();
            $options = new SmtpOptions(array(
                'host' => 'smtpout.secureserver.net',
                'connection_class' => 'login',
                'connection_config' => array(
                    'username' => 'info@kwiklearning.com',
                    'password' => 'Example247'
                ),
                'port' => 25,
            ));
            $transport->setOptions($options);
            $transport->send($mail);

            $this->flashMessenger()->addInfoMessage('Confirmation email has been sent to your email address. Please check your inbox.');
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
}
