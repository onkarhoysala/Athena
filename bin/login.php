<?php
	include_once "session.php";
	include_once "query.php";
	if(strlen($_POST['email'])==0 or strlen($_POST['password'])==0)
	{
		header("Location: ../login.php?error=2");
		die;
	}
	$x=authenticate();
	if($x!="1")
	{
		$_SESSION['page']="browse";
		header("Location:  ../login.php?error=1");
		die;
	}
	else if(!isset($_SESSION['logentry']))
	{
		$uid=$_SESSION['uid'];
		logger("Logged in");
		login($uid);
		$_SESSION['logentry']='set';
	}
	header("Location: ../home.php");
?>
