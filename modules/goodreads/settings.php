<?php
	include_once "bin/session.php";
	include_once "bin/query.php";
	if(isset($_GET['gr_save']))
	{
		echo "<script>$(\"#goodreads\").css(\"display\",\"block\");</script>";
		$err=$_GET['gr_save'];
		if($err=="done")
			echo "<p>The API key has been updated</p>";
		else
			echo "<p>Error updating the API key.</p>";
	}
	$key=database_fetch_array(database_query("select * from goodreads"));
	echo "<div id='gr_key_show'>";
	echo "API Key: $key[1]<br/><span style='text-decoration:underline;cursor:pointer' onclick='$(\"#gr_key_show\").hide();$(\"#gr_key_edit\").show()'>Edit</span>";
	echo "</div>";
	
	echo "<form method='get' id='gr_key_edit' action='modules/goodreads/save.php' style='display:none'>API Key: <input type='text' value='$key[1]' name='apikey' id='apikey'/>";
	echo "<input type='submit' class='custombutton' value='Set' /><br/><span style='text-decoration:underline;cursor:pointer' onclick='$(\"#gr_key_show\").show();$(\"#gr_key_edit\").hide()'>Cancel</span></form>";

?>
