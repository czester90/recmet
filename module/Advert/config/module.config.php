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
            ),
        ),
    ),
);
