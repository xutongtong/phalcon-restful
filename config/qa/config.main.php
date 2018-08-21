<?php
/**
 * Created by PhpStorm.
 * User: tong
 * Date: 2018/8/20
 * Time: 15:43
 */
/*开发测试环境配置*/

error_reporting(E_ALL);

//开发测试环境禁止爬虫爬取,以屏蔽对生成环境的干扰
if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|slurp|spider/i', $_SERVER['HTTP_USER_AGENT'])) {
    header('HTTP/1.1 404 Not Found');
    die();
}

$dir = __DIR__ . '/';
$dbConfig = include $dir . 'config.db.php'; //db 相关配置
$appConfig = include $dir . 'config.application.php';//业务 相关配置

$configMain = new \Phalcon\Config();

$configMain->merge($dbConfig);
$configMain->merge($appConfig);

return $configMain;