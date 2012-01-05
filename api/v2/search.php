<?php
	include_once "../../bin/query.php";
	header("Content-Type: application/xml");
	$xml="<?xml version='1.0' encoding='utf-8'?><athena>";
	
	$q=$_GET['q'];
	
	if(!isset($_GET['by']))
		$by="all";
	else
		$by=$_GET['by'];
	
	if(!isset($_GET['page']))
		$p="1";
	else
		$p=$_GET['page'];
	
	if(!isset($_GET['type']))
		$t="1,";
	else
		$t=$_GET['type'];
	
	$q=escape_sql($q);
	$t=escape_sql($t);
	$p=escape_sql($p);
	$by=escape_sql($by);
	
	$results=searchresources($q,$by,$p,$t);
	//returns eid,name,fname,sname,description,tyid
	$query=$results[0];
	$total=$results[1];
	$xml.="<total_results>$total</total_results>";
	while($row=database_fetch_array($query))
	{
		$xml.="<resource><eid>$row[0]</eid><name>$row[1]</name><added_by>$row[2] $row[3]</added_by><type>$row[4]</type></resource>";
	}
	
	$xml.="</athena>";
	echo $xml;
?>
