<?php
	include_once "session.php";
	include_once "query.php";
	$uid=$_GET['uid'];
	$email=getemail($uid);

	echo "<script>document.title='Athena - Compose';</script>";

	echo "<h3>Compose</h3>";
	echo "<div id='compose'>";
	echo "<br/>To<input type=text' value='$email' id='to' size='60'/>";
	echo "<br/>Subject:<input type='text' id='subject' size='60' />";
	echo "<br/><textarea rows='30' cols='70' id='msg' style='text-align-left'></textarea>";
	echo "<br/><span id='cuid' stye='display:none'>$uid</span>";
	echo "<br/><input type='button' value='Send' onclick='send();'/>";
	echo "</div>"
?>
