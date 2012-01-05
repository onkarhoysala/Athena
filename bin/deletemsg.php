<?php
	include_once "session.php";
	include_once "query.php";
	$mid=$_GET['mid'];
	$res=database_query("update message set delete_flag='0' where mid='$mid';");
	if(!$res)
		echo database_error();
	else echo "done";
?>
