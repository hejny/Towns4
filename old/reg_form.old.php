<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/login/reg_form.php

   Registrační formulář
*/
//==============================
?>
<?php if(defined('countdown') and countdown-time()>0){}else{ ?>

<div style="background:#222222;" ><?php le('register_info1'); ?></div><br/>


<?php

$key=rand(1111111,9999999);
$GLOBALS['ss']['register_key']=$key;
ahref(trr(nbsp.lr('register_ok').nbsp,19,3,'style="background: rgba(30,30,30,0.9);border: 2px solid #222222;border-radius: 4px;"'),"q=register new,$key;login_try=1");br(2);/*[register_username]*/


?>

<div style="background:#222222;" ><?php le('register_info2'); ?></div><br/>

<?php /* ?>
<form id="register" name="register" method="POST" action="<?php url("q=register [register_username];login_try=1"); ?>">
<table width="100%">
<tr>
<td width="10"><b>{register_username}:</b></td>
<td align="left"><input <?php e($disabled); ?> type="input" name="register_username" id="register_username" value="<?php echo($_POST["register_username"]) ?>" size="17"  style="border: 2px solid #000000; background-color: #eeeeee"/></td>
</tr>
<td colspan="2"><input  type="submit" value="{register_ok}" /></td></tr>
</tr></table></form> */ ?>


<?php

/*
if(defined('fb_appid') and defined('fb_secret'))
eval(subpage('login-fb_login'));
?>

<script type="text/javascript">
document.register.register_username.focus();
</script>

<?php */} ?>
