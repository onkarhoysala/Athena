<?php
	include_once "../bin/query.php";
	session_start();
	
	//Borrow API
	if(isset($_GET['borrow']))
	{
		$x=$_GET['borrow'];
		$s=$_GET['sid'];
		$r="<session>";
		$s=str_replace("-","=",$s);
		$s=base64_decode($s);
		$s=explode(" ",$s);
		$pass=mysql_fetch_array(mysql_query("select password('$s[1]');"));
		$res=mysql_query("select * from user where password='$pass[0]' and email='$s[0]';");
		if(!$res)
		{
			echo mysql_error();
		}
		if(mysql_num_rows($res)==0)
		{
			$r.="<authentication>Authentication failure</authentication></session>";
			echo $r;
			die;
		}
	
		$uid=getuserid($s[0]);
	
		if($uid=="")
		{
			$r.="<error>Login to borrow </error></session>";
			echo $r;
			die;	
		}
		if($x=="" or !isset($_GET['borrow']))
		{
			$r.="<error>Enter the eid of the resource to be borrowed</error></session>";
			echo $r;
			die;
		}
		$res=mysql_query("select * from borrowed where eid='$x';");
		if(mysql_num_rows($res)!=0)
		{
			$e="Already borrowed.";
			$r.="<status>$e</status><uid>$uid</uid></session>";
			header("Content-Type:application/xml");
			echo $r;
			die;
		}
	
		$res=mysql_query("insert into borrowed values('$x','$uid',now());");
		if(!$res)
		{
			$e="Error borrowing.";
			echo mysql_error();
			die;
		}
	
		$res=mysql_query("insert into log values(now(),'borrowed','$uid','$x','','');");
		if(!$res)
		{
			$e="Error borrowing.";
			echo mysql_error();
			die;
		}
		else 
			$e="Borrowed";
		$r.="<status>$e</status><uid>$uid</uid></session>";
		header("Content-Type:application/xml");
		echo $r;
		die;
	}
	
	//Return API
	if(isset($_GET['return']))
	{
		$eid=$_GET['return'];
		$s=$_GET['sid'];
		$loc=$_GET['location'];
		$r="<session>";
		$s=str_replace("-","=",$s);
		$s=base64_decode($s);
		$s=explode(" ",$s);
		$pass=mysql_fetch_array(mysql_query("select password('$s[1]');"));
		$res=mysql_query("select * from user where password='$pass[0]' and email='$s[0]';");
		if(!$res)
		{
			echo mysql_error();
		}
		if(mysql_num_rows($res)==0)
		{
			$r.="<authentication>Authentication failure</authentication></session>";
			header("Content-Type:application/xml");
			echo $r;
			die;
		}
		if($eid=="")
		{
			$r.="<error>Enter the eid of the resource to return</error></session>";
			header("Content-Type:application/xml");
			echo $r;
			die;
		}
		if($loc=="")
		{
			$r.="<error>Enter the new location</error></session>";
			header("Content-Type:application/xml");
			echo $r;
			die;
		}
		$uid=getuserid($s[0]);
		$res=mysql_query("select uid from borrowed where eid='$eid';");
		if(mysql_num_rows($res)==0)
		{
			$r.="<error>The resource with eid $eid is not borrowed by you</error></session>";
			header("Content-Type:application/xml");
			echo $r;
			die;
		}
		$res=mysql_query("delete from borrowed where eid='$eid';");
		if(!$res)
		{
			$e="<error>Error returning.</error></session>";
			header("Content-Type:application/xml");
			echo $r;
			die;
		}
		
		$res=mysql_query("update resource set location='$loc' where eid='$eid';");
		if(!$res)
		{
			$e="<error>Error returning.</error></session>";
			header("Content-Type:application/xml");
			echo $r;
			die;
		}
		
		$res=mysql_query("insert into log values(now(),'returned','$uid','$eid','','');");
		if(!$res)
		{
			$e="<error>Error returning.</error></session>";
			header("Content-Type:application/xml");
			echo $r;
			die;
		}
		else 
			$e="Returned";
		$r.="<status>$e</status><uid>$uid</uid></session>";
		header("Content-Type:application/xml");
		echo $r;
		die;
	}
	
	//User details API
	if(isset($_GET['uid']))
	{
		$row=getuserdetails($_GET['uid']);
		$r="<user>";
		$r.="<uid>$row[0]</uid>";
		$r.="<email>$row[1]</email>";
		$r.="<fname>$row[2]</fname>";
		$r.="<sname>$row[3]</sname>";
		$r.="<phone>$row[4]</phone>";
		$r.="<designation>$row[5]</designation>";
		$r.="</user>";
		header("Content-Type:application/xml");
		echo $r;
		die;
	}
	
	//Borrowed results API
	if(isset($_GET['borrowed']))
	{
		$uid=$_GET['borrowed'];
		$r="<session>";
		if($uid=="")
		{
			$r.="<error>Enter the uid of the user</error></session>";
			header("Content-Type:application/xml");
			echo $r;
			die;
		}
		$r.="<uid>$uid</uid>";
		$res=borrowed($uid);
		while($row=mysql_fetch_array($res))
		{
			$r.="<borrowed>";
			$r.="<eid>$row[0]</eid>";
			$r.="<name>$row[4]</name>";
			$r.="<date>$row[2]</date>";
			$r.="</borrowed>";	
		}
		$r.="</session>";
		header("Content-Type:application/xml");
		echo $r;
		die;
	}
?>
