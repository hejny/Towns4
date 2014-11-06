<h3>E-mail novinky</h3>
<?php
$array=sql_array("SELECT post_title,post_content,guid,post_name FROM `".sql($GLOBALS['inc']['wp_posts'])."` WHERE `post_type` = 'post' AND `post_status` = 'publish' AND `post_password` = '' ORDER BY `ID` DESC LIMIT 1");
list($post_title,$post_content,$guid,$post_name)=$array[0];

//echo('Posílaný příspěvek: <a href="'.$guid.'" target="_blank">'.$post_title.'</a>');



$array=sql_array("SELECT `id`,`name`,`t`,`profile` FROM `".mpx."objects` WHERE `type`='user' ORDER BY id");



   
$i=0;
$mails=array();
foreach($array as $row){
   list($id,$name,$t,$profile)=$row;
   $profile=new vals($profile);
   $profile=$profile->vals2list();
   $mail=$profile['mail'];
   $sendmail=$profile['sendmail'];
	if($mail and $mail!='@' and $sendmail){
	   if($mail and $mail!='@'){
		if($sendmail){
			//e($mail);
			$mails[]=$mail;	
		}
	   }else{
		$mail='';
	   }
	}  
}



$from='Towns.cz <ph@towns.cz>';
//$from='ph@towns.cz';
$subject=/*'Towns.cz - '.*/$post_title;
$ssid=sql_1data('SELECT townssessid FROM [mpx]log WHERE logid='.$mailmail_);

//success('Sending to '.$to);
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
  <div align="left"><?php e(nbsp3); ?><span style="font-size:19px;color:#cccccc;">Towns - M&#283;sta </span><br/><?php e(nbsp3); ?><span style="font-size:17px;color:#cccccc;"><?php e($post_title); ?></span> </div>
  </td>
  </tr>
  </table>

  
  </td>
  </tr>
  <tr>
  <td align="center"><div style="width:500px;">
	
	
<span style="color:#cccccc;">
  <div align="justify"><div style="width:100%;background: rgba(0,0,0,0.44);border: 2px solid rgba(50,50,50,0.44);padding: 3px;" ><span class="style5">
  <?php
	
	$post_content=substr2($post_content,'width="','"',0, '100%');
	$post_content=substr2($post_content,'width="100%"','>',0, ' ');
	e(nl2br($post_content));
  ?>
  </span></div></div>
  <div align="justify"><div style="width:100%;background: rgba(0,0,0,0.44);border: 2px solid rgba(100,30,50,0.44);padding: 3px;" ><span class="style5">
  Pokud máte připomínky nebo nějaký dotaz, můžete na tento mail odpovědět nebo nám napsat komentář na:<br/>
  <a href="<?php e($guid); ?>" target="_blank"><span style="color:#cccccc;"><?php e($guid); ?></span></a>
  </span></div></div>

</span>	
	
  </td>
  </tr>
  <tr>
  <td colspan="2" align="center"><a href="http://www.towns.cz/world1/?ref=mail"  ><span style="text-decoration:none;"><br />
    <img  src="http://www.towns.cz/tmp/world1/word/14/175/222195.png" width="90" height="21" alt="Začít hrát" style="background: rgba(30,30,30,0.9);border: 3px solid #222222;border-radius: 2px;"/></span></a></td>
  </tr>
  </table>

</span>

  <br/>
  </span> </td>
  </tr>
  </table>
</td></tr>
<tr><td align="center" valign="top"><div align="center"><br />
<span style="width:100%;text-align:center;color:#000000;">Tento e-mail byl odeslán na základě Vaší registrace na Towns.cz. Pokud si už nepřejete dostávat maily, změnte své nastavení v Towns nebo odešlete odpověd s textem unsubscribe.
<br/></span></div></td>
</tr>
</table>


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
    
   //e(textbr('FROM:'.nbsp).$from);br();
   textb('TO:');br();
   e(implode(', ',$mails));br();
   e(textbr('SUBJECT:'.nbsp).$subject);br();
   textb('TEXT:');br();
   e($message);


//--------------




?>



