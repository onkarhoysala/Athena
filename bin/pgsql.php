<?php
	function database_query($query)
	{
		return pg_query($query);
	}
	function database_fetch_array($res)
	{
		return pg_fetch_array($res);
	}
	function database_num_rows($res)
	{
		return pg_num_rows($res);
	}
	function database_error()
	{
		return pg_last_error();
	}
	function escape_sql($str)
	{
		return pg_escape_string($str);
	}
	function check_for_php_modules()
	{
		/**Checks for Postgres and cURL PHP modules, without which Athena won't work.*/
		$pgsql=function_exists("pg_connect");
		$curl=function_exists("curl_init");
		if(!$pgsql or !$curl)
		{
			if(!$pgsql)
				echo "You haven't installed PHP Postgres module.";
			if(!$curl)
				echo "You haven't installed PHP cURL module";
			return false;
			die;
		}
		return true;
	}
	check_for_php_modules();
	$db=pg_connect("host=$hostname user=$dbuser password=$dbpw dbname=$dbname");

	$error=fopen("error.log","a");
	check_db_connectivity($db);
	/* Function definitions */
	//-----------------------
	function check_db_connectivity($db)
	{
		if(!$db)
		{
			echo "<script>window.location='notinstalled.php';</script>";
		}	
		return true;
	}
	function validate($email,$pass)
	{
		/** Used to validate users when they login and whenever else required*/
		$email=pg_escape_string($email);
		$pass=pg_escape_string($pass);
		$res=pg_query("select md5('$pass');");
		$p1=pg_fetch_array($res);
		$res=pg_query("select password from users where email='$email';");
		if(!$res)
		{
			fputs($error,pg_last_error());
			return "0";
		}
		$p2=pg_fetch_array($res);
		if($p1[0]==$p2[0])
			return "1";
		else return "0";
	}
	function getemail($uid)
	{
		/**Returns the email (as a string) of the users with id as $uid. */
		$uid=pg_escape_string($uid);
		$row=pg_fetch_array(pg_query("select email from users where uid='$uid';"));
		return $row[0];
	}
	function getuserid($email)
	{
		$email=pg_escape_string($email);
		$row=pg_fetch_array(pg_query("select uid from users where email='$email';"));
		return $row[0];
	}
	function getname($email)
	{
		$email=pg_escape_string($email);
		$row=pg_fetch_array(pg_query("select fname,sname from users where email='$email';"));
		return $row[0]." ".$row[1];
	}
	function getname_uid($uid)
	{
		$uid=pg_escape_string($uid);
		$row=pg_fetch_array(pg_query("select fname,sname from users where uid='$uid';"));
		return $row[0]." ".$row[1];
	}
	function getuserdetails($email)
	{
		$email=pg_escape_string($email);
		$row=pg_fetch_array(pg_query("select * from users where email='$email' or uid='$email';"));
		return $row;
	}
	function gettypes()
	{
		$res=pg_query("select * from type;");
		return $res;
	}
	function getattr($tyid)
	{
		$tyid=pg_escape_string($tyid);
		$res=pg_query("select attrib from resource_attrib where tyid='$tyid';");
		//echo "select attrib from resource_attrib where tyid='$x';";
		if(!$res)
			fputs($error,pg_last_error());
		return $res;
	}
	function gettypename($x)
	{
		$x=pg_escape_string($x);
		$row=pg_fetch_array(pg_query("select description from type where tyid='$x';"));
		return $row[0];
	}
	function gettypename_eid($x)
	{
		$x=pg_escape_string($x);
		$row=pg_fetch_array(pg_query("select description from type natural join resource where eid='$x';"));
		return $row[0];
	}
	function getresources($l,$p,$t)
	{
		$l=pg_escape_string($l);
		$p=pg_escape_string($p);
		$t=pg_escape_string($t);
		$y=($p-1)*12;
		if($t=="")
			$query="select distinct eid,name,fname,sname,description,tyid,lost from (resource natural join type) natural join users where name like '$l%' order by name limit 12 offset $y";
		else
			$query="select distinct eid,name,fname,sname,description,tyid,lost from (resource natural join type) natural join users where name like '$l%' and tyid='$t' order by name limit 10 offset $y;";
		$res=pg_query($query);
		if(!$res)
		{
			fputs($error,pg_last_error());
			die;
		}
		return $res;
	}
	function getbr_count($l,$t)
	{
		$l=pg_escape_string($l);
		$t=pg_escape_string($t);
		//$y=($p-1)*10;
		if($t=="")
			$query="select distinct eid,name,fname,sname,description,tyid,lost from (resource natural join type) natural join users where name like '$l%' order by name";
		else
			$query="select distinct eid,name,fname,sname,description,tyid,lost from (resource natural join type) natural join users where name like '$l%' and tyid='$t' order by name;";
		$res=pg_query($query);
		if(!$res)
		{
			fputs($error,pg_last_error());
			die;
		}
		return $res;
	}
	function checkborrow($eid)
	{
		$eid=pg_escape_string($eid);
		$res=pg_query("select * from borrowed where eid='$eid';");
		if(pg_num_rows($res)==0)
		{
			return "1";
		}
		else
		{
			$row=pg_fetch_array($res);
			return $row[1];
		}
	}
	function borrowed($uid)
	{
		$uid=pg_escape_string($uid);
		$res=pg_query("select * from borrowed,resource where borrowed.uid='$uid' and resource.eid=borrowed.eid;");
		
		if(!$res)
		{
			fputs($error,pg_last_error());
			die;
		}
		return $res;
	}
	function getresname($eid)
	{
		$eid=pg_escape_string($eid);
		$res=pg_query("select name from resource where eid='$eid';");
		if(!$res)
		{
			fputs($error,pg_last_error());
			die;
		}
		$row=pg_fetch_array($res);
		return $row[0];
	}
	function getmessages($uid)
	{
		$uid=pg_escape_string($uid);
		$res=pg_query("select * from message where to_uid='$uid' and delete_flag='1';");
		if(!$res)
		{
			fputs($error,pg_last_error());
			die;
		}
		return $res;
	}
	function gettyid($eid)
	{
		$eid=pg_escape_string($eid);
		$res=pg_query("select tyid from resource where eid='$eid';");
		$row=pg_fetch_array($res);
		return $row[0];
	}
	function gettypeid($t)
	{
		$t=pg_escape_string($t);
		$res=pg_query("select tyid from type where description='$t';");
		$row=pg_fetch_array($res);
		return $row[0];
	}
	function searchresources($q,$by,$p,$t)
	{
		$q=pg_escape_string($q);
		$by=pg_escape_string($by);
		$p=pg_escape_string($p);
		$cl = new SphinxClient ();
		$sql = "";
		$mode = SPH_MATCH_EXTENDED;
		$host = "localhost";
		$port = 9312;
		$groupby = "";
		$groupsort = "@group desc";
		$filter = "group_id";
		$filtervals = array();
		$distinct = "";
		$sortby = "";
		$limit = 1000;
		$ranker = SPH_RANK_PROXIMITY_BM25;
		$select = "";
		$cl->SetServer ( $host, $port );
		$cl->SetConnectTimeout ( 1 );
		$cl->SetArrayResult ( true );
		$cl->SetWeights ( array ( 100, 1 ) );
		$cl->SetMatchMode ( $mode );
		if ( count($filtervals) )	$cl->SetFilter ( $filter, $filtervals );
		if ( $groupby )				$cl->SetGroupBy ( $groupby, SPH_GROUPBY_ATTR, $groupsort );
		if ( $sortby )				$cl->SetSortMode ( SPH_SORT_EXTENDED, $sortby );
		if ( $sortexpr )			$cl->SetSortMode ( SPH_SORT_EXPR, $sortexpr );
		if ( $distinct )			$cl->SetGroupDistinct ( $distinct );
		if ( $select )				$cl->SetSelect ( $select );
		if ( $limit )				$cl->SetLimits ( 0, $limit, ( $limit>1000 ) ? $limit : 1000 );
		$cl->SetRankingMode ( $ranker );
		$y=($p-1)*10;
		$uid=$_SESSION['uid'];
		if($by=="all")
		{
			//$query="select eid,name,fname,sname,description,tyid from (resource natural join type) natural join users where name like '%$q%' or description like '$q%' order by name limit $y,10";
			$index = "*";
			$resname = $cl->Query("$q*",$index);
			if ( $resname===false )
			{
				echo "Query failed: " . $cl->GetLastError() . ".\n";

			} else
			{
				if ( $cl->GetLastWarning() )
					//echo "WARNING: " . $cl->GetLastWarning() . "\n\n";

				print "Search for '$q' retrieved $resname[total] of $resname[total_found] matches in $resname[time] sec.<br/>";	
				if ( is_array($resname["matches"]) )
				{
					foreach ( $resname["matches"] as $docinfo )
					{
						$results.=$docinfo[id].",";
					}
				}
			}
			$query="select eid,name,fname,sname,description,tyid from (resource natural join type) natural join users where eid in ($results"."0) and tyid in ($t"."0".") limit 10 offset $y";
			//echo $query;
		}
		else if($by=="name")
		{
			//$query="select eid,name,fname,sname,description,tyid from (resource natural join type) natural join users where name like '%$q%' order by name limit $y,10;";
			//$query="select eid,name,fname,sname,description,tyid from (resource natural join type) natural join users where name like \"$q%\" limit $y,10;";
			
			$index = "nameindex";
			$resname = $cl->Query ( "$q*", $index );
			if ( $resname===false )
			{
				echo "Query failed: " . $cl->GetLastError() . ".\n";

			} else
			{
				if ( $cl->GetLastWarning() )
					echo "WARNING: " . $cl->GetLastWarning() . "\n\n";

				print "Search for '$q' retrieved $resname[total] of $resname[total_found] matches in $resname[time] sec.<br/>";	
				if ( is_array($resname["matches"]) )
				{
					foreach ( $resname["matches"] as $docinfo )
					{
						$results.=$docinfo[id].",";
					}
				}
			}
			$query="select eid,name,fname,sname,description,tyid from (resource natural join type) natural join users where eid in ($results"."0) and tyid in ($t"."0".") limit $y,10";
			
		}
		else if($by=="tag")
			$query="select distinct resource.eid,name,fname,sname,description,type.tyid from resource,type,user,resource_tag where resource.tyid=type.tyid and resource.uid=user.uid and resource.eid=resource_tag.eid and tagname like '%$q%' and type.tyid in ($t"."0".") limit 10 offset $y;";
		elseif($by=="taguser")
			$query="select distinct resource.eid,resource.name,user.fname,user.sname,type.description,type.tyid from resource,type,user,resource_tag where resource.tyid=type.tyid and resource.uid=user.uid and resource.eid=resource_tag.eid and tagname like '%$q%' and user.uid='$uid' and type.tyid in ($t"."0".")order by name limit 10 offset $y;";
		else
		{
			$i=0;
			$res=pg_query("select description from type natural join resource_attrib where attrib='$by';");
			while($row=pg_fetch_array($res))
			{
				$q1="select eid from $row[0]details where $by like '$q%';";
				$res2=pg_query($q1);
				if(!$res2)
				{
					fputs($error,pg_last_error());
					die;
				}
				while($row2=pg_fetch_array($res2))
				{
					$searchr[$i]=$row2[0];
					$i++;
				}
			}
			$query="select eid,name,fname,sname,description,tyid from (resource natural join type) natural join users where ";
			if($i>=1)
				foreach($searchr as $x1)
				{
					$query.="eid='$x1' or ";
				}
			$query.="eid='0' order by name;";
		
			
		}
		$res=pg_query($query);
		if(!$res)
		{
			fputs($error,pg_last_error());
			die;
		}
		$res1[0]=$res;
		$res1[1]=$resname[total];
		return $res1;
	}
	function getcount($q,$by,$p)
	{
		$q=pg_escape_string($q);
		$by=pg_escape_string($by);
		$p=pg_escape_string($p);
		if($by=="all")
			$query="select distinct count(*) from (resource natural join type) natural join users where name like '$q%' or description like '$q%' order by name";
		else if($by=="name")
		{
			//$query="select distinct count(*) from (resource natural join type) natural join users where name like '$q%' order by name;";
			$query="select count(eid) from (resource natural join type) natural join users where name like '$q%';";
		}
		else if($by=="tag")
		{
			//$query="select distinct count(*) from ((resource natural join type) natural join user) natural join resource_tag where tagname like '$q%' order by name;";
			$query="select count(distinct resource.eid) from resource,type,user,resource_tag where resource.tyid=type.tyid and resource.uid=user.uid and resource.eid=resource_tag.eid and tagname like '%$q%' order by name";
		}
		else
		{
			$i=0;
			$res=pg_query("select description from type natural join resource_attrib where attrib='$by';");
			while($row=pg_fetch_array($res))
			{
				$q1="select eid from $row[0]details where $by like '%$q%';";
				$res2=pg_query($q1);
				if(!$res2)
				{
					fputs($error,pg_last_error());
					die;
				}
				while($row2=pg_fetch_array($res2))
				{
					$searchr[$i]=$row2[0];
					$i++;
				}
			}
			$query="select count(*) from (resource natural join type) natural join users where ";
			if($i>=1)
				foreach($searchr as $x1)
				{
					$query.="eid='$x1' or ";
				}
			$query.="eid='0' order by name;";
		
			
		}
		$res=pg_query($query);
		if(!$res)
		{
			echo pg_last_error();
			die;
		}
		$c=pg_fetch_array($res);
		return $c[0];
	}
	function gettags($eid,$uid)
	{
		$eid=pg_escape_string($eid);
		$uid=pg_escape_string($uid);
		$res=pg_query("select tagname from resource_tag where eid='$eid' and uid='$uid';");
		return $res;
	}
	function getpoptags($eid)
	{
		$eid=pg_escape_string($eid);
		if($eid=="0")
		{
			$res=pg_query("select distinct tagname from resource_tag group by tagname having count(tagname)>'150';");
		}
		else
			$res=pg_query("select distinct tagname from resource_tag where eid='$eid';");
		return $res;
	}
	function gettagcount($tag,$eid)
	{
		$eid=pg_escape_string($eid);
		$tag=pg_escape_string($tag);
		if($eid=='0')
			$res=pg_query("select count(*) from resource_tag where tagname='$tag';");
		else
			$res=pg_query("select count(*) from resource_tag where tagname='$tag' and eid='$eid';");
		$row=pg_fetch_array($res);
		return $row[0];
	}
	function getcomments($eid)
	{	
		$eid=pg_escape_string($eid);
		$res=pg_query("select * from comment_table where eid='$eid';");
		if(!$res)
		{
			fputs($error,pg_last_error());
			die;
		}
		return $res;
	}
	function getmodule($page)
	{
		$page=pg_escape_string($page);
		$res1=pg_query("select * from module where mod_loc='$page';");
		if(pg_num_rows($res1)==0)
		{
			return;
		}
		if(!$res1)
		{
			fputs($error,pg_last_error());
			die;
		}
		else
		{
			while($row=pg_fetch_array($res1))
			{
				
				echo "<div id='$row[0]' class='module'>";
				if($page=="menu.php")
					include_once "modules/$row[0]";
				else
					include_once "modules/$row[0]";			
				echo "</div>";
				
			}
		}
		
	}
	function getlostres()
	{
		$res=pg_query("select * from resource where lost='1'");
		if(!$res)
		{
			fputs($error,pg_last_error());
			die;
		}
		return $res;
	}
	function borrowed_from_log($uid)
	{
		$uid=pg_escape_string($uid);
		$res=pg_query("select * from log,resource where log.uid='$uid' and resource.eid=log.eid and activity='borrowed' group by name order by time_entry desc;");
		
		if(!$res)
		{
			fputs($error,pg_last_error());
			die;
		}
		return $res;
	}
	function getattributes()
	{
		$res=pg_query("select distinct attrib from resource_attrib;");
		if(!$res)
		{
			fputs($error,pg_last_error());
			die;
		}
		return $res;
	}
	function checkauthor($tyid)
	{
		$tyid=pg_escape_string($tyid);
		$res=pg_query("select attrib from resource_attrib where tyid='$tyid' and attrib='author';");
		if(!$res)
		{
			fputs($error,pg_last_error());
			die;
		}
		$c=pg_num_rows($res);
		return $c;
	}
	function getauthor($eid,$tyid)
	{
		$eid=pg_escape_string($eid);
		$tyid=pg_escape_string($tyid);
		$ty=gettypename($tyid);
		$res=pg_query("select author from $ty where eid='$eid';");
		//echo "select author from $ty where eid='$eid';";
		if(!$res)
		{
			fputs($error,pg_last_error());
			die;
		}
		$row=pg_fetch_array($res);
		return $row[0];
	}
	function getusers()
	{
		$res=pg_query("select fname,sname,uid from users where uid<>'-1';");
		return $res;
	}
	function getrating($eid,$uid)
	{
		$eid=pg_escape_string($eid);
		$uid=pg_escape_string($uid);
		$res=pg_query("select rating from ratings where eid='$eid' and uid='$uid';");
		if(!$res)
		{
			fputs($error,pg_last_error());
			die;
		}
		$row=pg_fetch_array($res);
		return $row[0];
	}
	function getglobalrating($eid)
	{
		$eid=pg_escape_string($eid);
		$res=pg_query("select rating from ratings where eid='$eid';");
		$avg=0;
		$count=pg_num_rows($res);
		if($count!=0)
		{
			while($row=pg_fetch_array($res))
			{
				//$count++;
				$avg+=$row[0];
			}
			$avg=$avg/$count;
		}
		else $avg=0;
		return $avg;
	}
	function logger($s)
	{
		$s=pg_escape_string($s);
		$uid=$_SESSION['uid'];
		$res=pg_query("insert into reco_log values('','$uid','$s',now());");
	}	
	function rand_str($length = 32, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890')
	{
		// Length of character list
		$chars_length = (strlen($chars) - 1);
		// Start our string
		$string = $chars{rand(0, $chars_length)};
		// Generate random string
		for ($i = 1; $i < $length; $i = strlen($string))
		{
		// Grab a random character from our list
		$r = $chars{rand(0, $chars_length)};

		// Make sure the same two characters don't appear next to each other
		if ($r != $string{$i - 1}) $string .=  $r;
		}
		// Return the string
		return $string;
	}
	function getfav($uid)
	{
		$uid=pg_escape_string($uid);
		$res=pg_query("select resource.eid,name,tyid from favourites,resource where favourites.uid='$uid' and favourites.eid=resource.eid;");
		if(!$res or pg_num_rows($res)==0)
		{
			echo "You havn't added anything to your favourites list.";
			die;
		}
		return $res;
	}
	function login($uid)
	{
		$uid=pg_escape_string($uid);
		$res=pg_query("insert into online values('$uid',now());");
	}	
	function logout($uid)
	{
		$uid=pg_escape_string($uid);
		$res=pg_query("delete from online where uid='$uid';");
	}
	function getoverallrating($eid)
	{
		$eid=pg_escape_string($eid);
		$row=pg_fetch_array(pg_query("select rating from resource where eid='$eid';"));
		return $row[0];
	}
	function delete_resource($eid)
	{
		pg_query("delete from resource where eid='$eid';");
		/*pg_query("delete from resource_tag where eid='$eid';");
		pg_query("delete from log where eid='$eid';");
		pg_query("delete from reco_log where eid='$eid';");
		$typename=gettypename_eid($eid)."details";
		pg_query("delete from $typename where eid='$eid';");*/
		echo "done";
		
	}
	function getauthors()
	{
		$f=fopen("authors.txt","w");
		$i=0;
		$res=pg_query(" select author,count(author) from Book where author<>' ' group by author order by count(author) desc");
		while($row=pg_fetch_array($res))
		{
			$authors=explode(",",$row[0]);
			foreach($authors as $a)
			{
				fputs($f,"$a-$row[1]\n");
				$i++;
			}
		}
		fclose($f);
		return $i;
	}
	function getcovers($eid)
	{
		$res=pg_query("select url from images where eid='$eid' limit 3 offset 0;");
		return $res;
	}
	function getallcovers($eid)
	{
		$res=pg_query("select url,uid from images where eid='$eid';");
		return $res;
	}
	function check_for_download_request($eid,$uid)
	{
		$eid=pg_escape_string($eid);
		$uid=pg_escape_string($uid);
		$res=pg_query("select * from download_request where eid='$eid' and uid='$uid'");
		return $res;
	}
	function pending_download_requests_for_eid($eid,$uid)
	{
		$eid=pg_escape_string($eid);
		$uid=pg_escape_string($uid);
		$res=pg_query("select * from download_request where eid='$eid' and status='pending' and id in(select id from uploaded where uid='$uid');");
		return $res;
	}
	function get_installed_modules()
	{	
		$res=pg_query("select module_name,mod_id from installed_modules");
		return $res;
	}
	function notinstalled($name)
	{
		
		$res=pg_query("select * from installed_modules where module_name='$name'");
		if(pg_num_rows($res)==0)
		{
			return true;
		}
		else
			return false;
	}
	function most_popular()
	{
		$res=pg_query("select eid from log where activity<>'returned' group by eid order by count(*) desc limit 9;");
		return $res;
	}
?>
