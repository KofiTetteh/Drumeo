<?php

use App\App;
use Slim\Views\Twig; 
use Slim\Csrf\Guard; 
use Illuminate\Database\Capsule\Manager as Capsule;
use Respect\Validation\Validator as v;

session_start();

require __DIR__ . '/../vendor/autoload.php';

$app = new App;
$container = $app->getContainer(); 
$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'drumeo',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => ''

    ]);

$capsule->setAsGlobal();
$capsule->bootEloquent();
 
Braintree_Configuration::environment('sandbox');
Braintree_Configuration::merchantId('5vdpjgp53pbfxg88');
Braintree_Configuration::publicKey('gqmkc37kj8pzsjpq');
Braintree_Configuration::privateKey('3354a6a8c1598db77e5ae9045a27f3bf');

require __DIR__ . '/../app/routes.php';

$app->add(new \App\Middleware\ValidationErrorsMiddleware($container->get(Twig::class)));
$app->add(new \App\Middleware\OldInputMiddleware($container->get(Twig::class)));
$app->add(new \App\Middleware\CsrfViewMiddleware($container->get(Guard::class),$container->get(Twig::class)));

$app->add($container->get(Guard::class)); 

v::with('App\\Validation\\Rules\\');