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
        '/client/ajouter' => array(
            'class'     => 'Customer',
            'method'    => 'addAction',
            'route'     => 'add-customer',
        ),
        '/client/modifier/{slug}' => array(
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
        '/cheval/ajouter' => array(
            'class'     => 'Horse',
            'method'    => 'addAction',
            'route'     => 'add-horse',
        ),
        '/cheval/modifier/{slug}' => array(
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
        '/type-de-soin/ajouter' => array(
            'class'     => 'Treatment',
            'method'    => 'addAction',
            'route'     => 'add-treatment',
        ),
        '/type-de-soin/modifier/{slug}' => array(
            'class'     => 'Treatment',
            'method'    => 'editAction',
            'route'     => 'edit-treatment',
        ),
        
        // Gestion des types de cartes de séances
        '/types-de-cartes' => array(
            'class'     => 'Card',
            'method'    => 'defaultAction',
            'route'     => 'cards',
        ),
        '/type-de-carte/ajouter' => array(
            'class'     => 'Card',
            'method'    => 'addAction',
            'route'     => 'add-card',
        ),
        '/type-de-carte/modifier/{slug}' => array(
            'class'     => 'Card',
            'method'    => 'editAction',
            'route'     => 'edit-card',
        ),
        
        // Gestion des traitements
        '/sanitaire' => array(
            'class'     => 'TreatmentApply',
            'method'    => 'defaultAction',
            'route'     => 'treatment-apply',
        ),
        '/sanitaire/ajouter-un-soin' => array(
            'class'     => 'TreatmentApply',
            'method'    => 'addAction',
            'route'     => 'add-treatment-apply',
        ),
        '/sanitaire/modifier-un-soin/{id}' => array(
            'class'     => 'TreatmentApply',
            'method'    => 'editAction',
            'route'     => 'edit-treatment-apply',
        ),
    ),
    
    'POST' => array(
        // Gestion des clients
        '/client/ajouter' => array(
            'class'     => 'Customer',
            'method'    => 'addAction',
            'route'     => 'add-customer-submit',
        ),
        '/client/modifier' => array(
            'class'     => 'Customer',
            'method'    => 'editAction',
            'route'     => 'edit-customer-submit',
        ),
        
        // Gestion des chevaux
        '/cheval/ajouter' => array(
            'class'     => 'Horse',
            'method'    => 'addAction',
            'route'     => 'add-horse-submit',
        ),
        '/cheval/modifier/{slug}' => array(
            'class'     => 'Horse',
            'method'    => 'editAction',
            'route'     => 'edit-horse-submit',
        ),
        
        // Gestion des types de soins
        '/type-de-soin/ajouter' => array(
            'class'     => 'Treatment',
            'method'    => 'addAction',
            'route'     => 'add-treatment-submit',
        ),
        '/type-de-soin/modifier/{slug}' => array(
            'class'     => 'Treatment',
            'method'    => 'editAction',
            'route'     => 'edit-treatment-submit',
        ),
        
        // Gestion des types de cartes de séances
        '/type-de-carte/ajouter' => array(
            'class'     => 'Card',
            'method'    => 'addAction',
            'route'     => 'add-card-submit',
        ),
        '/type-de-carte/modifier/{slug}' => array(
            'class'     => 'Card',
            'method'    => 'editAction',
            'route'     => 'edit-card-submit',
        ),
        
        // Gestion des traitements
        '/sanitaire/ajouter-un-soin' => array(
            'class'     => 'TreatmentApply',
            'method'    => 'addAction',
            'route'     => 'add-treatment-apply-submit',
        ),
        '/sanitaire/modifier-un-soin/{id}' => array(
            'class'     => 'TreatmentApply',
            'method'    => 'editAction',
            'route'     => 'edit-treatment-apply-submit',
        ),
    ),
);