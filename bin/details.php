<?php
	include_once "session.php";
	include_once "query.php";
	$authent=authenticate();
?>

<?php
	
	$eid=$_GET['eid'];
	$check=check_exists($eid);
	if($check==0)
	{
		echo "<script>document.title='Not Found';</script>";
		echo "<p style='color:grey;text-align:center;font-size:14pt'>This item does not exist</p>";
		die;
	}
	$case=$_GET['case'];
	if($case=="rem")
	{
		echo "<script>$(\"#coverimage\").hide();$(\"#remove_tags\").show();</script>";
		
	}
	$_SESSION['page']="details.php?eid=$eid";
	$tyid=gettyid($eid);
	$typename=gettypename($tyid);
	$typename=str_replace(" ","_",$typename);
	$typeattr=getattr($tyid);
	$uid=$_SESSION['uid'];
	$view=$typename."details";
	$query="select name,fname,sname,location,owner,digital,uid from $view where eid='$eid';";
	$res=database_query($query);
	$row=database_fetch_array($res);
	$t=$row[0];
	$uid=$_SESSION['uid'];
	logger("details::$eid");
	echo "<script>document.title='$row[0] | Details';</script>";
	$row[0]=str_replace("\\","",$row[0]);
	$row[1]=str_replace("\\","",$row[1]);
	$row[2]=str_replace("\\","",$row[2]);
	$row[3]=str_replace("\\","",$row[3]);
	$row[4]=str_replace("\\","",$row[4]);

	echo "<h3>"."$row[0]"."</h3>";
	echo "<div id='resdetails'>";
		echo "<div class='detailsblock' id='resourcedetails' style='float:left'>";
			$rating=getrating($eid,$uid);
			if($_SESSION['email']=="admin")
			{
				echo "<span style='color:red;font-size:10pt;cursor:pointer' onclick='if(confirm(\"Confirm removal of $name from the catalog\")){delete_resource(\"$eid\");}'>Remove from catalog</span><br/><br/>";
			}
			echo "<div id='globalrating'>User rating ";
			$gl=getglobalrating($eid);
			$gl=floor($gl);
			for($m=0;$m<$gl;$m++)
			{
				$x=$m+1;
				echo "<img src='images/gold.png'/>";
			}
			for($m=$gl;$m<5;$m++)
			{
				$x=$m+1;
				echo "<img src='images/notgold.png'/>";
			}
			echo "</div>";
			echo "<br/><span style='font-weight:bold'>Type: </span>$typename";
			echo "<br/><span style='font-weight:bold'>Location: </span>$row[3]";
			echo "<br/><span style='font-weight:bold'>Owner: </span>$row[4]";
			echo "<br/><span style='font-weight:bold'>Added by: </span>$row[1] $row[2]";
	
			$query="select name";
			$i=0;
			while($a=database_fetch_array($typeattr))
			{
				$a[0]=str_replace(" ","_",$a[0]);
				$query.=",$a[0]";
				$i++;
				$attrs[$i]=$a[0];
		
			}
			$query.=" from $view where eid='$eid';";
			$res=database_query($query);
			if(!$res)
			{
				echo database_error();
				die;
			}	
		
			$row1=database_fetch_array($res);
			$resu=$res;
			for($k=1;$k<=$i;$k+=1)
			{
				if($attrs[$k]=="author" or $attrs[$k]=="Author")
				{
					$authors=explode(",",$row1[$k]);
					echo "<br/><span style='font-weight:bold'>$attrs[$k]: </span>";
					foreach($authors as $author)
					{
						echo "<a href='search.php?q=@author $author&by=all'>$author</a>,";
					}
					continue;
				}
				if($attrs[$k]=="url" or $attrs[$k]=="URL")
				{
					echo "<br/><span style='font-weight:bold'>$attrs[$k]: </span><a href='$row1[$k]'>$row1[$k]</a>";
					continue;
				}
				echo "<br/><span style='font-weight:bold'>$attrs[$k]: </span>$row1[$k]";
				if($attrs[$k]=="isbn" or $attrs[$k]=="ISBN")
				{
					$src="http://covers.openlibrary.org/b/isbn/".$row1[$k]."-L.jpg";
					$isbn=$row1[$k];
					echo "<br/><br/>'$row1[0]' on:<blockquote>";
					echo "<a href='http://books.google.com/books?vid=ISBN$row1[$k]'>Google Books</a><br/>";
					echo "<a href='http://www.librarything.com/isbn/$row1[$k]'>LibraryThing</a><br/><a href='http://www.amazon.com/exec/obidos/ASIN/$row1[$k]'>Amazon</a></blockquote>";
				}
			}
			//Check for pending download requests 
			
			$check_pending=pending_download_requests_for_eid($eid,$uid);
			if(database_num_rows($check_pending)!=0)
			{
				echo "<br/><br/><strong>Pending requests for download</strong>";
				echo "<ul>";
				while($pending=database_fetch_array($check_pending))
				{
					echo "<li id='pending$pending[0]'>".getname_uid($pending[3])." has requested to download a copy of this.<br/>";
					echo "<span class='smallbutton' onclick='accept_download(\"$pending[0]\");'>Accept</span><span class='smallbutton' onclick='deny_download(\"$check_pending[0]\");'>Deny</span>";
					echo "</li>";
				}
				echo "</ul>";
			}
			if($row[5]=='1') //If it is only a digital resource
			{
				echo "<br/><br/><span class='digital'>This is a digital $typename</span><br/>";
				$dig=database_query("select fname,request_download,uid,id from uploaded where eid='$eid';");
				if(database_num_rows($dig)>0)
				{
					echo "<blockquote>";
					while($d=database_fetch_array($dig))
					{
						if($d[1]=='0')
						{
							echo "<a href='$upload_folder/$d[0]'>Download a copy of $row1[0].</a><br/>";	
						}
						else
						{
							$check_status=check_for_download_request($eid,$uid);
							if(database_num_rows($check_status)==0)
							{
								$to=getemail($d[2]);
								$message=getname_uid($uid)." has requested the download of $row[0]. Click <a href=details.php?eid=$eid>here</a> to allow or deny the request.";
								$subject="Request for download";
								echo "<br/><span id='req_download_resonse'></span>";
								echo "<span id='req_download' style='cursor:pointer' onclick='requestdownload(\"$to\",\"$message\",\"$subject\",\"$d[3]\",\"$eid\");'>Request a copy of this for download</span>";
							}
							while($row_status=database_fetch_array($check_status))
							{
								if($row_status[4]=="pending")
								{
									echo "Your request for to download a copy of this is pending.<br/>";
								}
								else if($row_status[4]=="accepted")
								{
									echo "<a href='$upload_folder/$d[0]'>Download a copy of $row1[0].</a><br/>";
								}
							}
						}
					}
					echo "</blockquote>";
				}
				echo "";
			}
			else //If it is a digital copy of a physical resource
			{
				$dig=database_query("select fname,request_download,uid,id from uploaded where eid='$eid';");
				if(database_num_rows($dig)>0)
				{
					echo "<br/><br/><span class='digital'>A digital copy of this is available.</span><br/><blockquote>";
					while($d=database_fetch_array($dig))
					{
						if($d[1]=='1' and $d[2]!=$uid)
						{
							$check_status=check_for_download_request($eid,$uid);
							if(database_num_rows($check_status)==0)
							{
								$to=getemail($d[2]);
								$message=getname_uid($uid)." has requested the download of $row[0]. Click <a href=details.php?eid=$eid>here</a> to allow or deny the request.";
								$subject="Request for download";
								echo "<br/><span id='req_download_resonse'></span>";
								echo "<span id='req_download' style='cursor:pointer' onclick='requestdownload(\"$to\",\"$message\",\"$subject\",\"$d[3]\",\"$eid\");'>Request a copy of this for download</span>";
							}
							while($row_status=database_fetch_array($check_status))
							{
								if($row_status[4]=="pending")
								{
									echo "Your request for to download a copy of this is pending.<br/>";
								}
								else if($row_status[4]=="accepted")
								{
									echo "<a href='$upload_folder/$d[0]'>Download a copy of $row1[0].</a><br/>";
								}
							}
							
							
						}
						else
							echo "<a href='$upload_folder/$d[0]'>Download a copy of $row1[0].</a>";
					}
					echo "</blockquote>";
				}
				echo "";
			}
			if($authent=="1")
			{
				$tags=gettags($eid,$uid);
				echo "<br/><span style='font-weight:bold'>Your Tags: </span>";
				while($row2=database_fetch_array($tags))
				{
					echo "<a href='search.php?q=$row2[0]&by=taguser' class='tagbutton' onclick='load(\"search.php?q=$row2[0]&by=taguser\");'>$row2[0]</a> ";
				}
			}
			
		
			if($authent!="1")
			{
				echo "<br/><a href='index.php'>Back</a>";
				//die;
			}
			else
			{
				echo "<div id='ratings'><br/><br/>Your rating ";
				if($rating=="")
					for($m=0;$m<5;$m++)
					{
						$x=$m+1;
						echo "<img src='images/notgold.png' style='cursor:pointer' onclick='setrating(\"$uid\",\"$eid\",\"$x\");'/>";
					}
				else
				{
					for($m=0;$m<$rating;$m++)
					{
						$x=$m+1;
						echo "<img src='images/gold.png' style='cursor:pointer' onclick='setrating(\"$uid\",\"$eid\",\"$x\");'/>";
					}
					for($m=$rating;$m<5;$m++)
					{
						$x=$m+1;
						echo "<img src='images/notgold.png' style='cursor:pointer' onclick='setrating(\"$uid\",\"$eid\",\"$x\");' />";
					}
				}
				echo "</div>";
			
			}
			$r1=database_query("select * from resource where lost='1' and eid='$eid';");
			if(!$r1)
				echo database_error();
			if($authent==1)
			{
				echo "<div id='buttons'>";
					if(database_num_rows($r1)==0)
					{
						$res=database_query("select fname,sname,email,uid from borrowed natural join users where eid='$eid'");
						if(database_num_rows($res)==0)
						{
							$id=$eid."button";
							$uid=getuserid($_SESSION['email']);
							echo "<br/><br/><span id='$id'><span class='custombutton' onclick='borrow(\"$eid\",\"$uid\");'>Borrow</span></span>";
						}
						else
						{
							$row3=database_fetch_array($res);
							if($row3[2]==$_SESSION['email'])
							{
								echo "<br/><br/>You have borrowed this.";
								$uid=getuserid($_SESSION['email']);
					
								echo "<br/><br/><span class='custombutton' onclick='var x=prompt(\"What is the location of this resource now?\");returnres_det(\"$eid\",\"$uid\",x);'>Return</span>";
							}
							else
							{
								echo "<br/><br/>Borrowed by $row3[0] $row3[1].";
								$from=$_SESSION['email'];
								echo "<br/><br/><span class='custombutton' onclick='requestres(\"$row3[2]\",\"The resource $row[0] has been requested by $from\",\"Resource Requested\");'>Request</span>";
								echo "<br/><br/><span id='req_res'></span>";
							}
						}
					}
					else
					{
						echo "<br/><br/>Lost :-(";
					}
		
					echo "<span class='custombutton' onclick='$(\"#coverimage\").hide();$(\"#editdetails\").show(\"slow\");'>Edit</span>";
					echo "<span class='custombutton' onclick='$(\"#addtag\").slideDown(\"slow\");$(\"#resourcedetails\").hide();'>Add tags</span>";
					echo "<a href='comment.php?eid=$eid' class='custombutton'>Comment</a>";
					echo "<a href='track.php?eid=$eid' class='custombutton'>Track</a>";
					$fav=database_num_rows(database_query("select * from favourites where eid='$eid' and uid='$uid';"));
					if($fav==0)
					{
						echo "<br/><br/><span class='custombutton' id='fav' onclick='addfav(\"$eid\",\"$uid\");$(\"#fav\").hide();'>Add to Favourites</span>";
					}
					echo "<span class='custombutton' onclick='$(\"#coverimage\").hide();$(\"#addimage\").show(\"slow\");'>Add an image</span>";
					echo "<br/><br/><span class='custombutton' id='rem_tag' onclick='$(\"#coverimage\").hide();$(\"#remove_tags\").show(\"slow\");'>Remove tags</span>";
					if($row[5]=='0')
					{
						echo "<span class='custombutton' id='add_digital' onclick='$(\"#coverimage\").hide();$(\"#digital_details\").show();'>Add a digital copy</span><br/>";
				
				
					}
					else
					{
						echo "<span class='custombutton' id='add_phy'>Add a physical copy</span>";
				
					}
				echo "</div>";
			}
		echo "</div>";
		echo "<div class='detailsblock' id='coverimage' style='float:right'>";
			$res=database_query($query);
			$row1=database_fetch_array($res);
			for($k=1;$k<=$i;$k+=1)
			{
				//echo "<br/><span style='font-weight:bold'>$attrs[$k]: </span>$row1[$k]";
				if($attrs[$k]=="isbn" or $attrs[$k]=="ISBN")
				{
					$src="http://covers.openlibrary.org/b/isbn/".$row1[$k]."-L.jpg";
					echo "<br/><br/><img src='$src' alt='Cover' style='margin-left:50px'/><br/>";
				}
			}
			$tags=getpoptags($eid);
			echo "<br/><br/><span style='font-weight:bold'>Tag Cloud<br/></span>";
			while($row2=database_fetch_array($tags))
			{
				$count=gettagcount($row2[0],$eid);
				if($count!='1')
					$size=11+3*$count;
				else 
					$size="11";
				echo "<a href='search.php?q=$row2[0]&by=tag' class='tagbutton' style='font-size:$size' onclick='load(\"search.php?q=$row2[0]&by=tag\");'>$row2[0]</a> ";
			}
			echo "<br/><br/><strong>User added covers</strong><br/>";
			$cov=getcovers($eid);
			while($cover=database_fetch_array($cov))
			{
				echo "<a href='$cover[0]'><img class='usercover' src='$cover[0]' height='50px'/></a>";
			}
			echo "<br/><a href='cover.php?eid=$eid'><span style='font-size:9pt'>View all covers</span></a>";
		echo "</div>";
		
	//echo "</div>";
