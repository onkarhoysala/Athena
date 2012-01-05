<?php
	/**
	* This file is used to add new resources to the system
	* @global $case This can be either "file","image","resource","wish","comment","lost","feedback" etc. Based on the value of this variable, the given case conditions are executed. Again, based on the different values of this variable, more GET or POST variables might be passed.
	*/
	/**
	* Session.php contains all the necessary methods and variables necessay to maintain a session.	
	*/
	include_once "session.php";
	/**
	* Query.php contains all the necessary methods and variables necessary to maintain a database connection. It also contains methods that act as wrapper methods for database queries.
	*/
	include_once "query.php";
	$switch=$_GET['case'];
	if($switch=="file")
	{

		$eid=trim($_POST['digeid']);
		$request=trim($_POST['download_request']);
		
		if ($_FILES["file"]["error"] > 0)
		{
            	    echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
	        }
		else
		{
			echo "Upload: " . $_FILES["file"]["name"] . "<br />";
			echo "Type: " . $_FILES["file"]["type"] . "<br />";
			echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
			echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";
			if($request=="on")
				$request='1';
			else $request='0';
			if (file_exists("upload/" . $_FILES["file"]["name"]))
			{
			
			}
			else
			{
				move_uploaded_file($_FILES["file"]["tmp_name"],"../upload/" . $_FILES["file"]["name"]);
				$fname=$_FILES["file"]["name"];
				$uid=$_SESSION['uid'];
				database_query("insert into uploaded values('','$eid','$fname','$uid','$request');");
				echo "Stored in: " . "upload/" . $_FILES["file"]["name"];
			}
			
			
		}
		if(isset($_GET['returnadd']))
		{
			$ret_eid=$_GET['returnadd'];
			$address="details.php?eid=$ret_eid";
			echo "<script>window.location='../$address&added=true'</script>";
		}
		echo "<script>window.location='../addres?added=true'</script>";

	}
	if($switch=="image")
	{
		$uid=$_GET['uid'];
		$eid=$_GET['eid'];
		$url=$_POST['url'];
		$url=escape_sql($url);
		$res=database_query("insert into images values('','$eid',\"$url\",'$uid');");
		if(!$res)
		{
			echo database_error();
		}
		else echo "done";
	}
	if($switch=="resource")
	{
		$uid=getuserid($_SESSION['email']);
		$t=$_GET['title'];
		$loc=$_GET['loc'];
		$tyid=$_GET['tyid'];
		$owner=$_GET['owner'];
		$captcha=$_GET['captcha'];
		$tags=$_GET['tags'];
		$cap=$_GET['cap'];
		$digital=$_GET['digital'];
		if(trim($captcha)!=trim($cap))
		{
			echo "Please enter the captcha again";
			die;
		}
		$t=escape_sql($t);
		$loc=escape_sql($loc);
		$owner=escape_sql($owner);
		$tyid=escape_sql($tyid);
	
		$typename=gettypename($tyid);
		$q="insert into $typename values(last_insert_id()";
		$attr=getattr($tyid);
		while($row=database_fetch_array($attr))
		{
			$attribname=$row[0];
			$attribname=str_replace(" ","_",$attribname);
			$q.=",'".$_GET[$attribname]."'";
		}
		$q.=");";
		if($loc=='')
		{
			echo "Please Enter A Location";
			die;
		
		}
		//need to find if it already exists
		/*$exist=explode(",",check_exists_index($t,"name"));
		if($exist!="")
		{
			echo "The following items were found that match your entry.<br/><ul>";
			foreach($exist as $exist_eid)
			{
				echo "<li><a href='details.php?eid=$exist_eid'>".getresname($exist_eid)."</a></li>";
			}
			echo "</ul>";
			die;
		}*/
		
		$res=database_query("insert into resource values('','$t','$tyid',now(),'$uid','$loc','$owner','0','0','$digital');");
		if(!$res)
		{
			echo database_error();
			die;
		}
		$eidadded=database_fetch_array(database_query("select last_insert_id();"));
		if(!$res)
		{
			echo database_error();
			die;
		}
		$res=database_query($q);
		if(!$res)
		{
			echo database_error();
			die;
		}
	
		$file=fopen("stop.txt","r");
		$stop=fgets($file);
		//Tags
		$words=explode(",",$tags);
		foreach($words as $w)
		{
			if(strstr(strtolower($stop),strtolower($w))=="")
			{
				$w=escape_sql($w);
				database_query("insert into resource_tag values('$w',last_insert_id(),'$uid');");
			}	
		}
		
		//Tags added automatically from title
		$words=explode(" ",$t);
		foreach($words as $w)
		{
			if(strstr(strtolower($stop),strtolower($w))=="")
			{
				$w=escape_sql($w);
				database_query("insert into resource_tag values('$w',last_insert_id(),'$uid');");
			}
		}
	
		/*$res=database_query("insert into log values (now(),'added','$uid',last_insert_id(),'','');");
		if(!$res)
		{
			echo database_error();
			die;
		}*/
		$res=database_query("insert into log (eid,uid,time_entry,activity) values(last_insert_id(),\"$uid\",now(),\"added\");");
		if(!$res)
		{
			echo mysql_error();
			die;
		}
		//logger("added::$eidadded[0]");
		echo "done||$t added||$eidadded[0]";
	}
	if($switch=="wish")
	{
		$force=$_GET['force'];
		$uid=getuserid($_SESSION['email']);
		$t=$_GET['title'];
		$tyid=$_GET['tyid'];
		$captcha=$_GET['captcha'];
		$cap=$_GET['cap'];
		$digital=$_GET['digital'];
		$tyid=escape_sql($tyid);
		$typename=gettypename($tyid);
		if(trim($captcha)!=trim($cap))
		{
			echo "Please enter the captcha again";
			die;
		}
		
		//check if such a book exists already
		if($force=='0')
		{
			$results=searchresources_exists("$t","name","1","$tyid,");
			$exist=database_num_rows($results[0]);
			if($exist!=0)
			{
				echo "Hey! Seems like a similar $typename already exists. Check if the $typename you want is one from the list below<br/>";
				echo "<ol>";
				while($row=database_fetch_array($results[0]))
				{
					echo "<li><a href='details.php?eid=$row[0]'>$row[1]</a></li>";
				}
				echo "</ol>";
				echo "<input type='button' value='Add anyway' onclick='addWish$typename(\"1\");' />";
				echo "<input type='button' value='Cancel' onclick='document.form1.reset();$(\"#response_wishlist\").html(\"\");' />";
				die;
			}
		}
		
		//$t=escape_sql($t);
		$q="insert into $typename"."wishlist"." values(last_insert_id()";
		$attr=getattr($tyid);
		while($row=database_fetch_array($attr))
		{
			$q.=",\"".$_GET[$row[0]]."\"";
		}
		$q.=");";
		
		$res=database_query("insert into wishlist(wname,tyid,date_of_entry,uid,digital) values('$t','$tyid',now(),'$uid','$digital');");
		if(!$res)
		{
			echo database_error();
			die;
		}
		$res1=database_query("select last_insert_id();");
		$wid1=database_fetch_array($res1);
		$wid1=$wid1[0];
		$res=database_query($q);
		if(!$res)
		{
			echo database_error();
			die;
		}
	
		logger("wished for::$wid1::$t");
		echo "$t was added to your wishlist.||$wid1";
	}
	else if($switch=="comment")
	{
		$eid=$_GET['eid'];
		$uid=$_GET['uid'];
		$msg=$_GET['msg'];
		$step=$_GET['step'];
		$msg=strip_tags($msg);
		$res=database_query("insert into comment_table values('','$eid','$msg','$uid','$step');");
		if(!$res)
		{
			echo database_error();
			die;
		}
		$name=getresname($eid);
		$res=database_query("insert into log values(now(),\"commented on\",'$uid','$eid',\"$msg\",'');");
		if(!$res)
		{
			echo database_error();
			die;
		}
		
	}
	
	else if($switch=="lost")
	{
		$uid=getuserid($_SESSION['email']);
		$eid=$_GET['eid'];
		$res=database_query("update resource set lost='1' where eid='$eid';");
		if(!$res)
		{
			echo database_error();
		}
		$res1=database_query("insert into log values(now(),'reported as lost','$uid','$eid','','');");
		if(!$res)
		{
			echo database_error();
		}
		else echo "Lost :-(";
	}
	
	else if($switch=="feedback")
	{
		$uid=$_GET['uid'];
		$msg=$_GET['msg'];
		$msg=strip_tags($msg);
		$msg=escape_sql($msg);
		$type=$_GET['type'];
		$email=getemail($uid);
		$res=database_query("insert into feedback values('','$email','$msg','$type',now(),'0');"); 
		if(!res)
		{
			echo database_error();
		}
	}
	
	else if($switch=="newmod")
	{
	
		$n=$_GET['name'];
		$modname=$_GET['modname'];
		$l=$_GET['loc'];
		$q=$_GET['q'];
		$q1=explode("-",$q);
		$name1=split("-",$n);
		$loc1=split("-",$l);
		$i=0;
		foreach($name1 as $name)
		{
			
				$res=database_query("insert into module values('$name','$loc1[$i].php');");
				$i++;
			
		}
		$res=database_query("insert into installed_modules values('','$modname');");
		if(!$res)
		{	
			echo database_error();
			die;
		}
		
		foreach($q1 as $q)
		{
			if($q!="" and $q!=" ")
			{
				$res=database_query($q);
				if(!$res)
				{	
					echo database_error();
					die;
				}
			}	
		}
		echo "done";
	}
	
	
	
	else if($switch=="reply")
	{
		$step=$_GET['step'];
		$email=$_GET['email'];
		$msg=$_GET['msg'];
		$msg=strip_tags($msg);
		echo "insert into feedback values('','$email','$msg','reply',now(),'$step');";
		$res=database_query("insert into feedback values('','$email','$msg','reply',now(),'$step');");
		if(!$res)
		echo database_error();
	}
	
	else if($switch=="addres")
	{
		echo "<div id='addresource_left'>";
		$_SESSION['page']='addres';
		if(isset($_GET['tyid']))
		{
			$typename=gettypename($_GET['tyid']);
			$tyid=$_GET['tyid'];
			$all_attr="";
			echo "<br/><br/><h3>Add a new $typename</h3>";
			echo "<script>document.title='Add a $typename';</script>";
			$type_attr=getattr($_GET['tyid']);
			echo "<p>Enter the details of the $typename you want to add. Title, Location and Owner are compulsory fields. Enter the tags seperated by commas.</p>";
			echo "<form name='form1' id='form1'>";
			/*while($attr=database_fetch_array($type_attr))
			{
				if($attr[0]=="isbn")
				{
				
					echo "ISBN Lookup<input type='checkbox' name='isbnlookup' value='ISBNL' id='isbncheck' CHECKED /><br/>Title Lookup<input type='checkbox' name='titlelookup' value='titleL' id='titlecheck'/>";
					echo "<br/>$attr[0]: <input type='text' id='$attr[0]' onkeyup='check(this.value);' />";
				}
				$all_attr.=$attr[0]." ";
			}*/
			echo "<input type='text' id='tyid' value='$tyid' style='display:none' />";//stores the type id of the resource
			if(gettypename($tyid)=='book' or gettypename($tyid)=='Book')
				echo "<br/>Title: <input type='text' id='title' />";
			else
				echo "<br/>Title: <input type='text' id='title'  />";
			echo "<br/>Location: <input type='text' id='location' />";
			echo "<br/>Owner: <input type='text' id='owner' />";
			$uid=getuserid($_SESSION['email']);
			echo "<input type='text' id='uid' style='display:none' value='$uid' />"; //stores the owner uid
			$type_attr=getattr($_GET['tyid']);
			while($attr=database_fetch_array($type_attr))
			{
				//if($attr[0]!="isbn")
				//{
					$attribute=str_replace("_"," ",$attr[0]);
					$attr[0]=str_replace(" ","_",$attr[0]);
					echo "<br/>$attribute: <input type='text' id='$attr[0]' />";
				//}
			}
			echo "<br/>Tags <input type='text' id='tags' />";
			echo "<br/>Digital <input type='checkbox' id='digital' />";
			echo "<br/><br/>Please enter the text from the image below: <input type='text' id='captcha'/>";
			$string=rand_str(5);
			echo "<input type='text' style='display:none' id='cap' value='$string'/>";
			echo "<br/><img src='bin/captcha.php?x=$string'   />";		
			echo "</form>";
			echo "<br/><input type='button' value='Add' onclick=\"add$typename();\"/>";
		
			echo "<div id='dig' style='display:none'>Do you wish to upload the file to the server?<br/><strong>Disclaimer: Please make sure that by uploading the file you are not violating any copyrights.</strong>";
				echo "<br/><br/><span class='custombutton' onclick='$(\"#upload\").show();'>Yes</span><br/><br/><span class='custombutton' onclick='$(\"#dig\").hide();'>No</span>";
				echo "<div id='upload' style='display:none'>";
					echo "<br/><form action='bin/add_.php?case=file' method='post' enctype='multipart/form-data'>";
						echo "Select the file: <input type='file' name='file' id='file' /> ";
						echo "<br/><label>Ask your permission when this is being downloaded?</label><input type='checkbox' name='download_request'/><br/>";
						echo "<input type='text' name='digeid' id='digeid' />";
						echo "<br/><br/><input type='submit' class='custombutton' value='Upload' />";
					echo "</form>";
				echo "</div>";
			echo "</div>";
		
			if($tyid=="1")
				echo "<br/>ISBN and title lookup powered by:<br/><img src='http://books.google.com/intl/en/googlebooks/images/books_logo.gif' style='height: 30px;padding-top:5px'/><br/>";
			$c=database_num_rows(gettypes());
			$r=gettypes();
			for($i=1;$i<=$c;$i++)$z=database_fetch_array($r);
			$y=$z[0];
			echo "<script>";
				echo "for(var i=0;i<=$y;i++){ $(\"#\"+i).css(\"background-color\",\"#ccc\"); }";
				echo "$('#$tyid').css(\"background-color\",\"#96a8c8\");";
			echo "</script>";
			echo "<br/><span id='response'></span>";
			echo "</div>";
		echo "";
		if(strtolower($typename)=="book")
		{
			echo "<div id='addresource_right'>";
				echo "<p>Enter the name or ISBN of the book. Click 'Correct' to select the result.</p>";
				echo "Search for: <input type='text' id='gbsearch' onkeyup='check(this.value);' />";
				echo "<div id='suggestions_gb'></div>";
				//echo "<br/><input type='radio' CHECKED name='gblookup' id='titlelookup' />ISBN Lookup<input type='radio' name='gblookup' id='titlelookup' />Title Lookup<input type='radio' name='gblookup' id='nolookup' />Don't lookup<br/>";
			echo "</div>";
		}
		die;
		}

		else
		{
		echo "<div id='addnewres'>";
			$t=gettypes();
			echo "<h3>Select the type of resource to add</h3>";
			echo "<ul class='ultypes'>";
			while($type=database_fetch_array($t))
			{
				$typename=str_replace("_"," ",$type[1]);
				echo "<li class='types' id='$type[0]'><span style='cursor:pointer' onclick='fetchresource(\"$type[0]\");'>$typename</span></li>";
			}
			echo "<li class='types'><a href='#addnewtype' onclick='load(\"addnewtype\");'>Add new type</a></li>";
			echo "</ul>";
	
		echo "</div>";
		echo "<div id='resource'>";

		echo "</div>";
		}
		
	}
	else if($switch=="addwish")
	{
		
		
		echo "<div id='wish_form' style='width:300px; text-align:right;'>";
		if(isset($_GET['tyid']))
		{
			$typename=gettypename($_GET['tyid']);
			$tyid=$_GET['tyid'];
			$all_attr="title captcha digital uid cap ";
			echo "<br/><br/><h3>Add a new $typename</h3>";
			echo "<script>document.title='Add a $typename';</script>";
			$type_attr=getattr($_GET['tyid']);
			echo "<form name='form1' id='form1'>";
			while($attr=database_fetch_array($type_attr))
			{
				if($attr[0]=="isbn")
				{
				
					echo "<input type=\"radio\" name='isbnlookup' value='ISBNL' id='isbncheck'/>ISBN Lookup<input type=\"radio\" name='isbnlookup' value='titleL' id='titlecheck' CHECKED />Title Lookup<input type=\"radio\" name='isbnlookup' value='ISBNL' id='nocheck'/>Do not lookup";
					echo "<br/>$attr[0]: <input type='text' id='$attr[0]' onkeyup='check(this.value);' dir='rtl' />";
				}
				$all_attr.=$attr[0]." ";
			}
			echo "<input type='text' id='all_attr' value='$all_attr' style='display:none' dir='rtl'>";
			echo "<input type='text' id='tyid' value='$tyid' style='display:none' />";//stores the type id of the resource
			if(gettypename($tyid)=='book' or gettypename($tyid)=='Book')
				echo "<br/>Title: <input type='text' id='title' onkeyup='checkt_wishlist(this.value);'  dir='rtl' />";
			else
				echo "<br/>Title: <input type='text' id='title'  dir='rtl' />";
		
			$uid=getuserid($_SESSION['email']);
			echo "<input type='text' id='uid' style='display:none' value='$uid' />"; //stores the owner uid
			$type_attr=getattr($_GET['tyid']);
			while($attr=database_fetch_array($type_attr))
			{
				if($attr[0]!="isbn")
				{
					$attribute=str_replace("_"," ",$attr[0]);
					echo "<br/>$attribute: <input type='text' id='$attr[0]'  dir='rtl' />";
				}
			}
			echo "<br/>Digital <input type='checkbox' id='digital' />";
			echo "<br/><br/>Please enter the text from the image below: <input type='text' id='captcha'/>";
			$string=rand_str(5);
			echo "<input type='text' style='display:none' id='cap' value='$string'/>";
			echo "<br/><img src='bin/captcha.php?x=$string'   />";		
			echo "</form>";
			echo "<br/><input type='button' value='Add' onclick=\"addWish$typename('0');\"/>";
		
			if(strtolower($typename)=="book")
				echo "<br/>ISBN and title lookup powered by:<br/><img src='http://books.google.com/intl/en/googlebooks/images/books_logo.gif' style='height: 30px;padding-top:5px'/><br/>";
			$c=database_num_rows(gettypes());
			$r=gettypes();
			for($i=1;$i<=$c;$i++)$z=database_fetch_array($r);
			$y=$z[0];
			echo "<script>";
				echo "for(var i=0;i<=$y;i++){ $(\"#\"+i).css(\"background-color\",\"#ccc\"); }";
				echo "$('#$tyid').css(\"background-color\",\"#96a8c8\");";
			echo "</script>";
			echo "<br/><span id='response'></span>";
			echo "</div>";
			if(strtolower($typename)=="book")
			{
				echo "<div id='suggestions'>";
				echo "<span style='font-size:14pt;color:orange;opacity:0.7'>Type in a name to see more details about the book.</span>";
				echo "</div>";
			}
		}
		echo "<div id='response_wishlist'></div>";
		die;
		
	}
	else if($switch=="tags")
	{
		$eid=$_GET['eid'];
		$tags=$_GET['tags'];	
		$t=explode(",",$tags);
		$uid=$_SESSION['uid'];
		foreach($t as $x)
		{
		$x=escape_sql($x);
		$res=database_query("insert into resource_tag values ('$x','$eid','$uid');");
		if(!$res)
		{
			echo database_error();
		}
		//echo "insert into resource_tag values ('$eid','$x');";
		}
	}
	
	else if($switch=="user")
	{
		$email=$_GET['email'];
		$fname=$_GET['fname'];
		$sname=$_GET['sname'];
		$phone=$_GET['phone'];
		$desig=$_GET['desig'];
		$pass=$_GET['password'];
	
		$email=escape_sql($email);
		$fname=escape_sql($fname);
		$sname=escape_sql($sname);
		$phone=escape_sql($phone);
		$desig=escape_sql($desig);
		$pass=escape_sql($pass);
	
		$question=$_GET['question'];
		$answer=$_GET['answer'];
		$res=database_query("select * from users where email='$email';");
		$val='@cstep.in';
		$st=substr($email,-9);
		$i=substr_count($email,$val);
		/*if($i>1 or $st!=$val)
		{
			echo "Invalid Email. Please Enter Cstep Email";
			die;
		}*/
		if($fname=="" or $sname=="" or $email=="" or $pass=="" or $question=="" or $answer=="")
		{	
			echo "Error creating user. Please check if you have entered necessary details.";
			die;
		}
		if(database_num_rows($res)!=0)
		{
			echo "The email address $email has already been registered.<br/>Click <a href='#' onclick='$(\"#loginbox\").slideDown();$(\"#signup\").hide();'>here</a> to login.";
			die;
		}
		if($dbtype=="mysql")
		{
			$res=database_query("insert into users (email,fname,sname,phone,designation,password,question,answer)values('$email','$fname','$sname','$phone','$desig',password('$pass'),'$question','$answer');");
		}
		if($dbtype=="pgsql")
		{
			$res=database_query("insert into users (email,fname,sname,phone,designation,password,question,answer)values('$email','$fname','$sname','$phone','$desig',md5('$pass'),'$question','$answer');");
			//echo "insert into users (email,fname,sname,phone,designation,password,question,answer)values('$email','$fname','$sname','$phone','$desig',md5('$pass'),'$question','$answer');";
		}
		if(!$res)
		{
			echo database_error();
		}
		else echo "Registration successful.<br/>Click <a href='login.php'>here</a> to login.";
	}
	
	else if($switch=="rating")
	{
		$uid=$_GET['uid'];
		$eid=$_GET['eid'];
		$i=$_GET['i'];
		$no=database_num_rows(database_query("select rating from ratings where eid='$eid' and uid='$uid';"));
		if($no!=0)
		{
			database_query("update ratings set rating='$i' where eid='$eid' and uid='$uid';");
		}
		else database_query("insert into ratings values('$eid','$uid','$i');");
		$res=database_query("select rating from ratings where eid='$eid';");
		$avg=0;
		$count=database_num_rows($res);
		while($row=database_fetch_array($res))
		{
			//$count++;
			$avg+=$row[0];
		}
		$avg=$avg/$count;
		$res=database_query("update resource set rating='$avg' where eid='$eid';");
		echo $avg;
	}
	
	else if($switch=="markread")
	{
		$mid=$_GET['mid'];
		$res=database_query("update message set read_flag='0' where mid='$mid';");
		if(!$res)
		{
			echo database_error();
		}
	}
	else if($switch=="fav")
	{
		$uid=$_GET['uid'];
		$eid=$_GET['eid'];
		$res=database_query("insert into favourites values('$eid','$uid');");
		if(!$res)
		{
			echo database_error();
			die;
		}
		echo "done";
	}
	
?>
