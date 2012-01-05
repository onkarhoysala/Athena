<?php
	include_once "session.php";
	include_once "query.php";
	$uid=getuserid($_SESSION['email']);
	$res=database_query("select count(*) from message where to_uid='$uid' and read_flag='1' and delete_flag='1';");
	$no=database_fetch_array($res);
	echo $no[0];
?>
