<?php
	include_once "query.php";
	$email=$_POST['email'];
	$old=$_POST['old'];
	$new=$_POST['new'];
	
	$email=escape_sql($email);
	$old=escape_sql($old);
	$new=escape_sql($new);
	
	$row=database_fetch_array(database_query("select password from users where email='$email';"));
	$pass=database_fetch_array(database_query("select password('$old');"));
	if($pass[0]!=$row[0])
	{
		echo "Wrong password. Please re-try.";
		die;
	}
	$newpass=database_fetch_array(database_query("select password('$new');"));
	$res=database_query("update users set password='$newpass[0]' where email='$email';");
	if(!$res)
	{
		echo database_error();
	}
	else echo "Password successfully changed. Please use the new password to login the next time.";
?>
