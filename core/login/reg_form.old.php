<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/login/log_form.php

   Přihlašovací formulář
*/
//==============================

blue(lr('register_info'));
br();

ahref(trr(lr('register_random'),19,3,'style="background: rgba(30,30,30,0.9);border: 3px solid #222222;border-radius: 2px;"'),js2('register(1,\'\')'));
br();br();
info(lr('register_random_info'));
br();

tee(lr('register_near'),19,3,'style="background: rgba(30,30,30,0.9);border: 3px solid #222222;border-radius: 2px;"');br();br();
info(lr('register_near_info'));
br();
?>
<form id="reg_form" name="reg_form" method="POST" action="<?php url("q=login [login_username],towns,[login_password];login_try=1"); ?>">

<input type="input" name="near" id="near" value="<?php echo($_POST["near"]) ?>"  style="<?php echo(mobile?'font-size:18px;':''); ?>width:150px;border: 2px solid #000000; background-color: #999999"/>


<?php moveby(trr(nbsp.lr('register_near_ok').nbsp,13,3,'style="background: rgba(30,30,30,0.9);border: 2px solid #222222;border-radius: 2px;"',NULL,'input'),0,3); ?>


<span id="loading_reg" style="visibility:hidden;">&nbsp;&nbsp;&nbsp;<?php le('loading'); ?></span>

</form>
<script>
$("#reg_form").submit(function() {

    register(4,$('#near').val());
    return(false);
});
</script>
