<?php

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Slim\App;

require 'vendor/autoload.php';

function debug($var)
{
    echo '<pre>';
    print_r($var);
    echo '</pre>';
}

/*
 * DATABASE
 */
$config = new Configuration();
$connectionParams = [
    'driver' => 'pdo_mysql',
    'host' => getenv('DB_HOST'),
    'user' => getenv('DB_USER'),
    'password' => getenv('DB_PASSWORD'),
    'dbname' => getenv('DB_DATABASE'),
];
try {
    $conn = DriverManager::getConnection($connectionParams, $config);
    $db = $conn->createQueryBuilder();
} catch (Exception $e) {
    debug($e);
}

/*
 * SLIM
 */
$app = new App;

/*
 * ROUTES
 */
$app->get('/connections', function ($request, $response, $args) use ($db) {
    try {
        $connections = $db
            ->select('*')
            ->from('connections')
            ->execute()
            ->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        debug($e);
    }

    $response = $response->withHeader('Content-type', 'application/json');
    return $response->write(json_encode($connections));
});

/*
 * RUN
 */
$app->run();
