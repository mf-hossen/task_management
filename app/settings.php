<?php
const SLACK = false;
return [
    'settings' => [
        // Slim Settings
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => true,

        // View settings
        'view' => [
            'template_path' => __DIR__ . '/templates',
            'twig' => [
                //'cache' => __DIR__ . '/../cache/twig',
                'debug' => true,
                'auto_reload' => true,
            ],
        ],

        // monolog settings
        'logger' => [
            'name' => 'app',
            'path' => __DIR__ . '/../log/app.log',
        ],

        // pdo settings
        'db' => [
            'host' => 'localhost',
            'user' => USER,
            'pass' => PASS,
            'dbname' => DB,
        ],
    ],
];
