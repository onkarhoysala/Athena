<?php
	include_once "query.php";
	include_once "session.php";
	$uid=$_SESSION['uid'];
	echo "<script>document.title='Athena - Your favourites'</script>";
	echo "<h3>Favourites</h3>";
	$res=getfav($uid);
	echo "<ul>";
	while($row=database_fetch_array($res))
	{
		echo "<li style='color:#15397B;list-style-type:none;padding:1px'><a href='details.php?eid=$row[0]'>$row[1]</a></li>";

	}
	echo "</ul>";
?>
