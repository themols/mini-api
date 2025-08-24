<?php
require_once __DIR__ . '/vendor/autoload.php';

$f3 = \Base::instance();
$f3->set('DEBUG', 3);

$config = require __DIR__ . '/src/config/config.php';
$f3->set('config', $config);

$f3->set('DB', new \DB\SQL(
    $config['db']['dsn'],
    $config['db']['username'],
    $config['db']['password'],
    [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
));

require __DIR__ . '/routes.php';

$f3->run();
