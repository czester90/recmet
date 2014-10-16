<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module {

  public function onBootstrap(MvcEvent $e) {
    $eventManager = $e->getApplication()->getEventManager();
    $moduleRouteListener = new ModuleRouteListener();
    $moduleRouteListener->attach($eventManager);

    $application = $e->getApplication();
    $em = $application->getEventManager();

    $em->attach('dispatch', function($e) {
      $routeMatch = $e->getRouteMatch();
      $viewModel = $e->getViewModel();
      $viewModel->setVariable('controller', $routeMatch->getParam('controller'));
      $viewModel->setVariable('action', $routeMatch->getParam('action'));

    }, -100);

    $serviceManager = $e->getApplication()->getServiceManager();

    $serviceManager->get('viewhelpermanager')->setFactory('MetaTags', function ($sm) use ($e) {
      return new View\Helper\MetaTags($e, $sm);
    });
    $serviceManager->get('viewhelpermanager')->setFactory('getRepos', function ($sm) use ($e) {
      return new View\Helper\getRepos($e, $sm);
    });
  }

  public function loadConfiguration(MvcEvent $e) {
    $controller = $e->getTarget();
  }
  
  public function getConfig() {
    return include __DIR__ . '/config/module.config.php';
  }

  public function getAutoloaderConfig() {
    return array(
      'Zend\Loader\StandardAutoloader' => array(
        'namespaces' => array(
          __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
        ),
      ),
    );
  }

}
