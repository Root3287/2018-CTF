<?php namespace Root3287\classes;
class Log{
	private $_db;
	private static $_instance;
	public function __construct(){
		$this->_db = DB::getInstance();
	}

	public static function getInstance(){
		if(!self::$_instance){
			self::$_instance = new Log();
		}
		return self::$_instance;
	}

	public function log($action, $info = null, $user = null){
		if(!$user){
			$userTemp = new User();
			if($userTemp->isLoggedIn()){
				$user = $userTemp->data()->id;
			}
		}
		if($this->_db->insert("Logs", [
			"action" => $action,
			"user" => $user,
			"info" => $info,
			"data" => date("Y-m-d H:i:s"),
		])){
			return true;
		}
		return false;
	}

	public function get($where = []){
		return $this->_db->get("Logs", $where);
	}
}
?>