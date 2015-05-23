<?php

return array(
    'factories' => array(
        // other config code ,
        '\Zend\Log\Logger' => function($sm){
                $logger = new Zend\Log\Logger;
                $writer = new Zend\Log\Writer\Stream('./data/logs/' . date('Y-m-d') . '-error.log');

                $logger->addWriter($writer);
                return $logger;
            },
    ),
);