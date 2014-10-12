<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/login/login.php

   Úvodní přihlašovací obrazovka - obal
*/
//==============================




//window('',0,0,'login');
//r($GLOBALS['get']);
/*if($GLOBALS['get']["login_try"]){
    xquery("login",$post["login_username"],$post["login_password"]);
}*/



$topspace1=intval($GLOBALS['screenheight']*0.2);
$topspace2=$GLOBALS['screenheight']-600;
if($topspace2<0){$topspace2=0;}
$topspace=min(array($topspace1,$topspace2));
$topspace=max(array(1,$topspace));

?>
<table height="<?php e(/*$GLOBALS['screenheight']*/0); ?>" width="100%" border="0" cellpadding="0" cellspacing="0">

<tr>
  <td height="<?php echo($topspace); ?>" align="center" valign="middle"><?php
echo(/*"$topspace1,$topspace2,$topspace"*/'');
?>
</td>
</tr>
<tr><td align="center" valign="top">

  <table width="350" height="100" border="0" cellpadding="5" cellspacing="0" style="background: rgba(5,5,5,0.9);border: 2px solid #335599;">



<tr><td align="center" valign="middle" >

<?php
eval(subpage('login-loginx'));
?>

</td>
</tr>



</table>


</td>
</tr>
</table>

