<?php
use Root3287\classes;
$user = new classes\User();
if(classes\Input::exists()){
	if(classes\Token::check(classes\Input::get('token'))){
		$val = new classes\Validation();
		$validate = $val->check($_POST, [
			"username" => [
				"required" => true,
			],
			"password" => [
				"required" => true,
			],
		]);

		$msg = "";

		if($validate->passed()){
			if($user->login(classes\Input::get('username'), classes\Input::get('password'))){
				classes\Session::flash('alert-success', "You have been logged in!");
				classes\Redirect::to('/');
			}else{
				$msg .= "Wrong username/password<br>";
				classes\Session::flash('alert-danger', $msg);
			}
		}else{
			foreach ($validate->errors() as $error) {
				$msg.=$error."<br>";
			}
			classes\Session::flash('alert-danger', $msg);
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
		<?php if(classes\Session::exists('alert-danger')): ?>
			<div class="alert alert-danger"><?php echo classes\Session::flash('alert-danger'); ?></div>
		<?php endif; ?>
		<div class="card">
			<div class="card-body">
				<h1 class="text-center">Login</h1>
				<form action="" method="POST">
					<div class="form-group">
						<label for="username">Username:</label><input name="username" type="text" id="username" class="form-control">
					</div>
					<div class="form-group">
						<label for="password">Password:</label><input name="password" type="password" id="username" class="form-control">
					</div>
					<div class="form-row">
						<div class="col"><a href="/register" class="form-control btn btn-md btn-danger">Register</a></div>
						<div class="col"><input type="submit" value="Submit" class="form-control btn btn-md btn-primary"></div>
					</div>
					<input type="hidden" name="token" value="<?php echo classes\Token::generate(); ?>">
				</form>
			</div>
		</div>
	</div>
	<?php include 'assets/foot.php';?>
</body>
</html>