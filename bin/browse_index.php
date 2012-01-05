<?php
	include_once "session.php";
	include_once "query.php";
	$_SESSION['page']="browse_index";
	if(!isset($_GET['l']) or $_GET['l']=="")
	{
		$l='';
	}
	else $l=$_GET['l'];
	
	if(!isset($_GET['p']) or $_GET['p']=="")
	{
		$p='';
	}
	else $p=$_GET['p'];
	
	if(!isset($_GET['t']) or $_GET['t']=="")
	{
		$t='';
	}
	else $t=$_GET['t'];
	
	echo "<h3>Browse</h3>";
	echo "<div id='alldetails'>";
		//Display alphabets
		echo "<script>$('ul.uldetails li:odd').addClass('oddli');$('#$l').css('text-decoration','underline');</script>";
		
		echo "<ul>";
			echo "<li class='alphabet'>Browse by</li>";
			echo "<li class='alphabet'><a href='#' onclick='load(\"browse\");'>All</li>";
			for($i='A',$j=1;$j<=26;$i++,$j++)
				echo "<li class='alphabet' id='$i'><a href='#' onclick='load(\"browse.php?l=$i&p=$p&t=$t\");'>$i<a/></li>";
		echo "</ul>";
		echo "<br/><br/>";
		
		//Fetch resource details and display each of them in a div
		$res=getresources($l,$p,$t);
		
		echo "<ul class='uldetails'>";
		while($row=database_fetch_array($res))
		{
			echo "<li class='lidetails'>";
			echo "<div class='details' id='$row[0]'>";
			$eid=$row[0];
			$uid=getuserid($_SESSION['email']);
			echo "<a href='#' onclick='load(\"details.php?eid=$eid\");'>$row[1]</a>";
			echo "<p class='resname'>";
			echo "Type: ".gettypename($row[5]);
			
			$b=checkborrow($eid);
			if($b=="1")
			echo "<br/><a href='#' onclick='$(\"#loginbox\").slideToggle(\"slow\");'>Login</a> to borrow";
			else echo "<br/>Borrowed by ".getname_uid($b);
			
			echo "</p>";
			
			echo "</div>";
			echo "</li>";
		}
		echo "</ul>";
		
	echo "</div>";
?>
