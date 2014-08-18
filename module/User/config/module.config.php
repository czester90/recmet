<?php

return array(
    'view_manager' => array(
        'template_path_stack' => array(
            'user' => __DIR__ . '/../view',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'user' => 'User\Controller\UserController',
        ),
    ),
    'service_manager' => array(
        'aliases' => array(
            'user_zend_db_adapter' => 'Zend\Db\Adapter\Adapter',
        ),
    ),
    'router' => array(
        'routes' => array(
            'user' => array(
                'type' => 'Literal',
                'priority' => 1000,
                'options' => array(
                    'route' => '/user',
                    'defaults' => array(
                        'controller' => 'user',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'login' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/login[/:id]/',
                            'defaults' => array(
                                'controller' => 'user',
                                'action' => 'login',
                            ),
                        ),
                    ),
                    'reset' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/reset[/:email[/:hash]]/',
                            'defaults' => array(
                                'controller' => 'user',
                                'action' => 'reset',
                                'email' => null,
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'profile' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/profile[/:id]/',
                            'defaults' => array(
                                'controller' => 'user',
                                'action' => 'profile',
                            ),
                        ),
                        'priority' => 1000,
                    ),
                    'authenticate' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/authenticate/',
                            'defaults' => array(
                                'controller' => 'user',
                                'action' => 'authenticate',
                            ),
                        ),
                    ),
                    'logout' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/logout/',
                            'defaults' => array(
                                'controller' => 'user',
                                'action' => 'logout',
                            ),
                        ),
                    ),
                    'register' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/register/',
                            'defaults' => array(
                                'controller' => 'user',
                                'action' => 'register',
                            ),
                        ),
                    ),
                    'changepassword' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/change-password/',
                            'defaults' => array(
                                'controller' => 'user',
                                'action' => 'changepassword',
                            ),
                        ),
                    ),
                    'changeemail' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/change-email/',
                            'defaults' => array(
                                'controller' => 'user',
                                'action' => 'changeemail',
                            ),
                        ),
                    ),
                ),
            ),          
        ),
    ),
);
