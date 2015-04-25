<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2015
   _____________________________

   core/login/log_form.php

   Přihlašovací formulář
*/
//==============================

blue(lr('register_infox'));
br();

?>
<form id="reg_form" name="reg_form" method="POST" action="">

<table>
<tr><td><b><?php le("username"); ?>:</b></td><td><?php input_text("register_username",''); ?></td></tr>
<tr><td><b><?php le("password"); ?>:</b></td><td><?php input_pass("register_password",''); ?></td></tr>
<tr><td><b><?php le("email"); ?>:</b></td><td><?php input_text("register_email",'@'); ?></td></tr>
<tr><td colspan="2"><?php input_checkbox("register_sendmail",1); ?><b><?php le("sendmail"); ?></b></td></tr>
<tr><td colspan="2"><?php le("sendmail_info"); ?></b></td></tr>

</table>

<?php
    br();
    tee(nbsp.lr('register_finish').nbsp,13,3,'style="background: rgba(30,30,30,0.9);border: 2px solid #222222;border-radius: 2px;"',NULL,'input')

?>


</form>
<script type="text/javascript">
$("#reg_form").submit(function() {
    //alert(123);
    register($('#register_username').val(),$('#register_password').val(),$('#register_email').val(),($("#register_sendmail").is(':checked') ? 1 : 0));
    //alert(345);
    return(false);
});
</script>
