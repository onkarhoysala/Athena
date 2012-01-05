<html>
<head>
	<script>
		/*if(navigator.appName=="Microsoft Internet Explorer")
		{
			alert("You seen to be using Internet Explorer. Due to various security concerns and many other reasons, we recommend you use Mozilla Firefox. Click OK to redirect to Firefox download page.");
			window.location="http://www.getfirefox.com";
		}*/
	</script>
	<link rel="stylesheet" type="text/css" href='./style/lib.css' />
	<title>Athena Installation</title>
</head>
<body>
	<div id='container'>
		<div id='logo-top'>
		</div>
		<div id='logo'>
			<div id='logo-left'>
			<a href='home.php'><img src="images/title.png" alt="" border=0 id="slogo" /></a>
			</div>
			<div id='logo-right'>
			</div>
		</div>
		
		
		
		<div id='_content'>
			<!--<div id='menu'>
			
			</div>-->
			
			<div id='content1' style='width:1000px'>
				<div id='searchbox'>
				</div>
				<!--The content of this div changes changes-->

				<div id='content2'>
					<?php
						include_once "bin/settings.php";
						if($dbhost=="mysql")
						{
							mysql_connect($hostname,$dbuser,$dbpw);
							$res=mysql_select_db($dbname);
						}
						if($dbhost=="pgsql")
						{
							$r=pg_connect("host=$hostname user=$dbuser dbname=$dbname password=$dbpw");
							$res=pg_query("select * from users");
						}
						
						if(!$res)
						{
							echo "<p style='color:grey;font-size:14pt;text-align:center'>You haven't installed Athena yet. Click <a href='install/install.php'>here</a> to install it.</p>";
						}
						else
						{
							echo "<h3 style='font-size:14pt;color: grey;text-align:center'>Hey! You have already installed Athena. Please remove the existing database to reinstall.</h3>";
							
						}
					?>
					
				</div>
			
			</div>
			
		
		</div>
			
	</div>	
	<div id='footer'>
				
	</div>		    
</body>
</html>
