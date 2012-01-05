<?php
	include_once "../../bin/query.php";
	$hash=$_GET['hash'];
	header("Content-Type: application/xml");
	$xml="<?xml version='1.0' encoding='utf-8'?><athena>";
	$res=database_query("select * from users where uid=(select uid from api_register where hash='$hash')");
	
	while($row=database_fetch_array($res))
	{
		$xml.="<user><uid>$row[0]</uid><email>$row[1]</email><firstname>$row[2]</firstname><secondname>$row[3]</secondname><phone>$row[4]</phone><designation>$row[5]</designation></user>";
	}
	$xml.="</athena>";
	echo $xml;
?>
