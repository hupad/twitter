<?php

namespace models;

use CRUD\CrudProvider;
	/**
	   * This package is a simple abstraction class for User functions in the MySql database.
	   *
	   * @package models
	*/
class User extends CrudProvider
{
	// public $email;
	// public $password;
	// private $app;

	public function __construct($app_variable){
		//$this->app = $app_variable;
		parent::__construct($app_variable);
	}
}

?>