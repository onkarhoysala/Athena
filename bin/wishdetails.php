<?php
	include_once "query.php";
	include_once "session.php";
	
	$wid=$_GET['wid'];
	$wish=database_query("select * from wishlist where wid='$wid';");
	$row=database_fetch_array($wish);
	$wname=$row[1];
	$tyid=$row[2];
	$typename=str_replace(" ","_",gettypename($tyid));
	$details=database_fetch_array(database_query("select * from ".$typename."wishlist where wid='$wid';"));
	$a=getattr($tyid);
	$num=database_num_rows($a);
	
	echo "<h2>$wname</h2><h3>$typename</h3>";
	for($i=1;$i<=$num;$i++)
	{
		$attr=database_fetch_array($a);
		echo "<strong>$attr[0]</strong>: $details[$i]<br/>";
		if(strtolower($attr[0])=="isbn")
		{
			$isbn=$details[$i];
		}
	}
	if(isset($isbn))
	{
		$src="http://covers.openlibrary.org/b/isbn/".$isbn."-M.jpg";
		echo "<img src='$src' />";
	}
	echo "<br/><span style='text-align:left;margin-left:225px;cursor:pointer;color:orange' onclick='delete_wish(\"$wid\");'>Delete</span>";
?>
