<?php

date_default_timezone_set('Asia/Jakarta');

use Phalcon\Mvc\Micro;

error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', 1);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH);

try {
    /**
     * Read the configuration
     */
    $config = include __DIR__ . "/../config/config.php";

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

    $dotenv = Dotenv\Dotenv::createImmutable(APP_PATH);
    $dotenv->load();

    /**
     * Include Application
     */
    include APP_PATH . '/app.php';

    /*
     * Handle the request
     */
    $app->handle($_SERVER['REQUEST_URI']);
} catch (\Exception $e) {
    return (new Phalcon\Http\Response())->setStatusCode(500)->sendHeaders()->setJsonContent([
        'RC' => '0500',
        'RCM' => 'INTERNAL SERVER ERROR',
    ])->send();
}
