<?php

namespace Company\Controller;

use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;
use Library\MessageStatus;
use Zend\Crypt\Password\Bcrypt;
use Zend\Stdlib\Parameters;
use Company\Entity\Company;
use User\Entity\User;

class CompanyController extends BaseController {
  
  public function companiesListAction() {
    return new ViewModel(array(
      
    ));
  }
  
  public function priceTableAction() {
    return new ViewModel();
  }
  
  public function registerAction() {
    $request = $this->getRequest();
    $message = new MessageStatus();
    
    if($request->isPost()){
      $company = new Company();
      $company->setData($request->getPost());
      $this->em()->persist($company);
      $this->em()->flush();
      
      $bcrypt = new Bcrypt;
      $bcrypt->setCost(14);
      
      $user = new User();
      $user->setUsername($request->getPost('username'));
      $user->setPassword($bcrypt->create($request->getPost('password')));
      $user->setEmail($request->getPost('email'));
      $user->setFirstName($request->getPost('first_name'));
      $user->setLastName($request->getPost('last_name'));
      $user->setCompany_id($company->id);
      $roles = $this->em('User\Entity\Role')->find(1);
      $user->getRolesAdd()->add($roles);
      $this->em()->persist($user);
      $result = $this->em()->flush();
      if($result){
        $request->setPost(new Parameters(array(
            'identity' => $request->getPost('email'),
            'credential' => $request->getPost('password'),
            'remember' => 1
        )));
        
        $this->UserAuthentication()->getAuthAdapter()->resetAdapters();
        $this->UserAuthentication()->getAuthService()->clearIdentity();
        $adapter = $this->UserAuthentication()->getAuthAdapter();
        $adapter->prepareForAuthentication($this->getRequest());

        $this->UserAuthentication()->getAuthService()->authenticate($adapter);

        $message->setMessage('Firma została założona.');
      }else{
        $message->setMessage('Wystapił błąd Firma nie może zostać zarejestrowana.');
      }
    }
    
    return new ViewModel(array(
      'status' => $message->getMessage()
    ));
  }
}
