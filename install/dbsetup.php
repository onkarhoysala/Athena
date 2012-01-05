<?php
	include_once "sql.php";
?>
<html>
<head>
<title>Database Setup</title>
<link rel="stylesheet" type="text/css" href="../style/lib.css"/>
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
					<h3>Hi! Follow the next few steps to install Athena</h3>
					<?php
						$u=$_POST['user'];
						$rootu=$_POST['rootuser'];
						$rootpw=$_POST['rootpw'];
						$p=$_POST['pw'];
						$dbname=$_POST['db'];
						$hostname=$_POST['host'];
						$db=mysql_connect("$hostname","$rootu","$rootpw");
						if(!$db)
						{
							echo "<script>window.location='install.php?error=1';</script>";
							die;
						}
						$r1=mysql_query("create database $dbname;");
						$r2=mysql_query("grant all privileges on $dbname.* to '$u'@'%' identified by '$p';");
						if(!$r1 or !$r2)
						{
							echo "Something went wrong. Please contact your system administrator. Sorry!";
							die;
						}
						mysql_close($db);
						
						$db=mysql_connect("$hostname","$u","$p");
						if(!$db)
						{
							echo mysql_error();
							//echo "<script>window.location='install.php?error=1';</script>";
						}
						$db=mysql_select_db("$dbname");
						if(!$db)
						{
							echo mysql_error()." -1";
							die;
						}
						$res=mysql_query($type);
						$res=mysql_query($resource);
						$res=mysql_query($user);
						$res=mysql_query($borrowed);
						$res=mysql_query($comment);
						$res=mysql_query($fav);
						$res=mysql_query($feedback);
						$res=mysql_query($images);
						$res=mysql_query($log);
						$res=mysql_query($message);
						$res=mysql_query($module);
						$res=mysql_query($online);
						$res=mysql_query($ratings);
						$res=mysql_query($reco_log);
						$res=mysql_query($res_attr);
						$res=mysql_query($res_tag);
						$res=mysql_query($uploaded);
						$res=mysql_query($download_req);
						$res=mysql_query($ins_mod);
						$res=mysql_query($insert);
						$res=mysql_query($api);
						$res=mysql_query($settings_table);
						$res=mysql_query($insert);
						$res=mysql_query($wishlist);
						echo "</span style='font-size:14pt'>Database is setup successfully. We need to setup the administrator user now. The administrator username is 'admin'. Enter the following details:</span>";
						
										
					?>
						<form action='finish.php' style='text-align:right;width:600px' method='post'>
							<label>Password: </label><input type='password' name='admin_pw' /><br/>
							<label>First name: </label><input type='text' name='fname' /><br/>
							<label>Second name: </label><input type='text' name='sname' /><br/>
							<label>Email: </label><input type='text' name='email' /><br/>
							<label>Security Question: </label><input type='text' name='sec_q' /><br/>
							<label>Answer: </label><input type='text' name='sec_a' /><br/>
							<label>Phone: </label><input type='text' name='phone' /><br/>
							<label>What is the name of your organisation? </label><input type='text' name='org' /><br/>
							<label>Website: </label><input type='text' name='web' /><br/>
							<?php 
							echo "<input type='text' style='display:none' value='$dbname' name='dbname' /><input type='text' style='display:none' value='$hostname' name='host' /><br/>";
							echo "<input type='text' style='display:none' value='$u' name='dbuser' /><br/><input type='text' style='display:none' value='$p' name='dbpw' /><br/>"; 
							?>
							<input type='submit' value='Finish' />
						</form>
				</div>
			
			</div>
			
		
		</div>
			
	</div>	
	<div id='footer'>
				
	
	</div>
</body>
</html>
