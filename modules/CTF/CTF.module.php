<?php
use Root3287\classes\Module as Module;
use Root3287\classes\DB as DB;
use Root3287\classes\Output as Output;
use Root3287\classes\User as User;
use Root3287\classes\Redirect as Redirect;
use Root3287\classes\Session as Session;

$ctf = Module::Register("ctf");
$ctf->description =  "CTF Module";
$ctf->author = "Timothy Gibbons";
$ctf->version = "1.0.0";
$ctf->required = true;

$ctf->addPage('/challenges/(.*)', function(){
	require "pages/Challenges.php";
	return true;
});

$ctf->addPage('/team/(.*)/(.*)', function($page){
	$user = new User();
	if(!$user->isLoggedin()){
		Redirect::to('/');
	}
	switch($page){
		case "create":
			require "pages/team/create.php";
			break;
		case "join":
			require "pages/team/join.php";
			break;
		case "leave":
			$user->update([
				"team" => 0,
			]);
			Session::flash("alert-warning", "You have left your team!");
			Redirect::to('/');
			break;
		default:
			return false;
			break;
	}
	return true;
});

$ctf->addPage('/t/(.*)/(.*)', function($team){
	if(!isset($team)){
		return false;
	}
	die();
	if(DB::getInstance()->get('ctf_teams', ["code", "=", Output::clean($team)])->count() >= 1){
		require "pages/Teams.php";
	}else{
		return false;
	}
	return true;
});
$ctf->addPage('/api/challenges/(.*)/(.*)', function($challenge){
	if($challenge){
		$data = DB::getInstance()->get("ctf_challenges", ["id", "=", $challenge])->first();
		$q = [];
		$q["id"] = $data->id;
		$q["name"] = $data->name;
		$q["points"] = $data->points;
		$q["hint"] = $data->hint;
		$q["description"] = $data->description;
		header('Content-Type: application/json');
		die(json_encode($q)); 
	}
	return true;
});
?>