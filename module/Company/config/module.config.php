<?php

return array(
    'view_manager' => array(
        'template_path_stack' => array(
            'company' => __DIR__ . '/../view',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'company\company' => 'Company\Controller\CompanyController',
            'company\transport' => 'Company\Controller\TransportController',
            'company\site' => 'Company\Controller\SiteController',
            'company\message' => 'Company\Controller\MessageController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'transport' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/transport',
                    'defaults' => array(
                        'controller' => 'company\transport',
                        'action' => 'transport',
                    ),
                ),
            ),
            'site' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/site',
                    'defaults' => array(
                        'controller' => 'company\site',
                        'action' => 'site',
                    ),
                ),
            ),
            'companies' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/companies',
                    'defaults' => array(
                        'controller' => 'company\company',
                        'action' => 'companiesList',
                    ),
                ),
            ),
            'rules' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/rules',
                    'defaults' => array(
                        'controller' => 'company\company',
                        'action' => 'rules',
                    ),
                ),
            ),
            'message' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/message',
                    'defaults' => array(
                        'controller' => 'company\message',
                        'action' => 'index',
                    ),
                ),
            ),
            'company' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/company',
                    'defaults' => array(
                        'controller' => 'company\company',
                        'action' => 'companiesList',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'register' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/register/',
                            'defaults' => array(
                                'controller' => 'company\company',
                                'action' => 'register',
                            ),
                        ),
                    ),
                    'payments' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/payments',
                            'defaults' => array(
                                'controller' => 'company\company',
                                'action' => 'payments',
                            ),
                        ),
                    ),
                    'settings' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/settings[/:method]',
                            'defaults' => array(
                                'controller' => 'company\company',
                                'action' => 'settings',
                            ),
                        ),
                    ),
                    'representatives' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/representatives',
                            'defaults' => array(
                                'controller' => 'company\company',
                                'action' => 'representatives',
                            ),
                        ),
                    ),
                ),
            ),
            'prices' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/prices',
                    'defaults' => array(
                        'controller' => 'company\company',
                        'action' => 'priceTable',
                    ),
                ),
            ),
        ),
    ),
);
