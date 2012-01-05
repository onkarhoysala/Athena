<?php
	include_once "../../bin/query.php";
	$userpass=split(":",$_GET['user']);
	$user=$userpass[0];
	$pass=$userpass[1];
	header("Content-Type: application/xml");
	$xml="<?xml version='1.0' encoding='utf-8'?><athena>";
	$val=validate($user,$pass);
	if(!$val)
	{
		$xml.="<error>Authentication failure. Invalid username or password</error>";
	}
	else
	{
		$uid=getuserid($user);
		$check=database_query("select hash from api_register where uid='$uid';");
		$check_num=database_num_rows($check);
		if($check_num!=0)
		{
			$check_array=database_fetch_array($check);
			$sid=$check_array[0];
			$xml.="<authenticate>Success</authenticate><sessionid>$sid</sessionid><info>You need to use this session id for every transaction you do, till you logout.</info>";
		}
		else
		{
			$sid=md5($user.rand(1,10000).$pass);
			$res=database_query("insert into api_register (hash,uid,time_of_login) values (\"$sid\",\"$uid\",now())");
			if(!$res)
			{
				$xml.="<error>".mysql_error()."</error>";
			}
			else
				$xml.="<authenticate>Success</authenticate><sessionid>$sid</sessionid><info>You need to use this session id for every transaction you do, till you logout.</info>";
			api_logger($uid,"Logged in");
		}
		
		
	}
	$xml.="</athena>";
	echo $xml;
?>
