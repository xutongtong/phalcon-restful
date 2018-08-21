<?php
/**
 * Created by PhpStorm.
 * User: tong
 * Date: 2018/8/20
 * Time: 14:07
 */
$app->get('/hello', function () use ($app) {
    echo "Hello World!";
    exit();
});