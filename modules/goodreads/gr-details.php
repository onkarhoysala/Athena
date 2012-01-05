<style type='text/css'>
	#gr:hover
	{
		border-bottom: solid thin orange
	}
	div#goodreads
	{
		display:none;
		padding-left:20px;
		
	}
	li.author
	{
		list-style-type:none;
		display:inline
	}
	table
	{
		font-size:10pt
	}
</style>
<?php
	$t=gettypename_eid($_GET['eid']);
	$type=strtolower($t);
	$eid=$_GET['eid'];
	if($type=="book")
	{
		echo "<h3 onclick='$(\"#goodreads\").slideToggle()' style='cursor:pointer'><span id='gr'>Goodreads</span></h3>";
		
		
		$isbn=database_fetch_array(database_query("select isbn from $t where eid='$eid';"));
		$apikey=database_fetch_array(database_query("select api_key from goodreads"));
		if($apikey[0]=="")
		{
			echo "<p style='text-align:center;font-size:13pt;color:grey'>Goodreads Key is not installed. Please contact the adminsitrator.</p>";
		}
		$greads="http://www.goodreads.com/book/isbn?isbn=$isbn[0]&key=$apikey[0]";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $greads);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($curl);
		
		//echo $data;
		if($data==" ")
		{
			echo "<p style='text-align:center;color:grey;font-size:13pt'>Goodreads data is not available for this book.</p>";
			
		}
		else
		{
			echo "<div id='goodreads'><img src='http://www.goodreads.com//images/layout/goodreads_logo.gif' /><br/><br/><table width=900px><tr width='900px'><td width='400px'>";
			$xml=simplexml_load_string($data);
			foreach($xml->children() as $child)
			{
			
				foreach($child->children() as $c1)
				{
					//echo $c1->getName()."<br/>";
					if($c1->getName()=="isbn")
					{
						echo "<strong>ISBN: </strong>$c1<br/>";
					}
					if($c1->getName()=="description")
					{
						if($c1!="")
							echo "<strong>Description: </strong><div style='padding:10px;width:350px;height:150px;overflow:auto;border:solid thin #ccc;background-color:#eee;-moz-border-radius:10'>$c1</div><br/>";
					}
					if($c1->getName()=="isbn13")
					{
						echo "<strong>ISBN 13: </strong>$c1<br/>";
					}
					if($c1->getName()=="title")
					{
						echo "<strong>Title: </strong>$c1<br/>";
					}
					if($c1->getName()=="average_rating")
					{
						echo "<strong>Rating: </strong>$c1<br/>";
					}
					if($c1->getName()=="num_pages")
					{
						echo "<strong>Number of pages </strong>$c1<br/>";
					}
					if($c1->getName()=="url")
					{
						echo "<a href='$c1'><strong>Goodreads URL</strong></a><br/>";
					}
					if($c1->getName()=="image_url")
					{
						$image_gr=$c1;
					}
					if($c1->getName()=="book_links")
					{
						echo "Links:<br/><ul>";
						foreach($c1->children() as $booklink)
						{
							foreach($booklink->children() as $bl_attr)
							{
								if($bl_attr->getName()=="name")
									$bl_name=$bl_attr;
								if($bl_attr->getName()=="link")
								{
									$bl_link=$bl_attr;
									if($bl_name!="")
										echo "<li style='list-style-type:none'><a href='$bl_link'>$bl_name</a></li>";
								}
							}
						}
						echo "</ul>";
					}
					if($c1->getName()=="authors")
					{
						echo "<strong>Authors:<strong><br/><ul>";
						foreach($c1->children() as $author)
						{
							echo "<li class='author'>";
							foreach($author->children() as $au_attr)
							{
								if($au_attr->getName()=="small_image_url")
								{
									echo "<img src='$au_attr' title='$a_name' />";
								}
								if($au_attr->getName()=="name")
								{
									$a_name=$au_attr;
								}
							}
							echo "</li>";
						}
						echo "</ul>";
					}
					if($c1->getName()=="reviews")
					{
						echo "<strong>Reviews:</strong><div style='padding:5px;width:350px;height:250px;overflow:auto;border:solid thin #ccc;background-color:#eee;-moz-border-radius:10'><br/>";
						foreach($c1->children() as $review)
						{
							foreach($review->children() as $rc)
							{
								if($rc->getName()=="user")
								{
									foreach($rc->children() as $user)
									{
										if($user->getName()=="name")
										{
											$username=$user;
										}
										if($user->getName()=="link")
										{
											$userlink=$user;
										}
									}
								}//rating by which user		
								if($rc->getName()=="body")
								{
									if($rc!="")
									{
										echo "<a href='$userlink'>$username</a> says<blockquote>$rc</blockquote>Rating: $userrating";
										echo "<hr/>";
									}
								}
								if($rc->getName()=="rating")
								{
									$userrating=$rc;
								}			
							}
						}
						echo "</div>";
					}
				}
			}
		
			curl_close($curl);
			//echo "<div style='position:relative;left:60%;margin-top:-185px;width:30%;text-align:center'>";
			echo "</td><td width='400px'><div style='text-align:center'>";
			echo "Book Cover<br/><img src='$image_gr' />";
			echo "</div></td>";
			echo "</tr></table></div>";
		}
		
	}
?>
