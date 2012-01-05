<?php
	include_once "session.php";
	include_once "query.php";
	$eid=$_GET['eid'];
	echo "<script>document.title='Covers for ".getresname($eid)."'</script>";
	echo "<h3>Covers for ".getresname($eid)."</h3>";
	$cov=getallcovers($eid);
	if(database_num_rows($cov)==0)
	{
		echo "No covers added for this yet.";
	}
	else
	{
		echo "<ul>";
		while($cover=database_fetch_array($cov))
		{
			echo "<li class='usercoverli'><a href='$cover[0]'><abbr title='Added by ".getname_uid($cover[1])."'><img class='userbigcover' src='$cover[0]' height='50px'/></abbr></a></li>";
		}
		echo "</ul>";
	}
	
?>
<br/><br/><h3>Add another cover image</h3>
<strong>Please make sure that by linking to an image you are not violating any copyrights.</strong><br/>
Enter the URL of the image:
<input type='text' id='imageurl' />
<br/><br/><?php $uid=$_SESSION['uid'];echo "<span class='custombutton' onclick='addimage(\"$eid\",\"$uid\",\"cover\");'>Add</span>"; ?>
