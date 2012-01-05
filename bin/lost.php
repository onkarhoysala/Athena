<?php
	include_once "session.php";
	include_once "query.php";
	$_SESSION['page']='lost.php';
	echo"<h3>Lost Items :-(</h3>";
	echo"<script>document.title='Athena - Lost Items'</script>";
	$res=getlostres();
	if(database_num_rows($res)==0)
	{
		echo "Nothing's lost :-D";	
	}
	else
	{
		echo"<ul>";
		while($row=database_fetch_array($res))
		{
			echo "<li id='$row[0]'>";
			echo "<a href='#details.php?eid=$row[0]' onclick='load(\"details.php?eid=$row[0]\")'>$row[1]</a>";
			echo "<br/>";
			echo "<br/><span id='$row[0]button' class='custombutton' onclick='var x=prompt(\"What is the location of this item now?\");returnlostres(\"$row[0]\",x);'>Found</span>";
			echo "</li>";
			echo "<br/>";
		}
		echo "</ul>";
	}
	

?>
