<?php namespace Root3287\classes;
class Token{
	public static function generate(){
		return Session::put(Config::get('session/token_name'), md5(uniqid()));
	}
	public static function check($token){
		$tokenName = Config::get('session/token_name');
		if(Session::exists($tokenName) && $token == Session::get($tokenName)){
			Session::delete($token);
			return true;
		}
		return false;
	}
}