<?php
return new \Phalcon\Config([
    'database' => [
        'adapter' => 'Mysql',
        'host' => '192.168.10.220',
        'readhost' => '192.168.10.220',
        'port' => 30000,
        'username' => 'root',
        'password' => 'baobaobooks',
        'dbname' => 'baobaobooks',
        'charset' => 'utf8mb4',
        'persistent' => false,
        'options' => [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    ],

    'redis' => [
        'host' => '192.168.10.220',
        'port' => 30001,
        'index' => 0,
        'persistent' => true,
        'auth' => '',
    ],
]);