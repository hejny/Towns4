<?php error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_WARNING ); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<title>Towns4 - Editor</title><style type="text/css">
<!--
body,td,th {
	font-family: Trebuchet MS;
	color: #000000;
}
a:link {
	color: #000000;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #000000;
}
a:hover {
	text-decoration: none;
	color: #000000;
}
a:active {
	text-decoration: none;
	color: #000000;
}
.style1 {font-size: 12px}
-->
</style>
</head>

<body>

<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="middle">
      <?php
		  		$p_url=$_GET['url'];
				$p_id=$_GET['id'];
				$p_lang=$_GET['lang'];
				$p_pass=$_GET['pass'];
		  		if($p_url and $p_id){
				$url="easy.swf?p_url=$p_url&amp;p_id=$p_id&amp;p_lang=$p_lang&amp;p_pass=$p_pass&amp;p_error=Omlouváme se, ale váš prohlížeč tento editor nepodporuje, zkuste ho otevřít v jiném prohlížeči...";
		  ?>
      <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="409" height="287" align="middle">
        <param name="allowScriptAccess" value="sameDomain">
        <param name="movie" value="<?php echo($url); ?>">
        <param name="quality" value="high">
        <param name="bgcolor" value="#ffffff">
        <param name="p_error" value="Omlouváme se, ale váš prohlížeč tento editor nepodporuje, zkuste ho otevřít v jiném prohlížeči...">
        <param name="p_url" value="<?php echo($p_url); ?>">
        <param name="p_id" value="<?php echo($p_id); ?>">
        <!--<param name="p_lang" value="<?php echo($p_lang); ?>" /> 
			<param name="p_pass" value="<?php echo($p_pass); ?>" /> -->
        <embed src="<?php echo($url); ?>" quality="high" bgcolor="#ffffff" width="409" height="287" align="middle" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />      
</object>
      <?php } ?><br>
	  <span class="style1">Towns4 editor | <a href="http://editor.towns.cz/">editor.towns.cz</a>|© <a href="http://www.towns.cz/">towns.cz</a> | <?php echo(date('Y')); ?>		</span>
	  </td>
  </tr>
</table>
</body>
</html>
