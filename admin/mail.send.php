<h3>E-mail novinky</h3>
<?php
$array=sql_array("SELECT post_title,post_content,guid,post_name FROM `wp_posts` WHERE `post_type` = 'post' AND `post_status` = 'publish' AND `post_password` = '' ORDER BY `ID` DESC LIMIT 1");
list($post_title,$post_content,$guid,$post_name)=$array[0];

echo('Posílaný příspěvek: <a href="'.$guid.'" target="_blank">'.$post_title.'</a>');
br();
echo('<h3><a href="?page=mail&amp;action=show">zobrazit</a></h3>');
echo('<h3><a href="?page=mail&amp;action=test">testovat</a></h3>');
echo('<h3>?page=mail&amp;action=test&mail=</h3>');
echo('<h3><a href="?page=mail&amp;action=1">spustit</a></h3>');

$array=sql_array("SELECT `id`,`name`,`t`,`profile` FROM `".mpx."objects` WHERE `type`='user' ORDER BY id");


e('<table width="100%" border="1">');

e('<tr bgcolor="#ffffff">');
e('<td><b>#</b></td>');
e('<td><b>id</b></td>');
e('<td><b>jméno</b></td>');
e('<td><b>mail</b></td>');
e('<td><b>čas</b></td>');
e('<td><b>x</b></td>');
e('</tr>');  
 
   
$i=0;
$mailmail='';
foreach($array as $row){
   list($id,$name,$t,$profile)=$row;


   $profile=new vals($profile);
   $profile=$profile->vals2list();
   $mail=$profile['mail'];
   $sendmail=$profile['sendmail'];
	if($mail and $mail!='@' and $sendmail){
	   if($mail and $mail!='@'){
		if($sendmail)$mail=textbr($mail);
	   }else{
		$mail='';
	   }
	   

			if(($t>time()-(3600*24))){
			    	$bgcolor='eeffee';
			}else{
			    	$bgcolor='ffffff';
			}

	    $i++;
	   e('<tr bgcolor="#'.$bgcolor.'">');
		e('<td>'.$i.'</td>');	   
		e('<td>'.$id.'</td>');
	   e('<td>'.$name.'</td>');
	   e('<td>'.$mail.'</td>');
	   e('<td>'.contentlang(timer($t)).'</td>');

		if($i==$_GET['action']){
		 e('<td><b>x</b></td>');
		  $mailmail_=$id;
		 $mailmail=($profile['mail']);
		}else{
		 e('<td>&nbsp;</td>');
		}

	   e('</tr>');

	}
}
e('</table>');

if($_GET['action']=='test'){
    if($_GET['mail']){
    	$mailmail=$_GET['mail'];
    }else{
    	$mailmail='hejny.pavel@gmail.com';
    }

	$mailmail_=1000077;
}
if($_GET['action']=='show'){
	$mailmail='show';
	$mailmail_=1000077;
}

