<?php namespace Root3287\classes;
class Module{
	private static $_instance = [];
	private $_db;
	private $_uri = [], $_callback =[];
	private $_classReg;
	private $_name;
	private $_assets = [];

	public function __construct($module){
		$this->_name = $module;
	}

	public static function Register($module){
		return self::getModules($module);
	}

	public static function getModules($module = null){
		if($module){
			if(!isset(self::$_instance[$module])){
				self::$_instance[$module] = new Module($module);
			}
			return self::$_instance[$module];
		}else{
			return self::$_instance;
		}
	}

	public function addPage($uri, $callback){
		$this->_uri[] = $uri;
		$this->_callback[] = $callback;
	}

	public function getURI(){
		return $this->_uri;
	}

	public function getCallback(){
		return $this->_callback;
	}
	public function getNavItem(){
		return $this->nav;
	}
	public function classAutoload($autoload){
		$this->_classReg = $autoload;
	}
	public function getClassAutoload(){
		return $this->_classReg;
	}
	public function getName(){
		return $this->_name;
	}

	public function addAsset($type, $path, $option = ''){
		switch($type){
			case "css":
				$this->_assets[] = "<link rel=\"stylesheet\" href=\"modules/{$this->_name}/{$path}\" {$option}>";
				break;
			case "js":
				$this->_assets[] = "<script src=\"modules/{$this->_name}/{$path}\" $option></script>";
				break;
		}
	}
}
?>