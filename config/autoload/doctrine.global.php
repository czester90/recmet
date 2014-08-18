<?php

return array(
    'doctrine' => array(
        'service_manager' => array(
            'factories' => array(
                'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
            ),
        ),

        'driver' => array(
            'user_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => array(
                    __DIR__ . '/../../module/User/src/User/Entity',
                )
            ),
            'company_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => array(
                    __DIR__ . '/../../module/Company/src/Company/Entity',
                )
            ),
            'orm_default' => array(
                'drivers' => array(
                    'User\Entity' => 'user_entities',
                    'Company\Entity' => 'company_entities'
                ),
            ),
        ),
    ),
);
?>