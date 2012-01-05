<?php
	include_once "query.php";
	include_once "session.php";
	$uid=$_GET['uid'];
	$a=$_GET['a'];
	$res=database_query("select * from users where answer='$a' and uid='$uid';");
	if(database_num_rows($res)==0)
	{
		echo "The answer to your security question was wrong. Please try again.";
		die;
	}
	$r=rand_str(10);
	//echo $r;
	$res=database_query("update users set password=password('$r') where uid='$uid';");
	if(!$res)
	{
		echo database_error();
		die;
	}
	else echo "<script>$('#forgot').hide();</script>Your password has been reset to $r. Please login with this password and reset your password from your Profile. <br/>Click <a href='login.php'>here</a> to login.";
?>
