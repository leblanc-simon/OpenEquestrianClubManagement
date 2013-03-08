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
        
        '/chevaux' => array(
            'class'     => 'Horse',
            'method'    => 'defaultAction',
            'route'     => 'horses',
        ),
        
        '/types-de-soins' => array(
            'class'     => 'Treatment',
            'method'    => 'defaultAction',
            'route'     => 'treatments',
        ),
    ),
    
    'POST' => array(
    ),
);