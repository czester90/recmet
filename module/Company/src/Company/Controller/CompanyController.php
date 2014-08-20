<?php

namespace Company\Controller;

use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;
use Library\MessageStatus;
use Zend\Crypt\Password\Bcrypt;
use Zend\Stdlib\Parameters;

class CompanyController extends BaseController {
  
  public function companiesListAction() {
    return new ViewModel(array(
      
    ));
  }
  
  public function priceTableAction() {
    return new ViewModel();
  }
  
  public function registerAction() {
    
    if ($this->user()->getIdentity()) {
      echo "yes";
    }
    
    $request = $this->getRequest();
    $message = new MessageStatus();
    
    if($request->isPost()){
      $company = new \Company\Entity\Company();
      $company->setName($request->getPost('company'));
      $company->setCountry($request->getPost('country'));
      $company->setCity($request->getPost('city'));
      $company->setAddress($request->getPost('address'));
      $company->setPostCode($request->getPost('post_code'));
      $this->em()->persist($company);
      $this->em()->flush();
      
      $bcrypt = new Bcrypt;
      $bcrypt->setCost(14);
      
      $user = new \User\Entity\User();
      $user->setUsername($request->getPost('username'));
      $user->setPassword($bcrypt->create($request->getPost('password')));
      $user->setEmail($request->getPost('email'));
      $user->setFirstName('sdfsdfsdf');
      $user->setLastName('sdfsdf');
      $roles = $this->em('User\Entity\Role')->find(1);
      $user->getRolesAdd()->add($roles);
      $this->em()->persist($user);
      $this->em()->flush();
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
    }
    
    return new ViewModel(array(
      'status' => $message->getMessage()
    ));
  }
}
