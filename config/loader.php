<?php

/**
 * Registering an autoloader
 */
$loader = new \Phalcon\Loader();

/**
 * 通过目录自动加载
 */
$loader->registerDirs(
    [
        $config->application->modelsDir,
        $config->application->servicesDir,
        $config->application->utilsDir,
    ]
);

//Register autoloader
$loader->register();