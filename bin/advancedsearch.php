<?php
	/**
	* The use of this file is deprecated.
	*/
	/**
	* Session.php contains all the necessary methods and variables necessay to maintain a session.	
	*/
	include_once "session.php";
	/**
	* Query.php contains all the necessary methods and variables necessary to maintain a database connection. It also contains methods that act as wrapper methods for database queries.
	*/
	include_once "query.php";
	if(isset($_GET['tyid']))
	{
		$attrs=getattr($_GET['tyid']);
		$typename=gettypename($_GET['tyid']);
		echo "<strong>".$typename."</strong>";
		while($attr=database_fetch_array($attrs))
		{
			echo "<br/><label>$attr[0]</label><input type='text' name='$typename"."_"."$attr[0]' />";
		}
		die;
	}
	echo "<h3>Advanced search</h3>";

	echo "<div id='adv_search_sidebar'>";
		$types=gettypes();
		
		echo "<ul>";
		echo "Select what you want to search for: ";
		while($type=database_fetch_array($types))
		{
			echo "<li id='type$type[0]' class='adv_li' onclick='$(\"#nowsearching$type[0]\").show();$(\"#type$type[0]\").hide();show_type(\"$type[0]\");'>$type[1]</li>";
		}
		echo "</ul>";
	echo "</div>";
	echo "<div id='nowsearch'>";
		$types=gettypes();
		echo "<ul>";
		echo "Searching for:";
		while($type=database_fetch_array($types))
		{
			echo "<li id='nowsearching$type[0]' style='display:none' class='adv_li' onclick='$(\"#nowsearching$type[0]\").hide();$(\"#type$type[0]\").show();hide_type(\"$type[0]\");'>$type[1]</li>";
		}
		echo "</ul>";
	echo "</div>";
	echo "<form id='adv_search_main' method='post' action='advancedsearchresult.php'>";
		$types=gettypes();
		while($type=database_fetch_array($types))
		{
			echo "<div class='adv_types' id='$type[0]'>";
				
			echo "</div>";
		}
		echo "<input type='submit' value='Search' class='custombutton' />";
	echo "</form>";
?>

