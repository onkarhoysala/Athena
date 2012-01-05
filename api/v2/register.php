<?php
	include_once "../../bin/query.php";
	header("Content-Type: application/xml");
	$xml="<?xml version='1.0' encoding='utf-8'?><athena>";
	if(isset($_GET['email'])==0 or isset($_GET['fname'])==0 or isset($_GET['sname'])==0 or isset($_GET['phone'])==0 or isset($_GET['designation'])==0 or isset($_GET['pass'])==0 or isset($_GET['pass_repeat'])==0 or isset($_GET['question'])==0 or isset($_GET['answer'])==0)
	{
		$xml.="<error>You need to enter all fields.</error>";
	}
	else
	{
		$email=escape_sql($_GET['email']);
		$fname=escape_sql($_GET['fname']);
		$sname=escape_sql($_GET['sname']);
		$phone=escape_sql($_GET['phone']);
		$desig=escape_sql($_GET['designation']);
		$pass=escape_sql($_GET['pass']);
		$pass_repeat=escape_sql($_GET['pass_repeat']);
		$question=escape_sql($_GET['question']);
		$answer=escape_sql($_GET['answer']);
	
		if(strlen($email)==0 or strlen($fname)==0 or strlen($sname)==0 or strlen($phone)==0 or strlen($desig)==0 or strlen($pass)==0 or strlen($pass_repeat)==0 or strlen($question)==0 or strlen($answer)==0)
		{
			$xml.="<error>You need to enter all fields.</error>";
		}
	
		if($pass!=$pass_repeat)
		{
			$xml.="<error>You have not entered the same password twice.</error>";
		}
		else
		{
			$check=database_query("select uid from users where email='$email';");
			if(database_num_rows($check)!=0)
			{
				$xml.="<error>This email address has already been registered.</error>";
			}
			else
			{	
				if($dbtype=="mysql")
					$res=database_query("insert into users (email,fname,sname,phone,designation,password,question,answer) values (\"$email\",\"$fname\",\"$sname\",\"$phone\",\"$desig\",password(\"$pass\"),\"$question\",\"$answer\")");
				if($dbtype=="pgsql")
					$res=database_query("insert into users (email,fname,sname,phone,designation,password,question,answer) values (\"$email\",\"$fname\",\"$sname\",\"$phone\",\"$desig\",md5(\"$pass\"),\"$question\",\"$answer\")");
				if(!$res)
				{
					$xml.="<error>Something went wrong. Please contact the administrator at $admin_email.</error>";
				}
				else
				{
					$xml.="<registration>Registration done! You can now login with your username and password.</registration>";
				}
			}
		}
	}
	$xml.="</athena>";
	echo $xml;
?>
