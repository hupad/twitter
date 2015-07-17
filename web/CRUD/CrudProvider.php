<?php

	namespace CRUD;

	/**
	   * This class should act like parent to all the Models.
	   * Should be containing, all the common functionalities
	   * accross the models
	   *
	   * @package CRUD
	*/
	class CrudProvider
	{

		private $_app;

		function __construct($app){
			$this->_app = $app;
		}

		/**
			* @param: 
				* $sql: Main Sql query to be executed
				* $where: Where clause, type Array
		*/
		public function find($table, $where){
			$clause = '';
			$values = [];

			if ( count($where) == 0	) {
				return $this->_app['db']->fetchAll(
					"SELECT * from " . $table
				);
			}
			foreach($where as $key => $value){
				
				if ( strlen($clause) != 0 ) {
					$clause .= ' and ';
				}
				$clause .= $key . ' = ? ';
				array_push($values, $value);
			}
			echo var_dump($values);
			return $this->_app['db']->fetchAssoc(
				"SELECT * from " . $table . " where " . $clause,
				$values
			);
		}
		
		/**
			* @param: 
			*	. $sql: Main Sql query to be executed
			*	. $where: Where clause, type Array
		*/
		public function save($table, $values){
			$this->_app['db']->insert($table, $values);
		}
	}

?>