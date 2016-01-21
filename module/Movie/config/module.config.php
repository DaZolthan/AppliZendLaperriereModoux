<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Movie\Controller\Movie' => 'Movie\Controller\MovieController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'movie' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/movie[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Movie\Controller\Movie',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'movie' => __DIR__ . '/../view',
        ),
    ),
);
