<?php
	include_once "session.php";
	include_once "query.php";
	$authent=authenticate();
	if($authent=="1")$_SESSION['page']="browse";
	$case=$_GET['case'];
	if(!isset($_GET['l']) or $_GET['l']=="")
	{
		$l='';
	}
	else $l=$_GET['l'];
	
	if(!isset($_GET['p']) or $_GET['p']=="")
	{
		$p='1';
	}
	else $p=$_GET['p'];
	
	if(!isset($_GET['t']) or $_GET['t']=="")
	{
		$t='';
	}
	else $t=$_GET['t'];
	
	if($authent=="1")echo "<script>document.title='Athena - Browse';</script>";
	else echo "<script>document.title='Athena';</script>";
	/*if($authent=='1')
	{
		echo "Show: <select>";
			echo "<option onclick='window.location=\"browse\"'>Catalogue items</option>";
			echo "<option onclick='window.location=\"browse?case=author\"'>Authors</option>";
		echo "</select>";
	}*/
	echo "<h3 id='browseh3'>Browse</h3>";
	
	/*if($authent!="1")
	{
		$width="600px";
	}
	else $width="900px";
	echo "<div id='browse' style='width:$width'>";*/
	echo "<div id='browse'>";
		echo "<div class='browsepage' id='alldetails'>";
			//Display alphabets
			
			$types=gettypes();
			echo "<ul class='selection'>";
			echo "<li class='alphabet'>Show: </li>";
			echo "<li class='alphabet'><a onclick='load(\"browse.php?l=$i&p=$p&t=\");'>All</a></li>";
			while($row=database_fetch_array($types))
			{
				echo "<li class='alphabet'><a onclick='load(\"browse.php?l=$i&p=$p&t=$row[0]\");'>$row[1]</a></li>";
			}	
			echo "</ul><br/>";
			echo "<ul class='alphabetSelection'>";
				echo "<li class='alphabet'>Browse by</li>";
				echo "<li class='alphabet'><a href='browse'>All</li>";
				for($i='A',$j=1;$j<=26;$i++,$j++)
					echo "<li class='alphabet' id='$i'><a onclick='load(\"browse.php?l=$i&p=$p&t=$t\");'>$i<a/></li>";
			echo "</ul>";
			echo "<br/>";
		
		
			//Fetch resource details and display each of them in a div
			$res=getbr_count($l,$t);
			$c=database_num_rows($res);
			$pa=$c/12;
			$res=getresources($l,$p,$t);
			$set=ceil($p/10);
			$start=($set-0.9)*10;
			$prev=$p-1;
			echo "<ul class='pageSelection'><li class='pgnos'>Page</li><li class='pgnos'><a onclick='load(\"browse.php?p=1&l=$l&t=$t\");'>First</a></li>";
			if($prev!=0)echo "<li class='pgnos'><a onclick='load(\"browse.php?p=$prev&l=$l&t=$t\");'>Previous</a></li>";
			else echo "<li class='pgnos'><a onclick='load(\"browse.php?p=1&l=$l&t=$t\");'>Previous</a></li>";
			for($i=$start,$j=1;$i<=$pa+1;$i++,$j++)
			{
				echo "<li class='pgnos' id='pg$i' ><a onclick='load(\"browse.php?p=$i&l=$l&t=$t\");'>$i</a></li>";
				if($j==10) break;
			}
			$last=floor($pa+1);
			$next=$p+1;
			if($next!=$last+1)echo "<li class='pgnos'><a onclick='load(\"browse.php?p=$next&l=$l&t=$t\");'>Next</a></li></ul>";
			echo "<li class='pgnos'><a onclick='load(\"browse.php?p=$last&l=$l&t=$t\");'>Last</a></li></ul><br/><br/>";
		
			echo "<ul class='uldetails' id='ul_details'>";
			while($row=database_fetch_array($res))
			{
				$eid=$row[0];
				echo "<li class='lidetails'>";
		                if(strtolower(gettypename($row[5]))=='book')
		                {
		                	//TODO:: make it bloody more generic :|
		                	$bookTable=mysql_fetch_array(mysql_query("select description from type natural join resource_attrib where resource_attrib.attrib = 'isbn' or resource_attrib.attrib = 'ISBN' ;"));
		                        $isbn=database_fetch_array(database_query("select isbn from $bookTable[0] where eid='$eid';"));
		                        if($isbn[0]!=null)
		                        	$src="http://covers.openlibrary.org/b/isbn/".$isbn[0]."-S.jpg";
		                       	else
		                       		$src="../images/no_cover_bg.png";
		                        //echo "<img src='$src' alt='Cover'  /><br/>";
		                }
				echo "<div class='details' id='$row[0]' onclick='window.location=\"details.php?eid=$eid\"' style=\"background-image:url('$src');padding-left:70px\">";
				$uid=getuserid($_SESSION['email']);
				echo "<a href='details.php?eid=$eid'>$row[1]</a>";
				echo "<p class='resname'>";
				/*
		                echo "<div id='cover' style='float:left;margin-left:15px'>";
		                
		                if(gettypename($row[5])=='Book')
		                {
		                        $isbn=database_fetch_array(database_query("select isbn from Book where eid='$eid';"));
		                        $src="http://covers.openlibrary.org/b/isbn/-S.jpg";
		                        echo "<img src='$src' alt='Cover'  /><br/>";
		                }
		                echo "</div>";
		                */
				echo "Type: ".gettypename($row[5]);
		                $c=checkauthor($row[5]);
		                if($c!=0)
		                {
		                	echo "<br/>Author: ".getauthor($eid,$row[5]);
		                }
				$b=checkborrow($eid);
				if($b=="0")
				{
					if($authent=="1")
					{
					
						if($row[6]==0)
						{
							echo "</br><span id='report$row[0]' style='color:#15397b;cursor:pointer' onclick='reportlost(\"$row[0]\")'>Report Lost</span>";
							echo "<br/><br/><span id='$row[0]button' <span class='borrowbutton' onclick='borrow(\"$eid\",\"$uid\");'>Borrow</span></span>";
						}
						else
							echo "<br/><span style='margin-left:450px'>Lost :-(</span>";
					}
					else echo "<br/><a href='login.php'>Login</a> to borrow.";
				}
				else echo "<br/>Borrowed by ".getname_uid($b);
			
				echo "</p>";
			
				echo "</div>";
		              
				echo "</li>";
			}
			echo "</ul>";
			echo "<ul><li class='pgnos'>Page</li><li class='pgnos'><a href='browse.php?p=1&l=$l&t=$t'>First</a></li>";
			if($prev!=0)echo "<li class='pgnos'><a onclick='load(\"browse.php?p=$prev&l=$l&t=$t\");'>Previous</a></li>";
			else echo "<li class='pgnos'><a onclick='load(\"browse.php?p=1&l=$l&t=$t\");'>Previous</a></li>";
			for($i=$start,$j=1;$i<=$pa+1;$i++,$j++)
			{
				echo "<li class='pgnos' id='pg$i' ><a onclick='load(\"browse.php?p=$i&l=$l&t=$t\");'>$i</a></li>";
				if($j==10) break;
			}
			$last=floor($pa+1);
			$next=$p+1;
			if($next!=$last+1)echo "<li class='pgnos'><a onclick='load(\"browse.php?p=$next&l=$l&t=$t\");'>Next</a></li></ul>";
			echo "<li class='pgnos'><a onclick='load(\"browse.php?p=$last&l=$l&t=$t\");'>Last</a></li></ul><br/><br/>";
		
			echo "<script>$(\"#pg$p a\").css(\"background-color\",\"#96a8c8\");$(\"#$l\").css(\"color\",\"#96a8c8\");</script>";
		echo "</div>";
		echo "<script>$('ul.uldetails li:odd').addClass('oddli');$('#$l a').css('background-color','#96a8c8');$('#pg$p a').css('background-color','#96a8c8');</script>";
		if($authent!="1")
		{
			die;
		}
		/*echo "<div id='tagcloud' class='browsepage'>";
			
			$tags=getpoptags("0");
			echo "<br/><br/><span style='font-weight:bold'>Tag Cloud<br/></span>";
			while($row2=database_fetch_array($tags))
			{
				$count=gettagcount($row2[0],"0");
				if($count!='1')
				{
					$size=8+($count%20);
				}
				if($count=='1')
				{
					continue;
				}*/
				/*else 
					$size="11";*/
				/*echo "<a href='search.php?q=$row2[0]&by=tag' class='tagbutton' style='font-size:$size'>$row2[0]</a> ";
			}
		echo "</div>";*/
		
?>
<script>
	function masonry()
	{
		$("#browse").masonry({
				columnWidth: 50,
				itemSelector: ".browsepage"
			});
	}
	masonry();
</script>
