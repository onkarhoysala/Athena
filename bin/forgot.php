<?php
	include_once "query.php";
	include_once "session.php";
	echo "<h3>Reset your password</h3>";
	echo "<div id='forgot_result' style='height:200px;display:none;color:grey;font-size:12pt'></div>";
	echo "<div id='forgot'>";
		echo "<script>$('#loginbox').hide();</script>";
		if(isset($_POST['e']))
		{
			
			$x=$_POST['e'];
			$x=escape_sql($x);
			$res=database_query("select uid,question from users where email='$x';");
			$u=database_fetch_array($res);
			$uid=$u[0];
			if($u[1]=="-")
			{
				echo "You have not set a security question yet. For now, enter 'cstep' in the answer box below to reset the password. Please make sure you set the security question after logging in.<br/>Answer:<input type='text' id='answer1'/>";
			}
			else
				echo "<span style='font-weight:bold'>Security question:</span> $u[1]<br/><br/>Your answer:<input type='text' id='answer1'/>";
			echo "<br/><br/><span class='custombutton' onclick='resetpass(\"$uid\");'>Continue</span>";
			die;
		}
		echo "<form action='forgot.php' method='post'>";
		echo "Enter your email: <input type='text' id='email1' name='e' />";
		echo "<br/><input type='submit' class='custombutton' value='Go' />";
		echo "</form>"; 
		//echo "<span class='custombutton' onclick='var ema=$(\"#email1\").val();load(\"forgot.php?e=\"+ema);'>Continue</span>";
	echo "</div>";
	
?>
