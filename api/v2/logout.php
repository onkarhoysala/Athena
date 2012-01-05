<?php
	include_once "../../bin/query.php";
	$hash=$_GET['hash'];
	$uid=getuserid($_GET['user']);
	header("Content-Type: application/xml");
	$xml="<?xml version='1.0' encoding='utf-8'?><athena>";
	$check=database_query("select * from api_register where uid='$uid' and hash='$hash';");
	if(database_num_rows($check)==0)
	{
		$xml.="<error>You haven't logged in yet!</error>";
	}
	else
	{
		$res=database_query("delete from api_register where hash='$hash';");
		if(!$res)
		{
			$xml.="<error>Something went wrong. Please try again later or contact the admin at $admin_email.</error>";
		}
		else
		{
			$xml.="<logout>Done</logout>";
		}
	}
	$xml.="</athena>";
	echo $xml;
?>
