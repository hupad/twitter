<?php

// web/index.php
$loader = require_once '../vendor/autoload.php';
$loader->add('models', __DIR__);
$loader->add('controllers', __DIR__);

//include __DIR__.'/controllers/UserController.php';

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use models\User;
use models\Tweet;
use controllers\UserController;
use controllers\GlobalController;
use controllers\TweetController;

class TwitterApplication extends Application
{
	use Application\TwigTrait;
	// more traits in the future.
}

$app = new TwitterApplication();

$app['debug'] = true;

/* 
	Register Twig Templates to handle view rendering.
*/
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

/*
	Database registry.
*/
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_mysql',
        'dbname' => 'twitter',
        'host' => 'localhost',
        'user' => 'root',
        'password' => 'root',
        'charset'   => 'utf8mb4'
    )
));

$app->register(new Silex\Provider\SessionServiceProvider());

/*
	Register security provider to handle authentication.
*/
// $app->register(new Silex\Provider\SecurityServiceProvider(), array(
//     'security.firewalls' => array(
//     	'user' => array(
//     		'pattern' => '^/login$',
//     		'form' => array('login_path' => '/login', 'check_path' => '/user/login_check'),
//     		'users' => array(

//     		)
//     	)
//     )
// ));

$app->error(function (\Exception $e, $code) use ($app) {
	if ($app['debug']) {
        return;
    }
    return new Response('We are sorry, but something went terribly wrong.');
});

$app->mount('/global', new GlobalController());
$app->mount('/user', new UserController());
$app->mount('/message', new TweetController());

$app->run();