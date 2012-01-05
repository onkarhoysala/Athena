<?php
	include_once "query.php";
	include_once "session.php";
	if($_SESSION['email']!="admin")
		echo "<h2>Hi, ".getname($_SESSION['email'])."</h2>";
	else echo "<h2>Logged in as Administrator</h2>";
	$_SESSION['page']='homepage';
	echo "<p id='home_info'>Select an option from the menu bar above.</p>";
	$uid=$_SESSION['uid'];
	$row=database_fetch_array(database_query("select question from users where uid='$uid';"));	
	if($row[0]=="-")
	{
		echo "<div id='sec_q'>";
		echo "<strong>You have not set the security question yet. </strong>";
		echo "<br/>Enter a security question. This can be used to reset your password.<br/> <input type='text' id='securityq' size='50'/>";
		echo "<br/><br/>Enter the answer: <input type='text' id='securityp'/>";
		echo "<br/><br/><span class='custombutton' onclick='editsec_q();'>Save</span>";
		echo "</div>";
	}
	echo "<h3><span style='color:orange;cursor:pointer' onclick='$(\"#popular\").slideToggle(\"slow\");$(this).html(\"+\"); '>-</span>Most popular</h3>";
	echo "<div id='popular'>";
		$popular=most_popular();
		if(database_num_rows($popular)==0)
		{
			echo "Nothing here yet!";
		}
		else
		{
			echo "<ul id='ul_popular'>";
				while($pop=database_fetch_array($popular))
				{
					echo "<li class='li_popular' onclick=\"window.location='details.php?eid=$pop[0]';\">";
					
					echo "<a href='details.php?eid=$pop[0]' class='popular_titles'>".getresname($pop[0])."</a><br/>";
					$type_name=gettypename_eid($pop[0]);
					echo " $type_name";
					if($type_name=="book" or $type_name=="Book")
					{
						$isbn=database_fetch_array(database_query("select isbn from Book where eid='$pop[0]';"));
		                        	$src="http://covers.openlibrary.org/b/isbn/".$isbn[0]."-S.jpg";
		                        	echo "<img src='$src' alt='Cover' style='float:left;padding-right:15px' /><br/>";
						
					}
					echo "</li>";
				}	
			echo "</ul>";
		}
	echo "</div>";
	echo "<h3><span style='color:orange;cursor:pointer' onclick='$(\"#activity\").slideToggle(\"slow\");$(this).html(\"+\"); '>-</span>Recent activity</h3>";
	echo "<div id='homepage'>";
		echo "<div id='activity' class='homeblock'>";
			
	
			echo "<ul id='allactivity1'>";
				echo "<li class='list_header' >Filter: </li>";
				echo "<li class='act_type' id='all' onclick='loadactivity(\"all\",\"0\",\"today\");'>All</li>";
				echo "<li class='act_type' id='edited' onclick='loadactivity(\"edited\",\"0\",\"today\");'>Edited</li>";
				echo "<li class='act_type' id='borrowed' onclick='loadactivity(\"borrowed\",\"0\",\"today\");'>Borrowed</li>";
				echo "<li class='act_type' id='returned' onclick='loadactivity(\"returned\",\"0\",\"today\");'>Returned</li>";
				echo "<li class='act_type' id='added' onclick='loadactivity(\"added\",\"0\",\"today\");'>Added</li>";
		
			echo "</ul><br/>";
	
			echo "<div id='allactivity'>";
				include_once "activity.php";
			echo "</div>";
		echo "</div>";
	echo "</div>";	
	getmodule("homepage.php");
	
?>

