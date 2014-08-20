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
