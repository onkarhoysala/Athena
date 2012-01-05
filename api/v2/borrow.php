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
			$check_eid=database_query("select name from resource where eid='$eid';");
			if(database_num_rows($check_eid)!=0)
			{
				$res=database_query("insert into borrowed values(\"$eid\",\"$uid\",now())");
				if(!$res)
					$xml.="<error>Something went wrong. Please contact the administrator at $admin_email.</error>";
				else
					$xml.="<borrow>Done</borrow>";
				database_query("insert into log values(now(),'borrowed','$uid','$eid','','')");
			}
			else
			{
				$xml.="<error>No such resource exists.</error>";
			}
			
		}
		else
		{
			$xml.="<error>This has already been borrowed by ".getemail($check)."</error>";
		}
	}
	$xml.="</athena>";
	echo $xml;
?>
