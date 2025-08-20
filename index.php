<?php
require_once __DIR__ . '/vendor/autoload.php';

$f3 = \Base::instance();
$f3->set('DEBUG', 3);

// load config
$config = require __DIR__ . '/config/config.php';
$f3->set('config', $config);

// database connection
$f3->set('DB', new \DB\SQL(
    $config['db']['dsn'],
    $config['db']['username'],
    $config['db']['password'],
    [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
));

// routes
require __DIR__ . '/routes.php';

$f3->run();
