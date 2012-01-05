<?php
	include_once "session.php";
	include_once "query.php";
	$tyid=$_GET['typeid'];
	$res=database_query("select attrib from resource_attrib where tyid='$tyid';");
	while($row1=database_fetch_array($res))
	{
		echo"<br/><span style='font-weight:bold'>$row1[0]</span> <input type='text'  id='$row1[0]' name='$row1[0]'>";
	}
	

?>
