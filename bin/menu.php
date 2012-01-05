<?php
	include_once "query.php";
	include_once "session.php";
?>
<ul class='links'>
<a class='l' href="home.php" ><li id="linksli">Home</li></a>
<a class='l'  href="borrowed.php" ><li  id="linksli">Borrowed</li></a>
<!--<a class='l'  href="favourites.php" ><li  id="linksli">Favourites</li></a>-->
<a class='l' href='lists.php'><li id='linksli'>Your Lists</li></a>
<a class='l' href="browse.php" ><li  id="linksli">Browse</li></a>
<a class='l' href="addres.php" ><li  id="linksli">Add to catalogue</li></a>
<a class='l' href="lost.php" ><li  id="linksli">Lost</li></a>
<?php 
	if($_SESSION['email']=="admin")
	{
		echo "<a class='l' href='admin.php'><li id='linksli'>Admin</li></a>";	
		getmodule("menu.php");	
	}
?>
<ul>


