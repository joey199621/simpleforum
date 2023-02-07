<?php
	require_once("common.php");

	if(isLogin()) {
		// already login, back to home
		header("Location:index.php");
		die();
	}

	// if form submited
	if(isset($_POST['login'])) {
		// check email and password
		if(isset($_POST["email"]) && !empty($_POST["email"]) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
		&& isset($_POST["password"]) && !empty($_POST["password"])) {
			// check the login provided
			if(checkLogin($_POST["email"], $_POST['password'])) {
				header("Location:index.php");
				die();
			}
		}
	}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login - Joey Allard Forum</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<?php 
		include("includes/header.inc.php");
	?>

	<form class="fluid" id="loginForm" method="POST" name="login" action="login.php">
		<input type="email" name="email" placeholder="Email">
		<input type="password" name="password" placeholder="Password">
		<input type="submit" name="login" value="Login">
	</form>
</body>
</html>