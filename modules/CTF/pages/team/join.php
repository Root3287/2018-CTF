<?php
use Root3287\classes\Input as Input;
use Root3287\classes\Validation as Validation;
use Root3287\classes\DB as DB;
use Root3287\classes\Output as Output;
use Root3287\classes\Session as Session;
use Root3287\classes\User as User;
use Root3287\classes\Token as Token;

$user = new User();

if(!$user->isLoggedIn()){
	Redirect::to('/');
}
$currentTeamCode = "";
$currentTeamPoints = 0;

$db = DB::getInstance();
$teams = [];
if(Input::exists()){
	if(Token::check(Input::get('token'))){

	$validation = new Validation();
	$val = $validation->check($_POST, [
		"teamCode" => [
			"required" => true,
		],
	]);

	if($validation->passed()){
		$q = DB::getInstance()->get('ctf_teams', ["code", "=", Output::clean(Input::get("teamCode"))])->first();
		if($q){
			try{
				DB::getInstance()->update("users", $user->data()->id, ["team" => (int)$q->id]);
				Session::flash('alert-success', "You have joined team ".$q->name);
				$currentTeamCode = $q->code;
				$currentTeamPoints = $q->points;
			}catch(Exception $e){
				die($e->getMessage());
			}
		}

		$q2 = $db->get('ctf_teams', ["name", "LIKE", "%".Output::clean(Input::get("teamCode"))."%"]);

		if($q2->count() >= 1){
			foreach ($q2->results() as $data) {
				if($data->public == 0){ 
				}else{
					$teams[] = ["id"=>$data->id, "name" => $data->name, "code"=>$data->code, "date"=>$data->date];
				}
			}
		}
	}else{
		Session::flash('alert-danger', "Missing Fields");
	}
	}
}
foreach ($db->get('ctf_teams', ["1","=","1"])->results() as $data) {
	if($data->public == 0){ 
	}else{
		$teams[$data->name] = ["id"=>$data->id, "name" => $data->name, "code"=>$data->code, "date"=>$data->date];
	}
}
foreach ($db->get('ctf_teams', ["creator","=",$user->data()->id])->results() as $data) {
		$teams[$data->name] = ["id"=>$data->id, "name" => $data->name, "code"=>$data->code, "date"=>$data->date];
}
if($user->data()->team != 0){
	$currentTeamCode = DB::getInstance()->get('ctf_teams', ["id", "=", $user->data()->team])->first()->code;
	$currentTeamPoints = DB::getInstance()->get('ctf_teams', ["id", "=", $user->data()->team])->first()->points;
}
?>
<!DOCTYPE html>
<html>
<head>
	<?php include 'assets/head.php';?>
</head>
<body>
	<?php include 'assets/nav.php';?>
	<div class="container-fluid mt-4">
		<?php if(Session::exists('alert-danger')): ?>
			<div class="alert alert-danger"><?php echo Session::flash('alert-danger'); ?></div>
		<?php endif; ?>
		<?php if(Session::exists('alert-success')): ?>
			<div class="alert alert-success"><?php echo Session::flash('alert-success'); ?></div>
		<?php endif; ?>
		<?php if($user->data()->team != 0): ?>
		<div class="card my-2">
			<div class="card-body">
				<h1>Current Team</h1>
				<p class="card-subtitle text-muted mb-2">Code: <?php echo $currentTeamCode; ?><br>Points: <?php echo $currentTeamPoints; ?></p>
				<a href="/team/leave/" class="btn btn-danger">Leave</a>
			</div>
		</div>
		<?php endif; ?>
		<div class="card my-2">
			<div class="card-body">
				<h1>Join a Team</h1>
				<form action="" method="POST" autocomplete="off">
					<div class="form-group">
						<input type="text" class="form-control" name="teamCode">
					</div>
					<div class="form-group float-right"><input type="hidden" name="token" value="<?php echo Token::generate(); ?>"><input type="submit" value="Search" class="btn btn-primary"></div>
				</form>
			</div>
		</div>
		<div class="card my-2">
			<div class="card-body">
				<h1>Join Listed Team</h1>
				<table class="table">
					<thead>
						<tr>
							<td>Name</td>
							<td>Date</td>
							<td>Members</td>
							<td>Code</td>
						</tr>
					</thead>
					<tbody>
						<?php foreach($teams as $t): 
						$members = DB::getInstance()->get('users', ["team", "=", $t["id"]])->count();?>
						<tr>
							<td><?php echo $t["name"];?></td>
							<td><?php echo $t["date"];?></td>
							<td><?php echo $members; ?></td>
							<td><?php echo $t["code"];?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php include 'assets/foot.php';?>
</body>
</html>