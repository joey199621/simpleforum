<header>
	<a href="index.php"><h1>Joey Allard Forum</h1></a>
	<a href="index.php">Forum home</a>

	<?php 
		if(!isLogin()) {
			?>
			<a href="login.php">Login</a>
			<a href="register.php">Register</a>
			<?php
		}
		else {
			?>
			<a href="post.php">Post a topic</a>
			<a href="logout.php">Logout</a>
			<?php
		}

	?>
</header>