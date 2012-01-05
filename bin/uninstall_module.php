<?php
	$mod_name=$_GET['name'];
	include_once "modules/$mod_name/uninstall.php";
	include_once "query.php";
	include_once "session.php";
	if(getemail($_SESSION['uid'])!="admin")
	{
		echo "<p style='text-align:center;color:grey;size:14pt'>You do not have permissions to view this page</p>";
		die;
	}
	
	
	foreach($queries as $query)
	{
		$res=database_query($query);
		if(!$res)
		{
			echo "Query '$query' failed. Contact the module developed for more details.<br/>";
			die;
		}
	}
?>
<script>window.location="module.php";</script>
