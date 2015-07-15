<?php
	
namespace models;

	/**
	   * This package is a simple abstraction class for Tweets functions in the MySql database.
	   *
	   * @package models
	*/
class Tweet
{
	public $message;
	public $user_id;

	private $app;

	public function __construct($app_variable){
		$this->app = $app_variable;
	}

	/**
	   * Saves the tweet/message posted by the user to the Tweets table.
	   *
	   * @return nothing
	*/
	public function save(){
		$today = date("Y-m-d H:i:s");
		$this->app['db']->insert(
			'tweets',
			array('tweet' => $this->message, 'user_id' => $this->user_id, 'created_at' => $today, "updated_at" => $today)
		);
	}

	/**
	   * Fetches the list of all tweets posted by logged in user.
	   *
	   * @return list of all tweets by user
	*/
	public function get($user_id){
		return $this->app['db']->fetchAll( 'SELECT * from tweets where user_id = ?', array($user_id) );
	}
}

?>