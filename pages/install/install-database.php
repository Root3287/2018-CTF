<?php
require_once "inc/classes/Hash.class.php";
require_once "inc/classes/DB.class.php";
use Root3287\classes\DB as DB;
use Root3287\classes\Hash as Hash;
$db = DB::getInstance();
$data = [];
$data["tableCreate"][] = $db->createTable("groups", [
	"id"=>[
		"INT"=>11,
		"NOT NULL",
		"AUTO_INCREMENT",
	],
	"group_name"=>[
		"TEXT",
		"NOT NULL",
	],
	"permissions"=>[
		"TEXT",
		"NOT NULL",
	],
	"badge" => [
		"MEDIUMTEXT",
	],
	"PRIMARY KEY"=>"id",
]);
$data["tableCreate"][] = $db->createTable("users", [
	"id" => [
		"INT" => 11,
		"NOT NULL",
		"AUTO_INCREMENT",
	],
	"username"=> [
		"VARCHAR" => 50,
		"NOT NULL",
	],
	"password" => [
		"LONGTEXT",
		"NOT NULL",
	],
	"salt" => [
		"LONGTEXT",
		"NOT NULL",
	],
	"name" => [
		"VARCHAR" => 50,
		"NOT NULL",
	],
	"email" => [
		"TEXT",
		"NOT NULL",
	],
	"group" => [
		"INT" => 11,
		"NOT NULL",
	],
	"joined" => [
		"DATETIME",
	],
	"last_online" => [
		"DATETIME",
	],
	"active" => [
		"INT" => 11,
		"DEFAULT '1'",
	],
	"team" => [
		"INT" => 11,
		"DEFAULT '0'",
	],
	"PRIMARY KEY" => "id",
]);
$data["tableCreate"][]= $db->createTable("user_session", [
	"id" => [
		"INT" => 11,
		"NOT NULL",
		"AUTO_INCREMENT",
	],
	"user_id" => [
		"INT" => 11,
		"NOT NULL",
	],
	"hash" => [
		"LONGTEXT",
		"NOT NULL"
	],
	"PRIMARY KEY" => "id",
]);
$data["tableCreate"][] = $db->createTable("adm_user_session", [
	"id" => [
		"INT" => 11,
		"NOT NULL",
		"AUTO_INCREMENT",
	],
	"user_id" => [
		"INT" => 11,
		"NOT NULL",
	],
	"hash" => [
		"LONGTEXT",
		"NOT NULL",
	],
	"PRIMARY KEY" => "id",
]);
$data["tableCreate"][]= $db->createTable("settings", [
	"id" => [
		"INT"=> 11,
		"NOT NULL",
		"AUTO_INCREMENT",
	],
	"name" => [
		"TEXT",
		"NOT NULL",
	],
	"value"=>[
		"LONGTEXT",
	],
	"PRIMARY KEY" => "id",
]);
$data["tableCreate"][]= $db->createTable("logs", [
	"id" => [
		"BIGINT",
		"NOT NULL",
		"AUTO_INCREMENT",
	],
	"date" => [
		"DATETIME",
		"NOT NULL"
	],
	"user" => [
		"INT" => 11,
		"NOT NULL",
	],
	"action"=>[
		"TEXT",
		"NOT NULL",
	],
	"info" => [
		"TEXT",
	],
	"PRIMARY KEY" => "id",
]);

$data['insert'][] = $db->insert("settings", [
	"name" => "title",
	"value" => "CTF",
]);
$data['insert'][] = $db->insert("settings", [
	"name" => "theme",
	"value" => "bootstrap",
]);
$data['insert'][] = $db->insert("settings", [
	"name" => "debug",
	"value" => "Off",
]);
$data['insert'][] = $db->insert("settings", [
	"name" => "unique_id",
	"value" => Hash::unique_length(32),
]);

$data['insert'][] = $db->insert("settings", [
	"name" => "navbar-top",
	"value" => '{ "links":[ { "name": "Challenges", "type": "link", "content": "/challenges/", "haveLogIn": true }, { "name": "Team", "type": "multi-link", "haveLogIn": true, "content": [ { "name": "Create Team", "type": "link", "content": "/team/create/" }, { "name": "Join Team", "type": "link", "content": "/team/join/" } ] } ] }',
]);

