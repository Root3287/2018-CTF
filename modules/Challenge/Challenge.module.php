<?php
use Root3287\classes\Module as Module;
use Root3287\classes\DB as DB;
use Root3287\classes\Output as Output;

$ctf = Module::Register("ctf_challenge");
$ctf->description =  "CTF Challenge Module";
$ctf->author = "Timothy Gibbons";
$ctf->version = "1.0.0";
$ctf->required = true;

$ctf->addPage('/ctf/challenges/(.*)/(.*)', function($page){
	if(file_exists("modules/Challenge/Challenge/$page.php")){
		require "Challenge/$page.php";
		return true;
	}
	return false;
});
?>