<?php
use Root3287\classes\User as User;
use Root3287\classes\Redirect as Redirect;

$user = new User();

if($user->data()->team == 0){
	Redirect::to('/teams');
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
		<h1>Challenges</h1>

	</div>
	<?php include 'assets/foot.php';?>
</body>
</html>