<?php

namespace models;

class User
{
	public $email;
	public $password;
	
	private $app;

	public function __construct($app_variable){
		$this->app = $app_variable;
	}

	public function get(){
		return $this->app['db']->fetchAssoc('SELECT * from user where email = ? and password = ?', array($this->email, $this->password));
	}

	public function save() {

		$today = date("Y-m-d H:i:s");
		$this->app['db']->insert(
			'user',
			array('email' => $this->email, 'password' => $this->password, 'created_at' => $today, 'updated_at' => $today)
		);
	}
}

?>