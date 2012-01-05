<?php
	chdir("../../");
	include_once "bin/query.php";
	
	if(isset($_GET['apikey']))
	{
		
		
		$apikey=$_GET['apikey'];
		//echo "update goodreads set api_key='$apikey'";
		//$res=database_query("show tables");
		$res=mysql_query("update goodreads set api_key='$apikey'");
		if(!$res)
		{
			echo "error";
			header("Location: ../../module.php?gr_save=error");
		}
		echo "done";
		header("Location: ../../module.php?gr_save=done");
		die;
	}
?>
