<?php
	include_once "session.php";
	include_once "query.php";
	$v=authenticate();
	if($v=="1")
	{
?>
<div id='header'>
	
	<a href="inbox.php" id='ibox'>Inbox<span id='inbox'></span></a>
	<?php
		$uid=$_SESSION['uid'];
		echo "<a href='profile.php?uid=$uid' >Profile</a>";
	?>
	<a href='#' onclick="if(confirm('Do you want to logout?')) window.location='bin/logout.php'">Logout</a>&nbsp;&nbsp;&nbsp;&nbsp;
</div>
<?php
	}
	else
	{
?>
<div id='header'>
	<a href='login.php' style='margin-right:50px;font-size:10pt'>Login</a>
</div>
<?php
	}
?>
