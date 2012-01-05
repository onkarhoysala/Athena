<?php
	header("Content-Type:application/xml");
	$response="<resourcetype>";
	include_once "apiquery.php";
	include_once "../bin/query.php";
	if(isset($_GET['tyid']))
	{
		$tyid=$_GET['tyid'];
		$typename=gettypename($tyid);
		if($typename=="")
		{
			$response.="<error>No type for type id $tyid exists</error>";
			$response.="</resourcetype>";
			echo $response;
			die;
		}
		$response.="<tyid>$tyid</tyid><typename>$typename</typename>";
		$res=mysql_query('desc resource;');
		while($row=mysql_fetch_array($res))
		{
			$response.="<attribute>$row[0]</attribute>";
		}
		
		$res=getattr($tyid);
		while($row=mysql_fetch_array($res))
		{
			$response.="<attribute>$row[0]</attribute>";
		}
		$response.="</resourcetype>";
		
		echo $response;
		die;
	}
	
	if(isset($_GET['typename']))
	{
		$typename=$_GET['typename'];
		$tyid=gettypeid($typename);
		if($tyid=="")
		{
			$response.="<error>No such type '$typename' exists</error>";
			$response.="</resourcetype>";
			echo $response;
			die;
		}
		if($typename=="")
		{
			$response.="<error>Please pass the name of the type</error>";
			$response.="</resourcetype>";
			echo $response;
			die;
		}
		
		$response.="<tyid>$tyid</tyid><typename>$typename</typename>";
		$res=mysql_query('desc resource;');
		while($row=mysql_fetch_array($res))
		{
			$response.="<attribute>$row[0]</attribute>";
		}
		
		$res=getattr($tyid);
		while($row=mysql_fetch_array($res))
		{
			$response.="<attribute>$row[0]</attribute>";
		}
		$response.="</resourcetype>";
		
		echo $response;
		die;
	}
	if(isset($_GET['types']))
	{
		$res=gettypes();
		while($row=mysql_fetch_array($res))
		{
			$response.="<type><tyid>$row[0]</tyid><typename>$row[1]</typename></type>";
		}
		$response.="</resourcetype>";
		
		echo $response;
		die;
	}
?>
