<?php

	include_once "session.php";
	include_once "query.php";
	$_SESSION['page']="myprofile.php";
	$uid=getuserid($_SESSION['email']);
	echo "<script>document.title=\"Athena - Your profile\"</script>";
	echo "<h3>Your profile</h3>";
	echo "<div id='accountdetails'>";
		echo "<div id='profile'>";
			$row=getuserdetails($uid);
			echo "First name: $row[2]";
			echo "<br/>Second name: $row[3]";
			echo "<br/>Phone: $row[4]";
			echo "<br/>Designation: $row[5]";
			echo "<br/><br/><span class='custombutton' onclick='$(\"#edituser\").show();$(\"#accountdetails\").hide();'>Edit</span>";
			echo "<br/><br/><span class='custombutton' onclick='$(\"#changepass\").show();$(\"#accountdetails\").hide();'>Change password</span>";
		echo "</div>";
		echo "<br/><a href='users.php'>View all Users</a>";
		echo "<br/><br/><h3><span id='recentactivity_a' onclick='showactivity();'>+</span>Recent activity</h3>";
		echo "<div id='recentactivity'>";
			echo "<ul>";
				echo "<li class='act_type' id='all'><a href='#' onclick='loadactivity(\"all\",\"$uid\",\"today\");'>All</a></li>";
				echo "<li class='act_type' id='borrowed'><a href='#' onclick='loadactivity(\"borrowed\",\"$uid\",\"today\");'>Borrowed</a></li>";
				echo "<li class='act_type' id='returned'><a href='#' onclick='loadactivity(\"returned\",\"$uid\",\"today\");'>Returned</a></li>";
				echo "<li class='act_type' id='added'><a href='#' onclick='loadactivity(\"added\",\"$uid\",\"today\");'>Added</a></li>";
			echo "</ul>";
			echo "<br/>";
			echo "<div id='allactivity'>";
				echo "<script>loadactivity(\"all\",\"$uid\",\"today\");</script>";
			echo "</div>";
		echo "</div>";
		getmodule("myprofile.php");
	echo "</div>";
?>
<div id='edituser'>
	<input type='text' id='email' value='<?=$row[1]?>' style='display:none'/>
	First name: <input type='text' id='fname' value=<?=$row[2]?> />
	<br/>Second name: <input type='text' id='sname' value=<?=$row[3]?> />
	<br/>Phone: <input type='text' id='phone' value=<?=$row[4]?> />
	<br/>Designation<input type='text' id='designation' value=<?=$row[5]?> />
	<br/><br/><span class='custombutton' onclick='update();'>Save</span>
	<br/><br/><span class='custombutton' onclick="$('#edituser').hide();$('#accountdetails').show();">Cancel</span>
	<br/><br/><span id='confirm' /></span>
</div>
<div id='changepass'>
	<input type='text' id='email' value='<?=$row[1]?>' style='display:none'/>
	Enter old password: <input type='password' id='oldpass' />
	<br/>Enter new password: <input type='password' id='newpass1' />
	<br/>Re-enter new password: <input type='password' id='newpass2' />
	<br/><br/><span class='custombutton' onclick='changepass();'>Save</span>
	<br/><br/><span class='custombutton' onclick="$('#changepass').hide();$('#accountdetails').show();" >Cancel</span>
	<br/><br/><span id='confirm3' /></span>
</div>

