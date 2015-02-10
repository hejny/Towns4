<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/login/login.php

   Úvodní přihlašovací obrazovka - mobile
*/
//==============================


/*
*/
?>


<div style="position:absolute;top:<?php e(round($GLOBALS['screenwidth']/1.8)); ?>px;left:0px;width:100%;height:100%;text-align:center;z-index:5;">
<?php
	//tee('hoooo'.$GLOBALS['screenheight']);
	tee('TOWNS.CZ',17,1);
	br2(10);
	tee(lr('welcome'),13,1);

    

	br();


	if($GLOBALS['url_param']!='fbonly'){

		xreport();
		//------------------------------------------------------

		if(!$_POST['login_username']){hydepark('log');}
		br();
		e('<table width="100%"><tr><td align="center"><table width="10%"><tr><td align="center">');
		?>
		<?php eval(subpage('login-log_form')); ?>
		<?php
		e('</td></tr></table></td></tr></table>');
		if(!$_POST['login_username'])ihydepark('log');

		//------------------------------------------------------

		if(!$_POST['login_username']){hydepark('reg');}
		br();
		e('<table width="100%"><tr><td align="center"><table width="50%"><tr><td align="center">');
		e('<div style="width:100%;background: rgb(10,10,10);border: 3px solid rgba(30,150,250,0.9);padding: 3px;">');
		?>
		<?php eval(subpage('login-reg_form')); ?>
		<?php
		e('</div></td></tr></table></td></tr></table>');
		if(!$_POST['login_username'])ihydepark('reg');
		//--------------------------------------------------------
		/*if(true){hydepark('reg');}else{br();}
		br();
		?>
		<div style="position:absolute;z-index:1000000;">
		<div id="lshp_reg" style="position:relative; left:-73px; top:-250px; width:100%;background: rgb(10,10,10);border: 3px solid rgba(30,150,250,0.9);padding: 3px;">
		
		<?php eval(subpage('login-reg_form')); ?>
		
		<?php if(!$GLOBALS['mobile']){e("<script>$('#lshp_reg').draggable();</script>");} ?>
		
		
		
		</div></div>
		<?php
		ihydepark('reg');*/

		//------------------------------------------------------

	}
		/*ahref(trr(lr('register_ok'),16,3,'style="background: rgba(30,30,30,0.9);border: 3px solid #222222;border-radius: 2px;"'),/*"e=-html_fullscreen;q=register new,$key;login_try=1"* /js2('register()'));

		//e(nbsp3.nbsp3);
		e(nbsp);

		bhp(trr(lr('login'),16,3,'style="background: rgba(30,30,30,0.9);border: 3px solid #222222;border-radius: 2px;"'));


	}

	//br();




	if(!android){
		if(defined('fb_appid') and defined('fb_secret'))
		eval(subpage('login-fb_login'));
	}*/



	eval(subpage('login-buttons'));


	?>
	<div style="background:#442222;" ><?php le('info_mobile'); ?></div>
	<?php


    //br();
    e('<table width="100%"><tr><td align="center"><table width="93%"><tr><td align="center">');
    $GLOBALS['ss']["helppage"]='about';
    $GLOBALS['nowidth']=true;
    eval(subpage('help'));
    e('</td></tr></table></td></tr></table>');
    //br(2);

	



	//print_r($GLOBALS['mobile']);
	//print_r($GLOBALS['android']);
	?>




	<?php if(mobile and !android){
	br(2);

	icon($url="js=$(document).fullScreen(!$(document).fullScreen());","fullscreen",lr('fullscreen'),20);
	e(nbsp2);ahref(trr(lr('fullscreen'),14,1),$url);
	/*br();imge('design/blank.png','',$spacesize,$spacesize);*/br();
	e('<a href="?mobile=0">'.lr('nomobile').'</s>');

	}


	br();
	eval(subpage("copy"));
	?>
</div>






<div style="position:absolute;top:0px;left:0px;width:100%;height:140%;z-index:3;">
<?php
//e('design/wallm.png');br();
//imge('design/wallm.png','',100,100);
imge('design/wallm.png','','100%');
br();
imge('design/black.png','','100%','100%');
?>
</div>
<?php /**/ ?>



<div style="position:relative;top:0px;left:0px;width:100%;height:100%;z-index:2;">
<?php
    //$GLOBALS['mapzoom']=2;
    eval(subpage("map"));
?>


</div>







