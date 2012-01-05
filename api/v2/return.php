<?php
	include_once "../../bin/query.php";
	$eid=$_GET['eid'];
	$hash=$_GET['hash'];
	header("Content-Type: application/xml");
	$xml="<?xml version='1.0' encoding='utf-8'?><athena>";
	$getuser=database_query("select uid from api_register where hash='$hash';");	
	if(database_num_rows($getuser)==0)
	{
		$xml.="<error>You haven't logged in yet!</error>";
	}
	else
	{
		$uid=database_fetch_array($getuser);
		$uid=$uid[0];
		$check=checkborrow($eid);
		if($check=="0")
		{
			$xml.="<error>This item hasn't been borrowed.</error>";
			
		}
		else
		{
			
			if($check==$uid)
			{
				$exe=database_query("delete from borrowed where eid='$eid' and uid='$uid';");
				if(!$exe)
				{
					$xml.="<error>Something's gone wrong. Please try again later.</error>";
				}
				else
				{	
					$xml.="<return>Done</return>";
				}
				database_query("insert into log values(now(),'returned','$uid','$eid','','')");	
			}
			else
			{
				$xml.="<error>You haven't borrowed this item.</error>";	
			}
		}
	}
	$xml.="</athena>";
	echo $xml;
?>