?>
<div  id='editdetails'>

<?php
	echo "Name: <input type='text' id='title' value=\"$row[0]\" />";
	echo "<br/>Location: <input type='text' id='location' value=\"$row[3]\" />";
	echo "<br/>Owner: <input type='text' id='owner' value=\"$row[4]\" />";
	for($k=1;$k<=$i;$k+=1)
	{
		echo "<br/>$attrs[$k]: <input type='text' id='$attrs[$k]' value=\"$row1[$k]\" />";
	}
	echo "<br/><br/><span class='custombutton' onclick='edit$typename(\"$eid\");'>Save</span>";
?>
<span id='response'></span>
<br/><br/><span class='custombutton' onclick="$('#editdetails').hide();$('#coverimage').show();">Cancel</span>
</div>

<div id='digital_details' style='display:none'>Do you wish to upload the file to the server?<br/><strong>Disclaimer: Please make sure that by uploading the file you are not violating any copyrights.</strong>
<?php
	echo "<br/><br/><span class='custombutton' onclick='$(\"#upload\").show();'>Yes</span><br/><br/><span class='custombutton' onclick='$(\"#digital_details\").hide();$(\"#coverimage\").slideDown(\"slow\");'>No</span>";
	echo "<div id='upload' style='display:none'>";
		echo "<br/><form action='bin/add_.php?case=file&returnadd=$eid' method='post' enctype='multipart/form-data'>";
			echo "Select the file: <input type='file' name='file' id='file' /> ";
			echo "<input type='text' name='digeid' id='digeid' style='display:none' value='$eid'/>";
			echo "<br/><label>Ask your permission when this is being downloaded?</label><input type='checkbox' name='download_request'/><br/>";
			echo "<br/><br/><input type='submit' class='custombutton' value='Upload' />";
		echo "</form>";
	echo "</div>";
	
