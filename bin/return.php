<?php
	include_once "session.php";
	include_once "query.php";
	$eid=$_GET['eid'];
	$uid=$_GET['uid'];
	$loc=$_GET['loc'];
	if($loc=="" or $loc=="null")
		return;
	$res=database_query("update resource set location='$loc' where eid='$eid';");
	if(!$res)
	{
		echo database_error();
		die;
	}
	$res=database_query("delete from borrowed where eid='$eid';");
	if(!$res)
	{
		echo database_error();
		die;
	}
	logger("returned::$uid::$eid");
	echo "done";	
?>
