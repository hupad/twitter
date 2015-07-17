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
			* CREATE
			* @param: 
			*	. $table: Table from the database
			*	. $values: Values to be inserted = of type ARRAY
		*/
		public function save($table, $values){
			$this->_app['db']->insert($table, $values);
		}

		/**
			* READ
			* @param: 
				* $table: Table from the database
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
			return $this->_app['db']->fetchArray(
				"SELECT * from " . $table . " where " . $clause,
				$values
			);
		}

		/**
			* UPDATE
			* @param: 
			*	. $table: Table from the database
			*	. $values: Values to be updated, type Array
		*/
		public function udpate(){
			$this->_app['db']->update($table, $values);
		}

		/**
			* DELETE
			* @param: 
			*	. $sql: Main Sql query to be executed
			*	. $values: Values to be inserted = of type ARRAY
		*/
		public function delete($table, $values){
			$this->_app['db']->delete($table, $values);
		}

	}

?>