<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            'advert' => __DIR__ . '/../view',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'advert\advert' => 'Advert\Controller\AdvertController'
        ),
    ),
    'router' => array(
        'routes' => array(
            'advert' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/advert',
                    'defaults' => array(
                        'controller' => 'advert/advert',
                        'action'     => 'advertList',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'add' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/add',
                            'defaults' => array(
                                'action' => 'add'
                            ),
                        ),
                    ),
                    'edit' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/edit/[:id]',
                            'defaults' => array(
                                'action' => 'edit'
                            ),
                        ),
                    ),
                    'delete' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/delete/[:id]',
                            'defaults' => array(
                                'action' => 'delete'
                            ),
                        ),
                    ),
                    'dashboard' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/dashboard',
                            'defaults' => array(
                                'action' => 'dashboard'
                            ),
                        ),
                    ),
                    'manager' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/manager',
                            'defaults' => array(
                                'action' => 'manager-list'
                            ),
                        ),
                    ),
                    'view' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/view/[:id]/[:url]',
                            'defaults' => array(
                                'action' => 'view'
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
