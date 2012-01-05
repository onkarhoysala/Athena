<?php
	/**
	*This is the PHP file to display details of a resource.
	*/
	include_once "bin/session.php";
	include_once "bin/query.php";
	$x=authenticate();
	/*$url=$_SERVER['REQUEST_URI'];
	$eid=explode("php/",$url);
	//system("pwd");
	$_GET['eid']=$eid[1];*/
	/*else if(!isset($_SESSION['logentry']))
	{
		$uid=$_SESSION['uid'];
		$res=mysql_query("insert into reco_log values('','$uid','Logged in',now());");
		$_SESSION['logentry']='set';
	}*/
?>
<html>

<head>
	
	<title>Home</title>
	<?php
		include_once "bin/head.php";
	?>
	
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
				<?php 
				if($x=="1")
					include_once"bin/menu.php";
				?>
			</div>
			
			<div id='content1'>
				<div id='bcrumb'>
			
				</div>
				<div id='searchbox'>
					<?php 
						if($x=="1")
							include_once "bin/searchbox.php";
					?>
				</div>
				
				<div id='content2'>
					<?php
						include_once "bin/details.php";
						
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
