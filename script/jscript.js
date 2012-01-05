var current="homepage";
var googlebooks_start=1;
function checkload()
{
	var x=document.location.hash.substring(1);
	if(x==null)load("homepage");
	if(x==""){return;}
	if(x=="search"){}
	else if(x!=current)
		load(x);
	
}
function breadcrumb()
{
	/*var cur=new String(window.location);
	var ref=new String(document.referrer);
	cur=cur.split('/newlib/');
	ref=ref.split('/newlib/');
	var x="<a href='home'>Home</a>";
	if(cur[1]=="home")
	{
		$("#bcrumb").html(x);
		return;
	}
	else if(ref[1]=="home")
	{
		var c=cur[1].split(".php?");
		x+=" > <a href='"+cur[1]+"'>"+c[0]+"</a>";
		$("#bcrumb").html(x);
		return;
	}
	else
	{
		var c=cur[1].split(".php?");
		var r=ref[1].split(".php?");
		var referrer=" > <a href='"+ref[1]+"'>"+r[0]+"</a>";
		x+=referrer+" > <a href='"+cur[1]+"'>"+c[0]+"</a>";
		$("#bcrumb").html(x);
		return;
	}*/
}
function load(x)
{
	$.ajax({
		type:"GET",
		url: "bin/"+x,
		success: function(msg)
			 {
			 	$("#content2").html(msg);
			 	switch(x)
			 	{
			 		case "homepage": document.title="Home"; break;
			 		case "account": document.title="Your account"; break;
			 		case "addnewtype": document.title="Add a new resource type"; break;
			 		case "addres": document.title="Add resource"; break;
			 	}
			 	
			 }
		
	});
	current=x;
	//document.location.hash=x;
	
}
function load_into_div(x,div)
{
	$.ajax({
		type:"GET",
		url: "bin/"+x,
		success: function(msg)
			 {
			 	$("#"+div).html(msg);
			 	switch(x)
			 	{
			 		case "homepage": document.title="Home"; break;
			 		case "account": document.title="Your account"; break;
			 		case "addnewtype": document.title="Add a new resource type"; break;
			 		case "addres": document.title="Add resource"; break;
			 	}
			 	
			 }
		
	});

}
function signup()
{
	$.ajax({
		type:"GET",
		url: "bin/signup.php",
		success: function(msg)
			 {
			 	$("#content2").hide();
			 	$("#content2").html(msg);
			 	$("#loginbox").hide();
			 	$("#content2").slideDown("slow");
			 }
		
	});
}
function registeruser()
{
	var p1=$("#p1").val();
	var p2=$("#p2").val();
	var email=$("#newemail").val();
	var fname=$("#fname").val();
	var sname=$("#sname").val();
	var phone=$("#phone").val();
	var desig=$("#designation").val();
	var question=$("#question").val();
	var answer=$("#answer").val();
	if(p1!=p2)
	{
		$("#confirm").html("Please enter the same password in both the fields.");
		return;
	}
	if(email=="" || fname=="" || sname=="" || phone=="" || desig=="" || question=="" || answer=="")
	{
		$("#confirm").html("Please enter all the required fields.");
		return;
	}
	$.ajax({
		type:"GET",
		url:"bin/add_.php",
		data:"case=user&email="+email+"&fname="+fname+"&sname="+sname+"&phone="+phone+"&desig="+desig+"&password="+p1+"&question="+question+"&answer="+answer,
		success: function(msg)
			 {
			 	$("#confirm").html(msg);
			 	var p1=$("#p1").val("");
				var p2=$("#p2").val("");
				var email=$("#newemail").val("");
				var fname=$("#fname").val("");
				var sname=$("#sname").val("");
				var phone=$("#phone").val("");
				var desig=$("#designation").val("");
				var question=$("#question").val("");
				var answer=$("#answer").val("");
			 }
	});
}

