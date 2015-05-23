<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            'advert' => __DIR__ . '/../view',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'advert' => 'Advert\Controller\AdvertController',
            'category' => 'Advert\Controller\CategoryController',
            'offer' => 'Advert\Controller\OfferController'
        ),
    ),
    'router' => array(
        'routes' => array(
            'generate-category' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/category/view/',
                    'defaults' => array(
                        'controller' => 'category',
                        'action'     => 'generateCategory',
                    ),
                ),
            ),
            'offer' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/offer',
                    'defaults' => array(
                        'controller' => 'offer',
                        'action'     => 'dashboard'
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'received' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/received',
                            'defaults' => array(
                                'action' => 'received'
                            ),
                        ),
                        'priority' => 1000,
                    ),
                    'shipped' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/shipped',
                            'defaults' => array(
                                'action' => 'shipped'
                            ),
                        ),
                        'priority' => 1000,
                    ),
                ),
            ),
            'advert' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/advert',
                    'constraints' => array(
                        'page' => '[0-9]'
                    ),
                    'defaults' => array(
                        'controller' => 'advert',
                        'action'     => 'advertList',
                        'page'       => 1,
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
                        'priority' => 1000,
                    ),
                    'edit' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/edit/[:id]',
                            'defaults' => array(
                                'action' => 'edit'
                            ),
                        ),
                        'priority' => 1000,
                    ),
                    'get-advert' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/getAdvert',
                            'defaults' => array(
                                'action' => 'getAdvert'
                            ),
                        ),
                        'priority' => 1000,
                    ),
                    'sublist' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '[/:id][/:url]/[:page]',
                            'defaults' => array(
                                'action' => 'advertList'
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
                        'priority' => 1000,
                    ),
                    'offer-delete' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/offer/delete/[:id]',
                            'defaults' => array(
                                'action' => 'offer-delete'
                            ),
                        ),
                        'priority' => 1000,
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
                        'priority' => 1000,
                    ),
                    'magazine' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/magazine',
                            'defaults' => array(
                                'action' => 'magazine'
                            ),
                        ),
                    ),
                    'transations' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/transations',
                            'defaults' => array(
                                'action' => 'transations'
                            ),
                        ),
                    ),
                    'observe' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/observe',
                            'defaults' => array(
                                'action' => 'observe'
                            ),
                        ),
                    ),
                    'observe-list' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/observe-list',
                            'defaults' => array(
                                'action' => 'observe-list'
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
