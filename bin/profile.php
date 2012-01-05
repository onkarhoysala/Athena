<?php
	include_once "session.php";
	include_once "query.php";
	$uid=$_GET['uid'];
	$email=getemail($uid);
	echo "<script>document.title=\"Athena - ".getname_uid($uid)." | Profile\"</script>";
	if($email==$_SESSION['email'])
	{
		echo "<script>document.location.hash='#myprofile'</script>";
		echo "<script>load('myprofile');</script>";
	}
	$_SESSION['page']="profile.php?uid=$uid";
	echo "<h3>".getname_uid($uid)."</h3>";
	echo "<div id='profile'>";
		$row=getuserdetails($uid);
		echo "<a href='compose.php?uid=$uid' onclick='load(\"compose.php?uid=$uid\");'>Send a mail</a>";
		echo "<br/><br/>First name: $row[2]";
		echo "<br/>Second name: $row[3]";
		echo "<br/>Phone: $row[4]";
		echo "<br/>Designation: $row[5]";
	echo "</div>";
	echo "<br/><br/><h3><a href='#' id='recentactivity_a' onclick='showactivity();'>+</a>Recent activity</h3>";
	echo "<div id='recentactivity'>";
		echo "<ul>";
			echo "<li class='act_type' id='all'><a  onclick='loadactivity(\"all\",\"$uid\",\"today\");'>All</a></li>";
			echo "<li class='act_type' id='borrowed'><a  onclick='loadactivity(\"borrowed\",\"$uid\",\"today\");'>Borrowed</a></li>";
			echo "<li class='act_type' id='returned'><a  onclick='loadactivity(\"returned\",\"$uid\",\"today\");'>Returned</a></li>";
			echo "<li class='act_type' id='added'><a  onclick='loadactivity(\"added\",\"$uid\",\"today\");'>Added</a></li>";
		echo "</ul>";
		echo "<br/>";
		echo "<div id='allactivity'>";
			echo "<script>loadactivity(\"all\",\"$uid\",\"today\");</script>";
		echo "</div>";
	echo "</div>";
	echo "<div id='userfav'>";
		echo "<h3>".getname_uid($uid)."'s Favourites</h3>";
		$fav=getfav($uid);
		if(database_num_rows($fav)==0)
		{
			echo "Nothing here";
		}
		else
		{
			echo "<ul>";
			while($row=database_fetch_array($fav))
			{
				echo "<li><a href='details.php?eid=$row[0]'>$row[1]</a></li>";
			}
			echo "</ul>";
		}
	echo "</div>";
?>
