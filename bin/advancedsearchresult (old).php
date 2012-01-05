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
?>
<script>
	function navigate(x)
	{
		var z=document.location;
		z+="&p="+x;
		window.location=z;
	}
	document.title="Search results";
</script>
<?php
	//echo"<script>document.location.hash='search';</script>";
	echo "<h3>Search Results</h3>";
	echo "<script>$('ul.uldetails li:odd').addClass('oddli');$('#$l').css('text-decoration','underline');</script>";
	$authent=authenticate();
	$type=$_GET['type'];
	$name=$_GET['name'];
	if(!isset($_GET['p']) or $_GET['p']=="0")
	{
		$p='1';
	}
	else $p=$_GET['p'];
	$t=explode('-',$type);
	$i=0;
	/*foreach($t as $x)
	{
		$tname=gettypename($x);
		$q="select eid,name from ".$tname."details where ";
		//echo $x;
		$res=database_query("select attrib from resource_attrib where tyid='$x';");
		
		
		
		while($row=database_fetch_array($res))
		{
			
			if(isset($_GET[$row[0]]))
			{
				$z=$_GET[$row[0]];
				
				if($z!=null)
				{
					
					$q.=" $row[0] like '%$z%' and ";
				}
			}
		}
		
		$q=substr_replace($q,';',-4);
		//echo $q;
		$r=database_query($q);
		if($r)
		{
			while($row2=database_fetch_array($r))
			{
				$eid[$i++]=$row2[0];
			}
		}
		
	}
	$query="select eid,name,fname,sname,description,tyid,lost from (resource natural join type) natural join user where name like '%$name%' and ";
	if($i==0)
	{
		echo "No Results";
		die;
	}
	foreach($eid as $x)
	{
		$query.="eid='$x' or ";
	}
	$query=substr_replace($query,';',-3);
	//echo $query;
	//calculate page number
	$c=database_num_rows(database_query($query));
	echo "Number of results: $c<br/>";
	$pa=$c/10;
	echo "<ul><li class='pgnos'>Page</li><li class='pgnos'><span>First</span></li>";
	for($i=1;$i<=$pa+1;$i++)
	{
		echo "<li class='pgnos' id='pg$i'><span onclick='navigate(\"$i\");'>$i</span></li>";
		if($i==10) break;
	}
	$last=$pa+1;
	echo "<li class='pgnos'><span>Last</span></li></ul><br/><br/>";
	//execute query with page number
	
	$start=($p-1)*10;
	$query=substr_replace($query," limit $start,10;",-1);

	$res=database_query($query);

	echo "<ul class='uldetails'>";
	while($row=database_fetch_array($res))
	{
		echo "<li class='lidetailssearch'>";
		echo "<div class='details' id='$row[0]'>";
		$eid=$row[0];
		$uid=getuserid($_SESSION['email']);
		echo "<a href='details.php?eid=$eid' >$row[1]</a>";
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
		if($b=="1")
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
	echo "</ul>";*/
	
?>
