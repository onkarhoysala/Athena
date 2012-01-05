<?php
	$x=$_GET["isbn"];
	$x=str_replace(" ","+",$x);
	if(isset($_GET['start']))
	{
		$a=$_GET['start'];
	}
	else
		$a=1;
	
	$xmlr='<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"."<books>";
	function start($parser,$element_name,$element_attrs)
	{
		global $i;
		global $index;
		$index[$i]=$element_name;		
	}
	function stop($parser,$element_name)
	{

	}
	function char($parser,$data)
	{
		global $items;
		global $i;
		global $index;
		if(isset($items[$index[$i]]) and $index[$i]=="DC:CREATOR")
		{
			$items[$index[$i]]=$items[$index[$i]].", ".$data;
		}
		else
			$items[$index[$i]]=$data;
		//echo $index[$i]." ".$items[$index[$i]]."<br/>";
		$i++;  
	}

	$gbooks = "http://books.google.com/books/feeds/volumes?q='$x'&max-results=1&start-index=$a";
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $gbooks);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$data = curl_exec($curl);
	curl_close($curl);
	$parser=xml_parser_create();
	$index=array();
	$i=0;
	$items=array();
	
	xml_set_element_handler($parser,"start","stop");
	xml_set_character_data_handler($parser,"char");
	xml_parse($parser,$data) or
	die (sprintf("XML Error: %s at line %d",
	xml_error_string(xml_get_error_code($parser)),
	xml_get_current_line_number($parser)));
	$a1=$items['TITLE'];
	$a2=$items['DC:CREATOR'];
	$a3=$items['DC:PUBLISHER'];
	$a4=$items['DC:IDENTIFIER'];
	$a4=substr($a4,5);
	$xmlr.="<book><titl>$a1</titl><aut>$a2</aut><pub>$a3</pub><isbn>$a4</isbn></book>";
	$xmlr.="</books>";
	header("Content-Type: text/xml");
	
	echo $xmlr;
	//Free the XML parser
	xml_parser_free($parser);
?>
