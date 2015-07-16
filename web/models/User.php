<?php

namespace models;


	/**
	   * This package is a simple abstraction class for User functions in the MySql database.
	   *
	   * @package models
	*/
class User
{
	public $email;
	public $password;
	
	private $app;

	public function __construct($app_variable){
		$this->app = $app_variable;
	}

	/**
	   * Authenticate user with email and password
	   *
	   * @return Array with user information  
	*/
	public function get(){
		return $this->app['db']->fetchAssoc(
			'SELECT * from user where email = ? and password = ?', 
			array($this->email, $this->password)
		);
	}

	/**
	   * Creates a new user in the user table
	   *
	   * @return nothing
	*/
	public function save() {

		$today = date("Y-m-d H:i:s");
		$this->app['db']->insert(
			'user',
			array('email' => $this->email, 'password' => $this->password, 'created_at' => $today, 'updated_at' => $today)
		);
	}
}

?>