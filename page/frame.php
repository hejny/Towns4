<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/frame.php

   towns se zobrazí v iframe
*/
//==============================
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="<?php if($GLOBALS['ss']['lang']=='cz'){e('cs');}else{e($GLOBALS['ss']['lang']);}?>">

<head>


<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Towns 4</title>
<meta name="author" content="hejny" />

<meta property="og:image" content="<?php e(imageurl('logo/share.png')); ?>" />
<link rel="shortcut icon" href="../favicon.ico">

<link href="favicon.ico" rel="../favicon.ico" type="image/x-icon" />

<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0"/>
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


</head>
<body>
    <div style="width:100%;height:100%;overflow: hidden;">
    <iframe width="100%" height="100%" frameborder="0" scrolling="auto" style="overflow: auto;" src="<?php e(url.corexx); ?>"></iframe>
    </div>

</body>
</html>

