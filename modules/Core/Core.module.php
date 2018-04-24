<?php
use Root3287\classes;
$core = Root3287\classes\Module::Register("core");
$core->description =  "Some Module";
$core->author = "Timothy Gibbons";
$core->version = "1.0.0";
$core->required = true;

$core->classAutoload(function($class){
	if (file_exists('modules/Core/classes/'.$class.'.class.php')){
		require 'classes/'.$class.'.class.php';
		return true;
	}
	return false;
}); 

$core->addPage('/', function(){
	require 'pages/index.php';
	return true;
});
$core->addPage('/login', function(){
	$user= new classes\User();
	if($user->isLoggedIn()){
		classes\Session::flash('alert-warning', "You are already logged in!");
		Redirect::to('/');
	}
	require 'pages/login.php';
	return true;
});
$core->addPage('/admin', function(){
	classes\Redirect::to('/admin/');
	return true;
});
$core->addPage('/admin/', function(){
	$user = new classes\User();
	$userAdm = new classes\AdminUser();
	if($userAdm->isLoggedIn()){
		require "pages/admin/index.php";
	}else{
		if($user->isLoggedIn()){
			classes\Redirect::to('/admin/auth/');
		}else{
			classes\Redirect::to('/');
		}
	}
	return true;
});
$core->addPage('/logout', function(){
	$user = new classes\User();
	if(!$user->isLoggedIn()){
		classes\Redirect::to('/');
	}
	$userAdm = new AdminUser();
	if($user->hasPermission("Admin") && $userAdm->isLoggedIn()){
		$userAdm->logout();
	}
	$user->logout();
	classes\Redirect::to('/');
	return true;
});
$core->addPage('/register', function(){
	$user= new User();
	if($user->isLoggedIn()){
		classes\Session::flash('alert-warning', "You are already logged in!");
		classes\Redirect::to('/');
	}
	require 'pages/register.php';
	return true;
});
$core->addPage('/admin/(.*)/(.*)', function($page){
	$user = new classes\User();
	$userAdm = new classes\AdminUser();

	if($user->isLoggedIn()){
		if($userAdm->isLoggedIn()){
			switch ($page) {
				case "logout":
					$userAdm->logout();
					classes\Redirect::to('/admin/');
					break;
				default:
					classes\Redirect::to('/admin/');
					break;
			}
		}else{
			if($page == "auth"){
				require "pages/admin/auth.php";
			}else{
				classes\Redirect::to('/admin/auth');
			}
		}
	}else{
		classes\Redirect::to('/');
	}
	return true;
});
$core->addPage('/u/(.*)/(.*)', function($username){
	$user = new classes\User();
	$userQuery = new classes\User();
	if(!$userQuery->find($username)){
		return false;
	}
	require "pages/users.php";
	return true;
});
$core->addPage('/user/', function(){
	return true;
});
$core->addPage('/user/(.*)/(.*)', function($page){
	return true;
});
?>