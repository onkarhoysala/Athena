<?php
	include_once "session.php";
	include_once "query.php";
	echo "<h3>Inbox</h3>";
	$uid=getuserid($_SESSION['email']);
?>
	<script>
		document.title="Athena - Inbox";
	</script>
<?php
	$_SESSION['page']="inbox.php";
	$res=getmessages($uid);
	echo "<ol>";
	if(database_num_rows($res)==0)
	{
		echo "No messages";
	}
	while($row=database_fetch_array($res))
	{
		echo "<li class='message' id='msg$row[7]'>";
			echo "<ul id='msgdetail'>";
				echo "<span style='cursor:pointer;color:#96a8c8' onclick='$(\"#$row[7]\").toggle(\"slow\");markasread(\"$row[7]\");'><li id='msgdetails'><span style='font-weight:bold'>From:</span> ".getname_uid($row[0])."</li></span>";
				echo "<li id='msgdetails'><span style='font-weight:bold'>Subject:</span> $row[3]</li>";
				echo "<div id='$row[7]' style='display:none'>";
					echo "<br/><a href='#compose.php?uid=$row[0]' onclick='load(\"compose.php?uid=$row[0]\");'>Reply</a>";
					echo "<blockquote>$row[4]</blockquote>";
					echo "<br/><span class='custombutton' onclick='deletemsg(\"$row[7]\");'>Delete</span>";
				echo "</div>";
			echo "</ul>";
		echo "</li>";
	}
	echo "</ol>";
?>
