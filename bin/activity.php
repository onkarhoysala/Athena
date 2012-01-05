<?php
	/**
	* This file contains the code to display the activity of users based on their id and activity chosen. This is called through ajax.
	* @global $uid The user id 
	* @global $act Activity type
	* 
	*/
	/**
	* Session.php contains all the necessary methods and variables necessay to maintain a session.	
	*/
	include_once "session.php";
	/**
	* Query.php contains all the necessary methods and variables necessary to maintain a database connection. It also contains methods that act as wrapper methods for database queries.
	*/
	include_once "query.php";
	/**
	* @var string This is the user id
	*/
	$uid=$_GET['uid'];
	if($uid=="")
	{
		$uid="0";
	}
	if(isset($_GET['date']))
	{
		$d=$_GET['date'];
		if($d=='today')
		{
			$date=date("o-m-d")." 00:00:00";
		}
		else if($d=="yday")
		{
			$date=database_fetch_array(database_query("select date_sub(now(),interval 1 day)"));
			$date=$date[0];
		}
		else if($d=="lastweek")
		{
			$date=database_fetch_array(database_query("select date_sub(now(),interval 7 day)"));
			$date=$date[0];
		}
		else if($d=="all1")
		{
			$date="0000-00-00 00:00:00";
		}
	}
	else
	{
		$date=date("o-m-d");
	}
	if(isset($_GET['act']))
	{
		/**
		* @var string This is the activity type. This can be "borrowed","returned","edited","added" etc.
		*/
		$act=$_GET['act'];
	}
	else $act='all';
	if(isset($_GET['pg']))
	{
		$pg=$_GET['pg'];
	}
	else $pg=1;
	$pages=$pg*10;
	$pg++;
	if($act=="all" or $act=="%%") $act="%%";
	if(!isset($_GET['uid']) or $_GET['uid']=="0")
	{
		if($dbtype=="mysql")
			$query="select * from log where activity like '$act' and activity not in ('Logged in','Logged out','search','viewed','added a new type') and time_entry > '$date' order by time_entry desc limit 0,$pages;";
		if($dbtype=="pgsql")
			$query="select * from log where activity like '$act' and activity not in ('Logged in','Logged out','search','viewed') and time_entry > '$date' order by time_entry desc limit $pages offset 0;";
	}
	else
	{
		$uid=$_GET['uid'];
		$query="select * from log where activity like '$act' and activity not in ('Logged in','Logged out','search') and uid='$uid' and time_entry > '$date' order by time_entry desc limit 0,$pages;";
	}
	
	$res=database_query($query);
	if(!$res)
	{
		echo database_error();
		die;
	}
	
	echo "<ul>";
		if($act=="%%")
			$act="all";
		echo "<li class='list_header'>When: </li>";
		echo "<li class='act_type' id='today' onclick='loadactivity(\"$act\",\"$uid\",\"today\");'>Today</li>";
		echo "<li class='act_type' id='yday' onclick='loadactivity(\"$act\",\"$uid\",\"yday\");'>Yesterday</li>";
		echo "<li class='act_type' id='lastweek' onclick='loadactivity(\"$act\",\"$uid\",\"lastweek\");'>Last Week</li>";
		echo "<li class='act_type' id='all1' onclick='loadactivity(\"$act\",\"$uid\",\"all1\");'>All</li>";
		echo "</ul><br/><br/>";
	$dt=$_GET['date'];
	echo "<script>$('#today').css('text-decoration','none');$('#yday').css('text-decoration','none');$('#lastweek').css('text-decoration','none');$('#all1').css('text-decoration','none');	$('#$dt').css('text-decoration','underline');</script>";
	echo "<ul class='activity'>";
		
		while($row=database_fetch_array($res))
		{
			$t=str_replace(" ","T",$row[0]);
			echo "<script>jQuery('abbr.timeago').timeago();</script>";
			if($row[1]=="commented on")
			{
				echo "<li class='act'><a href='profile.php?uid=$row[2]'>".getname_uid($row[2])."</a> $row[1] <a href='details.php?eid=$row[3]'>".getresname($row[3])." </a><br/><abbr class='timeago' title='$row[0]'></abbr>";
				$type=gettypename_eid($row[3]);
				if($type=="Book")
				{
					$isbn=database_fetch_array(database_query("select isbn from Book where eid='$row[3]';"));
		                        $src="http://covers.openlibrary.org/b/isbn/".$isbn[0]."-S.jpg";
		                        //$src="http://covers.librarything.com/devkey/c81994d6a074c9e4f577ec44893dd9f1/small/isbn/$isbn[0]";
		                        echo "<img src='$src' alt='Cover' style='float:right;' /><br/>";
				}
				else
				{
					$dets=database_fetch_array(database_query("select location,owner from resource where eid='$row[3]';"));
					echo "<br/><span style='font-weight:bold;float:right'>$type</span>";
					echo "<br/><span style='font-size:9pt;float:right'>Location: $dets[0]</span>";
					echo "<br/><span style='font-size:9pt;float:right'>Owner: $dets[1]</span>";
				}
				echo "<blockquote style='font-size:9pt'>\"$row[4]\"</blockquote>";
				echo "</li>";
			}
			if($row[1]=="wished for")
			{
				echo "<li class='act'><a href='profile.php?uid=$row[2]'>".getname_uid($row[2])."</a> $row[1] <a href='details.php?eid=$row[3]'>".getresname($row[3])." </a><a href='lists.php?list=wishlist&type=2&wid=$row[6]'>$row[4]</a><br/><abbr class='timeago' title='$row[0]'></abbr>";
				
				echo "</li>";
			}
			else
			{
				echo "<li class='act'><a href='profile.php?uid=$row[2]'>".getname_uid($row[2])."</a> $row[1] <a href='details.php?eid=$row[3]'>".getresname($row[3])." </a><br/><abbr class='timeago' title='$row[0]'></abbr>";
				$type=gettypename_eid($row[3]);
				if($type=="Book")
				{
					$isbn=database_fetch_array(database_query("select isbn from Book where eid='$row[3]';"));
		                        $src="http://covers.openlibrary.org/b/isbn/".$isbn[0]."-S.jpg";
		                        //$src="http://covers.librarything.com/devkey/c81994d6a074c9e4f577ec44893dd9f1/small/isbn/$isbn[0]";
		                        echo "<img src='$src' alt='Cover' style='float:right;' /><br/>";
				}
				else
				{
					$dets=database_fetch_array(database_query("select location,owner from resource where eid='$row[3]';"));
					echo "<br/><span style='font-weight:bold;float:right'>$type</span>";
					echo "<br/><span style='font-size:9pt;float:right'>Location: $dets[0]</span><br/>";
					echo "<span style='font-size:9pt;float:right'>Owner: $dets[1]</span>";
				}
				echo "</li>";
			}
		}
		echo "<br/><li class='act1'><span class='custombutton' onclick='loadmoreactivity(\"$act\",\"$uid\",\"$d\",\"$pg\")'>More</span></li>";
	echo "</ul>";
	
	
?>
<script>
	function moreact()
	{
		
	}
</script>
