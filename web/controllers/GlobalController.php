<?php

	namespace controllers;

	use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Silex\Application;
    use Silex\ControllerProviderInterface;
	/**
	   * The routes used Globally within the application.
	   *
	   * @package controllers
	*/
	class GlobalController Implements ControllerProviderInterface
	{
		public function connect(Application $app)
    	{
    		/**
           		* @var \Silex\ControllerCollection $factory
           */
    		$factory = $app['controllers_factory'];
    		$factory->get(
    			'/', 
    			'controllers\GlobalController::home'
    		);

    		return $factory;
    	}

    	public function home(Application $app){
    		return $app->render('index.php.twig', array('error_message' => ''));
    	}
	}

?>