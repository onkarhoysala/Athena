<?php
	include_once "session.php";
	include_once "query.php";
	
	
	echo "<h3>Modules</h3>";
	$mods=scandir("modules/");
	echo "<ul>";
	foreach($mods as $mod)
	{
		
		if(is_dir("modules/$mod") and $mod!=".svn" and $mod!="." and $mod!="..")
		{
			$mod_files=scandir("modules/$mod");
			if(in_array("install.php",$mod_files) and in_array("uninstall.php",$mod_files) and in_array("settings.php",$mod_files))
			{
			
				$notinstall=notinstalled($mod);
				if(!notinstalled($mod))
				{
					$color="white";
				}
				else
				{
					$color="#eee";
				}
				echo "<li class='module' style='background-color:$color'><strong>$mod</strong>";
				if($notinstall)
				{
					echo "<br/><a href='install_module.php?name=$mod' style='color:green;margin-left:20px;cursor:pointer'>Install</a>";
				}
				else
				{
					echo "<br/><a href='uninstall_module.php?name=$mod' style='color:red;margin-left:20px;cursor:pointer'>Uninstall</a><br/><u style='cursor:pointer' onclick=\"$('#$mod').slideToggle();\">Settings</u><br/>";
					echo "<div id='$mod' style='display:none;margin-left:20px'>";
					include_once "modules/$mod/settings.php";
					echo "</div>";
				}
				echo "</li>";
			}
		}
	}
	echo "</ul>";
?>
