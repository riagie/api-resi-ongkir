<?php

return new \Phalcon\Config([
    'version'   => 'v1',
    'database'  => [
        'adapter'    => 'Mysql',
        'host'       => '',
        'username'   => '',
        'password'   => '',
        'dbname'     => '',
        'charset'    => 'utf8',
    ],

    'application' => array(
        'baseUri'        => preg_replace('/public([\/\\\\])index.php$/', '', $_SERVER["PHP_SELF"]),
        'appDir'         => APP_PATH . '/',

        'modelsDir'      => APP_PATH . '/models/',
        'helpersDir'     => APP_PATH . '/helpers/',
        'libraryDir'     => APP_PATH . '/library/',
        'migrationsDir'  => APP_PATH . '/migrations/',
        'viewsDir'       => APP_PATH . '/views/',

        'vendorDir'      => BASE_PATH . '/vendor/',
    )
]);
