<?php
use Root3287\classes\Module as Module;
$ctf = Module::Register("core");
$ctf->description =  "CTF Module";
$ctf->author = "Timothy Gibbons";
$ctf->version = "1.0.0";
$ctf->required = true;

$ctf->addPage('/challenges/(.*)', function(){
	require "pages/Challenges.php";
	return true;
});

$ctf->addPage('/team/(.*)/(.*)', function($page){
	switch($page){
		case "create":
			break;
		case "join":
			break;
		case "setting":
			break;
	}
	return true;
});

$ctf->addPage('/t/(.*)/(.*)', function($team){
	if(!isset($team)){
		//TODO: team listings.
	}
	//Team profile
});
?>