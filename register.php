<?php
	require_once("common.php");

	if(isLogin()) {
		// already login, back to home
		header("Location:index.php");
		die();
	}

	// if form submited
	if(isset($_POST['register'])) {
		
		if(
			isset($_POST["email"]) && !empty($_POST["email"]) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && strlen($_POST["email"]) <= 200
			&& isset($_POST["password"]) && !empty($_POST["password"]) && strlen($_POST["password"]) >= 8
			&& isset($_POST["password2"]) && $_POST['password'] == $_POST["password2"]
			&& isset($_POST["pseudo"]) && !empty($_POST["pseudo"]) && strlen($_POST["pseudo"]) <= 25 && ctype_alnum($_POST["pseudo"]) 
		) {
				if(!getUserByEmail($_POST["email"]) && !getUserByPseudo($_POST["pseudo"])) {
					// ok, user not existing; register
					$id = addUser($_POST["email"], $_POST["pseudo"], $_POST["password"]);
					if($id) {
						loginUser($id);
					}
					header("Location:index.php");
					die();
			}
			else {
				header("Location:register.php");
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
	<title>Register - Joey Allard Forum</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<?php 
		include("includes/header.inc.php");
	?>
	<form class="fluid" id="loginForm" method="POST" name="register" action="register.php">
	<p>Password should be 8+ characters, username should be max. 25 characters. Username and email are uniques.</p>
		
		<input type="email" name="email" placeholder="Email">
		<input type="password" name="password" placeholder="Password">
		<input type="password" name="password2" placeholder="Repeat password">
		<input type="text" name="pseudo" placeholder="Username">
		<input type="submit" name="register" value="Register">
	</form>
</body>
</html>