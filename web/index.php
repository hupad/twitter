<?php
$loader = require_once '../vendor/autoload.php';
$loader->add('models', __DIR__);
$loader->add('controllers', __DIR__);
$loader->add('CRUD', __DIR__);

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
    Doctrine Service Provider registry
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

/* 
    Session Service Provider Registry
*/
$app->register(new Silex\Provider\SessionServiceProvider());

$app->error(function (\Exception $e, $code) use ($app) {
	if ($app['debug']) {
        return;
    }
    return new Response('We are sorry, but something went terribly wrong.');
});

$app->mount('/global', new GlobalController());
/*
    Ideally, I would like to Inject user object here into the controller
    so that I am setting myself up for writing unit tests easily
*/
$app->mount('/user', new UserController());
/*
    Ideally, I would like to Inject the tweet object here into the controller
    so that I am setting myself up for writing unit tests easily
*/
$app->mount('/message', new TweetController());

$app->run();