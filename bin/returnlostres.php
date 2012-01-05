<?php
	include_once "session.php";
	include_once "query.php";
	$eid=$_GET['eid'];
	$loc=$_GET['loc'];
	$uid=getuserid($_SESSION['email']);
	if($loc=="" or $loc=="null")
		return;
	$res=database_query("update resource set location='$loc' where eid='$eid';");
	if(!$res)
	{
		echo database_error();
		die;
	}
	$res=database_query("update resource set lost='0' where eid='$eid';");
	if(!$res)
	{
		echo database_error();
		die;
	}
	$res=database_query("insert into log values(now(),'found','$uid','$eid','','');");
	if(!$res)
	{
		echo database_error();
		die;
	}
	echo "done";	
?>
