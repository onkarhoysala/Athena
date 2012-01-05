<?php
	include_once "session.php";
	include_once "query.php";
	require_once "Mail.php";
	$t=$_POST['to'];
	$msg=$_POST['msg'];
	$sub="[CSTEP Library]".$_POST['sub'];
	$f=$_SESSION['email'];
	$to=getuserid($t);
	$from=getuserid($f);
	
	$res=database_query("insert into message values ('$from','$to',now(),'$sub','$msg','1','1','');");
	if(!$res)
	{
		echo database_error();
	}	
	if(isset($_POST['type']))
	{
		$type=$_POST['type'];
		if($type=="download")
		{
			$id=$_POST['id'];
			$eid=$_POST['eid'];
			$res=database_query("insert into download_request values('','$id','$eid','$from','pending');");
			if(!$res)
			{
				echo database_error();
			}
		}	
	}
	echo "done";
	

	$from = "Info <info@cstep.in>";
	$to = "<".$t.">";

	$host = "smtp.ctrls.in";
	$username = "info@cstep.in";
	$password = "cstep.321";

	$headers = array ('From' => $from,
	  'To' => $to,
	  'Subject' => $sub);
	$smtp = Mail::factory('smtp',
	  array ('host' => $host,
	    'auth' => false,
	    'username' => $username,
	    'password' => $password));

	$mail = $smtp->send($to, $headers, $msg);

	if (PEAR::isError($mail)) {
	  echo("<p>" . $mail->getMessage() . "</p>");
	 } else {
	  echo("<p>Message successfully sent!</p>");
	 }
?>
