<?php

return array(
    'view_manager' => array(
        'template_path_stack' => array(
            'company' => __DIR__ . '/../view',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'company\company' => 'Company\Controller\CompanyController'
        ),
    ),
    'router' => array(
        'routes' => array(
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
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/settings',
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
