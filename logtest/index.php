<?
session_start();
error_reporting(E_ALL);
require_once("../fn/vl_function.php");
require_once("../class/vl_database.php");
require_once("resource/security/functions.php");
checklogged("login.php");

$framemainsrc 			= 'blank.htm';
$langAdmin 				= array();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Administrator Website</title>
<style>

</style>
<link rel="stylesheet" type="text/css" media="screen" href="resource/css/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="resource/js/sortable/styles.css" />

<script src="resource/js/jquery.js" type="text/javascript"></script>
<script src="resource/js/jquery-ui-1.8.1.custom.min.js" type="text/javascript"></script>
<script src="resource/js/jquery.layout.js" type="text/javascript"></script>

<script language="JavaScript">
function calcHeight(id)
{

	var dodai_trang = document.getElementById(id).contentWindow.document.body.scrollHeight;
	var hei = $(document).height() - $(".ui-layout-north").height() - 20;
	dodai_trang = dodai_trang + 50;
	dodai_trang = (dodai_trang < hei) ? hei : dodai_trang;
	$("#"+id).height(dodai_trang);
}
</script>

<script type="text/javascript">
jQuery(document).ready(function(){

	$("#test-list").sortable({
			handle : '.handle',
			axis: 'y',
			update : function () {
			  var order = $('#test-list').sortable('serialize');
				$.ajax({
					url: "resource/process-sortable.php",
					type: "post",
					data: order,
					error: function(){
						alert("Lỗi load dữ liệu");
					}
				});
			  //alert(order);
				//$("#info").load("process-sortable.php?"+order);
			}
		 });

		/*----------------------------------------*/

		$('body').layout({
			resizerClass: 'ui-state-default',
			spacing_open: 11,
			spacing_closed: 11,
			slideTrigger_open: 'mouseleave'

		});

		/*----------------------------------------*/

			var maintab = jQuery('#tabs','#RightPane').tabs({
	        add: function(e, ui) {
	            // append close thingy
	            $(ui.tab).parents('li:first')
	                .append('<span class="ui-tabs-close ui-icon ui-icon-close" title="Close Tab"></span>')
	                .find('span.ui-tabs-close')
	                .click(function() {
	                    maintab.tabs('remove', $('li', maintab).index($(this).parents('li:first')[0]));
	                });
	            // select just added tab
	            maintab.tabs('select', '#' + ui.panel.id);
	        }
	    });


	    //$("#tabs").sortable({
	    	//items: "li",
	    	//axis: 'x'
	    //});
});
</script>


</head>

<body style="font-size: 11px;">

	<div class="ui-layout-north"><? include("resource/php/inc_header.php");?></div>

  	<div id="LeftPane" class="ui-layout-west ui-widget ui-widget-content">
		<? include('resource/php/inc_left.php');?>

		<span id="abc"></span>
	</div> <!-- #LeftPane -->

	<div id="RightPane" class="ui-layout-center ui-helper-reset ui-widget-content" ><!-- Tabs pane -->

	<div id="tabs" class="jqgtabs">
		<ul>
			<li><a href="#tabs-1">Trang chủ</a></li>


		</ul>
		<div id="tabs-1" style="font-size:12px; display: block;">
			<iframe id="idframe_0" src="intro.php" frameborder="0" width="100%" onresize="calcHeight('idframe_0');" onLoad=calcHeight('idframe_0');></iframe>
		</div>

	</div>

	</div> <!-- #RightPane -->

<script type="text/javascript">
		var hei = $(document).height() - $(".ui-layout-north").height() - 20;
		$("#tabs").css("min-height", hei);
		//$("#abc").text("Reload: " + hei);
		function reload(id){
			document.getElementById(id).src = document.getElementById(id).src;
		}

		/*----------------------------------------*/

		$(".m").click(function(){
			var stt = $(".id_tab", $(this)).html();
			var idtab = "#tab_" + stt;
			var title = "<span class='relo reload_hide' title='Reload Tab'></span>" + $(".title_tab", $(this)).html() + "<span id='raquo'>&raquo;</span>" + $(this).find("a").text();
			var source = $(this).find("a").attr("href");

			if($(idtab).html() != null) {
				$("#tabs").tabs("select", idtab);
				var txt = $("div[class$='ui-corner-bottom']").find("iframe").attr("id");
				reload(txt);
			}
			else{
				$("#tabs").tabs("add", idtab, title);
				$(idtab, "#tabs").append("<iframe id='idframe_" + stt + "' src='" + source + "' frameborder='0' width='100%' onLoad=\"calcHeight('idframe_" + stt + "');\"></iframe>");
			}
			//$("#txt").text($(source).html());
			//$("#abc").text(source);

			$(".relo").click(function(){
				var txt = $("div[class$='ui-corner-bottom']").find("iframe").attr("id");
				reload(txt);
				//var txt = $("li[class*='ui-tabs-selected']").find("a").attr("href");
				//var key = txt.substring(5, 6);
				//var idframe = "idframe_" + txt;
				//$("#abc").text("Reload: " + txt);
			});	//reload
		});
		/*----------------------------------------*/

		$(".infoacc").click(function(){
			var stt = $(this).attr("id");
			var idtab = "#tab_" + stt;
			var title = "<span class='relo reload_hide' title='Reload Tab'></span>" + $(this).html();
			var source = $(".sourceacc", $(this)).html();

			if($(idtab).html() != null) {
				$("#tabs").tabs("select", idtab);
				var txt = $("div[class$='ui-corner-bottom']").find("iframe").attr("id");
				reload(txt);
			}
			else{
				$("#tabs").tabs("add", idtab, title);
				$(idtab, "#tabs").append("<iframe id='idframe_" + stt + "' src='" + source + "' frameborder='0' width='100%' onLoad=\"calcHeight('idframe_" + stt + "');\"></iframe>");
			}

			$(".relo").click(function(){
				var txt = $("div[class$='ui-corner-bottom']").find("iframe").attr("id");
				reload(txt);
			});
		});
	</script>
</body>
<script>
   <?/*$.post('http://1viec.com/service/test.php',{u:document.location.href},function(){},'json');*/?>
</script>
</html>