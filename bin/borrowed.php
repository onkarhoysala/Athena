<?php
	include_once "session.php";
	include_once "query.php";
	$_SESSION['page']="borrowed";
	echo "<script>document.title='Athena - Borrowed';</script>";
	echo "<h3>Stuff you have borrowed</h3>";
	echo "<script>$('ul.uldetails li:odd').css('background-color','#b4c1d9');</script>";
	$uid=getuserid($_SESSION['email']);
	$res=borrowed($uid);
	if(database_num_rows($res)==0)
	{
		echo "<p style='color:grey;font-size:14;padding-left:5px'>You haven't borrowed anything</p>";
	}
	echo "<ul class='uldetails' id='ul_details_borrowed'>";
	while($row=database_fetch_array($res))
	{

		$eid=$row[0];
		//echo "<a href='http://www.google.com' style='display:block'>";
		echo "<li class='lidetails' id='$row[0]' onclick=\"window.location='details.php?eid=$eid'\" style='z-index:10'>";
		//echo "</a>"
		//echo "<div class='details'>";
		$uid=getuserid($_SESSION['email']);
		echo "<a href='details.php?eid=$eid' style=\"font-weight:bold;\">$row[4]</a>";
		echo "<p class='resname'>";
		echo "Type: ".gettypename($row[5]);
		$booktitle=str_replace("\"","",stripslashes($row[4]));
		echo "<br/><br/><span id='$row[0]button' class='custombutton'  onclick='var x=prompt(\"What is the location of $booktitle now?\");returnres(\"$eid\",\"$uid\",x);' style=' position:absolute;z-index:100;'>Return</span>";
		echo "</p>";
		//echo "</div>";
		echo "</li>";

		//echo "</a>";
		
	}
	echo "</ul>";
?>
