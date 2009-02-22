//<script>

var step = 0;
var firstshow = false;
var sm=0;

function showPosts(num) {
if (step == 0) { hide(num); }
else if (step == 1) { ajax_get(num); }
else if (step == 2) { show(); }
}


var xmlHttp;

function ajax_get(str)
{ 
xmlHttp=GetXmlHttpObject()
if (xmlHttp==null)
 {
 alert ("Browser does not support HTTP Request")
 return
 }
var url=siteurl+"/wp-content/plugins/history-manager/history-return.php?q="+str+"&s="+sm;;
//url=url+"?q="+str
xmlHttp.onreadystatechange=stateChanged 
xmlHttp.open("GET",url,true)
xmlHttp.send(null)
}

function stateChanged() { 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete") { 
  document.getElementById("post-history-return").innerHTML = xmlHttp.responseText;
  step = 2; showPosts();
} else if (xmlHttp.readyState==1) { 
  document.getElementById("post-history-return").innerHTML = "Loading...";
//  document.loadimg.src = Pic1.src;
//  document.getElementById("post-history-return").style.visibility = "visible";
  }
}

function GetXmlHttpObject()
{
var xmlHttp=null;
try
 {
 // Firefox, Opera 8.0+, Safari
 xmlHttp=new XMLHttpRequest();
 }
catch (e)
 {
 //Internet Explorer
 try
  {
  xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
  }
 catch (e)
  {
  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
 }
return xmlHttp;
}

var i=0;
var c=0;
var intHide; var intShow;
var speed=2;

function hide(num) {

sm = 0;

if (!firstshow) { initshow(num); }
else {
	 if (i>17) { i=i-speed; document.getElementById("post-history-return").style.height=i+"px"; window.setTimeout("hide("+num+")", 10);}
	 else { step = 1; showPosts(num);} 
}
}

function show() {



if (i<document.getElementById("post-history-return").scrollHeight) { i=i+speed; document.getElementById("post-history-return").style.height=i+"px"; window.setTimeout("show()", 10);}
else { step = 0;} 

}

function initshow(num) {

if (i<17) { i=i+speed; document.getElementById("post-history-return").style.height=i+"px"; window.setTimeout("initshow("+num+")", 10);}
else { step = 1; showPosts(num); firstshow = true;} 

}

function showMore() {
	sm = 1;
	step = 1;
	showPosts();
}
