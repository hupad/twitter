<?php
	
namespace models;

use CRUD\CrudProvider;
	/**
	   * This package is a simple abstraction class for Tweets functions in the MySql database.
	   *
	   * @package models
	*/
class Tweet extends CrudProvider
{
	public function __construct($app_variable){
		parent::__construct($app_variable);
	}
}

?>