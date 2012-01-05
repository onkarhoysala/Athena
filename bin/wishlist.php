<?php
	include_once "session.php";
	$uid=$_SESSION['uid'];
	$type=$_GET['type'];
	if($type=='1')
	{
		echo "<h3  onclick='$(\"#yourwishlist\").slideToggle();$(\"#addtowishlist\").slideToggle();' style='cursor:pointer'>Your wishlist</h3>";
		echo "<script>document.title='Athena - Your wishlist';</script>";
		$wishes=getUserWishlist($uid);
		echo "<div id='yourwishlist'>";
		if(database_num_rows($wishes)==0)
		{
			echo "<span id='no_wishes' style='color:grey;font-size:11pt;margin-left:10px'>You have nothing in your wishlist</span>";
		}
		
		else
		{
			echo "<div id='wishes'>";
			echo "<ul id='wishes_ul'>";
			while($row=database_fetch_array($wishes))
			{
				echo "<li id='$row[0]' onclick='wishDetails(\"$row[0]\");' class='wishes_li'>";
				echo "$row[1]";
				
				echo "</li>";
			}
			echo "</ul>";
			echo "</div>";
			echo "<div id='wish_details'>";
			echo "<span style='color:grey;font-size:14pt'>Click on an item to view its details.</span>";
			echo "</div>";
		}
		echo "<br/><br/><input type='button' onclick='$(\"#yourwishlist\").slideToggle();$(\"#addtowishlist\").slideToggle();' value='Add an item to your wishlist' />";
		echo "</div>";
		echo "<br/><div id='addtowishlist' style='display:none'><h3>Add an item to your wishlist</h3>";
		echo "<select><option>Select a resource type</option>";
		$t=gettypes();
		while($type=database_fetch_array($t))
		{
			if(strlen($type[1])!="")
			{
				$typename=str_replace("_"," ",$type[1]);
				echo "<option id='$type[0]' onclick='fetchresource_wishlist(\"$type[0]\");'><span style='cursor:pointer' >$typename</span></option>";
			}
		}
		echo "<option></select>";
		echo "<br/><br/><input type='button' onclick='$(\"#yourwishlist\").slideToggle();$(\"#addtowishlist\").slideToggle();' value='Done' />";
		echo "<div id='resource' style='width:800px;margin-top:0px;text-align:left'>";
		echo "</div></div>";
	}
	if($type=='2')
	{
		echo "<h3>Public wishlist</h3>";
		echo "<script>document.title='Athena - Public wishlist';</script>";
		echo "<div id='yourwishlist'>";
		$wishes=getPublicWishlist($uid);
		if(isset($_GET['wid']))
			$wid=$_GET['wid'];
		if(database_num_rows($wishes)==0)
		{
			echo "<span style='color:grey;font-size:11pt;margin-left:10px'>You have nothing in your wishlist</span>";
		}
		
		else
		{
			echo "<div id='wishes'>";
			echo "<ul>";
			while($row=database_fetch_array($wishes))
			{
				echo "<li id='$row[0]' onclick='wishDetails(\"$row[0]\");' class='wishes_li'>";
				echo "$row[1]";
				echo "</li>";
			}
			echo "</ul>";
			echo "</div>";
			echo "<div id='wish_details'>";
			echo "<span style='color:grey;font-size:14pt'>Click on an item to view its details.</span>";
			echo "</div>";
		}
		echo "</div>";
		if(isset($_GET['wid']))
		{
			$wid=$_GET['wid'];
			echo "<script>wishDetails(\"$wid\");</script>";		
		}
	}
?>

