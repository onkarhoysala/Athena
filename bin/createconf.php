<?php
	include_once "settings.php";
	include_once "query.php";
	/*$dbtype_connect("$hostname","$dbuser","$dbpw");
	$dbtype_select_db("$dbname");*/
	$file=fopen("sphinx.conf","w");
	$add="source mainindex
			{
				type					= $dbtype

				sql_host				= $hostname
				sql_user				= $dbuser
				sql_pass				= $dbpw
				sql_db					= $dbname
				sql_port				= 3306	# optional, default is 3306

				sql_query				= \
					SELECT eid,name from resource;


			}


			index nameindex
			{
				source					= mainindex
				path					= $index_folder/nameindex
				docinfo					= extern
				charset_type			= sbcs
				enable_star = 1

				min_infix_len = 3

			}
			source loc_index
			{
				type					= $dbtype

				sql_host				= $hostname
				sql_user				= $dbuser
				sql_pass				= $dbpw
				sql_db					= $dbname
				sql_port				= 3306	# optional, default is 3306

				sql_query				= \
					SELECT eid,location from resource;


			}


			index locationindex
			{
				source					= loc_index
				path					= $index_folder/locationindex
				docinfo					= extern
				charset_type			= sbcs
				enable_star = 1

				min_infix_len = 3

			}
			";
	fwrite($file,$add);
	/*$res=database_query("select tyid,description,attrib from resource_attrib natural join type");
	while($row=database_fetch_array($res))
	{
		$indexname="index_$row[1]_$row[2]";
		$add="source $row[1]_$row[2]
			{
				type					= $dbtype

				sql_host				= $hostname
				sql_user				= $dbuser
				sql_pass				= $dbpw
				sql_db					= $dbname
				sql_port				= 3306	# optional, default is 3306

				sql_query				= \
					SELECT eid,$row[2] FROM $row[1];


			}


			index $indexname
			{
				source					= $row[1]_$row[2]
				path					= ./index/$indexname
				docinfo					= extern
				charset_type			= sbcs
				enable_star = 1

				min_infix_len = 3

			}
			";
		fwrite($file,$add);
	}
	$add="indexer
		{
			mem_limit				= 32M
		}


		searchd
		{
			port					= 9312
			log						= ./index/searchd.log
			query_log				= ./index/query.log
			read_timeout			= 5
			max_children			= 30
			pid_file				= ./index/searchd.pid
			max_matches				= 10000
			seamless_rotate			= 1
			preopen_indexes			= 0
			unlink_old				= 1
		}";*/
	$res=database_query("select description from type");
	$add="";
	while($type=database_fetch_array($res))
	{
		$indexname=$type[0]."_index";
		$add.="source $type[0]
			{
				type					= $dbtype

				sql_host				= $hostname
				sql_user				= $dbuser
				sql_pass				= $dbpw
				sql_db					= $dbname
				sql_port				= 3306	# optional, default is 3306

				sql_query				= \
					SELECT * FROM $type[0];


			}


			index $indexname
			{
				source					= $type[0]
				path					= $index_folder/$indexname
				docinfo					= extern
				charset_type			= sbcs
				enable_star = 1

				min_infix_len = 3

			}
			";
		
	}
	$add.="indexer
		{
			mem_limit				= 32M
		}


		searchd
		{
			port					= 9312
			log						= $index_folder/searchd.log
			query_log				= $index_folder/query.log
			read_timeout			= 5
			max_children			= 30
			pid_file				= $index_folder/searchd.pid
			max_matches				= 10000
			seamless_rotate			= 1
			preopen_indexes			= 0
			unlink_old				= 1
		}";
	fwrite($file,$add);
	fclose($file);
?>
