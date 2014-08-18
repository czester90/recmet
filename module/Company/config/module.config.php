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
                    'route'    => '/companies',
                    'defaults' => array(
                        'controller' => 'company\company',
                        'action'     => 'companiesList',
                    ),
                ),
            ),
            'prices' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/prices',
                    'defaults' => array(
                        'controller' => 'company\company',
                        'action'     => 'priceTable',
                    ),
                ),
            ),
        ),
    ),
);
