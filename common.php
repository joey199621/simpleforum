<?php
	session_start();
	
	
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

	include("/msconfig.php");
	$db = new PDO('mysql:host=localhost;dbname=jaforum', DBUSER, DBPASS);

	function getLatestTopics() {
		global $db;
		$stmt = $db->prepare("SELECT topic.*, pseudo FROM topic LEFT JOIN user ON author = user.id");
		$stmt->execute();
		$topics = $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];
		return $topics;
	}

	function getTopicById($id) {
		global $db;
		$stmt = $db->prepare("SELECT topic.*, pseudo FROM topic LEFT JOIN user ON author = user.id WHERE topic.id = :id");
		$stmt->bindValue(":id", $id, PDO::PARAM_INT);
		$stmt->execute();
		$topic = $stmt->fetch(PDO::FETCH_ASSOC);
		return $topic;
	}

	function getTopicMessages($topicId, $page, $perPage) {
		global $db;
		$stmt = $db->prepare("SELECT message.*, pseudo FROM message LEFT JOIN user ON message.author = user.id WHERE topic = :topicId ORDER BY datemessage ASC LIMIT :l OFFSET :o");
		$stmt->bindValue(":topicId", $topicId, PDO::PARAM_INT);
		$stmt->bindValue(":l", $perPage, PDO::PARAM_INT);
		$stmt->bindValue(":o", $page*$perPage-$perPage, PDO::PARAM_INT);
		$stmt->execute();
		$messages = $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];
		return $messages;
	}

	function isLogin() {
		return isset($_SESSION['id']);
	}

	function loginUser($id) {
		$_SESSION['id'] = $id;
	}

	function getLoginId() {
		return $_SESSION['id'];
	}

	function checkLogin($email, $password) {
		global $db;
		$stmt = $db->prepare("SELECT * FROM user WHERE LOWER(email) = :email");
		$stmt->bindValue(":email", strtolower(trim($email)));
		$stmt->execute();
		$user  = $stmt->fetch(PDO::FETCH_ASSOC);

		if($user && password_verify($password, $user["password"])) {
			loginUser($user["id"]);
			return true;
		}
		return false;
	}

	function addTopic($title, $content) {
		global $db;
		$stmt = $db->prepare("INSERT INTO topic (author, title) VALUES (:author, :title)");
		$stmt->bindValue(":author", getLoginId(), PDO::PARAM_INT);
		$stmt->bindValue(":title", $title);
		$stmt->execute();
		$topicId = $db->lastInsertId();

		// add the message to topic
		$stmt = $db->prepare("INSERT INTO message (author, topic, content) VALUES (:author, :topic, :content)");
		$stmt->bindValue(":author", getLoginId(), PDO::PARAM_INT);
		$stmt->bindValue(":topic", $topicId, PDO::PARAM_INT);
		$stmt->bindValue(":content", $content);

		$stmt->execute();
		return $topicId;

	}

	function replyToTopic($topicId, $content) {
		global $db;
		$stmt = $db->prepare("INSERT INTO message (author, topic, content) VALUES (:author, :topic, :content)");
		$stmt->bindValue(":author", getLoginId(), PDO::PARAM_INT);
		$stmt->bindValue(":topic", $topicId, PDO::PARAM_INT);
		$stmt->bindValue(":content", $content);
		$stmt->execute();
	}

	function countMessagesInTopic($topicId) {
		global $db;
		$stmt = $db->prepare("SELECT count(*) c FROM message WHERE topic = :topic");
		$stmt->bindValue(":topic", $topicId, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC)["c"];
	}


	function getUserByEmail($email) {
		global $db;
		$stmt = $db->prepare("SELECT * FROM user WHERE LOWER(email) = :email");
		$stmt->bindValue(":email", strtolower(trim($email)));
		$stmt->execute();
		$user  = $stmt->fetch(PDO::FETCH_ASSOC);

		
		return $user;
	}

	function getUserByPseudo($pseudo) {
		global $db;
		$stmt = $db->prepare("SELECT * FROM user WHERE LOWER(pseudo) = :pseudo");
		$stmt->bindValue(":pseudo", strtolower(trim($pseudo)));
		$stmt->execute();
		$user  = $stmt->fetch(PDO::FETCH_ASSOC);

		
		return $user;
	}

	function addUser($email, $pseudo, $password) {
		global $db;
		$stmt = $db->prepare("INSERT INTO user (email, pseudo, password) VALUES (:email, :pseudo, :password)");
		$stmt->bindValue(":email", $email);
		$stmt->bindValue(":pseudo", $pseudo);
		$stmt->bindValue(":password",password_hash($password, PASSWORD_DEFAULT));
		$stmt->execute();

		return $db->lastInsertId();
	}

?>