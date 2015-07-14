<?php

// web/index.php
$loader = require_once '../vendor/autoload.php';
$loader->add('models', __DIR__);

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use models\User;
use models\Tweet;

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


$app->get('/', function() use ($app) {
	return $app->render('index.php.twig', array('error_message' => ''));
});

$app->get('/sign_up', function() use ($app) {
	return $app->render('signup.php.twig');
});

$app->post('/login', function (Request $request) use ($app) {
	
	$email = $request->get('email');
	$password = $app->escape( $request->get('password') );

	if ($email && $password) {
		$user = new User($app);
		$user->email = $email;
		$user->password = md5($password);

		$user_info = $user->get();
		if ($user_info) {
			$app['session']->set('user', array('id' => $user_info['id']));
			return $app->redirect('tweets');
		}else{
			return $app->render('index.php.twig', array('error_message' => "Invalid Credentials. Please try again!"));
		}
	}else{
		return $app->render('index.php.twig', array('error_message' => "Valid Email and password are required!"));
	}
});


$app->post('/sign_up', function (Request $request) use($app) {

	$email = $request->get('email');
	$password = $app->escape( $request->get('password') );
	$confirm_password = $request->get('confirm_password');

	 $user = new User($app);
	 $user->email = $email;
	 $user->password = md5($password);
	 
	 try{
	 	$user->save();	
	 }catch(Exception $e){
	 	 echo 'Caught exception: ',  $e->getMessage(), "\n";
	 }

	return $app->render('tweets.php.twig');
});

$app->get('/tweets', function (Request $request) use ($app) {
	
	$user_id = intval( $app['session']->get('user')['id'] );
	$tweet = new Tweet($app);
	$user_tweets = $tweet->get( $user_id );

	return $app->render('tweets.php.twig', array('tweets' => $user_tweets));
});

$app->post('/tweets', function(Request $request) use ($app) {
	$message = $request->get('message');
	$user_id = intval( $app['session']->get('user')['id'] );

	$tweet = new Tweet($app);
	$tweet->message = $message;
	$tweet->user_id = $user_id;

	$tweet->save();
	$user_tweets = $tweet->get( intval($user_id) );

	return $app->render('tweets.php.twig', array('tweets' => $user_tweets));
});

$app->run();