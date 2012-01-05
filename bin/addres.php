<?php
	/**
	* This file contains the code to display the list of types and ask the user what type of resource she wants to add to the system.
	*/
	/**
	* Session.php contains all the necessary methods and variables necessay to maintain a session.	
	*/
	include_once "session.php";
	/**
	* Query.php contains all the necessary methods and variables necessary to maintain a database connection. It also contains methods that act as wrapper methods for database queries.
	*/
	include_once "query.php";
	
	echo "<div id='addnewres'>";
		$t=gettypes();
		echo "<h3>What do you want to add?</h3>";
		echo "<select><option>Select a resource type</option>";
		while($type=database_fetch_array($t))
		{
			$typename=str_replace("_"," ",$type[1]);
			echo "<option id='$type[0]' onclick='fetchresource(\"$type[0]\");'><span style='cursor:pointer' >$typename</span></option>";
		}
		echo "<option></option><option onclick='load(\"addnewtype.php\");'>Add new type</option></select>";
		
	echo "</div>";
	echo "<div id='resource'>";
	
	echo "</div>";
	echo "";
	
?>
