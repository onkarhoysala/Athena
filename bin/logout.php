<?php
	include_once "session.php";
	$uid=$_SESSION['uid'];
	logout($uid);
	logger("Logged out");
	//session_start();
	session_destroy();
	
	header("Location: ../index.php");
?>
