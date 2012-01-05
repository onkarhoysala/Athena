<?php
	include_once "bin/session.php";
	$x=authenticate();
	if($x=="1")
		header("Location: home.php");
?>
<html>
<head>
	<script>
		/*if(navigator.appName=="Microsoft Internet Explorer")
		{
			alert("You seen to be using Internet Explorer. Due to various security concerns and many other reasons, we recommend you use Mozilla Firefox. Click OK to redirect to Firefox download page.");
			window.location="http://www.getfirefox.com";
		}*/
	</script>
	<?php include_once "bin/head.php";?>
	<title>Athena - Login</title>
</head>
<body>
	<div id='container'>
		<div id='logo-top'>
		</div>
		<div id='logo'>
			<div id='logo-left'>
			<a href='index.php'><img src="images/title.png" alt="" border=0 id="slogo" /></a>
			</div>
			<div id='logo-right' style='text-align:left;padding-top:0;color:white'>
				
			</div>
		</div>
		
		
		
		<div id='_content'>
			<!--<div id='menu'>
			
			</div>-->
			
			<div id='content1' style='width:1000px;margin-top:37px'>
				<!--<div id='searchbox'>
				</div>-->
				<!--The content of this div changes changes-->
				
				<div id='content2'>
					<div id='loginbox'>
	
						<div id='login'>
							<form action='bin/login.php' method='post' id='loginform' style='color:grey;text-align:center;'>
							<span style='color:black;font-weight:bold'>
								<?php
			
									if(isset($_GET['error']))
									{
										echo "<script>$('#loginbox').show();</script>";
										switch($_GET['error'])
										{
											case '1': echo "Invalid email or password"; break;
											case '2': echo "Please enter the email and password"; break;
											case '3': echo "You need to login to view that page"; break;
										}
									}
								?>
							</span>
							<p style='color:#444;font-size:16pt'>Login</p>
							Email<br/><input type='text' name='email' id="email" style='font-size:14pt' class="required error"/>
							<br/>Password<br/><input type='password' name='password' style='font-size:14pt' id='password' class="required error"/>
							<br/><input type='submit' id='submitbutton' value="Login"/><br/><br/>
		
							<span style='float:left;padding-left:15px'>New users, sign up <span style='color:#444;cursor:pointer;text-decoration:underline' onclick='signup();'>here</span></span>
							<span style='float:right'><a href='forgot.php' style='color:#444;text-decoration:underline'>Forgot password?</a></span>
		
							<br/>
							</form>
						</div>
					</div>

				</div>
			
			</div>
			
		
		</div>
			
	</div>	
	<div id='footer'>
				
	<?php
		include_once "bin/footer.php";
	?>
	</div>		    
</body>
</html>
