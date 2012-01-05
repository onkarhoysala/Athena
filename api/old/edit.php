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

	if(!isset($_GET['eid']) or $_GET['eid']=="")
	{
		$r.="<error>Please enter eid of the resource</error></session>";
		echo $r;
		die;
	}
	$eid=$_GET['eid'];
	$res=getattr($tyid);

	$r.="<tyid>$tyid</tyid>";
	$typename=gettypename($tyid);
	$query="update $typename set ";
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
		$query.="$row[0]='$t',";
	}
	$len=strlen($query);
	$query[$len-1]=" ";
	$query.="where eid='$eid';";
	//$r.="<query>$query</query>";
	$res=mysql_query("update resource set name='$name',location='$loc' where eid='$eid';");
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
	
	/*$res=mysql_query("insert into log values(now(),'added','$uid',last_insert_id(),'','');");
	if(!$res)
	{
		$r.="<error>".mysql_error()."</error></session>";
		echo $r;
		die;
	}*/
	$r.="<status>Done. Resource with eid=$eid updated</status></session>";
	echo $r;
?>
