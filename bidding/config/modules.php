<?php
// config/modules.php

return [
    'namespace' => 'App\Modules',

    'modules' => [
        'Usuarios',
        'Licitacoes',
        'Cronogramas',
        'Processos',
        'Relatorios',
        'Notificacoes',
        'Dashboard',
    ],

    // Configurações específicas para cada módulo
    'module_specific' => [
        'Usuarios' => [
            'enabled' => true,
            'middlewares' => ['web', 'api'],
        ],
        'Licitacoes' => [
            'enabled' => true,
            'middlewares' => ['web', 'api', 'auth:sanctum'],
        ],
        'Cronogramas' => [
            'enabled' => true,
            'middlewares' => ['web', 'api', 'auth:sanctum'],
        ],
        'Processos' => [
            'enabled' => true,
            'middlewares' => ['web', 'api', 'auth:sanctum'],
        ],
        'Relatorios' => [
            'enabled' => true,
            'middlewares' => ['web', 'api', 'auth:sanctum'],
        ],
        'Notificacoes' => [
            'enabled' => true,
            'middlewares' => ['web', 'api', 'auth:sanctum'],
        ],
        'Dashboard' => [
            'enabled' => true,
            'middlewares' => ['web', 'api', 'auth:sanctum'],
        ],
    ],
];
