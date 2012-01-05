<?php
	mysql_connect("localhost","csteplib","cstep.321");
	mysql_select_db("lib");
	header("Content-Type:application/xml");
	$r="<session>";
	$u=$_GET['user'];
	$p=$_GET['password'];
	$pass=mysql_fetch_array(mysql_query("select password('$p');"));
	$res=mysql_query("select * from user where password='$pass[0]' and email='$u';");
	if(!$res)
	{
		echo mysql_error();
	}
	if(mysql_num_rows($res)==0)
	{
		$r.="<authentication>Failure</authentication></session>";
		echo $r;
		die;
	}
	else
	{
		session_start();
		$session=session_id();
		$s=base64_encode("$u $p $session");
		$s=str_replace("=","-",$s);
		session_id($s);
		$_SESSION['email']=$u;
		$r.="<sessionid>$s</sessionid></session>";
		echo $r;
	}
?>	
