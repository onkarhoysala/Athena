<?php

	include_once "../bin/query.php";
	header("Content-Type: application/xml");
	$email=$_GET['email'];
	$fname=$_GET['fname'];
	$sname=$_GET['sname'];
	$phone=$_GET['phone'];
	$desig=$_GET['designation'];
	$pass=$_GET['password'];
	$pass2=$_GET['passwordrepeat'];
	$xml="<?xml version='1.0'?><session>";
	$email=mysql_real_escape_string($email);
	$fname=mysql_real_escape_string($fname);
	$sname=mysql_real_escape_string($sname);
	$phone=mysql_real_escape_string($phone);
	$desig=mysql_real_escape_string($desig);
	$pass=mysql_real_escape_string($pass);

	$question=$_GET['question'];
	$answer=$_GET['answer'];
	$res=mysql_query("select * from user where email='$email';");
	$val='@cstep.in';
	$st=substr($email,-9);
	$i=substr_count($email,$val);
	if($i>1 or $st!=$val)
	{
		$xml.="<error>Invalid email. Please enter CSTEP email address</error></session>";
		echo $xml;
		die;
	}
	if($fname=="" or $sname=="" or $email=="" or $pass=="" or $question=="" or $answer=="")
	{	
		$xml.="<error>Error creating user. Please check if you have entered necessary details.</error></session>";
		echo $xml;
		die;
	}
	if(mysql_num_rows($res)!=0)
	{
		$xml.="<error>The email address $email has already been registered.</error></session>";
		echo $xml;
		die;
	}
	$res=mysql_query("insert into user values('','$email','$fname','$sname','$phone','$desig',password('$pass'),'$question','$answer');");
	if(!$res)
	{
		$xml.="<error>". mysql_error()."</error></session>";
		echo $xml;
		die;
	}
	else $xml.= "<status>Registration successful.</status></session>";
	echo $xml;
?>
