<?php
	include_once "bin/query.php";
	include_once "bin/session.php";
?>	
<script>
	function test(x)
	{
		if(x.which==13)
		{
			var y=$('#opt').val();var x=$('#search').val();load('search.php?q='+x+'&by='+y);document.location.hash="search";
			
		}
		//alert(x.which.keyCode);
	}
</script>
<form action ='search.php'>
<p><input type='text' size="25" name='q' id='q' onkeypress=test(event) />
<select name='by' id='by'>
	<option value='all'>Search all</option>
	<option value='name'>Search by name</option>
	<option value='tag'>Search by tags</option>

	<?php
		/*$r=getattributes();
		while($row=database_fetch_array($r))
		{
			echo"<option value='$row[0]'>$row[0]</option>";
		}*/
	
	?>
	
</select>
<input type='submit' class='custombutton' value='Search' />
<br/>
<div id='advanced' style='display:none;text-align:right;'>
<?php
	$types=gettypes();
	while($type=database_fetch_array($types))
	{
		echo "<label>$type[1]</label><input type='checkbox' name='search$type[0]' />";
	}
?>
</div>
<span id='adv' onclick="$('#advanced').slideToggle('slow');" style='cursor:pointer;color:#3b5998'>Advanced</span>

</p>
</form>

