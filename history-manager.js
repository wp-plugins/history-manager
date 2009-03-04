//<script>

var step = 1;
var thenum=0;
var $j = jQuery.noConflict();

$j(document).ready(function(){
$j("#post-history-load").stop().animate({ "opacity": 0 });
});

function restart() {
if (step == 0) { move(-1,0); }
else if (step == 1) { ajax_get(); }
else if (step == 2) { speed = 0; move(1,document.getElementById("post-history-return").scrollHeight); }
}

function showPosts(num) {
$j(document).ready(function(){
$j("#post-history-load").stop().animate({ "opacity": 1 });
thenum = num;
step = 0; speed = 0;
restart();
});
}

function ajax_get() {

$j(document).ready(function(){

 $j.ajax({
   type: "POST",
   url: siteurl+"/wp-content/plugins/history-manager/history-return.php",
   data: "q="+thenum,
   success: function(msg){
	  document.getElementById("post-history-return").innerHTML = msg;
	  step++; restart();
	  $j("#post-history-load").stop().animate({ "opacity": 0 });
   }
 });

});

}

var speed = 0;
var i = 0;
function move(dir,imax) {

speed+=0.05*dir;
i+=speed;
document.getElementById("post-history-return").style.height=i+"px";
if (i*dir <= imax) { window.setTimeout("move("+dir+","+imax+")", 10) } else { step++; restart(); document.getElementById("post-history-return").style.height=imax+"px"; }

} 