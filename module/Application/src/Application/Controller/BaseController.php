<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Authentication\AuthenticationService;

class BaseController extends AbstractActionController {

  /**
   * Returns an instance of the Doctrine entity manager loaded from the service
   * locator
   *
   * @return Doctrine\ORM\EntityManager
   */
  public function em($namespace = null, $database = 'default') {
    if($namespace) {
      return $this->getServiceLocator()->get('doctrine.entitymanager.orm_' . $database)->getRepository($namespace);
    } else {
      return $this->getServiceLocator()->get('doctrine.entitymanager.orm_' . $database);
    }
  }

  public function user() {
    $sm = $this->getServiceLocator();
    return $sm->get('user_auth_service');
  }
}
