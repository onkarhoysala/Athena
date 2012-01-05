<?php
	include_once "session.php";
	include_once "query.php";
	$q=$_GET['q'];
	$by=$_GET['by'];
	$_SESSION['page']="search.php?q=$q&by=$by";
	$uid=$_SESSION['uid'];
	$authent=authenticate();
	$types=gettypes();
	while($type=database_fetch_array($types))
	{
		if(isset($_GET["search$type[0]"]))
		{
			$t.="$type[0],";
		}
		$alltypes.="$type[0],";
	}
	
	if($t=="")
		$t=$_GET['t'];
	if($t=="")
		$t=$alltypes;
	
	//$t will contain types to search for
	
	if(!isset($_GET['p']) or $_GET['p']=="")
	{
		$p='1';
	}
	else $p=$_GET['p'];
	
	logger("search::$by:$q");
	

	
	echo "<script>document.title='Athena - Search results for $q';</script>";
	echo "<h3>Search results</h3>";
	echo "<div id='alldetails'>";
		//Display alphabets
		
		echo "<br/><br/>";
		
		//Fetch resource details and display each of them in a div
		$result=searchresources($q,$by,$p,$t);
		$res=$result[0];
		$c=$result[1];
		//$c=getcount($q,$by,$p);
		echo "<br/>Number of results: $c<br/>";
		$pa=$c/10;
		$set=ceil($p/10);
		$start=($set-0.9)*10;
		$prev=$p-1;
		echo "<ul><li class='pgnos'>Page</li><li class='pgnos'><a onclick='load(\"search.php?q=$q&by=$by&p=1&t=$t\");'>First</a></li>";
		if($prev!=0)echo "<li class='pgnos'><a onclick='load(\"search.php?q=$q&by=$by&t=$t&p=$prev\");'>Previous</a></li>";
		else echo "<li class='pgnos'><a onclick='load(\"search.php?q=$q&by=$by&p=1&t=$t\');'>Previous</a></li>";
		for($i=$start,$j=1;$i<=$pa+1;$i++,$j++)
		{
			echo "<li class='pgnos' id='pg$i' ><a onclick='load(\"search.php?q=$q&by=$by&p=$i&t=$t\");' >$i</a></li>";
			if($j==10) break;
		}
		$last=floor($pa+1);
		$next=$p+1;
		if($next!=$last+1)echo "<li class='pgnos'><a onclick='load(\"search.php?q=$q&by=$by&p=$next&t=$t\");'>Next</a></li></ul>";
		echo "<li class='pgnos'><a onclick='load(\"search.php?q=$q&by=$by&p=$last&t=$t\");'>Last</a></li></ul><br/><br/>";
		
		echo "<ul class='uldetails'  style='height:1400px'>";
		while($row=database_fetch_array($res))
		{
			echo "<li class='lidetailssearch'>";
			echo "<div class='details' id='$row[0]'>";
			$eid=$row[0];
			$uid=getuserid($_SESSION['email']);
			echo "<a href='details.php?eid=$eid'>$row[1]</a>";
			echo "<p class='resname'>";
			echo "<div id='cover' style='float:left;margin-right:15px'>";
                        if(gettypename($row[5])=='Book')
                        {
                                $isbn=database_fetch_array(database_query("select isbn from Book where eid='$eid';"));
                                $src="http://covers.openlibrary.org/b/isbn/".$isbn[0]."-S.jpg";
                                echo "<img src='$src' alt='Cover'  /><br/>";
                        }
                        echo "</div>";
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
						echo "<br/><span id='$row[0]button'><span class='borrowbuttonsearch' onclick='borrow(\"$eid\",\"$uid\");'>Borrow</span></span>";
					}
					else
						echo "<br/><span style='margin-left:450px'>Lost :-(</span>";
				}
				else echo "<br/><a href='#' onclick='$(\"#loginbox\").slideToggle(\"slow\");'>Login</a> to borrow.";
			}
			else echo "<br/>Borrowed by ".getname_uid($b);
			
			echo "</p>";
			
			echo "</div>";
			echo "</li>";
		}
		echo "</ul>";
		echo "<ul><li class='pgnos'>Page</li><li class='pgnos'><a onclick='load(\"search.php?q=$q&by=$by&p=1&t=$t\");'>First</a></li>";
		if($prev!=0)echo "<li class='pgnos'><a onclick='load(\"search.php?q=$q&by=$by&p=$prev&t=$t\");'>Previous</a></li>";
		else echo "<li class='pgnos'><a onclick='load(\"search.php?q=$q&by=$by&p=1&t=$t\');'>Previous</a></li>";
		for($i=$start,$j=1;$i<=$pa+1;$i++,$j++)
		{
			echo "<li class='pgnos' id='pg$i' ><a onclick='load(\"search.php?q=$q&by=$by&p=$i&t=$t\");' >$i</a></li>";
			if($j==10) break;
		}
		$last=floor($pa+1);
		$next=$p+1;
		if($next!=$last+1)echo "<li class='pgnos'><a onclick='load(\"search.php?q=$q&by=$by&p=$next&t=$t\");'>Next</a></li></ul>";
		echo "<li class='pgnos'><a onclick='load(\"search.php?q=$q&by=$by&p=$last&t=$t\");'>Last</a></li></ul><br/><br/>";
		echo "<script>$('ul.uldetails li:odd').addClass('oddli');$('#$l').css('text-decoration','underline');</script>";
	echo "</div>";
?>
