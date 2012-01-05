<?php
	/**
	* The use of this file is deprecated.
	*/
	/**
	* Session.php contains all the necessary methods and variables necessay to maintain a session.	
	*/
	include_once "session.php";
	/**
	* Query.php contains all the necessary methods and variables necessary to maintain a database connection. It also contains methods that act as wrapper methods for database queries.
	*/
	include_once "query.php";
	echo "<h3>Search results</h3>";
	require_once("sphinxapi.php");
	foreach($_POST as $key=>$val)
	{
		$indexnames.="index_".$key." ";
	}
	//echo $indexnames;
	$cl = new SphinxClient ();
	$sql = "";
	$mode = SPH_MATCH_ALL;
	$host = "localhost";
	$port = 9312;
	$groupby = "";
	$groupsort = "@group desc";
	$filter = "group_id";
	$filtervals = array();
	$distinct = "";
	$sortby = "";
	$limit = 20;
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
	$index = "$indexnames";
	foreach($_POST as $key=>$val)
	{
		$indexnames.="index_".$key." ";
	}
	//$resname = $cl->Query ( "$q*", $index );
	foreach($_POST as $key=>$val)
	{
		
	}
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
	$query="select eid,name,fname,sname,description,tyid from (resource natural join type) natural join user where eid in ($results"."0) limit $y,10";
	
?>
