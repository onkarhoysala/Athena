<?php
	include_once "../../bin/query.php";
	header("Content-Type: application/xml");
	$xml="<?xml version='1.0' encoding='utf-8'?><athena>";
	$case=$_GET['case'];
	if(!isset($_GET['case']))
	{
		$xml.="<error>You haven't selected what to browse. Options are 'popular', 'all', 'types','borrowed','favourites' or the typename. In the latter two cases, you need to specify the start position 'start' and the limit 'limit'.</error>";
	}
	else if($case=="popular")
	{
		$xml.="<popular>";
		$res=most_popular();
		while($data=database_fetch_array($res))
		{
			$xml.="<resource><eid>$data[0]</eid><name>".getresname($data[0])."</name></resource>";
		}
		$xml.="</popular>";
	}
	else if($case=="all")
	{
		$xml.="<all>";
		if(!isset($_GET['start']))
			$start=1;
		else $start=$_GET['start'];
		if(!isset($_GET['limit']))
			$limit=10;
		else $limit=$_GET['limit'];
		
		$res=database_query("select eid from resource limit $limit offset $start");
		while($row=database_fetch_array($res))
		{
			$xml.="<resource><eid>$row[0]</eid><name>".getresname($row[0])."</name></resource>";
		}
		$xml.="</all>";
	}
	else if($case=="borrowed")
	{	
		$xml.="<borrowed>";
		if(!isset($_GET['sessionid']))
		{
			$xml.="<error>Please enter the sessionid.</error>";
			$xml."</borrowed>";
			$xml.="</athena>";
			echo $xml;
			die;
		}
		$hash=$_GET['sessionid'];
		$getuser=database_query("select uid from api_register where hash='$hash';");	
		if(database_num_rows($getuser)==0)
		{
			$xml.="<error>You haven't logged in yet!</error>";
		}
		else
		{
			$uid=database_fetch_array($getuser);
			$list_borrowed=borrowed($uid[0]);
			if(database_num_rows($list_borrowed)==0)
			{
				$xml.="<info>You have not borrowed anything.</info>";
			}
			else
			{
				while($item=database_fetch_array($list_borrowed))
				{
					$xml.="<resource><eid>$item[0]</eid><name>".getresname($item[0])."</name></resource>";
				}
			}	
			
		}
		$xml.="</borrowed>";
	}
	else if($case=="favourites")
	{	
		$xml.="<favourites>";
		if(!isset($_GET['sessionid']))
		{
			$xml.="<error>Please enter the sessionid.</error>";
			$xml."</favourites>";
			$xml.="</athena>";
			echo $xml;
			die;
		}
		$hash=$_GET['sessionid'];
		$getuser=database_query("select uid from api_register where hash='$hash';");	
		if(database_num_rows($getuser)==0)
		{
			$xml.="<error>You haven't logged in yet!</error>";
		}
		else
		{
			$uid=database_fetch_array($getuser);
			$list_fav=database_query("select eid from favourites where uid='$uid[0]';");
			if(database_num_rows($list_fav)==0)
			{
				$xml.="<info>You have not added anything to your list of favourites.</info>";
			}
			else
			{
				while($item=database_fetch_array($list_fav))
				{
					$xml.="<resource><eid>$item[0]</eid><name>".getresname($item[0])."</name></resource>";
				}
			}	
			
		}
		$xml.="</favourites>";
	}
	$xml.="</athena>";
	echo $xml;
?>
