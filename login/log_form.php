<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/login/log_form.php

   Přihlašovací formulář
*/
//==============================
?>
<form id="login" name="login" method="POST" action="<?php url("q=login [login_username],towns,[login_password];login_try=1"); ?>">
<table width="100%">
<tr>
<td width="10"><b><?php le('login_username'); ?>:</b></td>
<td align="left"><input type="input" name="login_username" id="login_username" value="<?php echo($_POST["login_username"]) ?>"  style="<?php echo(mobile?'font-size:18px;':''); ?>width:150px;border: 2px solid #000000; background-color: #999999"/></td>
</tr><tr>
<td><b><?php le('login_password'); ?>:</b></td><td align="left"><input  type="password" name="login_password"  id="login_password" value=""   style="<?php echo(mobile?'font-size:18px;':''); ?>width:150px;border: 2px solid #000000; background-color: #999999"/></td>
</tr>
<tr>
<?php /* <td colspan="2"><?php input_checkbox("login_permanent",$post["login_permanent"]); ?> {login_permanent}</td></tr> */ ?>
<tr>
<td colspan="2">
<span id="xloading" style="visibility:visible;"><?php tee(nbsp.lr('login_ok').nbsp,15,3,'style="background: rgba(30,30,30,0.9);border: 2px solid #222222;border-radius: 2px;"',NULL,'input'); ?></span>
<span id="loading" style="visibility:hidden;">&nbsp;&nbsp;&nbsp;<?php le('loading'); ?></span>
</td></tr>
</tr>

</table></form>
<script>
$("#login").submit(function() {
    $('#loading').css('visibility','visible');/*$('#xloading').css('visibility','hidden');*/ 
    //alert(1);
    $.post('?e=-html_fullscreen&q=login [login_username],towns,[login_password]&login_try=1&width=' + screen.width + '&height=' + screen.height,
        { login_username: $('#login_username').val(), login_password: $('#login_password').val()/*, login_permanent: $('#login_permanent').val()*/ },
	function(vystup){$('#loading').css('visibility','hidden');/*$('#xloading').css('visibility','visible');*/   $('#html_fullscreen').html(vystup);/**/}
    );
    return(false);
});
</script>




<script type="text/javascript">
document.login.login_username.focus();
</script>
