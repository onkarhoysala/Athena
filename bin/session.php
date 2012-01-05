<?php
	include_once "query.php";
	session_start();
	function authenticate()
	{
		global $_SESSION;
		global $_POST;
		if(isset($_POST['email']) and isset($_POST['password']))
		{
			$v=validate($_POST['email'],$_POST['password']);
			if($v=="1")
			{
				$_SESSION['email']=$_POST['email'];
				$_SESSION['uid']=getuserid($_POST['email']);
				$uid=$_SESSION['uid'];
				
				if($_POST['email']=="admin")
					header("Location: admin.php");
				return "1";
			}
			else
			{
				header("Location: login.php?error=1");
			}
		}
		else if(isset($_SESSION['email']))
				return "1";
		else if($_POST['password']=="" or $_SESSION['email']=="")
		{
			if(isset($_SESSION['email']))
				return "1";
			else
			{
				//header("Location: index.php?error=2");
			}
		}
	}
?>
