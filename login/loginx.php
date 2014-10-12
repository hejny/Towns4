<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/login/login.php

   Úvodní přihlašovací obrazovka - vnitrek
*/
//==============================



?>










<!--<div style="width:100%; height: 100%; background-color:rgba(17,17,17,0.7);">-->
<table border="0" cellspacing="0" cellpadding="0">


  <tr height="35">
    
    <?php
    /*
        <!--<td width="0" height="0" valign="middle"  colspan="2"><?php moveby(imgr('logo/logo.png','',55));/*tee(lr('towns_towns'),19);* /imge('logo/tax.png','',300);  ?><br/><?php e(nbspo);tee(lr('welcome'),13); ?></span>-->
    
    <!--<td width="45" height="45" valign="top"><?php imge('logo/logo.png','',50); ?></td>-->
    <!--<td width="0" height="0" align="left" valign="middle"><span style="font-size:14px;" ><?php moveby(imgr('logo/tax.png','',300),0,-45); ?><br/>&nbsp;&nbsp;<?php tee(lr('welcome'),13); ?></span>-->
    
    */
    ?>
    
    
    
    <td width="45" height="45" valign="middle"><?php imge('logo/logo.png','',73); ?></td>
    <td width="0" height="0" valign="middle"><span style="font-size:14px;" >&nbsp;&nbsp;<?php tee(lr('towns_towns'),19); ?><br/>&nbsp;&nbsp;<?php tee(lr('welcome'),13); ?></span>



    <!--<td width="0" height="0" valign="middle"  colspan="2"><?php br();imge('logo/tax.png','',310);  ?></span>-->


<?php




if (ae_detect_ie()) {
?>
<div style="background:#442222;" ><?php le('info_ie'); ?></div>
<?php } ?>


</td>
 </tr> 


<tr><td colspan="2"><div style="width:300px;"><?php

xreport();


    $GLOBALS['ss']["helppage"]='about';
    $GLOBALS['nowidth']=true;
    eval(subpage('help'));
    br(2);
?></div></td></tr>


<tr><td colspan="2" align="center">


<?php

if(!logged()){//e('logged');
    //======================================================LOG
    if($GLOBALS['url_param']!='fbonly' or 1){
    
    	
    	bhp(trr(lr('register_ok'),16,3,'style="background: rgba(30,30,30,0.9);border: 3px solid #222222;border-radius: 2px;"'),/*"e=-html_fullscreen;q=register new,$key;login_try=1"js2('register()')*/'reg');
    
    	//e(nbsp3.nbsp3);
    	e(nbsp);
    
    	bhp(trr(lr('login'),16,3,'style="background: rgba(30,30,30,0.9);border: 3px solid #222222;border-radius: 2px;"'),'log');
    
    
    }
    
    //br();
    if(defined('fb_appid') and defined('fb_secret'))
    eval(subpage('login-fb_login'));
    
    
    //======================================================LOG
    if(!$_POST['login_username']){hydepark('log');}else{br();}
    br();
    ?>
    <div style="position:absolute;z-index:1000000;">
    <div id="lshp_log" style="position:relative; left:33px; top:-200px; width:100%;background: rgb(10,10,10);border: 3px solid rgba(30,150,250,0.9);padding: 3px;">
    
    <?php eval(subpage('login-log_form')); ?>
    
    <?php if(!$GLOBALS['mobile']){e("<script>$('#lshp_log').draggable();</script>");} ?>
    
    
    
    </div></div>
    <?php
    if(!$_POST['login_username'])ihydepark('log');
    //======================================================REG
    if(true){hydepark('reg');}else{br();}
    br();
    ?>
    <div style="position:absolute;z-index:1000000;">
    <div id="lshp_reg" style="position:relative; left:-73px; top:-250px; width:100%;background: rgb(10,10,10);border: 3px solid rgba(30,150,250,0.9);padding: 3px;">
    
    <?php eval(subpage('login-reg_form')); ?>
    
    <?php if(!$GLOBALS['mobile']){e("<script>$('#lshp_reg').draggable();</script>");} ?>
    
    
    
    </div></div>
    <?php
    ihydepark('reg');
    //======================================================
}else{//e('nologged');
    //======================================================Už je uživatel přihlášený
    
    ahref(trr(lr('logout_ok'),16,3,'style="background: rgba(30,30,30,0.9);border: 3px solid #222222;border-radius: 2px;"'),js2('logout();'));
    e(nbsp);
    ahref(trr(lr('continue_ok',short(id2name($GLOBALS['ss']['logid']),9)),16,3,'style="background: rgba(30,30,30,0.9);border: 3px solid #222222;border-radius: 2px;"'),js2('reloc();'));
    
    //======================================================
}


?>




<?php if($GLOBALS['mobile']){
br(2);

icon($url="js=$(document).fullScreen(!$(document).fullScreen());","fullscreen",lr('fullscreen'),20);
e(nbsp2);ahref(trr(lr('fullscreen'),14,1),$url);
/*br();imge('design/blank.png','',$spacesize,$spacesize);*/br();
e('<a href="?mobile=0">'.lr('nomobile').'</s>');

} ?>

</td></tr>

<!--<tr><td>
<?php
eval(subpage('fblike'));
e(nbsp3);
eval(subpage('langcontrol'));

?>
</td></tr>-->


</table>

<br/>












