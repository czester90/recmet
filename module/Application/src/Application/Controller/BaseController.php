<?php

namespace Application\Controller;

use User\Controller\UserController;
use Zend\Mvc\Controller\AbstractActionController;
use Library\access\Access;

class BaseController extends AbstractActionController
{
    public function isLogin() {
        if(!$this->UserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(UserController::ROUTE_LOGIN);
        }
    }

    public function em($namespace = null, $database = 'default')
    {
        if ($namespace) {
            return $this->getServiceLocator()->get('doctrine.entitymanager.orm_' . $database)->getRepository($namespace);
        } else {
            return $this->getServiceLocator()->get('doctrine.entitymanager.orm_' . $database);
        }
    }

    public function user()
    {
        $sm = $this->getServiceLocator();
        return $sm->get('user_auth_service');
    }
}
