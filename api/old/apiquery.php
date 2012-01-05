<?php
 	mysql_connect("localhost","csteplib","cstep.321");
 	mysql_select_db("lib");
 	function getres($name)
 	{
 		$res=mysql_query("select name,location,tyid from resource where name like '%$name%';");
 	
 		
	 	if(!$res)
	 		echo mysql_error();
	 	else
	 		return $res;
 	}
 	function gettagsapi($tag)
 	{
 		$res=mysql_query(" select * from resource natural join resource_tag where tagname like '%$tag%';");
 		
 		if(!$res)
 			echo mysql_error();
 		else
 			return $res;
 			
 	}
 
 	function getattrs($x)
 	{
 		$res=mysql_query("select * from resource_attrib where attrib='$x';");
 		if(!$res)
 			echo mysql_error();
 		else
 			return $res;
 	}
 	
 	
 	

?>

