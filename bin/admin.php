<?php
	/**
	* This file contains the code to provide the system adminstrator with administrative tasks such as adding/removing modules, backup the system, restore the system, remove or modify a resource type etc.
	* @global $action This variable is used in the switch case to determine what case statement needs to execute. Possible values are "backup","restore","removetype","addattr","removeattr" and "indexnow"
	*/
	/**
	* Session.php contains all the necessary methods and variables necessay to maintain a session.	
	*/
	include_once "session.php";
	/**
	* Query.php contains all the necessary methods and variables necessary to maintain a database connection. It also contains methods that act as wrapper methods for database queries.
	*/
	include_once "query.php";
	
	$dir= getcwd();
	$p=explode("/library",$dir);
	$path=$p[0]."/library/dbsettings.ini";
	$f=fopen($path,"r");
	$dbname=trim(fgets($f));
	$dbuser=trim(fgets($f));
	$dbpw=trim(fgets($f));
	
	if(isset($_GET['action']))
	{
		$x=$_GET['action'];
		if($x=="backup")
		{
			$now=date("r");
			$now=str_replace(" ","_",$now);
			system("mysqldump -u $dbuser $dbname -p$dbpw>../backup/$now.sql");
			echo substr(str_replace("_"," ",$now),0,-5);
		}
		if($x=="restore")
		{
			
			$restore=$_GET['restore'];
			$rootpw=$_GET['rootpw'];
			if($rootpw=="")
			{
				system("mysql -u root $dbname <../backup/$restore");
			}
			else
			{
				system("mysql -u root $dbname -p$rootpw<../backup/$restore");
			}
			//rename("../backup/$restore","../backup/$restore.restored");
			unlink("../backup/$restore");
			echo "Restored";
		}
		if($x=="removetype")
		{
			$tyid_remove=$_GET['tyid'];
			$typename_remove=$_GET['name'];
			$res_typerem=database_query("delete from type where tyid='$tyid_remove';");
			if(!$res_typerem)
			{
				echo database_error();
				die;
			}
			$res_typerem=database_query("drop table $typename_remove;");
			if(!$res_typerem)
			{
				echo database_error();
				die;
			}
			$res_typerem=database_query("drop view $typename_remove"."details;");
			if(!$res_typerem)
			{
				echo database_error();
				die;
			}
			$res_typerem=database_query("drop table $typename_remove"."wishlist;");
			if(!$res_typerem)
			{
				echo database_error();
				die;
			}							
			echo "done";
		}
		if($x=="removeattr")
		{
			$attr_rem=$_GET['attr'];
			$tyid=$_GET['tyid'];
			$typename=$_GET['typename'];
			$res_attrrem=database_query("delete from resource_attrib where tyid='$tyid' and attrib='$attr_rem';");
			if(!$res_attrrem)
			{
				echo database_error();
				die;
			}
			$res_attrrem=database_query("alter table $typename drop column $attr_rem;");
			if(!$res_attrrem)
			{
				echo database_error();
				die;
			}
			$res_attrrem=database_query("alter view $typename"."details as select * from (resource natural join $typename) natural join user;");
			if(!$res_attrrem)
			{
				echo database_error();
				die;
			}
			echo "done";
		}
		if($x=="addattr")
		{
			$attr_add=$_GET['attr'];
			$tyid=$_GET['tyid'];
			$typename=$_GET['typename'];
			if($attr_add==" " or $attr_add=="null")
			{
				echo "error";
			}
			$res_attrrem=database_query("insert into resource_attrib values(\"$tyid\",\"$attr_add\",\"\");");
			if(!$res_attrrem)
			{
				echo database_error();
				die;
			}
			$res_attrrem=database_query("alter table $typename add column $attr_rem varchar(255);");
			if(!$res_attrrem)
			{
				echo database_error();
				die;
			}
			$res_attrrem=database_query("alter view $typename"."details as select * from (resource natural join $typename) natural join user;");
			if(!$res_attrrem)
			{
				echo database_error();
				die;
			}
			echo "done";
		}
		if($x=="indexnow")
		{
			system("php createconf.php");
			system("indexer --all --rotate");
		}
		die;
	}	
	
	
	echo "<h3>Administration</h3>";
	echo "<a href='module.php' class='custombutton'>Modules</a>";
	echo "<span class='custombutton' onclick='backup();'>Backup now</span>";
	echo "<span id='indexnow1'><span class='custombutton' onclick='indexnow();' >Index now</span></span>";
	echo "<span id='backupresponse'></span>";
	$backups=scandir("./backup/");
	echo "<br/><br/><strong>Last Backup:</strong><span id='backuptime'>";
	$c=count($backups);
	echo substr(str_replace("_"," ",$backups[$c-1]),0,-9);
	echo "</span>";
	echo "<br/><br/><strong>Older Backups</strong><ul>";
	$backups=scandir("./backup");
	$c=count($backups);
	for($i=$c-2;$i>=3;$i--)
	{
		echo "<li>".substr(str_replace("_"," ",$backups[$i]),0,-9)."</li>";
	}
	echo "</ul>";
	echo "<br/>";
	echo "<span class='custombutton' onclick='$(\"#restore\").toggle(\"slow\");'>Restore</span>";
	echo "<div id='restore'>";
	echo "Select a backup to restore";
	$backups=scandir("./backup/");
	$c=count($backups);
	for($i=$c-1;$i>=2;$i--)
	{
		$temp=str_replace("_"," ",$backups[$i]);
		echo "<li>".substr($temp,0,-9)."<span style='margin-left:15px;color:green;cursor:pointer' onclick='var u=prompt(\"Please enter the root password\");restore(\"$backups[$i]\",u);'>Restore</span></li>";
	}
	echo "</div><br/><br/>";
	echo "<div id='edittype'>";
		echo "<h3>Edit Types</h3>";
		$types=gettypes();
		while($row=database_fetch_array($types))
		{
			echo "<div id='$row[0]' class='types'>";
			echo "<span class='pointers' onclick='$(\"#type$row[0]\").toggle();'>$row[1]</span>";
			echo "<div class='typeattr' id='type$row[0]'>";
			;
			echo "<u>Remove Attribute</u>";
			$attr=getattr($row[0]);
			echo "<ul id='list$row[1]'>";
			while($a=database_fetch_array($attr))
			{
				echo "<li style='display:inline;padding:15px' id='$row[0]$a[0]'><span style='color:#3B5998;cursor:pointer' onclick='removeattr(\"$row[0]\",\"$a[0]\",\"$row[1]\");'>$a[0]</span></li>";
			}
			echo "</ul>";
			echo "<span style='color:green;cursor:pointer' onclick='add_new_attr(\"$row[0]\",\"$row[1]\");'>Add a new attribute</span><br/>";
			echo "<span style='color:red;cursor:pointer' onclick='removetype(\"$row[0]\",\"$row[1]\");'>Remove type $row[1]</span><br/>";
			echo "</div>";
			echo "</div>";
			
		}
	echo "</div>";
	getmodule("admin.php");
?>