function update()
{
	var email=$("#email").val();
	var fname=$("#fname").val();
	var sname=$("#sname").val();
	var phone=$("#phone").val();
	var desig=$("#designation").val();
	if(email=="" || fname=="" || sname=="" || phone=="" || desig=="")
	{
		$("#confirm").html("Please enter all the required fields.");
		return;
	}
	$.ajax({
		type:"GET",
		url:"bin/edit_.php",
		data:"case=user&email="+email+"&fname="+fname+"&sname="+sname+"&phone="+phone+"&desig="+desig,
		success: function(msg)
			 {
			 	if(msg!="")
			 		$("#confirm").html(msg);
			 	else 
			 	{
			 		load("myprofile");
			 	}
			 }
	});
}
function changepass()
{
	var email=$("#email").val();
	var old=$("#oldpass").val();
	var new1=$("#newpass1").val();
	var new2=$("#newpass2").val();
	if(new1!=new2)
	{
		$("#confirm3").html("Please enter the same new password in both the fields.");
		return;
	}
	$.ajax({
		type:"POST",
		url:"bin/changepass.php",
		data:"email="+email+"&old="+old+"&new="+new1,
		success: function(msg)
			 {
			 	$("#confirm3").html(msg);
			 	$("#newpass1").val("");
			 	$("#oldpass").val("");
			 	$("#newpass2").val("");
			 }
	});
}
function addnewtype()
{
	var name1=$("#typename").val();
	var attr=$("#attr").val();
	$.ajax({
		type:"POST",
		url:"bin/addnewtype.php",
		data:"name="+name1+"&attr="+attr,
		success: function(msg)
			 {
			 	$("#confirm").html(msg);
			 	$("#typename").val("");
			 	$("#attr").val("");
			 	//location.reload(true);
			 }
	});
	
}
function fetchresource(x)
{
	$.ajax({
		type:"GET",
		url:"bin/add_.php",
		data:"case=addres&tyid="+x,
		success: function(msg)
			 {
			 	$("#resource").html(msg);
			 }
	});
}
function fetchresource_wishlist(x)
{
	$.ajax({
		type:"GET",
		url:"bin/add_.php",
		data:"case=addwish&tyid="+x,
		success: function(msg)
			 {
			 	$("#resource").html(msg);
			 }
	});
}
function check(x)
{
	
	$.ajax({
		type:"GET",
		url:"bin/gb.php",
		data:"isbn="+x,
		success: function(msg)
			 {
			 	$(msg).find("book").each(function(){
			 		/*$("#titlecheck").val($(this).find("titl").text());
			 		$("#isbncheck").val($(this).find("isbn").text());
			 		$("#authorcheck").val($(this).find("aut").text());
			 		$("#publishercheck").val($(this).find("pub").text());*/
			 		var str="<br/><input type='button' value='Correct' onclick='addtoform();'><input type='button' value='Next' onclick='googlebooks_start+=1;checkt_wishlist(\""+$("#title").val()+"\");'><input type='button' value='Back' onclick='googlebooks_start-=1;checkt_wishlist(\""+$("#title").val()+"\");'><input type='button' value='Reset search' onclick='googlebooks_start=1;check(\""+$("#gbsearch").val()+"\");'><br/>";
					 		str+="<strong><span id='s_title'>"+$(this).find("titl").text()+"</span></strong><br/>ISBN: <span id='s_isbn'>"+$(this).find("isbn").text()+"</span><br/><span id='s_aut'>"+$(this).find("aut").text()+"</span><br/>Publisher:<span id='s_pub'>"+$(this).find("pub").text()+"</span><br/><img src='http://covers.openlibrary.org/b/isbn/"+$(this).find("isbn").text()+"-M.jpg' />";
					 		
			 		$("#suggestions_gb").html(str);
			 	});
			 }
	});
	
}
function checkt(x)
{
	if($("#titlecheck").is(":checked"))
		$.ajax({
			type:"GET",
			url:"bin/gb.php",
			data:"isbn="+x,
			success: function(msg)
				 {
				 	$(msg).find("book").each(function(){
				 		$("#isbn").val($(this).find("isbn").text());
				 		$("#author").val($(this).find("aut").text());
				 		$("#publisher").val($(this).find("pub").text());
				 	});
				 }
		});
}
function check_wishlist(x)
{
	if($("#isbncheck").is(":checked"))
	{
		$.ajax({
			type:"GET",
			url:"bin/gb.php",
			data:"isbn="+x,
			success: function(msg)
				 {
				 	$(msg).find("book").each(function(){
				 		$("#title").val($(this).find("titl").text());
				 		$("#author").val($(this).find("aut").text());
				 		$("#publisher").val($(this).find("pub").text());
				 	});
				 }
		});
	}
}
function checkt_wishlist(x)
{
	if(x[x.length-1]==" " || x.length>=4)
	{
		if($("#titlecheck").is(":checked"))
			$.ajax({
				type:"GET",
				url:"bin/gb.php",
				data:"isbn="+x+"&start="+googlebooks_start,
				success: function(msg)
					 {
					 	$(msg).find("book").each(function(){
					 		/*$("#isbn").val($(this).find("isbn").text());
					 		$("#author").val($(this).find("aut").text());
					 		$("#publisher").val($(this).find("pub").text());*/
					 		var str="<br/><input type='button' value='Correct' onclick='addtoform();'><input type='button' value='Next' onclick='googlebooks_start+=1;checkt_wishlist(\""+$("#title").val()+"\");'><input type='button' value='Back' onclick='googlebooks_start-=1;checkt_wishlist(\""+$("#title").val()+"\");'><input type='button' value='Reset search' onclick='googlebooks_start=1;checkt_wishlist(\""+$("#title").val()+"\");'><br/>";
					 		str+="<strong><span id='s_title'>"+$(this).find("titl").text()+"</span></strong><br/>ISBN: <span id='s_isbn'>"+$(this).find("isbn").text()+"</span><br/><span id='s_aut'>"+$(this).find("aut").text()+"</span><br/>Publisher:<span id='s_pub'>"+$(this).find("pub").text()+"</span><br/><img src='http://covers.openlibrary.org/b/isbn/"+$(this).find("isbn").text()+"-M.jpg' />";
					 		
					 		$("#suggestions").html(str);
					 	});
					 }
			});
	}
}
function addtoform()
{
	$("#title").val($("#s_title").html());
	$("#isbn").val($("#s_isbn").html());
	$("#publisher").val($("#s_pub").html());
	$("#author").val($("#s_aut").html());
	googlebooks_start=1;
}
function borrow(eid,uid)
{
	$.ajax({
		type:"GET",
		url:"bin/borrow.php",
		data:"eid="+eid+"&uid="+uid,
		success: function(msg)
		 	 {
		 	 	$("#"+eid+"button").html(msg);
		 	 }
	});
}
function returnres(eid,uid,loc)
{

		$.ajax({
			type:"GET",
			url:"bin/return.php",
			data:"eid="+eid+"&uid="+uid+"&loc="+loc,
			success: function(msg)
			 	 {
			 	 	if(msg=="done")
			 	 	{
			 	 		$("#"+eid).hide("slow");
			 	 		window.location='borrowed.php';
			 	 	}
			 	 }
		});
	
}
function returnres_det(eid,uid,loc)
{

		$.ajax({
			type:"GET",
			url:"bin/return.php",
			data:"eid="+eid+"&uid="+uid+"&loc="+loc,
			success: function(msg)
			 	 {
			 	 	if(msg=="done")
			 	 	{
			 	 		load('details.php?eid='+eid);
			 	 	}
			 	 }
		});
	
}
function loadactivity(x,uid,date)
{
	$.ajax({
		type:"GET",
		url:"bin/activity.php?act="+x+"&uid="+uid+"&date="+date,
		success: function(msg)
		 	 {
		 	 	$('#added').css('text-decoration','none');
		 	 	$('#edited').css('text-decoration','none');
				$('#all').css('text-decoration','none');
				$('#borrowed').css('text-decoration','none');
				$('#returned').css('text-decoration','none');
				$('#'+x).css('text-decoration','underline');
		 	 	$("#allactivity").html(msg);
		 	 	
		 	 }
	});
}
function loadmoreactivity(act,uid,date,pg)
{
	$.ajax({
		type:"GET",
		url:"bin/activity.php?act="+act+"&uid="+uid+"&date="+date+"&pg="+pg,
		success: function(msg)
		 	 {
		 	 	$('#added').css('text-decoration','none');
				$('#all').css('text-decoration','none');
				$('#borrowed').css('text-decoration','none');
				$('#returned').css('text-decoration','none');
				$('#'+act).css('text-decoration','underline');
		 	 	$("#allactivity").html(msg);
		 	 	
		 	 }
	});
}
function showactivity()
{
	$("#recentactivity").slideToggle("slow");	
	if($("#recentactivity_a").html()=="+")
		$("#recentactivity_a").html("- ");
	else $("#recentactivity_a").html("+");
}
function send()
{
	var to=$("#to").val();
	var msg=$("#msg").val();
	var sub=$("#subject").val();
	var uid=$("#cuid").html();
	$.ajax({
		type:"POST",
		url:"bin/sendmessage.php",
		data:"to="+to+"&msg="+msg+"&sub="+sub,
		success: function()
			 {
			 	load('profile.php?uid='+uid);
			 }
		
	});
}
function markasread(x)
{
	$.ajax({
		type:"GET",
		url:"bin/add_.php.php",
		data:"case=markread&mid="+x,
		
	});
}

