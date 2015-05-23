<?php

namespace Application\Controller;

use User\Controller\UserController;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Advert\Repository\AdvertRepository;
use Advert\Repository\CategoryRepository;

class BaseController extends AbstractActionController
{
    public $request;
    public $param_id;
    public $session;
    public $advertRepository;
    public $categoryRepository;

    /**
     * @param CategoryRepository $categoryRepository
     */
    public function setCategoryRepository($categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @return CategoryRepository
     */
    public function getCategoryRepository()
    {
        return $this->categoryRepository;
    }

    /**
     * @param mixed $advertRepository
     */
    public function setAdvertRepository($advertRepository)
    {
        $this->advertRepository = $advertRepository;
    }

    /**
     * @return mixed
     */
    public function getAdvertRepository()
    {
        return $this->advertRepository;
    }

    public function __construct()
    {
        $this->request = $this->getRequest();
        $this->setAdvertRepository(new AdvertRepository());
        $this->getAdvertRepository()->setController($this);
        $this->setCategoryRepository(new CategoryRepository());
        $this->getCategoryRepository()->setController($this);
    }

    public function getParam($param)
    {
        $paramValue = $this->params()->fromRoute($param);
        return $paramValue ? $paramValue : null;
    }

    public function em($namespace = null, $database = 'default')
    {
        if ($namespace) {
            return $this->getServiceLocator()->get('doctrine.entitymanager.orm_' . $database)->getRepository($namespace);
        } else {
            return $this->getServiceLocator()->get('doctrine.entitymanager.orm_' . $database);
        }
    }

    public function jsonResponse(array $param)
    {
        return new JsonModel(array(
            'param' => $param,
            'success' => true,
        ));
    }

    public function user()
    {
        $sm = $this->getServiceLocator();
        return $sm->get('user_auth_service');
    }

    public function getUserId()
    {
        return $this->user()->getIdentity()->getId();
    }

    public function getCompanyId()
    {
        return $this->user()->getIdentity()->getCompanyId();
    }

    public function post($name)
    {
        return $this->request->getPost($name);
    }

    public function files($name)
    {
        return $this->request->getFiles($name);
    }

    public function isUser()
    {
        if (!$this->UserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(UserController::ROUTE_LOGIN);
        }

        return false;
    }

    public function isUserLogin()
    {
        return $this->UserAuthentication()->hasIdentity() ? true : false;
    }
}
