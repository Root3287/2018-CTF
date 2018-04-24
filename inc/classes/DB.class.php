<?php namespace Root3287\classes;
class DB{
	private static $_instance = array();
	private $_pdo, $_query, $_error = false, $_results, $_count = 0, $_prefix = '';
	

	public function __construct($host, $port, $db, $user, $pass, $prefix = '') {
		try {
			$this->_pdo = new \PDO('mysql:host='.$host.';port='.$port.';dbname='.$db,$user,$pass);
			$this->_prefix = $prefix;
		} catch(\PDOException $e) {
			die($e->getMessage());
		}
		self::$_instance[$db] = $this;
	}
	public static function getInstance($db = null) {
		if(!isset($db)){
			if(count(self::$_instance) > 0){
				reset(self::$_instance);
				$tes = self::$_instance[key(self::$_instance)];
				return $tes;
			}else{
				$db = new DB(Config::get('mysql/host'), Config::get('port'), Config::get('mysql/db'), Config::get('mysql/user'), Config::get('mysql/password'), Config::get('mysql/prefix'));
				return $db;
			}
		}
		return self::$_instance[$db];
	}
	public function query($sql, $params = array()) {
		$this->_error = false;
		if($this->_query = $this->_pdo->prepare($sql)) {
			$i = 1;
			if(count($params)) {
			   	foreach($params as $param) {
			 		$this->_query->bindValue($i, $param);
			 		$i++;
			 	}
			}	 
			if($this->_query->execute()) {
				$this->_results = $this->_query->fetchAll(\PDO::FETCH_OBJ);
				$this->_count = $this->_query->rowCount();
			} else {$this->_error = true;}
		}
		return $this;
	}
	public function action($action, $table, $where = array()) {
		if (count($where) == 3) {
			$operators = array('=','>','<','>=','<=',"IS", "IS NOT");
			$field    = $where[0]; 
			$operator = $where[1];
			$value    = $where[2];
			//check that operator is valid then contstruct the query
			$table = $this->_prefix.$table;
			if (in_array($operator, $operators)){
				$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
				//bind data if there is no errors with the query
				if (!$this->query($sql, array($value))->error()) {return $this;}
			}
		}
		return false;
	}
	public function get($table, $where){
		return $this->action('SELECT *', $table, $where);
	}
	public function delete($table, $where){
		return $this->action('DELETE', $table, $where);
	}
	public function insert($table, $fields = array()) {
		$keys = array_keys($fields);
		$values = '';
		$i = 1;
		foreach ($fields as $field) {
			$values .= "?";
			if ($i < count($fields)) {$values .= ", ";}
			$i++;
		}
		$table = $this->_prefix.$table;
		$sql = "INSERT INTO {$table} (`".implode('`, `', $keys)."`) VALUES ({$values}) ";
		if (!$this->query($sql, $fields)->error()) {return true;}
	
		return false;
	}
	public function update($table, $id, $fields) {
		$set = '';
		$i = 1;
		foreach ($fields as $name => $value) {
			$set .= "{$name} = ?";
			if ($i < count($fields)) {$set .= ', ';}
			$i++;
		}
		$table = $this->_prefix.$table;
		$sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
		if(!$this->query($sql, $fields)->_error) {return true;}
		return false;
	}

	public function createTable($tableName, $data = array()){
		$tableName = $this->_prefix.$tableName;
		$sql = "CREATE TABLE `{$tableName}`(";
		foreach ($data as $fields => $options) {
			if(is_array($options)){
				$sql.="`{$fields}` ";
				$i = 0;
				foreach ($options as $option => $value) {
					if(is_string($option)){
						$sql.= $option."({$value})";
					}else{
						$sql.=$value;
					}

					if($i < count($options)-1){$sql.=" ";}
					$i++;
				}
				$sql.=", ";
			}else if(is_string($options)){
				$sql.="{$fields} (`{$options}`)";
			}
		}
		$sql .= ")";
		// /return $sql;
		$q = $this->query($sql);
		return ($q->error()) ? false : true;
	}

	public function dropTable($table){
		$table = $this->_prefix.$table;
		$q = $this->query("DROP TABLE ?", [$table]);
		return  $q->error() ? false : $q;
	}

	public function addColumn($table, $column, $options){
		$table=$this->_prefix.$table;
		$q = $this->query("ALTER TABLE `{$table}` ADD `{$column}` {$options}");
		return ($q->error())?false:$q;
	}

	public function deleteColumn($table, $colum){
		$table = $this->_prefix.$table;
		$q = $this->query("ALTER TABLE `{$table}` DROP COLUMN `{$colum}` {$options}");
		return ($q->error())?false:$q;
	}

	public function modifyColumn($table, $colum, $data){
		$table = $this->_prefix.$table;
		$q = $this->query("ALTER TABLE `{$table}` MODIFY COLUMN `{$colum}` {$data}");
		return ($q->error())?false:$q;
	}

	public function results(){
		return $this->_results;
	}
	public function count() {
		return $this->_count;
	}
	public function first() {
		$results = $this->results();
		if(count($results) > 0){
			return $results[0];
		}
	}
	public function error() {
		return $this->_error;
	}
}