function checkmail()
{
	$.ajax({
		type:"GET",
		url:"bin/checkmail.php",
		success: function(msg)
			 {	
			 	if(msg!="0")
			 	{
			 		$("#inbox").html("("+msg+")");
			 		//$("#ibox").css("font-weight","bold");
			 		$("#ibox").css("color","#666");
			 	}
			 	else
			 	{
			 		$("#inbox").html("");
			 		//$("#ibox").css("font-weight","normal");
			 		$("#ibox").css("color","white");
			 	}	
			 }
	});
	
	//alert(x);
	setTimeout("checkmail();",10000);
}
function deletemsg(x)
{
	$.ajax({
		type:"GET",
		url:"bin/deletemsg.php",
		data:"mid="+x,
		success: function(msg)
			 {
			 	if(msg=="done")
			 	{
			 		$("#msg"+x).hide("slow");
			 	}
			 }
	});
}
function addtags(x)
{
	var t=$("#tags").val();
	$.ajax({
		type:"GET",
		url:"bin/add_.php",
		data:"case=tags&eid="+x+"&tags="+t,
		success: function()
			 {
			 	window.location='details.php?eid='+x;
			 }
		
	});
}
function addfeedback(x)
{
	var t=$("#feed_back").val();
	var s=$("#type").val();
	
	$.ajax({
		type:"GET",
		url:"bin/add_.php",
		data:"case=feedback&uid="+x+"&msg="+t+"&type="+s,
		success:function()
			{
				load('feedback.php?status=1');
			}
	});
}
function addcomment(x,y)
{
	msg=$("#c").val();
	$.ajax({
		type:"GET",
		url:"bin/add_.php",
		data:"case=comment&eid="+x+"&uid="+y+"&msg="+msg+"&step=0",
		success: function()
			 {
			 	load("comment.php?eid="+x);
			 }	
	});
}
function replycomment(x,eid,uid)
{
	msg=$("#r"+x).val();
	
	$.ajax({
		type:"GET",
		url:"bin/add_.php",
		data:"case=comment&eid="+eid+"&uid="+uid+"&msg="+msg+"&step="+x,
		success: function()
			 {
			 	load("comment.php?eid="+x);
			 }	
	});
}
function addmodule(mods,loc,redirect,q,modname)
{
	$.ajax({
		type:"GET",
		url:"bin/add_.php",
		data:"case=newmod&name="+mods+"&loc="+loc+"&q="+q+"&modname="+modname,
		success: function(msg)
			 {
			 	if(msg=="done")
			 		window.location="module.php?status=1&x="+redirect;
			 }
	});
}
function loadmodule(x)
{
	$.ajax({
		type:"GET",
		url:"modules/"+x,
		success: function(msg)
			 {
			 	$("#wishlist").html(msg);
			 }	
	});
}
function requestres(to,msg,sub)
{
	$.ajax({
		type:"POST",
		url:"bin/sendmessage.php",
		data:"to="+to+"&msg="+msg+"&sub="+sub,
		success: function()
			 {
			 	$("#req_res").html("Request sent");
			 }
		
	});
}
function requestdownload(to,msg,sub,id,eid)
{
	$.ajax({
		type:"POST",
		url:"bin/sendmessage.php",
		data:"to="+to+"&msg="+msg+"&sub="+sub+"&id="+id+"&eid="+eid+"&type=download",
		success: function(msg)
			 {
			 	if(msg=="done")
			 	{
			 		$("#req_download").hide();
			 		$("#req_download_resonse").html("Your request to download a copy of this is pending.");
			 	}
			 }
		
	});
}
function reportlost(x)
{
	$.ajax({
		type:"GET",
		url:"bin/add_.php",
		data:"case=lost&eid="+x,
		success:function(msg)
			{
				$("#report"+x).html(msg);
				$('#'+x+'button').html('');
			}
	});
}

