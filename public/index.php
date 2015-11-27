<?php

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Ramsey\Uuid\Uuid;
use Slim\App;

require __DIR__ . '/../vendor/autoload.php';

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
    'host' => getenv('RDS_HOSTNAME'),
    'user' => getenv('RDS_USERNAME'),
    'password' => getenv('RDS_PASSWORD'),
    'dbname' => getenv('RDS_DB_NAME'),
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

$app->post('/connections', function ($request, $response, $args) use ($db) {
    $faker = \Faker\Factory::create();

    try {
        $db
            ->insert('connections')
            ->setValue('id', '?')
            ->setValue('name', '?')
            ->setParameter(0, Uuid::uuid4())
            ->setParameter(1, $faker->firstName)
            ->execute();

        $success = true;
    } catch (Exception $e) {
        debug($e);
        $success = false;
    }

    $response = $response->withHeader('Content-type', 'application/json');
    return $response->write(json_encode([
        'success' => $success
    ]));
});

/*
 * RUN
 */
$app->run();
