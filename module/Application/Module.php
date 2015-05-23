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
use Locale;

class Module
{

    public function onBootstrap(MvcEvent $e)
    {
        $application = $e->getApplication();
        $em = $application->getEventManager();

        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($em);

        /*$user = $e->getApplication()->getServiceManager()->get('user_auth_service');

        if($user->hasIdentity()){
            $locale = $user->getIdentity()->getLocale();
        }else{
            if(isset($_COOKIE['lang'])) {
                $locale = $_COOKIE['lang'];
            }else{
                $langHttp = \Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']);
                $locale = $langHttp == 'pl' ? 'pl_PL' : 'en_US';
                setcookie('lang', $locale, time() + (86400 * 30 * 30), '/');
            }
        }

        $translator = $application->getServiceManager()->get('translator');
        $translator
            ->setLocale(\Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']))
            ->setFallbackLocale($locale);*/

        $em->attach('dispatch', function ($e) {
            $routeMatch = $e->getRouteMatch();
            $viewModel = $e->getViewModel();
            $viewModel->setVariable('controller', $routeMatch->getParam('controller'));
            $viewModel->setVariable('action', $routeMatch->getParam('action'));
            $viewModel->setVariable('params', $routeMatch->getParams());
        }, -100);

        $serviceManager = $e->getApplication()->getServiceManager();

        $serviceManager->get('viewhelpermanager')->setFactory('MetaTags', function ($sm) use ($e) {
            return new View\Helper\MetaTags($e, $sm);
        });
        $serviceManager->get('viewhelpermanager')->setFactory('getRepos', function ($sm) use ($e) {
            return new View\Helper\getRepos($e, $sm);
        });
        $serviceManager->get('viewhelpermanager')->setFactory('Bundle', function ($sm) use ($e) {
            return new View\Helper\Bundle($e, $sm);
        });

        $sharedManager = $e->getApplication()->getEventManager()->getSharedManager();

        $sharedManager->attach('Zend\Mvc\Application', 'dispatch.error',
            function($e) use ($serviceManager) {
                if ($e->getParam('exception')) {
                    $serviceManager->get('\Zend\Log\Logger')->crit($e->getParam('exception'));
                    return false;
                }
            }
        );
    }

    public function loadConfiguration(MvcEvent $e)
    {
        $controller = $e->getTarget();
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

}
