<?php
require_once "app/Root3287/classes/Hash.class.php";
require_once "app/Root3287/classes/DB.class.php";
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
	"value" => "Template",
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
	"value" => "{\"links\":[]}",
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