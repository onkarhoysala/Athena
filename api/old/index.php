<?php
	header("Content-Type:application/xml");
	$response="<resources>";
	include_once "apiquery.php";
	include_once "../bin/query.php";
	
	if(isset($_GET['name']))
	{
		$name=$_GET['name'];
		$results=getres($name);
		
		while($row4=mysql_fetch_array($results))
		{
			/*$response.="<resource>";
			$response.="<name>$row4[0]</name>";*/
			$q="select eid";
			$result=mysql_query("select attrib from resource_attrib where tyid='$row4[2]';");
			$i=1;
			while($row2=mysql_fetch_array($result))
			{
			
				$q.=",$row2[0]";
				$attr[$i]=$row2[0];
				$i++;
			
			}
			$typename=gettypename($row4[2]);
			
			$q.=" from ".$typename."details where name like '%$name%';";
	
			$r=mysql_query($q);
			if(!$r)
			{
				$response.="<error>No results</error></resources>";
				echo $response;
				die;
			}
			$row3=mysql_fetch_array($r);
			{
			
				$response.="<resource>";
				$response.="<eid>$row3[0]</eid><name>$row4[0]</name><location>$row4[1]</location>";
				for($j=1;$j<$i;$j++)
				{
			
					$response.="<$attr[$j]>$row3[$j]</$attr[$j]>";
			
				
				}
				$response.="</resource>";
			}
		}
		
		$response.="</resources>";
		echo $response;
		die;
	}
		
	
	else if(isset($_GET['tag']))
	{
		$tag=$_GET['tag'];
		$results=gettagsapi($tag);
		while($row4=mysql_fetch_array($results))
		{
			$response.="<resource><tag>$row4[10]</tag><eid>$row4[0]</eid>";
			$response.="<name>$row4[2]</name></resource>";
		
		}
		$response.="</resources>";
		echo $response;
		die;
		
	}
	
	
	
	
	
	$res=mysql_query("select * from resource_attrib natural join type");
	while($row=mysql_fetch_array($res))
	{
		$a=$row[1];
		if(isset($_GET[$a]))
		{
			$q="select eid,name";
			$result=mysql_query("select attrib from resource_attrib natural join type where description='$row[2]';");
			$i=2;
			while($row2=mysql_fetch_array($result))
			{
				
				$q.=",$row2[0]";
				$attr[$i]=$row2[0];
				$i++;
				
			}
			$q.=" from ".$row[2]."details where $a like '%$_GET[$a]%';";
		
			$r=mysql_query($q);
			if(!$r)
			{
				echo mysql_error();
				echo $q;
				die;
			}
			
			while($row3=mysql_fetch_array($r))
			{
				$response.="<resource>";
				$response.="<name>$row3[1]</name>";
				for($j=2;$j<$i;$j++)
				{
				
					$response.="<$attr[$j]>$row3[$j]</$attr[$j]>";
				
					
				}
				$response.="</resource>";
			}
			
			
			
		}
		
		
			
	}
	$response.="</resources>";
	echo $response;
	//$response.="</resources>";
	//echo $response;
	
	
	
	/*else if(isset($_GET['auth']))
	{
		$auth=$_GET['auth'];
	}
	else if(isset($_GET['isbn']))
	{
		$isbn=$_GET['isbn'];
	}
	else if(isset($_GET['pub']))
	{
		$pub=$_GET['pub'];
	}
	//$res1=gettags($tag,$name);
	$res=getres($auth,$tag,$name,$isbn,$pub);
	$response="<resources>";
	if(mysql_num_rows($res)==0)
		$response.="<number>0</number>";
		
	while($row=mysql_fetch_array($res))
	{
		$response.="<resource>";
		$response.="<name>$row[4]</name>";
		$response.="<tags>$row[11]</tags>";
		$response.="<author>$row[3]</author>";
		$response.="<isbn>$row[1]</isbn>";
		$response.="<publisher>$row[2]</publisher>";
		$response.="</resource>";
	}
	$response.="</resources>";
	echo $response;*/
?>
