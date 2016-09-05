<?php
include_once "autoLoader.php";

use Appi\Core\Init;
use Appi\Core\EnumConst;
use Appi\Core\QueryBuilder;
use Appi\Core\Installer;
use Appi\Core\Request;

$init = new Init;
$init->initAppi();

if (isset($_POST['install'])) {
	$qb = new QueryBuilder();
	$qb->connectDataBase($_POST['dbHost'], $_POST['dbName'], $_POST['dbUser'], $_POST['dbPass']);

	$inst = new Installer($qb);
	$inst->install();
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Appi - Install</title>
		<link rel="stylesheet" type="text/css" href="views/css/appi.style.css">
	</head>
	<body>
		<section class="center" style="width:300px; text-align: center;">
			<?php 
			if (isset($_POST['install'])) {
				echo '
				<img src="http://fw.byappi.com/img/progress.gif" style="width: 200px; margin-top: 100px;">
				<h1>Please wait 1 min...</h1>
				';
			} else {
				echo '
				<img src="http://fw.byappi.com/img/logo.png" style="width: 200px; height: 200px;">
				<h1>Fill in required fields</h1>
				<form method="POST">
					<input type="text" name="dbHost" placeholder="DataBase host" required>
					<input type="text" name="dbUser" placeholder="DataBase user name" required>
					<input type="text" name="dbName" placeholder="DataBase name" required>
					<input type="text" name="dbPass" placeholder="DataBase password" required>
					<input type="submit" name="install" value="Install framework">
				</form>
				';
			}
			 ?>
		</section>
	</body>
</html>