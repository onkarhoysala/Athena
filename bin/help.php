<?php
	include_once "session.php";
	include_once "query.php";
	$_SESSION['pages']='help.php';
	echo "<script>document.title='Help'</script>";
	echo"<h2>Help</h2>";
	echo "The help pages for the following FAQs can be found on the CSTEP wiki at http://wiki.cstep.in/faqlib . Clicking on the links below redirects to the CSTEP wiki. Please register yourself in the wiki to edit/add to the FAQs.";
	echo "<h3>FAQ</h3>";
	echo "<ol>";
		echo "<li><a href='http://wiki.cstep.in/index.php/Registration'>How do I register?</a></li>";
		echo "<li><a href='http://wiki.cstep.in/index.php/How_do_I_edit_details_of_resource%3F'>How do I edit details of resource?</a></li>";
		echo "<li><a href='http://wiki.cstep.in/index.php/How_do_I_add_a_new_resource%3F'>How do I add a new resource?</a></li>";
		echo "<li><a href='http://wiki.cstep.in/index.php/How_do_I_add_a_new_resource_type%3F'>How do I add a new resource type?</a></li>";
		echo "<li><a href='http://wiki.cstep.in/index.php/How_do_I_check_where_a_resource_is_located%3F'>How do I check where a resource is located?</a></li>";
	echo "</ol>"; 
	echo "<h3>User Manuals (Incomplete)</h3>";
	echo "<ul>";
		echo "<li><a href='./documentation/userguide.pdf'>User Guide</a></li>";
		echo "<li><a href='./documentation/developersguide.pdf'>Developers' Guide</a></li>";
	echo "</ul>";
	
?>
