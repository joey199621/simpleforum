<?php
	// simple forum
	// this page shows latest posts
	require_once("common.php");

	$topics = getLatestTopics();


?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Joey Allard Forum</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<?php 
		include("includes/header.inc.php");
	?>
	<div id="topics" class="fluid">
		<?php 
			foreach ($topics as $topic) {
				?>
				<a class="topic" href="topic.php?id=<?=$topic["id"]?>">
					<span><?=htmlspecialchars($topic["title"])?></span>
					<span class="pseudo">By <?=htmlspecialchars($topic["pseudo"])?> at <?=$topic["datetopic"]?></span>
				</a>
				<?php
			}
		?>
	</div>
</body>
</html>