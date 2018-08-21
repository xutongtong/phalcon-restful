<?php
use Phalcon\Mvc\Micro;

define('APP_PATH', realpath('.'));
mb_internal_encoding('UTF-8');
ini_set('default_charset', 'UTF-8');
date_default_timezone_set('Asia/shanghai');
//
define('APP_ENV', getenv('ENV'));
define('APP_DEBUG', (bool)get_cfg_var('app.debug'));
//
define('API_PATH', APP_PATH . '/apis');

try {
    /**
     * Read the configuration
     */
    $config = include APP_PATH . '/config/' . APP_ENV . '/config.main.php';


    //合并配置
//    $config->merge($configExt);

    $di = new \Phalcon\Di\FactoryDefault();

    /**
     * Include Services
     */
    include APP_PATH . '/config/services.php';

    /**
     * Include Autoloader
     */
    include APP_PATH . '/config/loader.php';

    /**
     * Starting the application
     * Assign service locator to the application
     */
    $app = new Micro($di);

    /**
     * 由于部分表存在not null字段的默认值为空字符串的问题，导致PresenceOf验证不通过。
     * 所以关闭model的非空字段自动校验
     */
    \Phalcon\Mvc\Model::setup([
        'notNullValidations' => false
    ]);

    /**
     * Include Application
     */
//    include APP_PATH . '/common.php';

    include API_PATH . '/goods.php';
    include APP_PATH . '/test/goods.php';
//    include API_PATH . '/author.php';
//    include API_PATH . '/category.php';
//    include API_PATH . '/collection.php';

    /**
     * Handle the request
     */
    $app->handle();

} catch (\PDOException $e) {
    var_dump("PDOException");
    var_dump($e->getMessage());
    exit();


    if (!$app->response->isSent()) {
        Utils::response('DatabaseError', $e->getMessage() . $e->getTraceAsString())->send();
    }
} catch (\Exception $e) {
    var_dump("Exception");
    var_dump($e->getMessage());
    exit();

    if (!$app->response->isSent()) {
        Utils::response('InternalError', $e->getMessage() . $e->getTraceAsString())->send();
    }
} catch (\Error $e) {
    var_dump("Error");
    var_dump($e->getMessage());
    exit();

    if (!$app->response->isSent()) {
        Utils::response('InternalError', $e->getMessage() . $e->getTraceAsString())->send();
    }
}
