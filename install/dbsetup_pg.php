<?php
	include_once "sql_pg.php";
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
						$p=$_POST['pw'];
						$dbname=$_POST['db'];
						$hostname=$_POST['host'];
											
						$db=pg_connect("host=$hostname user=$u password=$p dbname=$dbname");
						if(!$db)
						{
							echo pg_last_error();
							//echo "<script>window.location='install.php?error=1';</script>";
						}
						if(!$db)
						{
							echo pg_last_error()." -1";
							die;
						}
						$res=pg_query($type);
						if(!$res)
						{
							echo pg_last_error();
						}
						$res=pg_query($resource);
						if(!$res)
						{
							echo pg_last_error();
						}
						$res=pg_query($user);
						if(!$res)
						{
							echo pg_last_error();
						}
						$res=pg_query($borrowed);
						if(!$res)
						{
							echo pg_last_error();
						}
						$res=pg_query($comment);
						if(!$res)
						{
							echo pg_last_error();
						}
						$res=pg_query($fav);
						if(!$res)
						{
							echo pg_last_error();
						}
						$res=pg_query($feedback);
						if(!$res)
						{
							echo pg_last_error();
						}
						$res=pg_query($images);
						if(!$res)
						{
							echo pg_last_error();
						}
						$res=pg_query($log);
						if(!$res)
						{
							echo pg_last_error();
						}
						$res=pg_query($message);
						if(!$res)
						{
							echo pg_last_error();
						}
						$res=pg_query($module);
						if(!$res)
						{
							echo pg_last_error();
						}
						$res=pg_query($online);
						if(!$res)
						{
							echo pg_last_error();
						}
						$res=pg_query($ratings);
						if(!$res)
						{
							echo pg_last_error();
						}
						$res=pg_query($reco_log);
						if(!$res)
						{
							echo pg_last_error();
						}
						$res=pg_query($res_attr);
						if(!$res)
						{
							echo pg_last_error();
						}
						$res=pg_query($res_tag);
						if(!$res)
						{
							echo pg_last_error();
						}
						$res=pg_query($uploaded);
						if(!$res)
						{
							echo pg_last_error();
						}
						$res=pg_query($download_req);
						if(!$res)
						{
							echo pg_last_error();
						}
						$res=pg_query($ins_mod);
						if(!$res)
						{
							echo pg_last_error();
						}
						$res=pg_query($insert);
						echo "</span style='font-size:14pt'>Database is setup successfully. We need to setup the administrator user now. The administrator username is 'admin'. Enter the following details:</span>";
						
										
					?>
						<form action='finish_pg.php' style='text-align:right;width:600px' method='post'>
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
