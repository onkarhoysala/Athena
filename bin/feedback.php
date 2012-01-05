<?php
	include_once "session.php";
	include_once "query.php";
	$_SESSION['page']='feedback.php';
	echo "<script>document.title='Feedback'</script>";
	echo"<div id='feedback'>";
	
	echo"<select name='type' id='type'>";
	echo"<option value='bug'>Bug</option>";
	echo"<option value='feat'>Feature Request</option>";
	echo"<option value='other'>Other</option>";
	echo"</select>";
	echo "<textarea rows='8' cols='65' id='feed_back'></textarea>";
	echo"</br></br>";
	$uid=getuserid($_SESSION['email']);
	echo "<span class='custombutton' onclick='addfeedback(\"$uid\");'>Submit</span>";
	if($_GET['status']=='1')
	{
		echo "<br/><br/>Feedback Submitted<br/>";
	}
	echo"</br></br>";
	echo"<a style='cursor:pointer' id='response'  onclick=load(\"responsepage.php\")>View Other Feedback</a>";
	
	echo"</div>";
?>
