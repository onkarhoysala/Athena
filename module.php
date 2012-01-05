<?php
	include_once "bin/session.php";
	include_once "bin/query.php";
	$x=authenticate();
	if($x!="1")
	{
		$_SESSION['page']="browse";
		header("Location:  login.php?error=3");
		die;
	}
	/*else if(!isset($_SESSION['logentry']))
	{
		$uid=$_SESSION['uid'];
		$res=mysql_query("insert into reco_log values('','$uid','Logged in',now());");
		$_SESSION['logentry']='set';
	}*/
?>
<html>

<head>
	<?php include_once "bin/head.php";?>
	<title>Athena - Modules</title>
	
	
</head>
<body>
	<div id='busydiv'>
	</div>
        <div id='container'>
		<div id='logo-top'>
		</div>
		
		<div id='logo'>
			<div id='logo-left'>
			<a href='home.php'><img src="images/title.png" alt="" border=0 id="slogo" /></a>
			</div>
			<div id='logo-right'>
			<?php include_once "bin/header.php";?>
			</div>
		</div>
		
		
		
		<div id='_content'>
			<div id='menu'>
				<?php include_once"bin/menu.php";?>
			</div>
			
			<div id='content1'>
				<div id='bcrumb'>
			
				</div>
				<div id='searchbox'>
					<?php include_once "bin/searchbox.php";?>
				</div>
				
				<div id='content2'>
					<?php
						include_once "bin/module.php";
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
