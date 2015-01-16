<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/test/forum.php

   *zatím se nepoužívá* HTML stránka samotného diskusního fóra
*/
//==============================
?><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Towns 4 - fórum</title>
<meta name="author" content="hejny" />
<meta name="keywords" content="" />
<meta name="description" content="" />
<link rel="shortcut icon" href="<?php e(rebase(root.base)); ?>favicon.ico">
<style type="text/css">
<!--
body {
	background-color: #000000;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
body,td,th {
color: #cccccc;
font-size: 14px;
font-family: "trebuchet ms";
}
h1{
font-size: 25px;
}
h3{
font-size: 15px;
}
hr {
border-color: #cccccc;
height: 0.5px;
}
a{color: #cccccc;text-decoration: none;}
-->
</style>

<?php
function script_($script){
    e('<script type="text/javascript" src="'.rebase(url.base.$script).'"></script>');
}
script_('lib/jquery/js/jquery-1.6.2.min.js');
script_('lib/jquery/js/jquery-ui-1.8.16.custom.min.js');
script_('lib/jquery/jquery.fullscreen-min.js');
script_('lib/jquery/jquery.mousewheel.js');
script_('lib/jquery/jquery.scrollbar.js');
?>

<?php
if(defined('analytics')){
?>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?php echo(analytics); ?>']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ?  'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
<?php 
}
?>
</head>
<body>




<?php
eval(subpage('content','text-messages'));
//require(root.core."/text/messages.php");
?>

</body>
</html>
