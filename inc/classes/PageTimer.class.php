<?php namespace Root3287\classes;
class PageTimer{
	private static $_time;
	public static function start(){
		self::$_time = microtime(true);
	}
	public static function end(){
		return microtime(true)-self::$_time;
	}
}
?>