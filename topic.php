<?php
	require_once("common.php");

	$perPage = 10;
	$page = 1;

	if(isset($_GET['id']) && !empty($_GET['id']) && ctype_digit($_GET['id'])) {
		$topic = getTopicById($_GET["id"]);
		if(!$topic) {
			header("Location:404.php");
			die();
		}
		else {

			// check if trying to reply to topic
			if(isLogin() && isset($_POST["reply"]) && isset($_POST["token"]) && !empty($_POST["token"]) && isset($_SESSION['token']) && $_POST['token'] == $_SESSION["token"]) {
				// ok, check data
				if(isset($_POST["content"]) && !empty($_POST["content"]) ) {
						// ok, add to db and redirect to last page
						replyToTopic($topic["id"], $_POST["content"]);
						$newMessagesCount = countMessagesInTopic($topic["id"]);
						// get the last page
						$lastPage = ceil($newMessagesCount/$perPage);	
						header("Location:topic.php?id=".$topic["id"]."&page=".$lastPage);
						die();
					}
			}


			// get the messages in the topic, with pagination

			// get the page if provided
			if(isset($_GET['page']) && !empty($_GET['page']) && ctype_digit($_GET['page'])) {
				$page = $_GET['page'];
			}

			$messages = getTopicMessages($topic["id"], $page, $perPage);
			if(!$messages && $page > 1) {
				// trying to access an non existing page, back to page 1
				header("Location:topic.php?id=".$topic["id"]);
				die();
			}

			// print_r($messages);
		}

		// print_r($topic);
	}
	else {
		header("Location:index.php");
		die();
	}

	$_SESSION['token'] = bin2hex(random_bytes(32));
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= htmlspecialchars($topic["title"]) ?> - Joey Allard Forum</title>

	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<?php 
		include("includes/header.inc.php");
	?>

	<div id="topic" class="fluid">
		<?php 
			foreach($messages as $message) {
				?>
					<div class="message">
						<div class="messageUser">
							<span><?=htmlspecialchars($message["pseudo"])?></span>
							<span class="messageUserDate"><?=$message["datemessage"]?></span>
						</div>
						<div class="messageMain"><?=htmlspecialchars($message["content"])?></div>
					</div>
				<?php
			}

		?>
	</div>

	<?php 
		if(isLogin()) {
			?>
<form class="fluid" method="POST" action="topic.php?id=<?=$topic["id"]?>" method="POST">
	<p>Reply to this topic</p>

		<textarea name="content"></textarea>
		<input type="hidden" name="token" value="<?= $_SESSION['token'] ?>" />
		<input type="submit" name="reply" value="Post reply">
	</form>
			<?php
		}

	?>
	
</body>
</html>