<?php
require_once __DIR__ . '/../security/security.php';

use App\Security\Security;

Security::check();

return [
    'db' => [
        'dsn'      => 'mysql:host=127.0.0.1;port=3306;dbname=db;charset=utf8mb4',
        'username' => 'root',
        'password' => '',
    ],
    'jwt' => [
        'secret'      => '84cce18a07c141c4b592414df58e9a862a7777471711a775e0cdee4a2d385d277b43441d5b6518c663edb58ab74c62903fe39b79c2358a3d6bdf778ce6140a23',
        'issuer'      => 'api',
        'exp'         => 3600,       // 1 hour
        'refresh_exp' => 1209600,    // 14 days
    ],
    'cors' => [
        'allow_origin'  => '*',
        'allow_headers' => 'Content-Type, Authorization',
        'allow_methods' => 'GET, POST, PUT, PATCH, DELETE, OPTIONS',
    ]
];
