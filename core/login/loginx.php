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
    
     * 
     * 
     * <td width="0" height="0" valign="middle"><span style="font-size:14px;" >&nbsp;&nbsp;<?php imge('logo/tax.png','',190); ?><br/>&nbsp;&nbsp;<?php tee(lr('towns_towns'),19); ?><br/>&nbsp;&nbsp;<?php tee(lr('welcome'),13); ?></span>

    */
    ?>
    
    
    
    <td width="45" height="45" valign="middle"><?php imge('logo/logo.png','',73); ?></td>
    <td width="0" height="0" valign="bottom"><span style="font-size:14px;" >&nbsp;&nbsp;&nbsp;&nbsp;<?php tee(lr('towns_towns'),19); ?><br/>&nbsp;&nbsp;<?php tee(lr('welcome'),13); ?></span>




    <!--<td width="0" height="0" valign="middle"  colspan="2"><?php br();imge('logo/tax.png','',310);  ?></span>-->

</td>
 </tr> 


<tr><td colspan="2"><div style="width:300px;">
<?php
//br();
xreport();


if ($GLOBALS['isie']/** or 1/**/) {
?>
<div style="background:#442222;" ><?php le('info_ie'); ?></div>
<?php }


if ($GLOBALS['mobilex']/** or 1/**/) {
?>
<div style="background:#442222;" ><?php le('info_mobile'); ?></div>
<?php }


    $GLOBALS['ss']["helppage"]='about';
    $GLOBALS['nowidth']=true;
    eval(subpage('help'));
    br(1);
?></div></td></tr>


<tr><td colspan="2" align="center">


<?php


//======================================================LOG
    hydepark('log');
    //br();
    ?>
    <div style="position:absolute;z-index:1000000;">
    <div id="lshp_log" style="position:relative; left:33px; top:-200px; width:230px;background: rgb(10,10,10);border: 3px solid rgba(30,150,250,0.9);padding: 3px;">
    
    <?php
		moveby(ahrefr(imgr('icons/cancel.png','{close}',20,20),js2('$(\'#hydepark_log\').css(\'display\',\'none\');')),95,2);
		eval(subpage('login-log_form'));

	?>
    
    <?php if(!$GLOBALS['mobilex']){e("<script>$('#lshp_log').draggable({distance: 10});</script>");} ?>
    
    
    
    </div></div>
    <?php
    ihydepark('log');
	if($_POST['login_username'])js('$(\'#hydepark_log\').css(\'display\',\'block\');');
    //======================================================REG
    hydepark('reg');
    //br();
    ?>
    <div style="position:absolute;z-index:1000000;">
    <div id="lshp_reg" style="position:relative; left:20px; top:-305px; width:300px;background: rgb(10,10,10);border: 3px solid rgba(30,150,250,0.9);padding: 3px;">
    
    <?php
		moveby(ahrefr(imgr('icons/cancel.png','{close}',20,20),js2('$(\'#hydepark_reg\').css(\'display\',\'none\');')),130,2);
		eval(subpage('login-reg_form'));
	?>
    
    <?php if(!$GLOBALS['mobilex']){e("<script>$('#lshp_reg').draggable({distance: 10});</script>");} ?>
    
    
    
    </div></div>
    <?php
    ihydepark('reg');
	if($_GET['register_try'])js('$(\'#hydepark_reg\').css(\'display\',\'block\');');
	//======================================================





//eval(subpage('login-buttons'));



?>




</td></tr>

<!--<tr><td>
<?php
/*eval(subpage('fblike'));
e(nbsp3);
eval(subpage('langcontrol'));*/

?>
</td></tr>-->


</table>

<br/>












