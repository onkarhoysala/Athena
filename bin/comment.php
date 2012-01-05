<?php
	include_once "session.php";
	include_once "query.php";
	
	$eid=$_GET['eid'];
	$uid=$_SESSION['uid'];
	$_SESSION['page']="comment.php?eid=$eid";
	echo "<h3>Comments for ".getresname($eid)."</h3>";
	echo "<script>document.title='".getresname($eid)." | Comments';</script>";
	$c=getcomments($eid);
	echo "<div id='comments'>";
		echo "<textarea rows='8' cols='75' id='c' name='c'></textarea>";
		$uid=getuserid($_SESSION['email']);
		echo "<br/><br/><span class='custombutton' onclick='addcomment(\"$eid\",\"$uid\");'>Add</span><br/>";
		if(database_num_rows($c)==0)
		{
			echo "<br/>0 comments.";
		}
		echo "<script>$(\"ul#ulcom li:odd\").css(\"background-color\",\"#ccc\");</script>";
		while($com=database_fetch_array($c))
		{
			echo "<ul id='ulcom'>";
				if($com[4]=="0")
				{
					$com[2]=strip_tags($com[2]);
					$com[2]=escape_sql($com[2]);
					echo "<li id='com'>$com[2]<br/>By: ".getname_uid($com[3]);
					echo "<br/><span style='color:#96a8c8;cursor:pointer' onclick='$(\"#reply$com[0]\").toggle(\"slow\");'>Reply</span>";
					echo "<div id='reply$com[0]' style='display:none'><textarea rows='5' cols='50' id='r$com[0]'></textarea><br/><br/>";
					echo "<span class='custombutton' onclick='replycomment(\"$com[0]\",\"$eid\",\"$uid\");'>Submit</span><br/></div>";
					$r=database_query("select * from comment_table where step='$com[0]';");
					while($r1=database_fetch_array($r))
					{
						$from=getname_uid($r1[3]);
						echo "<blockquote class='replies'>$r1[2]<br/>From: $from</blockquote>";
					}
					echo "</li>";
				}
			echo "</ul>";
		}
		
	echo "</div>";	
?>
