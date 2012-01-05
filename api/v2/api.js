var script = document.createElement('script');
script.src = 'http://jqueryjs.googlecode.com/files/jquery-1.2.6.min.js';
script.type = 'text/javascript';
document.getElementsByTagName('head')[0].appendChild(script);




/*function random_details(div_name)
{
	$.ajax({
		url: "library/api/v2/random_details.php?type=js",
		success: function(msg)
			 {
			 	$(msg).find("name").each(function(){
			 		$('#'+div_name).append("Name: "+$(this).text());
			 	});
			 }
	});
}*/
