<?php
	header("Content-Type:application/xml");
	include_once "apiquery.php";
	include_once "../bin/query.php";
	$tyid=$_GET['tyid'];
	$name=$_GET['name'];
	$loc=$_GET['loc'];
	$r="<session>";
	if(!isset($_GET['name']) or !isset($_GET['loc']) or $_GET['name']=="" or $_GET['loc']=="")
	{
		$r.="<error>please enter all fields</error></session>";
		echo $r;
		die;
	}
	$s=$_GET['sid'];
	
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
		$r.="<error>Login to add </error></session>";
		echo $r;
		die;	
	}
	
	$res=getattr($tyid);

	$r.="<tyid>$tyid</tyid>";
	$typename=gettypename($tyid);
	$query="insert into $typename values (last_insert_id()";
	$res=getattr($tyid);
	while($row=mysql_fetch_array($res))
	{
		if(!isset($_GET[$row[0]]))
		{
			$r.="<error>$row[0] not entered. Please enter all the fields</error></session>";
			echo $r;
			die;
		}
		$t=$_GET[$row[0]];
		$query.=",'$t'";
	}
	$query.=")";
	//$r.="<query>$query</query>";
	$res=mysql_query("insert into resource values('','$name','$tyid',now(),'$uid','$loc','1','0');");
	if(!$res)
	{
		$r.="<error>".mysql_error()."</error></session>";
		echo $r;
		die;
	}
	$res=mysql_query($query);
	if(!$res)
	{
		$r.="<error>".mysql_error()."</error></session>";
		echo $r;
		die;
	}
	
	$res=mysql_query("insert into log values(now(),'added','$uid',last_insert_id(),'','');");
	if(!$res)
	{
		$r.="<error>".mysql_error()."</error></session>";
		echo $r;
		die;
	}
	$r.="</session>";
	echo $r;
?>
