<?php
	namespace controllers;

	use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Silex\Application;
    use Silex\ControllerProviderInterface;

    use models\Tweet;

	/**
	   * The routes used for Tweets.
	   *
	   * @package controllers
	*/
	class TweetController Implements ControllerProviderInterface
	{
        private $error_message = '';
		/**
	       * Connect function is used by Silex to mount the controller to the application.
	       * Please list all routes inside here.
	       * @param Application $app Silex Application Object.
	       *
	       * @return Response Silex Response Object.
       */
    	public function connect(Application $app)
    	{
    		/**
           		* @var \Silex\ControllerCollection $factory
           */
    		$factory = $app['controllers_factory'];

    		$factory->get(
    			'/tweets', 
    			'controllers\TweetController::get_user_tweets'
    		);

    		$factory->post(
    			'/tweets',
    			'controllers\TweetController::create'
    		);

    		return $factory;
    	}

    	public function get_user_tweets(Application $app, Request $request){
    		$user_id = intval( $app['session']->get('user')['id'] );
			$tweet = new Tweet($app);
			$user_tweets = $tweet->get( $user_id );

			return $app->render('tweets.php.twig', array('tweets' => $user_tweets, 'error_message' => $error_message));
    	}

    	public function create(Application $app, Request $request){
    		$message = $request->get('message');
			$user_id = intval( $app['session']->get('user')['id'] );

            if ( strlen($message) <= 140 ) {
                $tweet = new Tweet($app);
                $tweet->message = $message;
                $tweet->user_id = $user_id;
                $tweet->save();

                $user_tweets = $tweet->get( intval($user_id) );
            }else{
                $error_message = "Please limit the message to 140 charecters.";
            }
			
			return $app->render('tweets.php.twig', array('tweets' => $user_tweets, 'error_message' => $error_message));
    	}
	}


?>