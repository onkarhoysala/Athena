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
	<title>Reset the password</title>
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
			
			<div id='content1' style='width:1000px'>
				<div id='searchbox'>
				</div>
				<!--The content of this div changes changes-->
				
				<div id='content2'>
				<?php
					include_once "bin/forgot.php";
				?>
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
