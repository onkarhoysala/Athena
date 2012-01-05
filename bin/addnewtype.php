<?php
	/**
	*This file contains the code to add a new resource type to the system. 
	* @global $name The name of the new resource type
	* @global $attr A comma seperated list of attributes of the new resource type
	*/
	/**
	* Session.php contains all the necessary methods and variables necessay to maintain a session.	
	*/
	include_once "session.php";
	/**
	* Query.php contains all the necessary methods and variables necessary to maintain a database connection. It also contains methods that act as wrapper methods for database queries.
	*/
	include_once "query.php";
	if(isset($_POST['attr']) and isset($_POST['name']))
	{
		$a=$_POST['attr'];
		$attr=explode(",",$a);
	
		if(count($attr)<1)
		{
			echo "Please enter optional attributes";
			die;
		}
		
		$n=str_replace(" ","_",$_POST['name']);
		$res=database_query("select * from type where description='$n';");
		if(database_num_rows($res)!=0)
		{
			echo "Resource type $n already exists.";
			die;
		}
		$n=escape_sql($n);
		$res=database_query("insert into type values('','$n');");
		$tyid=database_fetch_array(database_query("select last_insert_id();"));
		$tyid=$tyid[0];
		
		foreach($attr as $x)
		{
			$x=escape_sql($x);
			if(strtolower($x)!="title" and strtolower($x)!="owner" and strtolower($x)!="location" and strtolower($x)!="name")
				$res=database_query("insert into resource_attrib values ('$tyid','$x','');");
		}
		if($dbtype=="mysql")
			$q="create table $n (eid int(11) references resource.eid";
		if($dbtype=="pgsql")
			$q="create table $n (eid integer references resource(eid)";
		$wishlist="create table ".$n."wishlist(wid int(11) references wishlist.wid";
		foreach($attr as $z)
		{
			$x=str_replace(" ","_",$z);
			$q.=",$x varchar(255)";
			$wishlist.=",$x varchar(255)";
		}
		$q.=",foreign key(eid) references resource(eid) on delete cascade on update cascade) Engine=InnoDB;";
		$wishlist.=",foreign key(wid) references resource(eid) on delete cascade on update cascade) Engine=InnoDB;";
		
		$res=database_query($q);
		if(!$res)
		{
			echo database_error();
			database_query("delete from type where tyid='$tyid';");
			database_query("delete from resource_attrib where tyid='$tyid';");
			die;
		}
		$res=database_query($wishlist);
		if(!$res)
		{
			echo database_error();
			database_query("delete from type where tyid='$tyid';");
			database_query("delete from resource_attrib where tyid='$tyid';");
			database_query("drop table $n");
			die;	
		}
		$view="create view ".$n."details as select * from (resource natural join $n) natural join users;";
		$res=database_query($view);
		if(!$res)
		{
			echo database_error();
			die;
		}
		
		else echo "Resource type $n added.";
		system("php createconf.php");
		
		logger("added a new resource type::$n");
		die;
	}
?>
<h3>Add new type</h3>
<div id='newtype'>
	Name of the new resource type: <input type='text' id='typename' />
	<br/><br/>Enter the attributes required for this resource type, with different attribute names seperated by commas. <br/><strong>Title,Owner and Location are default attributes; it isn't necessary to include them here.</strong><br/>
	<input type='text' id='attr' size="50"/>
	<br/><input type='button' value='Add' onclick='addnewtype();' />
	<br/><br/><input type="button" value="Cancel" onclick="window.location='addres.php';";>
	<br/><span id='confirm'></span>
</div>
