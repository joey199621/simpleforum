# simpleforum
Forum scratched in 1 hour

to make it work, create a file named '/msconfig.php' (or change location in common.php file) with the following content:

<?php
  //databaser user
	define("DBUSER", "root");
   //database password
	define("DBPASS", "root");

?>

Create a mysql database named jaforum and import the .sql file

Place the other files (but NOT the sql file) in your server root directory
