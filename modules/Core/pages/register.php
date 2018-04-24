<?php
use Root3287\classes;
$user = new classes\User();
if(classes\Input::exists()){
	if(classes\Token::check(classes\Input::get('token'))){
		$val = new Validation();
		$validate = $val->check($_POST, [
			"username" => [
				"required" => true,
				"min" => 2,
				"unique" => "users",
				"spaces" => false,
			],
			"password_conf" => [
				"required" => true,
				"matches" => "password",
			],
			"password" => [
				"required" => true,
				"matches" => "password_conf",
				"min" => 8,
			],
			"email" => [
				"required" => true,
				"min" => 3,
				"unique" => "users",
				"spaces" => false,
			],
			"name" => [
				"required" => true,
				"min" => 2,
			],
		]);

		$msg = "";

		if($validate->passed()){
			try{
				$salt = classes\Hash::salt(16);
				$user->create([
					"username" => classes\Output::clean(classes\Input::get('username')),
					"password" => classes\Hash::make(classes\Input::get('password'), $salt),
					"salt" => $salt,
					"group" => 1,
					"name" => classes\Output::clean(classes\Input::get('name')),
					"email" => classes\Output::clean(classes\Input::get('email')),
					"joined" => date("Y-m-d H:i:s"),
					"active" => 1,
				]);
				if($user->login(classes\Input::get('username'), classes\Input::get('password'))){
					classes\Session::flash('alert-success', "You have been registered and logged in!");
					classes\Redirect::to('/');
				}
			}catch(\Exception $e){
				classes\Session::flash('alert-danger', $e->getMessage());
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
				<h1 class="text-center">Register</h1>
				<form action="" method="POST">
					<div class="form-group">
						<label for="username">Username:</label><input name="username" type="text" id="username" class="form-control">
					</div>
					<div class="form-group">
						<label for="username">Name:</label><input name="name" type="text" id="name" class="form-control">
					</div>
					<div class="form-group">
						<label for="username">Email:</label><input name="email" type="email" id="email" class="form-control">
					</div>
					<div class="form-row form-group">
						<div class="col">
							<label for="password">Password:</label><input name="password" type="password" id="password" class="form-control">
						</div>
						<div class="col">
							<label for="password_conf">Confirm Password:</label><input name="password_conf" type="password" id="password_conf" class="form-control">
						</div>
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