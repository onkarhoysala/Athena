<?php
	include_once "session.php";
	include_once "query.php";
	$eid=$_GET['eid'];
	$uid=$_GET['uid'];
	
	$res=database_query("insert into borrowed values('$eid','$uid',now());");
	if(!$res)
	{
		echo database_error();
		die;
	}
	logger("borrowed::$uid::$eid");
	echo "Borrowed";
?>
