<?php
	include_once "../bin/query.php";
	$q="select fname,sname,email,eid,activity,uid from log natural join users order by time_entry desc limit 20;";
	$res=database_query($q);
	if(!$res)
	{
		echo database_error();
		die;
	}
	header("Content-Type:application/xml");
	$rss="<?xml version='1.0'?><rss version='2.0'>";
	$rss.="<channel>";
		$rss.="<title>CSTEP library</title>";
		$rss.="<link>http://shani.cstep.in/library</link>";
		$rss.="<description>CSTEP library RSS </description>";
		while($row=database_fetch_array($res))
		{
			$rss.="<item>";
				$t=getresname($row[3]);
				$rss.="<title>$row[0] $row[4] $t</title>";
				$rss.="<link>http://shani.cstep.in/library/details.php?eid=$row[3]</link>";
				$rss.="<description>$row[4] by $row[0] $row[1]</description>";
			$rss.="</item>";
		}
	$rss.="</channel>";
	$rss.="</rss>";
	echo $rss;
?>
