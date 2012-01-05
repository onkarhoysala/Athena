<html>
<head>
<link rel="stylesheet" type="text/css" href="../style/lib.css"/>
<title>Installation</title>
</head>
<body>
	<div id='container'>
		<div id='logo-top'>
		</div>
		
		<div id='logo'>
			<div id='logo-left'>
			<img src="../images/title.png" alt="" border=0 id="slogo" />
			</div>
			<div id='logo-right'>
			<?php include_once "bin/header.php";?>
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
						include_once "../bin/settings.php";
						if($dbtype=="mysql")
						{
							mysql_connect($hostname,$dbuser,$dbpw);
							$res=mysql_select_db($dbname);
						}
						if($dbtype=="pgsql")
						{
							$r=pg_connect("host=$hostname user=$dbuser dbname=$dbname password=$dbpw");
							$res=pg_query("select * from users");
						}
						if(!$res)
						{
							echo "<h3>Hi! Follow the next few steps to install Athena</h3>";
							echo "<p style='color:grey;font-size:12pt'>You are installing on a Postgres database. Click <a href='install.php'>here</a> to install on MySQL.</p>";
						}
						else
						{
							echo "<h3 style='font-size:14pt;color: grey;text-align:center'>Hey! You have already installed Athena. Please remove the existing database to reinstall.</h3>";
							die;
						}
						if(isset($_GET['error']))
						{
							echo "<span style='color:red'>Incorrect root username or password. Please try again.</span><br/>";
						}
					?>
					<!--<form action="dbsetup.php" method="POST" style='width:500px;position:relative;text-align:right'>
						<label>MySQL root user</label><input type='text' name='user' /><br/>
						<label>MySQL root password</label><input type='password' name='pw' /><br/>
						<input type='submit' value='Go' />
					</form>
					If you don't know the MySQL root user name and password, contact your System Administrator.-->
					
					<ol>
						<?	
							$pgsql=function_exists("pg_connect");
							$curl=function_exists("curl_init");
							$f1=substr(sprintf("%o",fileperms("../bin/settings.php")),-2);
							$f2=substr(sprintf("%o",fileperms("../bin/sphinx.conf")),-2);
							$f3=substr(sprintf("%o",fileperms("../bin/index/")),-2);
							$f4=substr(sprintf("%o",fileperms("../bin/error.log")),-2);
							$f5=substr(sprintf("%o",fileperms("../error.log")),-2);
							$f6=substr(sprintf("%o",fileperms("../api/error.log")),-2);
							
							if($f1<66 or $f2<66 or $f3<66 or $f4<66 or $f5<66 or $f6<66)
							{
								echo "<span style='color:red;font-size:11pt'>Before continuing, grant write permissions to:";
								echo "<ul>";
								if($f1 < 66)
								{
									echo "<li>./bin/settings.php</li>";
									
								}
								if($f2 < 66)
								{
									echo "<li>./bin/sphinx.conf</li>";
									
								}
								if($f3 < 66)
								{
									echo "<li>./bin/index/</li>";
									
								}
								if($f4 < 66)
								{
									echo "<li>./bin/error.log</li>";
									
								}
								if($f5 < 66)
								{
									echo "<li>./error.log</li>";
									
								}
								if($f6 < 66)
								{
									echo "<li>./api/error.log</li>";
									
								}
								echo "</ul></span>";
								echo "<br/><br/>Click <a href='install.php'>here</a> to restart the installation.";
								die;
							}
							if(!$pgsql)
								$c1="red";
							else $c1="green";
							if(!$curl)
								$c2="red";
							else $c2="green";
							if($c1=="red")
								echo "<li style='color:$c1'>You haven't installed PHP Postgres module</li>";
							if($c2=="red")	
								echo "<li style='color:$c2'>You haven't installed PHP cURL</li>";
							if(!$pgsql or !$curl)
							{
								if(!$mysql)
									echo "<br/><span style='font-weight:bold;color:red'>You haven't installed PHP Postgres module.</span><br/>";
								if(!$curl)
									echo "<span style='font-weight:bold;color:red'>You haven't installed PHP cURL module</span>";
								echo "<br/><br/>Please install the above modules to continue with the installation";
								die;
							}
							if(isset($_GET['error']))
							{
								$code=$_GET['error'];
								if($code=='1')
								{
									echo "<span style='font-weight:bold'>Seems like you entered the wrong root username/password!</span>";
								}
							}
						?>
					</ol>	
					<p style='font-weight:bold'>Enter the database name, username and password which you want Athena to use. This database must exist and the username provided must have all accesses to this database</p>
					<form action='dbsetup_pg.php' method='POST' style='width:600px;text-align:right'>
						
						
						<label>PostgreSQL hostname </label><input type='text' name='host' /><br/>
						<label>PostgreSQL database name </label><input type='text' name='db' /><br/>
						<label>PostgreSQL username </label><input type='text' name='user' /><br/>
						<label>PostgreSQL password </label><input type='password' name='pw' /><br/>
						<input type='submit' value='Go' />
					</form>
				</div>
			
			</div>
			
		
		</div>
			
	</div>	
	<div id='footer'>
				
	
	</div>
</body>
</html>
