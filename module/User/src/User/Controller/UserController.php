<?php

namespace User\Controller;

use Zend\Form\Form;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\Stdlib\Parameters;
use Zend\View\Model\ViewModel;
use User\Service\User as UserService;
use User\Options\UserControllerOptionsInterface;
use Zend\Crypt\Password\Bcrypt;
use Application\Controller\BaseController;
use Zend\Mvc\Controller\AbstractActionController;

class UserController extends BaseController {

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
  public function indexAction() {

    if(!$this->UserAuthentication()->hasIdentity()) {
      return $this->redirect()->toRoute(static::ROUTE_LOGIN);
    }
    return new ViewModel();
  }

  /**
   * Profile User
   */
  public function profileAction() {

    return new ViewModel(array(
        'company' => $this->em('Company\Entity\Company')->find($this->user()->getIdentity()->getCompany_id())
    ));
  }

  /**
   * Login form
   */
  public function loginAction() {
    $request = $this->getRequest();
    $form = $this->getLoginForm();

    if($this->getOptions()->getUseRedirectParameterIfPresent() && $request->getQuery()->get('redirect')) {
      $redirect = $request->getQuery()->get('redirect');
    } else {
      $redirect = false;
    }

    if(!$request->isPost()) {
      return array(
        'loginForm' => $form,
        'redirect' => $redirect,
        'enableRegistration' => $this->getOptions()->getEnableRegistration(),
      );
    }

    $post = $request->getPost();

    $post->set('credential', trim($post->get('credential')));
    $form->setData($post);

    if(!$form->isValid()) {
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
  public function logoutAction() {

    $this->UserAuthentication()->getAuthAdapter()->resetAdapters();
    $this->UserAuthentication()->getAuthAdapter()->logoutAdapters();
    $this->getServiceLocator()->get('user_remember')->forgetMe();
    $this->UserAuthentication()->getAuthService()->clearIdentity();

    $sessionManager = new \Zend\Session\SessionManager();
    $sessionManager->forgetMe();

    $redirect = $this->params()->fromPost('redirect', $this->params()->fromQuery('redirect', false));

    if($this->getOptions()->getUseRedirectParameterIfPresent() && $redirect) {
      return $this->redirect()->toUrl($redirect);
    }

    return $this->redirect()->toRoute($this->getOptions()->getLogoutRedirectRoute());
  }

  /**
   * General-purpose authentication action
   */
  public function authenticateAction() {

    if($this->UserAuthentication()->getAuthService()->hasIdentity()) {
      return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
    }
    $adapter = $this->UserAuthentication()->getAuthAdapter();
    $redirect = $this->params()->fromPost('redirect', $this->params()->fromQuery('redirect', false));

    $result = $adapter->prepareForAuthentication($this->getRequest());

    // Return early if an adapter returned a response
    if($result instanceof Response) {
      return $result;
    }

    $auth = $this->UserAuthentication()->getAuthService()->authenticate($adapter);

    if(!$auth->isValid()) {
      $this->flashMessenger()->addErrorMessage($this->failedLoginMessage);
      $adapter->resetAdapters();
      return $this->redirect()->toUrl($this->url()->fromRoute(static::ROUTE_LOGIN)
                      . ($redirect ? '?redirect=' . $redirect : ''));
    }

    return $this->redirect()->toRoute('user/profile');
  }

  /**
   * Register new user
   */
  public function registerAction() {
    if($this->UserAuthentication()->hasIdentity()) {
      return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
    }
    // if registration is disabled
    if(!$this->getOptions()->getEnableRegistration()) {
      return array('enableRegistration' => false);
    }

    $request = $this->getRequest();
    $service = $this->getUserService();
    $form = $this->getRegisterForm();

    if($this->getOptions()->getUseRedirectParameterIfPresent() && $request->getQuery()->get('redirect')) {
      $redirect = $request->getQuery()->get('redirect');
    } else {
      $redirect = false;
    }

    $redirectUrl = $this->url()->fromRoute(static::ROUTE_REGISTER)
            . ($redirect ? '?redirect=' . $redirect : '');
    $prg = $this->prg($redirectUrl, true);

    if($prg instanceof Response) {
      return $prg;
    } elseif($prg === false) {
      return array(
        'registerForm' => $form,
        'enableRegistration' => $this->getOptions()->getEnableRegistration(),
        'redirect' => $redirect,
        'categories' => $this->em('Article\Entity\Category'),
      );
    }

    $post = $prg;
    $user = $service->register($post);

    $redirect = isset($prg['redirect']) ? $prg['redirect'] : null;

    if(!$user) {
      return array(
        'registerForm' => $form,
        'enableRegistration' => $this->getOptions()->getEnableRegistration(),
        'redirect' => $redirect,
        'categories' => $this->em('Article\Entity\Category'),
      );
    }

    if($service->getOptions()->getLoginAfterRegistration()) {
      $identityFields = $service->getOptions()->getAuthIdentityFields();
      if(in_array('email', $identityFields)) {
        $post['identity'] = $user->getEmail();
      } elseif(in_array('username', $identityFields)) {
        $post['identity'] = $user->getUsername();
      }
      $post['credential'] = $post['password'];
      $request->setPost(new Parameters($post));
      return $this->forward()->dispatch(static::CONTROLLER_NAME, array('action' => 'authenticate'));
    }

    // TODO: Add the redirect parameter here...
    return $this->redirect()->toUrl($this->url()->fromRoute(static::ROUTE_LOGIN) . ($redirect ? '?redirect=' . $redirect : ''));
  }

  public function getUserService() {
    if(!$this->userService) {
      $this->userService = $this->getServiceLocator()->get('user_user_service');
    }
    return $this->userService;
  }

  public function setUserService(UserService $userService) {
    $this->userService = $userService;
    return $this;
  }

  public function getRegisterForm() {
    if(!$this->registerForm) {
      $this->setRegisterForm($this->getServiceLocator()->get('user_register_form'));
    }
    return $this->registerForm;
  }

  public function setRegisterForm(Form $registerForm) {
    $this->registerForm = $registerForm;
  }

  public function getLoginForm() {
    if(!$this->loginForm) {
      $this->setLoginForm($this->getServiceLocator()->get('user_login_form'));
    }
    return $this->loginForm;
  }

  public function setLoginForm(Form $loginForm) {
    $this->loginForm = $loginForm;
    $fm = $this->flashMessenger()->setNamespace('user-login-form')->getMessages();
    if(isset($fm[0])) {
      $this->loginForm->setMessages(
              array('identity' => array($fm[0]))
      );
    }
    return $this;
  }

  public function setOptions(UserControllerOptionsInterface $options) {
    $this->options = $options;
    return $this;
  }

  public function getOptions() {
    if(!$this->options instanceof UserControllerOptionsInterface) {
      $this->setOptions($this->getServiceLocator()->get('user_module_options'));
    }
    return $this->options;
  }
}
