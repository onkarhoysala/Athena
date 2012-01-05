<?php
	if(php_sapi_name()!="cli")
	{
		echo "Hey! Please run the update from the command line.";
		die;
	}
	echo "\nHi. This will update Athena's database structure to the latest version. Before you continue, make sure you have updated to the latest version of the core files by running 'svn update'. Press Y to continue and N to exit:";
	$x=fopen("php://stdin","r");
	$y=fread($x,1);
	if($y=="Y" or $y=="y")
	{
		include_once "bin/settings.php";
		include_once "bin/query.php";
		$version=database_fetch_array(database_query("select option_value from settings where option_name='version';"));
		$version=$version[0];
		//updating to 0.11
		if($version>=0.15)
		{
			echo "\nAthena is already updated to the latest version.\n\n";
			die;
		}
		$queries=array("drop table if exists wishlist",
		"create table wishlist (wid int(11) primary key auto_increment,wname varchar(255),tyid int(11) references type(tyid) on delete cascade on update cascade,date_of_entry timestamp default now(),uid int(11) references users.uid on delete cascade on update cascade,digital bool) engine=innodb;"
		);
		
		$types=gettypes();
		while($row=database_fetch_array($types))
		{
			$query="create table ".$row[1]."wishlist(wid int(11)";
			$attr=getattr($row[0]);
			while($at=database_fetch_array($attr))
			{
				$query.=",$at[0] varchar(255)";
			}
			$query.=",primary key(wid), foreign key (wid) references wishlist(wid) on delete cascade on update cascade)engine=innodb;";
			array_push($queries,"drop table if exists $row[1]"."wishlist");
			array_push($queries,$query);
		}
		array_push($queries,"alter table log add column wid int(11) references wishlist(wid) on delete cascade on update cascade;");
		array_push($queries,"update settings set option_value='0.14' where option_name='version';");
		foreach($queries as $query)
		{
			$res=database_query($query);
			if(!$res)
				echo mysql_error();
		}
		echo "\n\nDone!\n";
	}
?>
