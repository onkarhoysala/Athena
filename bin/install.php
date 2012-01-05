<?php
	include_once "query.php";
	include_once "session.php";
	$m=$_GET['m'];
	$name=substr($m,0,-4);
	echo "<h3>Install $name</h3>";
	$file=fopen("../modules/$m","r");
	if(!$file)
		echo "Error in locating the .ini file. Please copy it to the modules folder.";
	while(!feof($file))
	{
		$kv=fgets($file,4096);
		$k=explode(":",$kv);
		switch($k[0])
		{
			case "uninstall": echo "<br/>Uninstallation: $k[1]";break;
			case "location": echo "<br/>Location(s) of installation: $k[1]";$loc=trim($k[1]);break;
			case "change": if($k[1]=="yes")
					{
						echo "Select pages where you want to install $name";
					}
					break;
			case "name":$n=trim($k[1]);break;
			case "db": $q=trim($k[1]); break;
		}
	}
	echo "<br/>Proceed with installation?";
	echo "<br/><br/><a class='custombutton' onclick='addmodule(\"$n\",\"$loc\",\"$m\",\"$q\",\"$m\");'>Yes</a>    <a href='module.php' class='custombutton'>No</a>";
?>
