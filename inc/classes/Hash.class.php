<?php namespace Root3287\classes;
class Hash{
	public static function make($string, $salt= ''){
		return hash('sha512', $string.$salt);	
	}
	
	public static function salt($length) {
		return Hash::unique_length($length);
	}
	public static function unique() {
		return self::make(uniqid());
	}
	public static function unique_length($length){
		return substr(Hash::make(substr(uniqid(rand(), true), 0, 4)), rand(1,13),$length);
	}
}