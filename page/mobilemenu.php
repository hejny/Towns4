<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/page/mobilemenu.php

   mobilní hlavní menu
*/
//==============================

if(!mobile){
?>
<script>
setTimeout(function(){
    w_close('content');
},10);
</script>
<?php
}else{





$iconsize=30;
$border0=array(2,'999CFE');//logo
$border1=array(2,'699CFE');//profiles
$border2=array(2,'998822');//gold
$border3=array(2,'665544');//messages
$border4=array(2,'444444');//surkey
$textsize=13;
$spacesize=6;

?>


<?php




contenu_a();

	/*border(ahrefr(imgr('logo/share.png','{towns}',$iconsize),'e=content;ee=help;page=copy'),$border1,$iconsize);
	e(nbsp2);
	e('</td><td valign="middle">');
	imge('design/blank.png','',1,4);br();
	tee('Towns',17,1);
	e(nbsp3);
	e('</td><td valign="middle">');*/



imge('design/blank.png','',$spacesize,$spacesize);br();


if(!android){
	e("<div style=\"text-align:center;\">");
		ahref(imgr('logo/share.png',lr('towns'),'24'),'e=content;ee=help;page=copy');
		e(nbsp2);
		//e('</td><td valign="middle">');
		imge('design/blank.png','',1,4);
		ahref(trr('Towns',14,1),'e=content;ee=help;page=copy');
	e('</div>');

	br();imge('design/blank.png','',$spacesize,$spacesize);br();
}


$url=centerurl($GLOBALS['hl'],$GLOBALS['hl_x'],$GLOBALS['hl_y'],$GLOBALS['hl_ww']);
border(iconr($url,'fx_center2',lr('fx_center'),$iconsize),$border1,$iconsize);  
e(nbsp2);ahref(trr(lr('fx_center'),$textsize,1),$url);
br();imge('design/blank.png','',$spacesize,$spacesize);br();



border(iconr($url="e=content;ee=profile;id=".$GLOBALS['ss']['logid'],"profile_user",lr('profile_user'),$iconsize),$border1,$iconsize);
e(nbsp2);ahref(trr(lr('profile_user'),$textsize,1),$url);
br();imge('design/blank.png','',$spacesize,$spacesize);br();

border(iconr($url="e=content;ee=plus-index","res_gold",lr('title_plus'),$iconsize),$border2,$iconsize);
e(nbsp2);ahref(trr(lr('title_plus'),$textsize,1),$url);
br();imge('design/blank.png','',$spacesize,$spacesize);br();


//$cooldown=-(time()-$GLOBALS['ss']["use_object"]->set->ifnot("automine_time",0)-(600));
//border(iconr("e=mine;ee=attack-automine;ref=towns","fx_automine","{automine}",$iconsize),$border2,$iconsize,NULL,NULL,($cooldown>0)?$cooldown:false);
//e(space);



eval(subpage('chat_aac'));
//subref("chat_aac",3);

$url="e=content;ee=text-messages;ref=chat;id=".$GLOBALS['ss']['useid'];
e(nbsp2);ahref(trr(lr('f_text'),$textsize,1),$url);
br();imge('design/blank.png','',$spacesize,$spacesize);br();



border(iconr($url="e=content;ee=chat","f_text",lr('title_chat'),$iconsize),$border3,$iconsize);
e(nbsp2);ahref(trr(lr('title_chat'),$textsize,1),$url);
br();imge('design/blank.png','',$spacesize,$spacesize);br();



border(iconr($url='menu:map',"menu_show",lr('menu_show'),$iconsize),$border3,$iconsize);
//border(iconr(js2('if($(\'#expandarea\').css(\'display\')==\'block\'){$(\'#expandarea\').css(\'display\',\'none\')}else{$(\'#expandarea\').css(\'display\',\'block\')}1'),"expand","{expand}",$iconsize),$border3,$iconsize);
e(nbsp2);ahref(trr(lr('menu_show'),$textsize,1),$url);
br();imge('design/blank.png','',$spacesize,$spacesize);br();

//border(iconr("e=content;ee=help;page=index;page=tutorial_x",'help',"{help}",$iconsize),$border3,$iconsize); 
//e(nbsp3);

border(iconr($url="e=content;ee=settings;submenu=1","settings",lr('title_settings'),$iconsize),$border3,$iconsize);
e(nbsp2);ahref(trr(lr('title_settings'),$textsize,1),$url);
br();imge('design/blank.png','',$spacesize,$spacesize);br();


if(!android){
border(iconr($url="js=$(document).fullScreen(!$(document).fullScreen());","fullscreen",lr('fullscreen'),$iconsize),$border3,$iconsize);
e(nbsp2);ahref(trr(lr('fullscreen'),$textsize,1),$url);
br();imge('design/blank.png','',$spacesize,$spacesize);br();
}

border(iconr($url=/*"q=logout"*/js2('logout()'),"logout",lr('logout'),$iconsize),$border3,$iconsize);   
e(nbsp2);ahref(trr(lr('logout'),$textsize,1),$url,"logout");


contenu_b(true);



}

?>



