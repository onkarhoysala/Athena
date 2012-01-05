<?php
	include_once "session.php";
	include_once "query.php";
	$res=getusers();
	while($row=database_fetch_array($res))
	{
		if($row[2]!=$_SESSION['uid'])
		echo "<li><a href='profile.php?uid=$row[2]' onclick='load(\"profile.php?uid=$row[2]\");'>$row[0] $row[1]</a></li>";
	}
?>
