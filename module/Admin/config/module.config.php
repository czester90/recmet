<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            'admin' => __DIR__ . '/../view',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'admin/install' => 'Admin\Controller\InstallController'
        ),
    ),
    'router' => array(
        'routes' => array(
            'admin' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/admin',
                    'defaults' => array(
                        'controller' => 'admin/install',
                        'action'     => 'index',
                    ),
                    'may_terminate' => true,
                    'child_routes' => array(
                        'add' => array(
                            'type'    => 'Segment',
                            'options' => array(
                                'route'    => '/install',
                                'defaults' => array(
                                    'action' => 'install'
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
