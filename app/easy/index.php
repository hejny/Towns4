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
		  		$url=$_GET['url'];
				$id=$_GET['id'];
				$lang=$_GET['lang'];
				$token=$_GET['token'];
		  		if($p_url and $p_id){
				$src="easy.swf?url=$url&amp;id=$id&amp;lang=$lang&amp;token=$token";
		  ?>
      <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="409" height="287" align="middle">
        <param name="allowScriptAccess" value="sameDomain">
        <param name="movie" value="<?php echo($url); ?>">
        <param name="quality" value="high">
        <param name="bgcolor" value="#ffffff">
        <param name="url" value="<?php echo($url); ?>">
        <param name="id" value="<?php echo($id); ?>">
        <param name="lang" value="<?php echo($lang); ?>" /> 
		<param name="token" value="<?php echo($token); ?>" />

        <embed src="<?php echo($src); ?>" quality="high" bgcolor="#ffffff" width="409" height="287" align="middle" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />

Omlouváme se, ale váš prohlížeč tento editor nepodporuje, zkuste ho otevřít v jiném prohlížeči...    
</object>
      <?php } ?><br>
	  <span class="style1">Towns4 editor | <a href="http://editor.towns.cz/">editor.towns.cz</a>|© <a href="http://www.towns.cz/">towns.cz</a> | <?php echo(date('Y')); ?>		</span>
	  </td>
  </tr>
</table>
</body>
</html>
