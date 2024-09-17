<?php

/**
 * Registering an autoloader
 */
$loader = new \Phalcon\Loader();

$loader->registerDirs(
    [
        $config->application->modelsDir,
        $config->application->helpersDir,
        $config->application->libraryDir,
        $config->application->vendorDir,
    ]
)->register();

$loader->registerNamespaces(
    [
        'App\Helpers' => APP_PATH . '/helpers/',
        'App\Library' => APP_PATH . '/library/',
        'App\Library\Resi' => APP_PATH . '/library/resi/',
    ]
)->register();

$loader->registerFiles(
    [
        BASE_PATH . '/vendor/autoload.php',
    ]
)->loadFiles();
