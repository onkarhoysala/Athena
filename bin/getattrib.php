<?php
	include_once "query.php";
	$i=$_GET['i'];
	$tyid=$_GET['tyid'];
	$res=database_query("select attrib from resource_attrib where tyid='$tyid' limit $i,1;");
	$row=database_fetch_array($res);
	echo $row[0];
?>
