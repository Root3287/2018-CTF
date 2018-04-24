<?php
use Root3287\classes;
$refUserBadge = classes\DB::getInstance()->get("groups", ["id", "=", $userQuery->data()->group])->first()->badge;
?>
<!DOCTYPE html>
<html>
<head>
	<?php include 'assets/head.php';?>
</head>
<body>
	<?php include 'assets/nav.php';?>
	<div class="container-fluid mt-4">
		<div class="jumbotron">
			<h1><?php echo $userQuery->data()->username;?> <?php echo $refUserBadge;?></h1>
		</div>
	</div>
	<?php include 'assets/foot.php';?>
</body>
</html>