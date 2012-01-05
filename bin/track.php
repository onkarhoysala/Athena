<?php
	include_once "session.php";
	include_once "query.php";
	$eid=$_GET['eid'];
	$title=getresname($eid);
	echo "<script>document.title='Tracking $title'</script>";
	
		echo "<h3>Tracking</h3>";
	$res=database_query("select * from log where eid='$eid' order by time_entry;");
	echo "<ol>";
	while($row=database_fetch_array($res))
	{
		echo "<li class='track'>";
		$email=getemail($row[2]);
		echo $row[1]." by ";
		echo "<a href='profile.php?uid=$row[2]' class='links' onclick='load(\"profile.php?uid=$row[2]\")'>".getname($email)."</a>";
		echo " on ".$row[0]; 
		echo "</li>";
	}
	echo "</ol>";
	

?>
