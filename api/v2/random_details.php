<?php
	include_once "../../bin/query.php";
	
	$xml="<?xml version='1.0' encoding='utf-8'?><athena>";
	

	
	$eid=database_fetch_array(database_query("select eid from resource order by rand() limit 1"));
	$eid=$eid[0];
	$tyid=gettyid($eid);
	$view=gettypename($tyid)."details";
	$attr=getattr($tyid);
	$query="select name,fname,sname,location";
	while($a=database_fetch_array($attr))
	{
		$query.=",$a[0]";
	
	}

	$query.=" from $view where eid='$eid';";
	$res=database_query($query);
	$data=database_fetch_array($res);
	$xml.="<resource><eid>$eid</eid><name>$data[0]</name><added_by>$data[1] $data[2]</added_by><location>$data[3]</location>";
	$i=4;
	$img="";
	$attr=getattr($tyid);
	while($a=database_fetch_array($attr))
	{
		$a[0][0]=strtoupper($a[0][0]);
		$xml.="<attribute><attributename>$a[0]</attributename><attributevalue>$data[$i]</attributevalue>";
		if(strtolower($a[0])=="isbn")
		{
			$img="http://covers.openlibrary.org/b/isbn/".$data[$i]."-M.jpg";
			
		}
		$xml.="</attribute>";
		$i++;
	
	}
	if($img!="")
		$xml.="<imagelink>$img</imagelink>";
	$xml.="</resource>";
		
	
	
	$eid=$_GET['eid'];
	$xml.="</athena>";
	if($_GET['type']=="js")
	{
		header("Content-Type: text/javascript");
		echo "var reply=\"$xml\";";
		?>
		
		var eid;
		function random_details(div_name)
		{
			
			$(reply).find("eid").each(function(){
				eid=$(this).text();
				});
			$(reply).find("imagelink").each(function(){
				$('#'+div_name).append("<p style='position:relative;text-align:center'><img src='"+$(this).text()+"'/></p>");
				});
			$(reply).find("name").each(function(){
				$('#'+div_name).append("<p style='font-weight:bold;text-align:center'><a href='http://shani.cstep.in/library/details.php?eid="+eid+"'>"+$(this).text()+"</a><p>");
				});
				
			/*$(reply).find("attribute").each(function(){
				$($(this).find("attributename").each(function(){
					$('#'+div_name).append("<strong>"+$(this).text()+": </strong>");
				}));
				$($(this).find("attributevalue").each(function(){
					$('#'+div_name).append($(this).text()+"<br>");
				}));
			});*/
		}
		<?php
		
	}
	else
	{
		header("Content-Type: application/xml");
		echo $xml;	
	}
?>
