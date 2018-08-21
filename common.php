<?php

/**
 * Executed before every route is executed
 * Return false cancels the route execution
 */
$app->before(function () use ($app) {
    $app->response->setStatusCode(200);
    $app->response->setContentType('application/json');

    //请求客户端类型，取值ayb-ios、ayb-android…
    $clientType = $app->request->getHeader('X-HB-Client-Type');

    if (empty($clientType)) {
        Utils::response('ValidationError', '未传递header参数X-HB-Client-Type')->send();
        return false;
    } elseif (!in_array($clientType, (array)$app->config->allowClientTypes)) {
        Utils::response('ValidationError', 'X-HB-Client-Type不被允许')->send();
        return false;
    }

    //存储客户端请求header中传递的参数
    $client = new stdClass();
    $app->getDI()->set('client', $client);

    //请求客户端类型
    $client->clientType = $clientType;

    $app->getDI()->set('client', $client);

    return true;
});

/**
 * Not found handler
 */
$app->notFound(function () use ($app) {
    return Utils::response('NotFound');
});