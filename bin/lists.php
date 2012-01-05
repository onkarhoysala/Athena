<style>
	li.lists
	{
		list-style-type:none;
		padding:5px;
		border: solid thin #ccc;
		background-color: #eee;
		display:inline;
		
		-moz-border-radius-topright:10;
		-moz-border-radius-topleft:10;
	
		-webkit-border-top-radius:10;
		
	}
	
</style>
<h3>Your lists</h3>
<ul>
	<li class='lists' id='favourites'><a href='lists.php?list=favourites'>Favourites</a></li>
	<li class='lists' id='wishlist1'><a href='lists.php?list=wishlist&type=1'>Your Wishlist</a></li>
	<li class='lists' id='wishlist2'><a href='lists.php?list=wishlist&type=2'>Public Wishlist</a></li>

</ul>
<div style='width:500px;margin-top:-7px;margin-left:40px;border-left:solid thin #ccc; padding:5px'>
<?php
	
	if(isset($_GET['list']) and $_GET['list']!="")
	{
		$list=$_GET['list'];
		include_once "bin/$list.php";
		if($list=="wishlist")
		{
			$type=$_GET['type'];
		}
		else
			$type="";
		echo "<script>$('#$list$type').css('background-color','white');$('#$list$type').css('border-bottom','none');</script>";
		
	}
	else
	{
		include_once "bin/favourites.php";
		echo "<script>$('#favourites').css('background-color','white');$('#favourites').css('border-bottom','none');</script>";
	}
?>
</div>
