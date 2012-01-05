<?php
	include_once "query.php";
	include_once "session.php";
	$switch=$_GET['case'];
	if($switch=="removemod")
	{
		if($_SESSION['email']!="admin")
		{
			echo "You don't have the necessary permissions for this action";
			die;
		}
		$mod=$_GET['mod'];
		$f=fopen("../modules/$mod","r");
		while($str=fgets($f))
		{
		
			$str2=explode(":",$str);
			if($str2[0]=="db")
			{
				$str3=explode("-",$str2[1]);
				foreach($str3 as $query)
				{
					$table=explode("create table ",$query);
					$table=explode("(",$table[1]);
					database_query("drop table $table[0];");
					if(!$res)
					{
							
						
					}
				}
			}
			if($str2[0]=="name")
			{
				$str3=explode("-",$str2[1]);
				foreach($str3 as $s)
				{
					$s=trim($s);
					database_query("delete from module where mod_name='$s';");
					if(!$res)
					{
					}
				}
			}
			
		}
		database_query("delete from installed_modules where module_name = '$mod';");
		//rename("../modules/$mod","../modules/$mod.uninstall");
		echo "done";
	}
	if($switch=="deletewish")
	{	
		$wid=$_GET['wid'];
		$res=database_query("delete from wishlist where wid='$wid';");
		if(!$res)
		{
		
		}
		else
		echo "done";
	}
	if($switch=="resource")
	{
		$uid=getuserid($_SESSION['email']);
		$t=$_GET['title'];
		$loc=$_GET['loc'];
		$tyid=$_GET['tyid'];
		$eid=$_GET['eid'];
		$owner=$_GET['owner'];
		
		$old=database_fetch_array(database_query("select name,location,owner from resource where eid='$eid';"));
		
		$t=escape_sql($t);
		$loc=escape_sql($loc);
		$owner=escape_sql($owner);
		$tyid=escape_sql($tyid);
	
		$typename=gettypename($tyid);
		$q="update $typename set ";
		$rollback="rollback:update resource set name=\"$old[0]\",owner=\"$old[2]\", location=\"$old[1]\" where eid='$eid';update $typename set ";
		$attr=getattr($tyid);
		while($row=database_fetch_array($attr))
		{
			$q.="$row[0]='".$_GET[$row[0]]."',";
			$roll1=database_fetch_array(database_query("select $row[0] from $typename where eid='$eid';"));
			$rollback.="$row[0]='$roll1[0]',";
		}
		$l=strlen($q);
		$q[$l-1]=" ";
		$q.=" where eid='$eid';";
		
		$l=strlen($rollback);
		$rollback[$l-1]=" ";
		$rollback.=" where eid='$eid';";
		$rollback=escape_sql($rollback);
		
		$res=database_query("update resource set location=\"$loc\",name=\"$t\",owner=\"$owner\" where eid='$eid';");
		if(!$res)
		{
			echo database_error();
			die;
		}
		
		$res=database_query($q);
		if(!$res)
		{
			echo database_error();
			die;
		}
		 
		/*$res=database_query("insert into log values(now(),'edited','$uid','$eid','$rollback','');");
		if(!$res)
		{
			echo database_error();
			die;
		}
	
		else echo "done";*/
		logger("edited::$eid::$rollback");
		echo "done";
	}
	else if($switch=="deleteres")
	{
		$eid=$_GET['eid'];
		$x=delete_resource($eid);
		echo $x;
	}
	else if($switch=="sec_q")
	{
		$q=$_GET['q'];
		$p=$_GET['p'];
		$uid=$_SESSION['uid'];
		$res=database_query("update users set question='$q',answer='$p' where uid='$uid';");
		if(!$res)
		{
			echo database_error();
			die;
		}
		echo "done";
	}
	
	else if($switch=="user")
	{
		$email=$_GET['email'];
		$fname=$_GET['fname'];
		$sname=$_GET['sname'];
		$phone=$_GET['phone'];
		$desig=$_GET['desig'];
		$res=database_query("update users set fname='$fname',sname='$sname',phone='$phone',designation='$desig' where email='$email';");
		if(!$res)
		{
		echo database_error();
		}
	}
	
	else if ($switch=="removetag")
	{
		$uid=$_GET['uid'];
		$eid=$_GET['eid'];
		$tag=$_GET['tag'];
		database_query("delete from resource_tag where eid='$eid' and tagname='$tag' and uid='$uid';");
	}
	
	else if($switch=="accept_download")
	{
		$did=$_GET['did'];
		$res=database_query("update download_request set status='accepted' where download_id='$did';");
		if(!$res)
		{
			echo "error";
		}	
		else echo "done";
	}
	
	else if($switch=="deny_download")
	{
		$did=$_GET['did'];
		$res=database_query("update download_request set status='denied' where download_id='$did';");
		if(!$res)
		{
			echo "error";
		}	
		else echo "done";
	}
	
?>
