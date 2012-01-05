<?php
	function database_query($query)
	{
		return mysql_query($query);
	}
	function database_fetch_array($res)
	{
		return mysql_fetch_array($res);
	}
	function database_num_rows($res)
	{
		return mysql_num_rows($res);
	}
	function database_error()
	{
		return mysql_error();
	}
	function escape_sql($str)
	{
		return mysql_real_escape_string($str);
	}
	function check_for_php_modules()
	{
		/**Checks for MySQL and cURL PHP modules, without which Athena won't work.*/
		$mysql=function_exists("mysql_connect");
		$curl=function_exists("curl_init");
		if(!$mysql or !$curl)
		{
			if(!$mysql)
				echo "You haven't installed PHP MySQL module.";
			if(!$curl)
				echo "You haven't installed PHP cURL module";
			return false;
			die;
		}
		return true;
	}
	check_for_php_modules();
	$db=mysql_connect("$hostname","$dbuser","$dbpw");
	$d=mysql_select_db("$dbname");
	$error=fopen("error.log","a");
	check_db_connectivity($db,$d);
	/* Function definitions */
	//-----------------------
	function check_db_connectivity($db,$d)
	{
		if(!$db)
		{
			echo "<script>window.location='notinstalled.php';</script>";
		}	
		if(!$d)
		{
			echo "<script>window.location='notinstalled.php';</script>";
		}
		return true;
	}
	function validate($email,$pass)
	{
		/** Used to validate users when they login and whenever else required*/
		$email=mysql_real_escape_string($email);
		$pass=mysql_real_escape_string($pass);
		$res=mysql_query("select password('$pass');");
		$p1=mysql_fetch_array($res);
		$res=mysql_query("select password,uid from users where email='$email';");
		if(!$res)
		{
			fputs($error,mysql_error());
			return "0";
		}
		$p2=mysql_fetch_array($res);
		if($p1[0]==$p2[0])
			return "1";
		else return "0";
	}
	function check_file_write()
	{
		
		/*$f1=substr(sprintf("%o",fileperms("upload/")),-2);
		echo $f1;
		if($f1<77)
		{
			echo "you need to grant write to upload";
			die;
		}*/
	}
	function getemail($uid)
	{
		/**Returns the email (as a string) of the users with id as $uid. */
		$uid=mysql_real_escape_string($uid);
		$row=mysql_fetch_array(mysql_query("select email from users where uid='$uid';"));
		return $row[0];
	}
	function getuserid($email)
	{
		$email=mysql_real_escape_string($email);
		$row=mysql_fetch_array(mysql_query("select uid from users where email='$email';"));
		return $row[0];
	}
	function getname($email)
	{
		$email=mysql_real_escape_string($email);
		$row=mysql_fetch_array(mysql_query("select fname,sname from users where email='$email';"));
		return $row[0]." ".$row[1];
	}
	function getname_uid($uid)
	{
		$uid=mysql_real_escape_string($uid);
		$row=mysql_fetch_array(mysql_query("select fname,sname from users where uid='$uid';"));
		return $row[0]." ".$row[1];
	}
	function getuserdetails($email)
	{
		$email=mysql_real_escape_string($email);
		$row=mysql_fetch_array(mysql_query("select * from users where email='$email' or uid='$email';"));
		return $row;
	}
	function gettypes()
	{
		$res=mysql_query("select * from type;");
		return $res;
	}
	function getattr($tyid)
	{
		$tyid=mysql_real_escape_string($tyid);
		$res=mysql_query("select attrib from resource_attrib where tyid='$tyid';");
		//echo "select attrib from resource_attrib where tyid='$x';";
		if(!$res)
			fputs($error,mysql_error());
		return $res;
	}
	function gettypename($x)
	{
		$x=mysql_real_escape_string($x);
		$row=mysql_fetch_array(mysql_query("select description from type where tyid='$x';"));
		$name=str_replace("_"," ",$row[0]);
		return $name;
	}
	function gettypename_eid($x)
	{
		$x=mysql_real_escape_string($x);
		$row=mysql_fetch_array(mysql_query("select description from type natural join resource where eid='$x';"));
		$name=str_replace("_"," ",$row[0]);
		return $name;
	}
	/*function getresources($l,$p,$t)
	{
		$l=mysql_real_escape_string($l);
		$p=mysql_real_escape_string($p);
		$t=mysql_real_escape_string($t);
		$y=($p-1)*12;
		if($t=="")
			$query="select distinct eid,name,fname,sname,description,tyid,lost from (resource natural join type) natural join users where name like '$l%' order by name limit $y,12";
		else
			$query="select distinct eid,name,fname,sname,description,tyid,lost from (resource natural join type) natural join users where name like '$l%' and tyid='$t' order by name limit $y,10;";
		//$query="select * from browse_view limit 12;";
		$res=mysql_query($query);
		
		if(!$res)
		{
			fputs($error,mysql_error());
			die;
		}
		return $res;
	}
	
	function getbr_count($l,$t)
	{
		$l=mysql_real_escape_string($l);
		$t=mysql_real_escape_string($t);
		//$y=($p-1)*10;
		if($t=="")
			$query="select distinct eid,name,fname,sname,description,tyid,lost from (resource natural join type) natural join users where name like '$l%' order by name";
		else
			$query="select distinct eid,name,fname,sname,description,tyid,lost from (resource natural join type) natural join users where name like '$l%' and tyid='$t' order by name;";
		//$query="select * from browse_view limit 12;";
		$res=mysql_query($query);
		if(!$res)
		{
			fputs($error,mysql_error());
			die;
		}
		return $res;
	}*/
	function getresources($l,$p,$t)
	{
		$l=mysql_real_escape_string($l);
		$p=mysql_real_escape_string($p);
		$t=mysql_real_escape_string($t);
		$y=($p-1)*12;
		if($t=="")
			$query="select distinct eid,name,fname,sname,description,tyid,lost from (resource natural join type) natural join users where name like '$l%' order by name limit 12 offset $y";
		else
			$query="select distinct eid,name,fname,sname,description,tyid,lost from (resource natural join type) natural join users where name like '$l%' and tyid='$t' order by name limit 10 offset $y;";
		$res=mysql_query($query);
		if(!$res)
		{
			fputs($error,database_error());
			die;
		}
		return $res;
	}
	function getbr_count($l,$t)
	{
		$l=mysql_real_escape_string($l);
		$t=mysql_real_escape_string($t);
		//$y=($p-1)*10;
		if($t=="")
			$query="select distinct eid,name,fname,sname,description,tyid,lost from (resource natural join type) natural join users where name like '$l%' order by name";
		else
			$query="select distinct eid,name,fname,sname,description,tyid,lost from (resource natural join type) natural join users where name like '$l%' and tyid='$t' order by name;";
		$res=mysql_query($query);
		if(!$res)
		{
			fputs($error,database_error());
			die;
		}
		return $res;
	}
	function checkborrow($eid)
	{
		$eid=mysql_real_escape_string($eid);
		$res=mysql_query("select * from borrowed where eid='$eid';");
		if(mysql_num_rows($res)==0)
		{
			return "0";
		}
		else
		{
			$row=mysql_fetch_array($res);
			return $row[1];
		}
	}
	function borrowed($uid)
	{
		$uid=mysql_real_escape_string($uid);
		$res=mysql_query("select * from borrowed,resource where borrowed.uid='$uid' and resource.eid=borrowed.eid;");
		
		if(!$res)
		{
			fputs($error,mysql_error());
			die;
		}
		return $res;
	}
	function getresname($eid)
	{
		$eid=mysql_real_escape_string($eid);
		$res=mysql_query("select name from resource where eid='$eid';");
		if(!$res)
		{
			fputs($error,mysql_error());
			die;
		}
		$row=mysql_fetch_array($res);
		return stripslashes($row[0]);
	}
	function getmessages($uid)
	{
		$uid=mysql_real_escape_string($uid);
		$res=mysql_query("select * from message where to_uid='$uid' and delete_flag='1';");
		if(!$res)
		{
			fputs($error,mysql_error());
			die;
		}
		return $res;
	}
	function gettyid($eid)
	{
		$eid=mysql_real_escape_string($eid);
		$res=mysql_query("select tyid from resource where eid='$eid';");
		$row=mysql_fetch_array($res);
		return $row[0];
	}
	function gettypeid($t)
	{
		$t=mysql_real_escape_string($t);
		$res=mysql_query("select tyid from type where description='$t';");
		$row=mysql_fetch_array($res);
		return $row[0];
	}
	function searchresources($q,$by,$p,$t)
	{
		$q=mysql_real_escape_string($q);
		$by=mysql_real_escape_string($by);
		$p=mysql_real_escape_string($p);
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

				$res1[3]= "Search for '$q' retrieved $resname[total] of $resname[total_found] matches in $resname[time] sec.<br/>";	
				if ( is_array($resname["matches"]) )
				{
					foreach ( $resname["matches"] as $docinfo )
					{
						$results.=$docinfo[id].",";
					}
				}
			}
			$query="select eid,name,fname,sname,description,tyid from (resource natural join type) natural join users where eid in ($results"."0) and tyid in ($t"."0".") limit $y,10";
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

				$message= "Search for '$q' retrieved $resname[total] of $resname[total_found] matches in $resname[time] sec.<br/>";
				echo $message;	
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
			$query="select distinct resource.eid,name,fname,sname,description,type.tyid from resource,type,user,resource_tag where resource.tyid=type.tyid and resource.uid=user.uid and resource.eid=resource_tag.eid and tagname like '%$q%' and type.tyid in ($t"."0".") limit $y,10;";
		elseif($by=="taguser")
			$query="select distinct resource.eid,resource.name,user.fname,user.sname,type.description,type.tyid from resource,type,user,resource_tag where resource.tyid=type.tyid and resource.uid=user.uid and resource.eid=resource_tag.eid and tagname like '%$q%' and user.uid='$uid' and type.tyid in ($t"."0".")order by name limit $y,10;";
		else
		{
			$i=0;
			$res=mysql_query("select description from type natural join resource_attrib where attrib='$by';");
			while($row=mysql_fetch_array($res))
			{
				$q1="select eid from $row[0]details where $by like '$q%';";
				$res2=mysql_query($q1);
				if(!$res2)
				{
					fputs($error,mysql_error());
					die;
				}
				while($row2=mysql_fetch_array($res2))
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
		$res=mysql_query($query);
		if(!$res)
		{
			fputs($error,mysql_error());
			die;
		}
		$res1[0]=$res;
		$res1[1]=$resname[total];
		$res1[2]=$message;
		return $res1;
	}
	function searchresources_exists($q,$by,$p,$t)
	{
		$q=mysql_real_escape_string($q);
		$by=mysql_real_escape_string($by);
		$p=mysql_real_escape_string($p);
		$cl = new SphinxClient ();
		$sql = "";
		$mode = SPH_MATCH_PHRASE;
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
			$resname = $cl->Query("$q",$index);
			if ( $resname===false )
			{
				echo "";

			} else
			{
				if ( $cl->GetLastWarning() )
					//echo "WARNING: " . $cl->GetLastWarning() . "\n\n";

				$res1[3]= "Search for '$q' retrieved $resname[total] of $resname[total_found] matches in $resname[time] sec.<br/>";	
				if ( is_array($resname["matches"]) )
				{
					foreach ( $resname["matches"] as $docinfo )
					{
						
							$results.=$docinfo[id].",";
					}
				}
			}
			$query="select eid,name,fname,sname,description,tyid from (resource natural join type) natural join users where eid in ($results"."0) and tyid in ($t"."0".") limit $y,10";
			//echo $query;
		}
		else if($by=="name")
		{
			//$query="select eid,name,fname,sname,description,tyid from (resource natural join type) natural join users where name like '%$q%' order by name limit $y,10;";
			//$query="select eid,name,fname,sname,description,tyid from (resource natural join type) natural join users where name like \"$q%\" limit $y,10;";
			
			$index = "nameindex";
			$resname = $cl->Query ( "$q", $index );
			if ( $resname===false )
			{
				echo "";

			} else
			{
				if ( $cl->GetLastWarning() )
					echo "WARNING: " . $cl->GetLastWarning() . "\n\n";

				$message= "Search for '$q' retrieved $resname[total] of $resname[total_found] matches in $resname[time] sec.<br/>";	
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
			$query="select distinct resource.eid,name,fname,sname,description,type.tyid from resource,type,user,resource_tag where resource.tyid=type.tyid and resource.uid=user.uid and resource.eid=resource_tag.eid and tagname like '%$q%' and type.tyid in ($t"."0".") limit $y,10;";
		elseif($by=="taguser")
			$query="select distinct resource.eid,resource.name,user.fname,user.sname,type.description,type.tyid from resource,type,user,resource_tag where resource.tyid=type.tyid and resource.uid=user.uid and resource.eid=resource_tag.eid and tagname like '%$q%' and user.uid='$uid' and type.tyid in ($t"."0".")order by name limit $y,10;";
		else
		{
			$i=0;
			$res=mysql_query("select description from type natural join resource_attrib where attrib='$by';");
			while($row=mysql_fetch_array($res))
			{
				$q1="select eid from $row[0]details where $by like '$q%';";
				$res2=mysql_query($q1);
				if(!$res2)
				{
					fputs($error,mysql_error());
					die;
				}
				while($row2=mysql_fetch_array($res2))
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
		$res=mysql_query($query);
		if(!$res)
		{
			fputs($error,mysql_error());
			die;
		}
		$res1[0]=$res;
		$res1[1]=$resname[total];
		$res1[2]=$message;
		return $res1;
	}
	function getcount($q,$by,$p)
	{
		$q=mysql_real_escape_string($q);
		$by=mysql_real_escape_string($by);
		$p=mysql_real_escape_string($p);
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
			$res=mysql_query("select description from type natural join resource_attrib where attrib='$by';");
			while($row=mysql_fetch_array($res))
			{
				$q1="select eid from $row[0]details where $by like '%$q%';";
				$res2=mysql_query($q1);
				if(!$res2)
				{
					fputs($error,mysql_error());
					die;
				}
				while($row2=mysql_fetch_array($res2))
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
		$res=mysql_query($query);
		if(!$res)
		{
			echo mysql_error();
			die;
		}
		$c=mysql_fetch_array($res);
		return $c[0];
	}
	function check_exists_index($value,$type)
	{
		$value=mysql_real_escape_string($value);
		$type=mysql_real_escape_string($type);
		$q="$value";
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
		$index = "*";
		$resname = $cl->Query("$q",$index);
		if ( $resname===false )
		{
			echo "error";

		} else
		{
			if($resname[total_found]==0)
			{
				echo "error";
				return;
			}
			if ( $cl->GetLastWarning() )
				//echo "WARNING: " . $cl->GetLastWarning() . "\n\n";

			
			if ( is_array($resname["matches"]) )
			{
				foreach ( $resname["matches"] as $docinfo )
				{
					$results.=$docinfo[id].",";
					echo $docinfo[id];
				}
			}
		}
		return $results;
		
	}
	function gettags($eid,$uid)
	{
		$eid=mysql_real_escape_string($eid);
		$uid=mysql_real_escape_string($uid);
		$res=mysql_query("select tagname from resource_tag where eid='$eid' and uid='$uid';");
		return $res;
	}
	function getpoptags($eid)
	{
		$eid=mysql_real_escape_string($eid);
		if($eid=="0")
		{
			$res=mysql_query("select distinct tagname from resource_tag group by tagname having count(tagname)>'150';");
		}
		else
			$res=mysql_query("select distinct tagname from resource_tag where eid='$eid';");
		return $res;
	}
	function gettagcount($tag,$eid)
	{
		$eid=mysql_real_escape_string($eid);
		$tag=mysql_real_escape_string($tag);
		if($eid=='0')
			$res=mysql_query("select count(*) from resource_tag where tagname='$tag';");
		else
			$res=mysql_query("select count(*) from resource_tag where tagname='$tag' and eid='$eid';");
		$row=mysql_fetch_array($res);
		return $row[0];
	}
	function getcomments($eid)
	{	
		$eid=mysql_real_escape_string($eid);
		$res=mysql_query("select * from comment_table where eid='$eid';");
		if(!$res)
		{
			fputs($error,mysql_error());
			die;
		}
		return $res;
	}
	function getmodule($page)
	{
		$page=mysql_real_escape_string($page);
		$res1=mysql_query("select * from module where mod_loc='$page';");
		if(mysql_num_rows($res1)==0)
		{
			return;
		}
		if(!$res1)
		{
			fputs($error,mysql_error());
			die;
		}
		else
		{
			while($row=mysql_fetch_array($res1))
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
		$res=mysql_query("select * from resource where lost='1'");
		if(!$res)
		{
			fputs($error,mysql_error());
			die;
		}
		return $res;
	}
	function borrowed_from_log($uid)
	{
		$uid=mysql_real_escape_string($uid);
		$res=mysql_query("select * from log,resource where log.uid='$uid' and resource.eid=log.eid and activity='borrowed' group by name order by time_entry desc;");
		
		if(!$res)
		{
			fputs($error,mysql_error());
			die;
		}
		return $res;
	}
	function getattributes()
	{
		$res=mysql_query("select distinct attrib from resource_attrib;");
		if(!$res)
		{
			fputs($error,mysql_error());
			die;
		}
		return $res;
	}
	function checkauthor($tyid)
	{
		$tyid=mysql_real_escape_string($tyid);
		$res=mysql_query("select attrib from resource_attrib where tyid='$tyid' and attrib='author';");
		if(!$res)
		{
			fputs($error,mysql_error());
			die;
		}
		$c=mysql_num_rows($res);
		return $c;
	}
	function getauthor($eid,$tyid)
	{
		$eid=mysql_real_escape_string($eid);
		$tyid=mysql_real_escape_string($tyid);
		$ty=gettypename($tyid);
		$res=mysql_query("select author from $ty where eid='$eid';");
		//echo "select author from $ty where eid='$eid';";
		if(!$res)
		{
			fputs($error,mysql_error());
			die;
		}
		$row=mysql_fetch_array($res);
		return $row[0];
	}
	function getusers()
	{
		$res=mysql_query("select fname,sname,uid from users where uid<>'-1';");
		return $res;
	}
	function getrating($eid,$uid)
	{
		$eid=mysql_real_escape_string($eid);
		$uid=mysql_real_escape_string($uid);
		$res=mysql_query("select rating from ratings where eid='$eid' and uid='$uid';");
		if(!$res)
		{
			fputs($error,mysql_error());
			die;
		}
		$row=mysql_fetch_array($res);
		return $row[0];
	}
	function getglobalrating($eid)
	{
		$eid=mysql_real_escape_string($eid);
		$res=mysql_query("select rating from ratings where eid='$eid';");
		$avg=0;
		$count=mysql_num_rows($res);
		if($count!=0)
		{
			while($row=mysql_fetch_array($res))
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
		$s=mysql_real_escape_string($s);
		$uid=$_SESSION['uid'];
		if($s=='Logged in' or $s=='Logged out')
		{
			$res=mysql_query("insert into log (time_entry,activity,uid) values(now(),'$s','$uid')");
			return;
		}
		
		$data=explode("::",$s);
		if($data[0]=="details")
		{
			$data[1]=mysql_real_escape_string($data[1]);
			$res=mysql_query("insert into log (time_entry,activity,uid,eid) values(now(),'viewed','$uid','$data[1]')");
			return;
		}
		if($data[0]=="wished for")
		{
			$data[1]=mysql_real_escape_string($data[1]);
			$res=mysql_query("insert into log (time_entry,activity,uid,wid,comments) values(now(),'wished for','$uid','$data[1]',\"$data[2]\")");
			//echo "insert into log (time_entry,activity,uid,wid,comments) values(now(),'viewed','$uid','$data[1]',\"$data[2]\")";
			return;
		}
		
		if($data[0]=="search")
		{
			$data[1]=mysql_real_escape_string($data[1]);
			$res=mysql_query("insert into log (time_entry,activity,uid,comments) values (now(),'search','$uid',\"$data[1]\")");
			return;
		}
		if($data[0]=="added")
		{
			$data[1]=mysql_real_escape_string($data[1]);
			$res=mysql_query("insert into log (time_entry,activity,uid,eid) values (now(),'added','$uid',\"$data[1]\")");
			return;
		}
		if($data[0]=="borrowed")
		{
			$data[1]=mysql_real_escape_string($data[1]);
			$res=mysql_query("insert into log (time_entry,activity,uid,eid) values (now(),'borrowed','$data[1]',\"$data[2]\")");
			if(!$res)
			{
				echo "insert into log (time_entry,activity,uid,eid) values (now(),'borrowed','$data[1]',\"$data[2]\")";
			}
			return;
		}
		if($data[0]=="returned")
		{
			$data[1]=mysql_real_escape_string($data[1]);
			$res=mysql_query("insert into log (time_entry,activity,uid,eid) values (now(),'returned','$data[1]',\"$data[2]\")");
			if(!$res)
			{
				echo mysql_error();
			}
			return;
		}
		if($data[0]=="added new resource type")
		{
			$data[1]=mysql_real_escape_string($data[1]);
			$res=mysql_query("insert into log(time_entry,activity,uid,comments) values(now(),'added new resource type','$uid',\"$data[1]\")");
			return;
		}
		if($data[0]=="edited")
		{
			$data[1]=mysql_real_escape_string($data[1]);
			$data[2]=mysql_real_escape_string($data[2]);
			$res=mysql_query("insert into log(time_entry,activity,uid,eid,comments) values(now(),'edited','$uid','$data[1]','$data[2]');");
			return;
		}
	}
	function api_logger($uid,$s)
	{
	
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
		$uid=mysql_real_escape_string($uid);
		$res=mysql_query("select resource.eid,name,tyid from favourites,resource where favourites.uid='$uid' and favourites.eid=resource.eid;");
		if(!$res or mysql_num_rows($res)==0)
		{
			echo "You havn't added anything to your favourites list.";
			die;
		}
		return $res;
	}
	function login($uid)
	{
		$uid=mysql_real_escape_string($uid);
		$res=mysql_query("insert into online values('$uid',now());");
	}	
	function logout($uid)
	{
		$uid=mysql_real_escape_string($uid);
		$res=mysql_query("delete from online where uid='$uid';");
	}
	function getoverallrating($eid)
	{
		$eid=mysql_real_escape_string($eid);
		$row=mysql_fetch_array(mysql_query("select rating from resource where eid='$eid';"));
		return $row[0];
	}
	function delete_resource($eid)
	{
		mysql_query("delete from resource where eid='$eid';");
		
		echo "done";
		
	}
	function getauthors()
	{
		$f=fopen("authors.txt","w");
		$i=0;
		$res=mysql_query(" select author,count(author) from Book where author<>' ' group by author order by count(author) desc");
		while($row=mysql_fetch_array($res))
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
		$res=mysql_query("select url from images where eid='$eid' limit 0,3;");
		return $res;
	}
	function getallcovers($eid)
	{
		$res=mysql_query("select url,uid from images where eid='$eid';");
		return $res;
	}
	function check_for_download_request($eid,$uid)
	{
		$eid=mysql_real_escape_string($eid);
		$uid=mysql_real_escape_string($uid);
		$res=mysql_query("select * from download_request where eid='$eid' and uid='$uid'");
		return $res;
	}
	function pending_download_requests_for_eid($eid,$uid)
	{
		$eid=mysql_real_escape_string($eid);
		$uid=mysql_real_escape_string($uid);
		$res=mysql_query("select * from download_request where eid='$eid' and status='pending' and id in(select id from uploaded where uid='$uid');");
		return $res;
	}
	function get_installed_modules()
	{	
		$res=mysql_query("select module_name,mod_id from installed_modules");
		return $res;
	}
	function notinstalled($name)
	{
		
		$res=mysql_query("select * from installed_modules where module_name='$name'");
		if(mysql_num_rows($res)==0)
		{
			return true;
		}
		else
			return false;
	}
	function most_popular()
	{
		$res=mysql_query("select eid from log where activity<>'returned' and eid<>'NULL' group by eid order by count(*) desc limit 9;");
		return $res;
	}
	function check_exists($eid)
	{
		$res=mysql_num_rows(mysql_query("select eid from resource where eid='$eid';"));
		return $res;
	}
	function getUserWishlist($uid)
	{
		$uid=mysql_real_escape_string($uid);
		$res=database_query("select * from wishlist where uid='$uid';");
		return $res;
	}
	function getPublicWishlist()
	{
		$res=database_query("select * from wishlist;");
		return $res;
	}
?>
