<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/page/mailx.php

   mailx
*/
//==============================

ob_end_clean();
ignore_user_abort(true);
ob_start();
header("Connection: close");
header("Content-Length: " . ob_get_length());
ob_end_flush();
flush();


//---------------

$subject=$_GET['subject'];
/*$subject=trim($subject);
if($subject=='-')$subject='';
if()*/

$from='Towns.cz <ph@towns.cz>';
$to      = $_GET['to'];//'hejny.pavel@gmail.com';
$subject = 'Towns.cz - '.$subject;//'the subject';
$body1 = $_GET['body1'];//'hello';
$body2 = $_GET['body2'];
$body3 = $_GET['body3'];
$body4 = $_GET['body4'];

    $headers  = "From: $from\r\n"; 
    $headers .= "Content-type: text/html\r\n"; 




$ww=400;

    ob_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0"/>
<title><?php e($subject); ?></title>
<style type="text/css">
<!--
body {
font-family: trebuchet ms;
background-color: #ffffff;
background-repeat: no-repeat;
margin-left: 0px;
margin-top: 0px;
margin-right: 0px;
margin-bottom: 0px;
}
body,td,th {
color: #CCCCCC;
}
a:link {
color: #CCCCCC;
}
a:visited {
color: #CCCCCC;
}
a:hover {
color: #CCCCCC;
}
a:active {
color: #CCCCCC;
}
a {
font-family: trebuchet ms;
}
.style4 {
font-size: 14px;
color: #000000;
}
.style5 {
background-color: rgba;
font-size: 15px;
}
-->
</style></head>
<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#79B0B1">
<tr>
<td height="1" align="center" valign="middle"></td>
</tr>
<tr>
  <td align="center" valign="top"><br />
  <table width="<?php e($ww); ?>" height="100" border="0" cellpadding="5" cellspacing="0" bgcolor="#111111" style="border: 2px solid #335599;">
  <tr>
  <td align="center" valign="middle" ><span id="login-loginx">
  <!--<div style="width:100%; height: 100%; background-color:rgba(17,17,17,0.7);">-->
  <table border="0" cellspacing="0" cellpadding="0">
  <tr>
  <td align="center" valign="middle">
  
  <table border="0" cellspacing="0" cellpadding="0" width="0" height="0">
  <tr height="35">
  <td width="0" height="0" align="right"valign="middle"><span title=""><img src="http://www.towns.cz/tmp/world1/image/167/50/305090.jpg" border="0" alt="" width="50" /></span></td>
  <td width="0" height="0" align="center" valign="middle">
  <div align="left"><?php e(nbsp3); ?><span style="font-size:19px;"><?php e($subject.nbsp2); ?></span> </div>
  </td>
  </tr>
  </table>
  
  </td>
  </tr>
  <tr>
  <td align="center"><div style="width:<?php e($ww-100); ?>px;">
  
  
  
  <?php
  //----------------------------------
  if($body1){
  ?>
  <div align="justify"><div style="width:100%;background: rgba(0,0,0,0.44);border: 2px solid rgba(100,30,50,0.44);padding: 3px;" ><span class="style5">
  <?php e(xx2x($body1)); ?>
  </span></div></div>
   <?php
  //----------------------------------
  }
  if($body2){
  ?>
  <div align="justify"><div style="width:100%;background: rgba(0,0,0,0.44);border: 2px solid rgba(50,100,30,0.44);padding: 3px;" ><span class="style5">
  <?php e(xx2x($body2)); ?>
  </span></div></div>
    <?php
  //----------------------------------
  }
  if($body3){
  ?>
  <div align="justify"><div style="width:100%;background: rgba(0,0,0,0.44);border: 2px solid rgba(30,50,100,0.44);padding: 3px;" ><span class="style5">
  <?php e(xx2x($body3)); ?>
  </span></div></div>
   <?php
  }
  //----------------------------------
  if($body4){
  ?>
  <div align="justify"><div style="width:100%;background: rgba(0,0,0,0.44);border: 2px solid rgba(50,50,50,0.44);padding: 3px;" ><span class="style5">
  <?php e(xx2x($body4)); ?>
  </span></div></div>
  <?php } ?>
  
  
  
  <!--<div align="justify"><div style="width:100%;background: rgba(0,0,0,0.44);border: 2px solid rgba(100,30,50,0.44);padding: 3px;" ><span class="style5">
  Pokud máte připomínky nebo nějaký dotaz, můžete na tento mail odpovědět-->
  </span></div></div>
  
  
  
  
  
  </td>
  </tr>
  
  <!--
  <tr>
  <td colspan="2" align="center"><a href="http://www.towns.cz/world1/?ref=mail"  ><span style="text-decoration:none;"><br />
    <img  src="http://www.towns.cz/tmp/world1/word/14/175/222195.png" width="90" height="21" alt="Začít hrát" style="background: rgba(30,30,30,0.9);border: 3px solid #222222;border-radius: 2px;"/></span></a></td>
  </tr>
  -->
  
  </table>
  <br/>
  </span> </td>
  </tr>
  </table>
</td></tr>
<tr><td align="center" valign="top"><div align="center"><br />
<span class="style4" style="width:100%;text-align:center;color:#000000;">Tento e-mail byl odeslán na základě Vaší registrace na Towns.cz. Pokud si už nepřejete dostávat maily, změňte své uživatelské nastavení nebo klikněte na:<br/> 
<span style="color:#cccccc;"><a href="<?php e(url.'?unsubscribe='.$to.$ssid); ?>" target="_blank">
<?php e(url.'?unsubscribe='.$to.$ssid); ?>
</a></span><br/></span></div></td>
</tr>
</table>
</body>
</html>

<?php
    $message = ob_get_contents();
    ob_end_clean();   














if($to){

    success('Sending mail:');
    br();
    textb('From: ');
    e(htmlspecialchars($from));br();
    textb('To: ');
    e(htmlspecialchars($to));br();
    textb('Subject: ');
    e(htmlspecialchars($subject));br(2);
    hr();e($message);br();
    //e(nl2br(htmlspecialchars($message)));br();
    
    if(!$_GET['test']){
        mail($to, $subject, $message, $headers);
    }


}

//---------------

exit;
?>