<?php
	$mod_name=$_GET['name'];
	include_once "modules/$mod_name/install.php";
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
	
	foreach($pages as $page)
	{
		$p=explode(":",$page);
		database_query("insert into module values('$mod_name/$p[0]','$p[1]')");
	}
	database_query("insert into installed_modules(module_name) values('$mod_name')");
	
?>
<script>window.location="module.php";</script>
