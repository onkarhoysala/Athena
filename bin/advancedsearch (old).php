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
	include_once "query.php";;
	
?>
<?php
	echo "<script>";
	echo "function adv(page)";
	echo "{";
		$res=database_query("select * from type;");
		echo "var x,t='',q='advancedsearchresult.php?search=1&';";
		echo "var n=$('#name').val();";
		while($row=database_fetch_array($res))
		{
			echo "x=$('#$row[1]').is(':checked');";
			echo "if(x){";
				echo "t+='$row[0]-';";
				$res1=getattr($row[0]);
				while($row1=database_fetch_array($res1))
				{
					echo " var z=$('#$row1[0]').val();";
					echo "q+='$row1[0]='+z+'&';";
				}
			echo "}";
			
		}
		echo "q+='type='+t+'&name='+n;";
		echo "$.ajax({ type:'GET',success:function(){ load(q); } });";
		//echo "alert(q); ";
	echo "}";
	echo "</script>";

?>
<script>document.title='Advanced Search'</script>
<h3>Advanced Search</h3>
<div id='advancesearch'>
<form action="advancedsearchresult">
Search for <input type='text' id='name' name='name'>
<br/><br/>
<?php
	$res=database_query("select * from type;");
	while($row=database_fetch_array($res))
	{
		$x=str_replace('_',' ',$row[1]);
		echo " <input type='checkbox' name='type' value='$x'  id='$row[1]' onclick='if(this.checked){gettypeattributes(\"$row[0]\");}'/> $x";
		//echo "<span onclick='gettypeattributes(\"$row[0]\");'>$x</span>";
	}
	echo"<br/>";

?>
<div id='attribdiv'>
</div>
</div>

</form>
<?php 
	echo "<br/><input type='submit' class='custombutton' value='Search' style='margin-left:65%' onclick='adv(\"0\");')";
?>
