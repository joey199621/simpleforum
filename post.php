<?php
	// simple forum
	// this page shows latest posts
	require_once("common.php");
	if(!isLogin()) {
		header("Location:index.php");
		die();
	}

	if(isLogin() && isset($_POST["post"]) && isset($_POST["token"]) && !empty($_POST["token"]) && isset($_SESSION['token']) && $_POST['token'] == $_SESSION["token"]) {
		// post submited with a valid csrf token
		// check data, add to db and redirect to topic if added
		if(isset($_POST["title"]) && !empty($_POST["title"]) && strlen($_POST["title"]) <= 200
		&& isset($_POST["content"]) && !empty($_POST["content"]) ) {
			// ok, add to db
			$id = addTopic($_POST["title"], $_POST["content"]);
			header("Location:topic.php?id=".$id);
			die();
		}
		else {
			header("Location:post.php");
			die();
		}
	}

	$_SESSION['token'] = bin2hex(random_bytes(32));
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>New topic - Joey Allard Forum</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<?php 
		include("includes/header.inc.php");
	?>

	<form class="fluid" id="postForm" action="post.php" action="post.php" method="POST">
	<p>Give a title (max. 200 characters) and write your message</p>

		<input placeholder="Topic title" type="text" name="title">
		<textarea placeholder="Your message" name="content"></textarea>
		<input type="hidden" name="token" value="<?= $_SESSION['token'] ?>" />
		<input type="submit" name="post" value="Post">
	</form>
</body>
</html>