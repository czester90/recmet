<?php
return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'support' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/support',
                    'defaults' => array(
                        'controller'    => 'Application\Controller\Index',
                        'action'        => 'support',
                    ),
                )
            ),
            'stock' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/stock',
                    'defaults' => array(
                        'controller'    => 'Application\Controller\Index',
                        'action'        => 'stock',
                    ),
                )
            ),
            'system' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/set',
                    'defaults' => array(
                        'controller'    => 'Application\Controller\System',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'lang' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/language',
                            'defaults' => array(
                                'action' => 'setLanguage'
                            ),
                        ),
                        'priority' => 1000,
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'pl_PL',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\System' => 'Application\Controller\SystemController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'layout/home'             => __DIR__ . '/../view/layout/home.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'MetaTags'  => 'Application\View\Helper\MetaTags',
            'getRepos'  => 'Application\View\Helper\getRepos',
            'LayoutIni' => 'Application\View\Helper\LayoutIni',
            'Bundle'    => 'Application\View\Helper\Bundle',
        )
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
