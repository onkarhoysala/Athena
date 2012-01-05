<?php
	include_once "query.php";
	$res=database_query("select distinct tagname from user_tag where tagname not in(select tagname from resource_tag);");
	while($row=database_fetch_array($res))
	{
		$r=database_query("select distinct eid from user_tag where tagname='$row[0]';");
		while($row1=database_fetch_array($r))
		{
			$r2=database_query("select count(*) from user_tag where tagname='$row[0]' and eid='$row1[0]';");
			$count=database_fetch_array($r2);
			if($count[0]>="2")
			{
				database_query("insert into resource_tag values('$row[0]','$row1[0]');");
				//echo "tagname $row[0] has a count of $count[0] for $row1[0]\n";
			}
		}
	}
?>
