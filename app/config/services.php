<?php

/**
 * Shared configuration service
 */
$di->setShared('config', function () {
    return include APP_PATH . '/config/config.php';
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () {
    $config = $this->getConfig();

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $connection = new $class([
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname,
        'charset' => $config->database->charset
    ]);

    return $connection;
});

$di->set(
    "view",
    function () {
        $view = new \Phalcon\Mvc\View();
        $view->setViewsDir(APP_PATH . "/views/");
        $view->registerEngines(
            [
                ".phtml" => 'Phalcon\Mvc\View\Engine\Php',
            ]
        );
        return $view;
    }
);
