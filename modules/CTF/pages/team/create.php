<?php
use Root3287\classes\User as User;
$user = new User();
if(!$user->isLoggedIn()) {
	Redirect::to('/');
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
		
	</div>
	<?php include 'assets/foot.php';?>
</body>
</html>