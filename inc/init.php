<?php
if(file_exists('inc/config.php')){
	require 'inc/config.php';
}

if(file_exists('inc/install.php')){
	require 'inc/install.php';
}

session_start();

require "vendor/autoload.php";

use Root3287\classes;

$directories = scandir("modules");
foreach ($directories as $dir) {
	if(file_exists("modules/{$dir}/{$dir}.module.php")){
		require "modules/{$dir}/{$dir}.module.php";
	}
}

foreach (Root3287\classes\Module::getModules() as $instance) {
	if($instance->getClassAutoload()){
		spl_autoload_register($instance->getClassAutoload());
	}
}

Root3287\classes\PageTimer::start();

require_once 'functions.php';

if(isset($GLOBALS['config']['install']) && classes\Config::get('config/install')){
	$db = classes\DB::getInstance();
	if(classes\Cookies::exists(classes\Config::get('session/cookie_name')) && !classes\Session::exists(classes\Config::get('session/session_name'))){
		$hash = classes\Cookies::get(classes\Config::get('session/cookie_name'));
		$hashCheck= $db->get('user_session', array('hash','=',$hash));
		if($hashCheck->count()){
			$user = new classes\User($hashCheck->first()->user_id);
			$user->login();
		}
	}

	if(classes\Cookies::exists(classes\Config::get('session/admin_cookie_name')) && !classes\Session::exists(classes\Config::get('session/admin_session_name'))){
		$hash = classes\Cookies::get(classes\Config::get('session/admin_cookie_name'));
		$hashCheck= $db->get('adm_user_session', array('hash','=',$hash));
		if($hashCheck->count()){
			$user = new classes\User($hashCheck->first()->user_id);
			$user->login();
		}
	}

	//Error Reporting
	ini_set('diplay_errors', classes\Setting::get('debug'));
	$error_reporting =(classes\Setting::get('debug') == 'Off')? '0':'-1';
	error_reporting($error_reporting);

	//if we didnt set a unique id then lets make one.
	if(classes\Setting::get('unique_id') == null || classes\Setting::get('unique_id') == ""){
		classes\Setting::update('unique_id', substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0,62));
	}

	$user = new classes\User();
	if($user->isLoggedIn()){

		//ACTIVE CODES:
		// 0 - Not Confirm
		// 1 - Active
		// 2 - Temp Ban
		// 3 - Locked
		// 4 - Banned
		if($user->data()->active == 3){
			$user->logout();
		} 

		// Update online status
		$user->update([
			'last_online'=> date('Y-m-d H:i:s'),
		]);
	}
}