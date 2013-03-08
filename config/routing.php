<?php

$routing = array(
    'GET' => array(
        '/' => array(
            'class'     => 'Index',
            'method'    => 'defaultAction',
            'route'     => 'homepage',
        ),
        
        '/clients' => array(
            'class'     => 'Customer',
            'method'    => 'defaultAction',
            'route'     => 'customers',
        ),
    ),
    
    'POST' => array(
    ),
);