?>
</div>

<div id='addimage' style='display:none'>
	<strong>Please make sure that by linking to an image you are not violating any copyrights.</strong><br/>
	Enter the URL of the image:
	<input type='text' id='imageurl' />
	<br/><br/><?php echo "<span class='custombutton' onclick='addimage(\"$eid\",\"$uid\",\"details\");'>Add</span>"; ?>
	<br/><br/><span class='custombutton' onclick="$('#addimage').hide();$('#coverimage').show();">Cancel</span>
</div>

<div id='addtag' >
	Enter the tags seperated by commas
	<br/><input type='text' id='tags' size='30' />
	<br/><br/>
	<?php
	echo "<span class='custombutton' onclick='addtags(\"$eid\");'>Save</span>";
	?>
	<br/><br/><span class='custombutton' onclick="$('#addtag').hide('slow');$('#resourcedetails').show('slow');">Cancel</span>
</div>

<div id="remove_tags">
	<?php
		echo "<h3>Remove tags</h3>Click on a tag to remove it<br/>";
		$tags=gettags($eid,$uid);
		while($row2=database_fetch_array($tags))
		{
			echo "<span class='tagbutton' onclick='remove_tag(\"$eid\",\"$uid\",\"".trim(escape_sql($row2[0]))."\");'>$row2[0]</span> ";
		}
		if($_SESSION['email']=="admin")
		{
			echo "<h4>Automatically added tags</h4>";
			$tags=gettags($eid,"-1");
			while($row2=database_fetch_array($tags))
			{
				echo "<span class='tagbutton' onclick='remove_tag(\"$eid\",\"-1\",\"".trim(escape_sql($row2[0]))."\");'>$row2[0]</span> ";
			}
		}
		
	?>
	<br/><br/><span class='custombutton' onclick="$('#remove_tags').hide('slow');$('#coverimage').show('slow');">Done</span>
</div>
</div>
<?php
	if($case=="rem")
	{
		echo "<script>$(\"#coverimage\").hide();$(\"#remove_tags\").show();</script>";
	}
?>
<script>

		function remove_tag(eid,uid,tag)
		{
			$.ajax({
				url:"bin/edit_.php?case=removetag&eid="+eid+"&uid="+uid+"&tag="+tag,
				success: function()
					 {
					 	window.location="details.php?case=rem&eid="+eid;
					 }
			});
		}
</script>
<?
	echo "<br/><br/>";
	getmodule("details.php");
?>
