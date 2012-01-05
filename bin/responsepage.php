<?php
	include_once "session.php";
	include_once "query.php";
	$_SESSION['page']='responsepage.php';
	$email=$_SESSION['email'];
	echo "<script>document.title='Feedback response'</script>";
	echo"<h3>Feedback</h3>";
	$res=database_query("select * from feedback order by time_entry desc;");
	echo"<script>";
		echo"$('ul#fb li:odd').css('background-color','#c7d4ec');";
	echo"</script>";
	echo"<ul id='fb'>";
	while($row=database_fetch_array($res))
	{
		if($row[5]==0)
		{
			echo"<li id='feedback_response'>";
			echo "<div id='$row[0]'>";
				echo "Type: ".$row[3];
				echo"<br/><br/>";
				$row[2]=strip_tags($row[2]);
				echo "Message:".$row[2];
				echo"<br/><br/>";
				echo "From: ".$row[1];
				echo"<br/><br/>";
				echo "<span class='custombutton' id='$row[0]' onclick='$(\"#response$row[0]\").show(\"slow\");'>Reply</span><br/><br/>";
				$res1=database_query("select * from feedback where step='$row[0]';");
			
				while($row1=database_fetch_array($res1))
				{
					echo "<blockquote class='replies'>$row1[2]<br/><br/>From:".getname($row1[1])."</blockquote>";
				}
				echo"<div id='response$row[0]' style='display:none'>";
				echo"<br/><textarea id='text$row[0]' rows='8' cols='75'></textarea>";
				echo"<br/><br/><span class='custombutton' onclick='replyfeedback(\"$row[0]\",\"$email\")'>Submit</span>";
			
				echo "</div>";
			echo "</div>";
			echo"</li>";
		}
		
	}
	echo"</ul>";
?>
