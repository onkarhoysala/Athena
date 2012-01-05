<?php
	include_once "query.php";
	include_once "session.php";
	check_file_write();
?>
<link rel="shortcut icon" href="./images/favicon.png" type="image/png"/>
<script type="text/javascript" src="script/jquery.js"></script>
<script type="text/javascript" src="script/jscript.js"></script>
<script type="text/javascript" src="script/jquery.validate.js"></script>
<script src="script/jquery.timeago.js" type="text/javascript"></script>
<script type="text/javascript" src="script/jquery.masonry.js"></script>
<script>
	window.onload=function()
		      {

		      	checkmail();
		      	jQuery('abbr.timeago').timeago();
			checkload();
			breadcrumb();
			/*$("#loginform").validate({
			  rules: {
			    field: {
			      required: true,
			      email: true
			    }
			  }
			});*/
			$("#signup").validate({
			  rules: {
			    field: {
			      required: true,
			      email: true
			    }
			  }
			});
			
			//window.location.reload();
			//location.reload(false);
			/**@author Amar
			*/
			resizer();
		      }
	
</script>
<?php
	$res=gettypes();
	while($row=database_fetch_array($res))
	{
		echo "<script type='text/javascript'>";
			echo "function add$row[1]()";
			echo "{";
				$tyid=$row[0];
				echo "var t=$(\"#title\").val();";
				echo "var loc=$(\"#location\").val();";
				echo "var owner=$(\"#owner\").val();";
				echo "var captcha=$(\"#captcha\").val();";
				echo "var cap=$(\"#cap\").val();";
				echo "var tags=$(\"#tags\").val();";
				echo "var digital=$(\"#digital\").is(':checked');";
				echo "if(digital==true) digital=1;else digital=0;";
				echo "var q=\"title=\"+t+\"&digital=\"+digital+\"&loc=\"+loc+\"&tyid=$tyid\"+\"&owner=\"+owner+\"&captcha=\"+captcha+\"&cap=\"+cap+\"&tags=\"+tags;";
				$attr=getattr($row[0]);
				while($a=database_fetch_array($attr))
				{
					$attribname=$a[0];
					$attribname=str_replace(" ","_",$attribname);
					echo "var ".$attribname."a=$(\"#$attribname\").val();";
					echo "q+=\"&$attribname=\"+".$attribname."a;";
				}
				
				echo "$.ajax({type: \"GET\",url: \"bin/add_.php?case=resource&\"+q,success: function(msg) { msg=msg.split('||');";
				echo "if(msg[0]=='done')";
				echo "{if(digital=='1'){ $(\"#response\").html(msg[1]);$(\"#dig\").show();$(\"#digeid\").val(msg[2]);return;$(\"#response\").html(msg[1]); document.form1.reset();}} $(\"#response\").html(msg[1]); document.form1.reset();}});";
				
				
			echo "}";
		
		/*if(isset($_SESSION['page']))
		{
			$x=$_SESSION['page'];
			echo "load('$x');";
		}*/
		
		echo "</script>";
	}
?>
<?php
	$res=gettypes();
	while($row=database_fetch_array($res))
	{
		echo "<script type='text/javascript'>";
			echo "function edit$row[1](x)";
			echo "{";
				$tyid=$row[0];
				echo "var t=$(\"#title\").val();";
				echo "var loc=$(\"#location\").val();";
				echo "var owner=$(\"#owner\").val();";
				echo "var q=\"title=\"+t+\"&loc=\"+loc+\"&tyid=$tyid\"+\"&eid=\"+x+\"&owner=\"+owner;";
				$attr=getattr($row[0]);
				while($a=database_fetch_array($attr))
				{
					echo "var $a[0]a=$(\"#$a[0]\").val();";
					echo "q+=\"&$a[0]=\"+$a[0]a;";
				}
				
				echo "$.ajax({type: \"GET\",	url: \"bin/edit_.php?case=resource&\"+q,	success: function(msg) { if(msg==\"done\"){ window.location=\"details.php?eid=\"+x; } else { $(\"#response\").html(msg);} }});";
				
				
			echo "}";
		
		echo "</script>";
	}
?>
<?php
	$res=gettypes();
	while($row=database_fetch_array($res))
	{
		echo "<script type='text/javascript'>";
			echo "function addWish$row[1](force)";
			echo "{";
				$tyid=$row[0];
				echo "var t=$(\"#title\").val();";
				echo "var captcha=$(\"#captcha\").val();";
				echo "var cap=$(\"#cap\").val();";
				echo "var digital=$(\"#digital\").is(':checked');";
				echo "if(digital==true) digital=1;else digital=0;";
				echo "var q=\"title=\"+t+\"&digital=\"+digital+\"&tyid=$tyid\"+\"&captcha=\"+captcha+\"&cap=\"+cap+\"&force=\"+force;";
				$attr=getattr($row[0]);
				while($a=database_fetch_array($attr))
				{
					echo "var $a[0]a=$(\"#$a[0]\").val();";
					echo "q+=\"&$a[0]=\"+$a[0]a;";
				}
				?>
				$.ajax({
					type:"GET",
					url: "bin/add_.php?case=wish&"+q,
					success: function(msg)
						 {
						 	var str=msg.split("||");
						 	if(str[0]=="done")
						 	{
							 	$("#no_wishes").hide();
							 	$("#wishes_ul").append("<li id='"+str[1]+"' onclick='wishDetails(\""+str[1]+"\");' class='wishes_li'>"+t+"</li>");
							 	$("#response_wishlist").html(str[0]);
							 	document.form1.reset();
						 	}
						 	else
						 	{
						 		$("#response_wishlist").html(str[0]);
						 	}
						 }
				});
				
				<?php
				/*echo "$.ajax({type: \"GET\",url: \"bin/add_.php?case=wish&\"+q,success: function(msg) { msg=msg.split('||');";
				echo "if(msg[0]=='done')";
				echo "{if(digital=='1'){ $(\"#response\").html(msg[1]);$(\"#dig\").show();$(\"#digeid\").val(msg[2]);return;$(\"#response\").html(msg[1]); document.form1.reset();}} $(\"#response\").html(msg[0]); document.form1.reset();}});";*/
				
				
			echo "}";
		echo "</script>";
	}
?>
<link rel="stylesheet" type="text/css" href="style/lib.css"/>


