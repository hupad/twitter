<?php
	
namespace models;

class Tweet
{
	public $message;
	public $user_id;

	private $app;

	public function __construct($app_variable){
		$this->app = $app_variable;
	}

	public function save(){
		$today = date("Y-m-d H:i:s");
		$this->app['db']->insert(
			'tweets',
			array('tweet' => $this->message, 'user_id' => $this->user_id, 'created_at' => $today, "updated_at" => $today)
		);
	}

	public function get($user_id){
		return $this->app['db']->fetchAll( 'SELECT * from tweets where user_id = ?', array($user_id) );
	}
}

?>