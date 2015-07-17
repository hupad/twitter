<?php
	namespace controllers;

	use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Silex\Application;
    use Silex\ControllerProviderInterface;

    use models\User;

    /**
	   * The routes used for Users.
	   *
	   * @package controllers
	*/

    class UserController implements ControllerProviderInterface
    {
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
    			'/sign_up', 
    			'controllers\UserController::go_to_sign_up_page'
    		);

    		$factory->post(
    			'/sign_up',
    			'controllers\UserController::create_new_account'
    		);

    		$factory->post(
    			'/login',
    			'controllers\UserController::authenticate'
    		);
    		return $factory;
    	}

    	/**
			* Redirects to Sign up page, where new accounts can be created
			* @param 	Nothing
			*
			* @return Nothing
    	*/
    	public function go_to_sign_up_page(Application $app){
    		return $app->render('signup.php.twig');
    	}

    	/**
			* Creates a New account
			* @param 	Request object - which contains email, password etc
			*
			* @return Nothing
    	*/
    	public function create_new_account(Application $app,Request $request){
    		
    		$email = $request->get('email');
			$password = $app->escape( $request->get('password') );
			$confirm_password = $request->get('confirm_password');

			 $user = new User($app);
			 try{
			 	$user->save(
			 		'user',
			 		array(
			 			'email' => $email,
			 			'password' => md5($password),
						'create_at' => date("Y-m-d H:i:s"),
						'updated_at' => date("Y-m-d H:i:s")
			 		)
			 	);
			 }catch(Exception $e){
			 	 echo 'Caught exception: ',  $e->getMessage(), "\n";
			 }

			return $app->render('tweets.php.twig', array('error_message' => '', 'tweets' => [] ) );
    	}

    	/**
			* Authenticats the user 
			* @param 	Request Object - contains email and password
			*
			* @return Nothing
    	*/
    	public function authenticate(Application $app,Request $request){

    		$email = $request->get('email');
			$password = $app->escape( $request->get('password') );
			if ($email && $password) {	
				$user = new User($app);
				$user_info = $user->find('user',
					array(
						'email' => $email, 
						'password' => md5($password),
					)
				);
				if ($user_info) {
					$app['session']->set('user', 
						array('id' => $user_info[0])
					);
					return $app->redirect($request->getBaseUrl().'/message/tweets');
				}else{
					return $app->render('index.php.twig', array('error_message' => "Invalid Credentials. Please try again!"));
				}
			}else{
				return $app->render('index.php.twig', array('error_message' => "Valid Email and password are required!"));
			}
    	}
    }

?>