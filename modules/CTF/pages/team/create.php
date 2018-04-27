<?php
use Root3287\classes\User as User;
use Root3287\classes\Input as Input;
use Root3287\classes\Token as Token;
use Root3287\classes\Session as Session;
use Root3287\classes\DB as DB;
use Root3287\classes\Output as Output;
use Root3287\classes\Hash as Hash;
use Root3287\classes\Validation as Validation;
use Root3287\classes\Redirect as Redirect;

$user = new User();
if(!$user->isLoggedIn()) {
	Redirect::to('/');
}

if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$validate = new Validation();
		$val = $validate->check($_POST, [
			"name" => [
				"required" => true,
				"min" => 2,
				"unique" => "ctf_teams",
			],
		]);

		if($val->passed()){
			DB::getInstance()->insert('ctf_teams', [
				"name" => Output::clean(Input::get('name')),
				"code" => Hash::unique_length(16),
				"creator" => $user->data()->id,
				"date" => date("Y-m-d H:i:s"),
				"public" => (Input::get('public') !== null)? true: false,
			]);

			Redirect::to("/t/{$code}/");
		}else{
			$msg = "";
			foreach($val->errors() as $e){
				$msg .= $e."<br>";
			}
			Session::flash('alert-danger', $msg);
		}
	}
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
		<h1>Team Create</h1>
		<?php if(Session::exists('alert-danger')): ?>
			<div class="alert alert-danger"><?php echo Session::flash('alert-danger'); ?></div>
		<?php endif; ?>
		<div class="card">
			<div class="card-body">
				<form action="" method="POST">
					<div class="form-group">
						<label for="name">Team Name:</label>
						<input type="text" name="name" id="name" class="form-control" value="<?php echo Input::get('name'); ?>">
					</div>
					<div class="form-check">
						<input type="checkbox" name="public" id="public" class="form-check-input" value="<?php echo (Input::get('public') !== null)?true:false; ?>">
						<label class="form-check-label" for="public">Public
						</label>
					</div>
					<div class="form-group float-right">
						<input type="submit" value="Submit" class="btn btn-primary">
						<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
					</div>
				</form>
			</div>
		</div>
	</div>
	<?php include 'assets/foot.php';?>
</body>
</html>