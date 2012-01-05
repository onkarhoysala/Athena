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
						$pw=$_POST['admin_pw'];
						$fname=$_POST['fname'];
						$sname=$_POST['sname'];
						$sec_q=$_POST['sec_q'];
						$phone=$_POST['phone'];
						$sec_a=$_POST['sec_a'];
						$email=$_POST['email'];
						$org=$_POST['org'];
						$website=$_POST['web'];
						$u=$_POST['dbuser'];
						$p=$_POST['dbpw'];
						$hostname=$_POST['host'];
						$dbname=$_POST['dbname'];
						if($pw=="" or $pw==" " or $fname=="" or $sname=="" or $email=="")
						{
							echo "Password/First name/Second name fields cannot be blank. Please try again";
							echo "<form action='finish.php' style='text-align:right;width:600px' method='post'>
								<label>Password: </label><input type='password' name='admin_pw' /><br/>
								<label>First name: </label><input type='text' name='fname' value='$fname'/><br/>
								<label>Second name: </label><input type='text' name='sname' value='$sname'/><br/>
								<label>Email: </label><input type='text' name='email' value='$email' /><br/>
								<label>Security Question: </label><input type='text' name='sec_q' value='$sec_q'/><br/>
								<label>Answer: </label><input type='text' name='sec_a' /><br/>
								<label>Phone: </label><input type='text' name='phone' value='$phone'/><br/>
								<label>What is the name of your organisation? </label><input type='text' name='org' value='$org' /><br/>
								<label>Website: </label><input type='text' name='web' value='$website' /><br/>
								<input type='text' style='display:none' value='$dbname' name='dbname' /><br/>
								<input type='text' style='display:none' value='$u' name='dbuser' /><br/>
								<input type='text' style='display:none' value='$p' name='dbpw' /><br/>
								<input type='text' style='display:none' value='$hostname' name='host' /><br/>
								<input type='submit' value='Finish' />
							      </form>";
							die;
						}
						mysql_connect("$hostname","$u","$p");
						mysql_select_db("$dbname");
						$res=mysql_query("insert into users values('','admin','$fname','$sname','$phone','admin',password('$pw'),'$sec_q','$sec_a');");
						if(!$res)
						{
							echo mysql_error();
							die;
						}
						$string="<?php\n\$dbname='$dbname';\n\$dbuser='$u';\n\$dbpw='$p';\n\$admin_email='$email';\n\$org='$org';\n\$website='$website';\n\$hostname='$hostname';\n\$dbtype='mysql';\n\$upload_folder='/upload-athena';\n\$index_folder='/index-athena';\n?>";
						$file=fopen("../bin/settings.php","w");
						if(!$file)
						{
							echo "The settings file could not be created. Open the file 'settings.php' in the bin folder and paste the following code.";
							echo "<br/><br/><textarea rows='25' cols='50'>$string</textarea>";
							echo "<br/>You're done! Click <a href='../index.php'>here</a> to go to the home page.";
							die;
						}
						$len=fwrite($file,$string);
						echo "<br/><p style='color:grey;text-align:center;font-size:14'>You're done! Click <a href='../index.php'>here</a> to go to the home page.</a>";
					?>
				</div>
			
			</div>
			
		
		</div>
			
	</div>	
	<div id='footer'>
				
	
	</div>
</body>
</html>