function returnlostres(eid,x)
{

		$.ajax({
			type:"GET",
			url:"bin/returnlostres.php",
			data:"eid="+eid+"&loc="+x,
			success: function(msg)
			 	 {
			 	 	if(msg=="done")
			 	 	{
			 	 		$("#"+eid).hide("slow");
			 	 	}
			 	 }
		});
	
}
function replyfeedback(step,email)
{
	var msg=$("#text"+step).val();
	
	$.ajax({
		type:"GET",
		url:"bin/add_.php",
		data:"case=reply&step="+step+"&email="+email+"&msg="+msg,
		success:function()
			{
				load('responsepage');
			}
	
	});
}
function gettypeattributes(typeid)
{
	
	$.ajax({
		type:"GET",
		url:"bin/getattributes.php",
		data:"typeid="+typeid,
		success:function(msg)
			{
				$("#attribdiv").append(msg);
			}
	});
}
function editsec_q()
{
	q=$("#securityq").val();
	p=$("#securityp").val();
	$.ajax({
		type:"GET",
		url:"bin/edit_.php",
		data:"case=sec_q&q="+q+"&p="+p,
		success: function(msg)
			 {
			 	if(msg=="done")
			 	{
			 		$("#sec_q").hide("slow");
			 	}
			 }
	});
}
function resetpass(uid)
{
	a=$("#answer1").val();
	$.ajax({
		type:"GET",
		url:"bin/resetpass.php",
		data:"a="+a+"&uid="+uid,
		success: function(msg)
			 {
			 	$("#forgot_result").html(msg);
			 	$("#forgot_result").slideDown('slow');
			 	
			 }
	});
}
function setrating(uid,eid,i)
{
	$.ajax({
		type:"GET",
		url:"bin/add_.php",
		data:"case=rating&uid="+uid+"&eid="+eid+"&i="+i,
		success: function(msg)
			 {
			 	var x="<br/><br/>Your rating ";
			 	for(var k=0;k<parseInt(i);k++)
			 	{
			 		j=k+1;
			 		x+="<img src='images/gold.png' style='cursor:pointer' onclick='setrating(\""+uid+"\",\""+eid+"\",\""+j+"\");' />";
			 	}
			 	for(k=parseInt(i);k<5;k++)
			 	{
			 		j=k+1;
			 		x+="<img src='images/notgold.png' style='cursor:pointer' onclick='setrating(\""+uid+"\",\""+eid+"\",\""+j+"\");' />";
			 	}
			 	$("#ratings").html(x);
			 	var globalr=parseInt(msg);
			 	var g="User rating ";
			 	for(k=0;k<parseInt(globalr);k++)
			 	{
			 		j=k+1;
			 		g+="<img src='images/gold.png'/>";
			 	}
			 	for(k=parseInt(globalr);k<5;k++)
			 	{
			 		j=k+1;
			 		g+="<img src='images/notgold.png' />";
			 	}
			 	$("#globalrating").html(g);
			 }	
	});
}
function addfav(eid,uid)
{
	$.ajax({
		url:"bin/add_.php",
		data:"case=fav&eid="+eid+"&uid="+uid,
		success: function(msg)
			 {
			 	if(msg=="done")
			 	{
			 		$("#fav").html("");
			 		$("#fav").removeClass("custombutton");
			 	}
			 }	
	});
}
function delete_resource(eid)
{
	$.ajax({
		url:"bin/edit_.php",
		data:"case=deleteres&eid="+eid,
		success: function(msg)
		 	 {
		 	 	if(msg=="done")
				{
					window.location="browse.php";
				}
		 	 }
	});
}
function uninstall_module(mod)
{
	$.ajax({
		type:"GET",
		url:"bin/edit_.php?case=removemod&mod="+mod,
		success: function(msg)
			 {
			 	if(msg=="done")
			 		window.location="module.php";
			 }
	});
}
function backup()
{
	$.ajax({
		type:"GET",
		url:"bin/admin.php?action=backup",
		success: function(msg)
			 {
			 	$("#backupresponse").html("Backup successful");
			 	$("#backuptime").html(" "+msg);
			 }
	});
}
function restore(date,rootpw)
{
	
	$.ajax({
		type:"GET",
		url:"bin/admin.php?action=restore&restore="+date+"&rootpw="+rootpw,
		success: function(msg)	
			 {
			 	alert(msg);
			 	window.location="admin";
			 }
	});
}	
function addimage(eid,uid,page)
{
	var url=$("#imageurl").val();
	$.ajax({
		type:"POST",
		url:"bin/add_.php?case=image&eid="+eid+"&uid="+uid,
		data:"url="+url,
		success: function(msg)
		 	 {
				if(msg=="done")
				{
					window.location=page+".php?eid="+eid;
				}		 	
		  	 }
	});
}
function removetype(tyid,typename)
{
	var conf=confirm("THIS OPERATION IS NOT REVERSIBLE. You are about to remove a resource type from the catalogue. Doing this will also remove all resources of the type "+typename+" from the catalogue. If you wish to continue, click OK.");
	if(conf==true)
	{
		$.ajax({
			type: "GET",
			url: "bin/admin.php?action=removetype&tyid="+tyid+"&name="+typename,
			success: function(msg)
				 {
				 	if(msg=="done")
				 	{
				 		$("#"+tyid).hide("slow");
				 	}
				 }
		});
	}
}	
function removeattr(tyid,attr,typename)
{
	var conf=confirm("THIS OPERATION IS NOT REVERSIBLE. You are about to remove the attribute "+attr+". If you wish to continue, click OK.");
	if(conf==true)
	{
		$.ajax({
			type:"GET",
			url: "bin/admin.php?action=removeattr&tyid="+tyid+"&attr="+attr+"&typename="+typename,
			success: function(msg)
				 {
				 	if(msg=="done")
				 	{
				 		$("#"+tyid+attr).hide("slow");
				 	}
				 }
		});
	}
}
function add_new_attr(tyid,typename)
{
	var attr=prompt("Enter the name of the new attribute for "+typename);
	if(attr==null)
	{
		return;
	}
	if(attr!="" && attr!=" ")
	{
		$.ajax({
			type:"GET",
			url: "bin/admin.php?action=addattr&tyid="+tyid+"&attr="+attr+"&typename="+typename,
			success: function(msg)
				 {
				 	if(msg!="error")
				 	{
				 		window.location="admin.php";
				 	}
				 }
		});
	}
}
function indexnow()
{
	$("#indexnow1").html("Indexing");
	$.ajax({
		type: "GET",
		url: "bin/admin.php?action=indexnow",
		success: function(msg)
			 {
			 	$("#indexnow1").html("<span class='custombutton' onclick='indexnow();' >Index now</span>");
			 }
	});
}
function accept_download(did)
{
	$.ajax({
		type: "GET",
		url: "bin/edit_.php?case=accept_download&did="+did,
		success: function(msg)
			 {
			 	if(msg=="done")
			 	{
			 		$("#pending"+did).hide("slow");
			 	}
			 }
	});
}
function deny_download(did)
{
	$.ajax({
		type: "GET",
		url: "bin/edit_.php?case=deny_download&did="+did,
		success: function(msg)
			 {
			 	if(msg=="done")
			 	{
			 		
			 	}
			 }
	});
}
function show_type(tyid)
{
	$.ajax({
		type: "GET",
		url: "bin/advancedsearch.php?tyid="+tyid,
		success: function(msg)
			 {
			 	$("#"+tyid).html(msg);
			 	$("#"+tyid).show("slow");
			 }	
	});
}
function hide_type(tyid)
{
	$("#"+tyid).hide("slow");
	$("#"+tyid).html("");
}
function wishDetails(wid)
{
	$.ajax({
		url: "bin/wishdetails.php?wid="+wid,
		success: function(msg)
			 {
			 	$("#wish_details").html(msg);
			 	$(".wishes_li").css('background-color','#eee');
			 	$("#"+wid).css('background-color','#ccc');
			 	
			 }
	});
}
function delete_wish(wid)
{
	var check=confirm("Do you want to remove this from your wishlist?");
	if(!check)
	{
		return;
	}
	$.ajax({
		url: "bin/edit_.php?case=deletewish&wid="+wid,
		success: function(msg)
			 {
			 	if(msg=="done")
			 	{	
			 		//window.location="lists.php?list=wishlist&type=1";
			 		$("#wish_details").html("");
			 		$("#"+wid).hide("slow");
			 	}
			 }
	});
}
//adds by Amar
function resizer(){
	//resize the browse books container according to the number of list elements found
	if((browse_li_id=document.getElementById('ul_details'))!=null){
		var browse_li_count=browse_li_id.getElementsByTagName('li').length;
		var browse_li_rows=Math.ceil(browse_li_count/3);
		var browse_li_height=browse_li_rows*181;
		document.getElementById('ul_details').style.height=(browse_li_height+20)+"px";
		document.getElementById('browse').style.height=(browse_li_height+150)+"px";
	}
	//resize popular list 
	if((popular_li_id=document.getElementById('ul_popular'))!=null){
		var popular_li_count=popular_li_id.getElementsByTagName('li').length;
		var popular_li_rows=Math.ceil(popular_li_count/3);
		var popular_li_height=popular_li_rows*104;
		document.getElementById('ul_popular').style.height=popular_li_height+"px";
		document.getElementById('popular').style.height=(popular_li_height+20)+"px";
	}
	//resize list borrowed
	if((borrowed_li_id=document.getElementById('ul_details_borrowed'))!=null){
		var borrowed_li_count=borrowed_li_id.getElementsByTagName('li').length;
		var borrowed_li_rows=Math.ceil(borrowed_li_count/3);
		var borrowed_li_height=borrowed_li_rows*181;
		document.getElementById('ul_details_borrowed').style.height=(borrowed_li_height)+"px";
	}
}
