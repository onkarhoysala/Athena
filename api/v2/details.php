<?php
	include_once "../../bin/query.php";
	header("Content-Type: application/xml");
	$xml="<?xml version='1.0' encoding='utf-8'?><athena>";
	if(!isset($_GET['eid']))
	{
		$xml.="<error>Please enter the id of the entity...</error>";
	}
	else
	{
		$eids=explode(",",$_GET['eid']);
		foreach($eids as $eid)
		{
			$check=check_exists($eid);
			if($check==0)
			{
				$xml.="<error>This item with eid $eid does not exist.</error>";
			}
			else
			{
				$tyid=gettyid($eid);
				$view=gettypename($tyid)."details";
				$attr=getattr($tyid);
				$query="select name,fname,sname,location";
				while($a=database_fetch_array($attr))
				{
					$query.=",$a[0]";
				
				}
			
				$query.=" from $view where eid='$eid';";
				$res=database_query($query);
				$data=database_fetch_array($res);
				$xml.="<resource><name>$data[0]</name><added_by>$data[1] $data[2]</added_by><location>$data[3]</location>";
				$i=4;
				$attr=getattr($tyid);
				while($a=database_fetch_array($attr))
				{
					$xml.="<attribute><attributename>$a[0]</attributename><attributevalue>$data[$i]</attributevalue></attribute>";
					$i++;
				
				}
				$xml.="</resource>";
			}
		}
	}
	$eid=$_GET['eid'];
	$xml.="</athena>";
	if($_GET['type']=="js")
	{
		echo "var reply=\"$xml\";";
	}
	else
		echo $xml;
?>
