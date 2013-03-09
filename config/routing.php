<?php

$routing = array(
    'GET' => array(
        // Page d'accueil
        '/' => array(
            'class'     => 'Index',
            'method'    => 'defaultAction',
            'route'     => 'homepage',
        ),
        
        // Gestion des clients
        '/clients' => array(
            'class'     => 'Customer',
            'method'    => 'defaultAction',
            'route'     => 'customers',
        ),
        '/ajouter-un-client' => array(
            'class'     => 'Customer',
            'method'    => 'addAction',
            'route'     => 'add-customer',
        ),
        '/modifier-un-client/{slug}' => array(
            'class'     => 'Customer',
            'method'    => 'editAction',
            'route'     => 'edit-customer',
        ),
        
        // Gestion des chevaux
        '/chevaux' => array(
            'class'     => 'Horse',
            'method'    => 'defaultAction',
            'route'     => 'horses',
        ),
        '/ajouter-un-cheval' => array(
            'class'     => 'Horse',
            'method'    => 'addAction',
            'route'     => 'add-horse',
        ),
        '/modifier-un-cheval/{slug}' => array(
            'class'     => 'Horse',
            'method'    => 'editAction',
            'route'     => 'edit-horse',
        ),
        
        // Gestion des types de soins
        '/types-de-soins' => array(
            'class'     => 'Treatment',
            'method'    => 'defaultAction',
            'route'     => 'treatments',
        ),
        '/ajouter-un-type-de-soin' => array(
            'class'     => 'Treatment',
            'method'    => 'addAction',
            'route'     => 'add-treatment',
        ),
        '/modifier-un-type-de-soin/{slug}' => array(
            'class'     => 'Treatment',
            'method'    => 'editAction',
            'route'     => 'edit-treatment',
        ),
    ),
    
    'POST' => array(
        // Gestion des clients
        '/ajouter-un-client' => array(
            'class'     => 'Customer',
            'method'    => 'addAction',
            'route'     => 'add-customer-submit',
        ),
        '/modifier-un-client' => array(
            'class'     => 'Customer',
            'method'    => 'editAction',
            'route'     => 'edit-customer-submit',
        ),
        
        // Gestion des chevaux
        '/ajouter-un-cheval' => array(
            'class'     => 'Horse',
            'method'    => 'addAction',
            'route'     => 'add-horse-submit',
        ),
        '/modifier-un-cheval/{slug}' => array(
            'class'     => 'Horse',
            'method'    => 'editAction',
            'route'     => 'edit-horse-submit',
        ),
        
        // Gestion des types de soins
        '/ajouter-un-type-de-soin' => array(
            'class'     => 'Treatment',
            'method'    => 'addAction',
            'route'     => 'add-treatment-submit',
        ),
        '/modifier-un-type-de-soin/{slug}' => array(
            'class'     => 'Treatment',
            'method'    => 'editAction',
            'route'     => 'edit-treatment-submit',
        ),
    ),
);