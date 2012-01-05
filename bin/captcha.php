<?php
	header ("Content-type: image/png");
	$handle = ImageCreate (120, 30) or die ("Cannot Create image");
	$bg_color = ImageColorAllocate ($handle, 200, 200, 200);
	$txt_color = ImageColorAllocate ($handle, 0, 0, 0);
	$text=$_GET['x'];
	ImageString ($handle, 9, 30, 8, "$text", $txt_color);

	ImagePng ($handle);
?>
