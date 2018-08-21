<?php
/**
 * Services are globally registered in this file
 *
 * If you are using isolated transactions you have to set the service as non-shared
 * as it needs it to create an isolated transaction, in any other case setting the
 * service as shared will be a safe decision.
 * https://docs.phalconphp.com/en/latest/reference/model-transactions.html#isolated-transactions
 *
 * @var \Phalcon\Config $config
 */

use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\Model\Manager as ModelsManager;

/**
 * Set config
 */
$di->setShared('config', $config);
//
/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () use ($config) {
    return new DbAdapter($config->database->toArray());
});

/**
 * Readonly Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('rDb', function () use ($config) {
    return new DbAdapter([
        'host' => $config->database->readhost,
        'port' => $config->database->port,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname,
        'charset' => $config->database->charset,
        'persistent' => $config->database->persistent,
        'options' => $config->database->options->toArray()
    ]);
});


/**
 * Redis，用于计数等提升性能的数据存储
 */
$di->setShared('redis', function () use ($config) {
    //方法参考：https://github.com/phpredis/phpredis#class-redis
    $redis = new Redis();
    if ($config->redis->persistent) {
        try{
            $redis->pconnect($config->redis->host, $config->redis->port);
        }
        catch (Exception $e) {
            $redis->pconnect($config->redis->host, $config->redis->port);
        }

    } else {
        try{
            $redis->connect($config->redis->host, $config->redis->port);
        }
        catch (Exception $e){
            $redis->connect($config->redis->host, $config->redis->port);
        }
    }

    $config->redis->auth && $redis->auth($config->redis->auth);
    $redis->select($config->redis->index);

    return $redis;
});

//
/*执行SQL查询*/
$di->setShared('modelsManager', function () {
    return new ModelsManager();
});