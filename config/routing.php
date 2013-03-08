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
        '/nouveau-cheval' => array(
            'class'     => 'Horse',
            'method'    => 'addAction',
            'route'     => 'add-horse',
        ),
        '/modifier-cheval/{slug}' => array(
            'class'     => 'Horse',
            'method'    => 'editAction',
            'route'     => 'edit-horse',
        ),
        
        '/types-de-soins' => array(
            'class'     => 'Treatment',
            'method'    => 'defaultAction',
            'route'     => 'treatments',
        ),
    ),
    
    'POST' => array(
        '/nouveau-cheval' => array(
            'class'     => 'Horse',
            'method'    => 'addAction',
            'route'     => 'add-horse-submit',
        ),
        '/modifier-cheval/{slug}' => array(
            'class'     => 'Horse',
            'method'    => 'editAction',
            'route'     => 'edit-horse-submit',
        ),
    ),
);