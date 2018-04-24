<?php
require 'inc/init.php';

$router = new Root3287\classes\Router();

$router->add('/install/', function(){
	Root3287\classes\Redirect::to('/install/intro/');
	return true;
});
$router->add('/install/(.*)/(.*)', function($step){
	$step = escape($step);
	if(file_exists('pages/install/install.php') && !isset($GLOBALS['config']['install'])){
		require 'pages/install/install.php';
	}else{
		Root3287\classes\Redirect::to('/');
	}
	return true;
});
$router->add('/404', function(){
	require 'pages/errors/404.php';
	return true;
});

foreach (Root3287\classes\Module::getModules() as $instance) {
	foreach($instance->getURI() as $page => $page_val){
		$router->add($page_val, $instance->getCallback()[$page]);
	}
}

if(!$router->run()){
	Root3287\classes\Redirect::to(404);
}