$data['insert'][] = $db->insert("settings", [
	"name" => "navbar-bottom",
	"value" => "{\"links\":[]}",
]);

$data['insert'][] = $db->insert("groups", [
	"group_name" => "Standard",
	"permissions" => "{}",
]);
$data['insert'][] = $db->insert("groups", [
	"group_name"=>"Mod",
	"permissions"=> "{\"Mod\":1}",
]);
$data['insert'][] = $db->insert("groups", [
	"group_name"=>"Admin",
	"permissions"=> "{\"Admin\":1, \"Mod\":1}",
]);


// CTF
$data["tableCreate"][]= $db->createTable("ctf_attempt", [
	"id" => [
		"INT" => 11,
		"NOT NULL",
		"AUTO_INCREMENT",
	],
	"team" => [
		"INT" => 11,
		"NOT NULL",
	],
	"challenge" => [
		"INT" => 11,
		"NOT NULL",
	],
	"answer" => [
		"MEDIUMTEXT",
	],
	"answer" => [
		"DATETIME",
	],
	"PRIMARY KEY" => "id",
]);
$data["tableCreate"][]= $db->createTable("ctf_category", [
	"id" => [
		"INT" => 11,
		"NOT NULL",
		"AUTO_INCREMENT",
	],
	"name" => [
		"TEXT",
		"NOT NULL",
	],
	"description" => [
		"TEXT",
	],
	"PRIMARY KEY" => "id",
]);
$data["tableCreate"][]= $db->createTable("ctf_challenge_complete", [
	"id" => [
		"INT" => 11,
		"NOT NULL",
		"AUTO_INCREMENT",
	],
	"team" => [
		"INT" => 11,
		"NOT NULL",
	],
	"challenge" => [
		"INT" => 11,
		"NOT NULL",
	],
	"completed" => [
		"INT" => 11,
		"NOT NULL",
		"DEFAULT '1'",
	],
	"date" => [
		"DATETIME",
	],
	"PRIMARY KEY" => "id",
]);
$data["tableCreate"][]= $db->createTable("ctf_challenges", [
	"id" => [
		"INT" => 11,
		"NOT NULL",
		"AUTO_INCREMENT",
	],
	"name" => [
		"TEXT",
		"NOT NULL",
	],
	"description" => [
		"MEDIUMTEXT",
	],
	"category" => [
		"INT" => 11,
		"NOT NULL",
	],
	"hint" => [
		"MEDIUMTEXT",
	],
	"flag" => [
		"MEDIUMTEXT",
		"NOT NULL",
	],
	"points" => [
		"INT" => 11,
		"NOT NULL",
		"DEFAULT '0'",
	],
	"date" => [
		"DATETIME",
	],
	"PRIMARY KEY" => "id",
]);
$data["tableCreate"][]= $db->createTable("ctf_teams", [
	"id" => [
		"INT" => 11,
		"NOT NULL",
		"AUTO_INCREMENT",
	],
	"name" => [
		"TEXT",
		"NOT NULL",
	],
	"points" => [
		"int"=>11,
		"NOT NULL",
		"DEFAULT '0'",
	],
	"code" => [
		"TEXT",
		"NOT NULL",
	],
	"creator" => [
		"INT" => 11,
		"NOT NULL",
	],
	"public" => [
		"TINYINT" => 1,
		"NOT NULL",
		"DEFAULT 'true'",
	],
	"date" => [
		"DATETIME",
	],
	"PRIMARY KEY" => "id",
]);

?>
<div class="container-fluid">
	<?php
	$i = 0;
	foreach ($data as $d) {
		foreach ($d as $key) {
	?>
		<div class="alert <?php if($key === true){echo "alert-success";}else{echo "alert-danger";} ?>">
			<?php echo "[{$i}]".$name.": "; if($key === true){echo "Executed Command!";}else{echo "Failed to execute command!";}?>
		</div>
	<?php
	$i++;
	}
	}
	?>
	<div class="float-right">
		<a href="/install/register/" class="btn btn-primary">Continue</a>
	</div>
</div>