if($mailmail){
hr();

$from='Towns.cz <ph@towns.cz>';
//$from='ph@towns.cz';
$to=$mailmail;
$subject=/*'Towns.cz - '.*/$post_title;
$ssid=sql_1data('SELECT townssessid FROM [mpx]log WHERE logid='.$mailmail_);

success('Sending to '.$to);
/*textb("FROM: $from");
textb("TO: $to");
textb("Subject: $to");
textb("Text:");
br();
e($text);*/


//---------mail


    //change this to your email. 
    //$to = "ph@towns.cz"; 
    //$from = "m2@maaking.com"; 
    //$subject = "Hello! This is HTML email"; 

    //begin of HTML message 
    
    
    ob_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0"/>
<title>Towns - Vytvo&#345;te si vlastn&iacute; sv&#283;t!</title>
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
  <table width="600" height="100" border="0" cellpadding="5" cellspacing="0" bgcolor="#111111" style="border: 2px solid #335599;">
  <tr>
  <td align="center" valign="middle" ><span id="login-loginx">
  <!--<div style="width:100%; height: 100%; background-color:rgba(17,17,17,0.7);">-->
  <table border="0" cellspacing="0" cellpadding="0">
  <tr>
  <td align="center" valign="middle">
  
  <table border="0" cellspacing="0" cellpadding="0" width="0" height="0">
  <tr height="35">
  <td width="0" height="0" align="right"valign="middle"><span title=""><img src="http://www.towns.cz/tmp/world1/image/167/50/305090.jpg" border="0" alt="" width="73" /></span></td>
  <td width="0" height="0" align="center" valign="middle">
  <div align="left"><?php e(nbsp3); ?><span style="font-size:19px;">Towns - M&#283;sta </span><br/><?php e(nbsp3); ?><span style="font-size:17px;"><?php e($post_title); ?></span> </div>
  </td>
  </tr>
  </table>
  
  </td>
  </tr>
  <tr>
  <td align="center"><div style="width:500px;">
  <div align="justify"><div style="width:100%;background: rgba(0,0,0,0.44);border: 2px solid rgba(50,50,50,0.44);padding: 3px;" ><span class="style5">
  <?php e(nl2br($post_content)); ?>
  </span></div></div>
  <div align="justify"><div style="width:100%;background: rgba(0,0,0,0.44);border: 2px solid rgba(100,30,50,0.44);padding: 3px;" ><span class="style5">
  Pokud máte připomínky nebo nějaký dotaz, můžete na tento mail odpovědět nebo nám napsat komentář na:<br/>
  <a href="<?php e($guid); ?>" target="_blank"><span style="color:#cccccc;"><?php e($guid); ?></span></a>
  </span></div></div>
  </td>
  </tr>
  <tr>
  <td colspan="2" align="center"><a href="http://www.towns.cz/world1/?ref=mail"  ><span style="text-decoration:none;"><br />
    <img  src="http://www.towns.cz/tmp/world1/word/14/175/222195.png" width="90" height="21" alt="Začít hrát" style="background: rgba(30,30,30,0.9);border: 3px solid #222222;border-radius: 2px;"/></span></a></td>
  </tr>
  </table>
  <br/>
  </span> </td>
  </tr>
  </table>
</td></tr>
<tr><td align="center" valign="top"><div align="center"><br />
<span class="style4" style="width:100%;text-align:center;color:#000000;">Tento e-mail byl odeslán na základě Vaší registrace na Towns.cz. Pokud si už nepřejete dostávat maily, klikněte na:<br/> 
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


/*

<span style="width:100%;font-size:19px;text-align:center;"><?php e($post_title); ?></span><br/>
background="http://www.towns.cz/app/mail/img/background.jpg"

$message = '<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>'.$subject.'</title>
</head>
<body>

    <hr>

    <h2>'.$subject.'</h2>

   <p>'.$post_content.'</p>

    <hr>

    <p>Pokud máte připomínky, nebo nějaký dotaz můžete na tento mail odpovědět nebo nám napsat komentář na<br/>
    <a href="'.$guid.'" target="_blank">'.$guid.'</a></p>

    <p>Tento e-mail byl odeslán na základě Vaší registrace na Towns.cz. Pokud si už nepřejete dostávat maily, klikněte na:<br/> 
    <a href="'.url.'?unsubscribe='.$to.$ssid.'" target="_blank">'.url.'?unsubscribe='.$to.$ssid.'</a></p>
    
    
</body>
</html>';*/


   //end of message 
    $headers  = "From: $from\r\n"; 
    $headers .= "Content-type: text/html\r\n"; 

    //options to send to cc+bcc 
    //$headers .= "Cc: [email]maa@p-i-s.cXom[/email]"; 
    //$headers .= "Bcc: [email]email@maaking.cXom[/email]"; 
     
    // now lets send the email. 
    if($to!='show'){
	success('php func mail');
        mail($to, $subject, $message, $headers); 
    }else{
        echo($message);
    }
    //echo "Message has been sent....!"; 

//--------------

}

if($_GET['action'] and $_GET['action']!='test' and $_GET['action']!=$i){
	$url=url."admin?page=mail&action=".($_GET['action']-1+2);
	echo('<script language="javascript">
    window.location.replace("'.$url.'");
    </script>');
	//e('<h2>Znovu posílám za '.timejsr(time()+10,$url).'</h2>');
}
?>



