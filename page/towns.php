<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/page/towns.php

   horní menu
*/
//==============================
//e(contentlang('{a}'));

if(!onlymap){


$iconsize=30;
$border0=array(2,'999CFE');//logo
$border1=array(2,'699CFE');//profiles
$border2=array(2,'998822');//gold
$border3=array(2,'665544');//messages
$border4=array(2,'444444');//surkey

if(!$GLOBALS['mobile']){
$style=' style="position:relative;top:-2px;left:-2px;background: rgba(30,30,30,0.9);border: 2px solid #222222;border-radius: 2px;"';
}else{
$style=' style="position:relative;top:-2px;left:-2px;background: rgba(20,10,30,0.93);border: 2px solid #222222;border-radius: 2px;"';
}
?>

<table height="<?php e($iconsize); ?>" width="100%" border="0" cellpadding="0" cellspacing="0" >
<tr><td align="center">


<table height="<?php e($iconsize); ?>" <?php e($GLOBALS['mobile']?'width="104%"':''); ?> border="0" cellpadding="0" cellspacing="0" <?php e($style); ?>>
<tr><td valign="middle" align="center">



<?php
//<span style="display:block;" id="mine">&nbsp;</span>

//e($GLOBALS['hl'].','.$GLOBALS['hl_x'].','.$GLOBALS['hl_y'].','.$GLOBALS['hl_ww']);

/*js('alert("'.$GLOBALS['ss']["log_object"]->set->val('map_xc').'")');
if(!$GLOBALS['ss']["log_object"]->set->val('map_xc')){
	js('alert("123")');
	$url=centerurl($GLOBALS['hl'],$GLOBALS['hl_x'],$GLOBALS['hl_y'],$GLOBALS['hl_ww'],true);	
	click($url,-1);
}*/

//UZ NE//$url=centerurl($GLOBALS['hl'],$GLOBALS['hl_x'],$GLOBALS['hl_y'],$GLOBALS['hl_ww'],false);






//e($GLOBALS['screenwidth']);
if($GLOBALS['mobile']){
	
	/*ahref(imgr('logo/share.png','{towns}','18'),'e=content;ee=help;page=copy');
	e(nbsp2);
	//e('</td><td valign="middle">');
	imge('design/blank.png','',1,4);
	ahref(trr('Towns',14,1),'e=content;ee=mobilemenu');
	e('</td><td valign="middle">');*/

	
}else{


	//border(ahrefr(imgr('logo/share.png',lr('towns'),$iconsize),/*"e=content;ee=plus-index"*/'e=content;ee=help;page=copy'),$border1,$iconsize);
	//e(nbsp2);
	e('</td><td valign="middle" align="right">');
	
	////stare logoimge('design/blank.png','',1,4);br();
	//stare logo//tee('Towns',17,1);
	
	//e(nbsp2);
	ahref(imgr('logo/tax.png','',false,32),'e=content;ee=help;page=copy');
	//e(nbsp);
	
	//moveby(imgr('logo/50.png','',40)/*.trr('Towns',16,1)*/,0,-5);
	//imge('design/blank.png','',50,2/*$iconsize*/);
	//e($space);
	//e(nbsp3);
	e('</td><td valign="middle">');



$space=nbsp3;

//e($GLOBALS['hl'].','.$GLOBALS['hl_x'].','.$GLOBALS['hl_y'].','.$GLOBALS['hl_ww']);

//UZ NE//border(iconr($url,'fx_center2',lr('fx_center'),$iconsize),$border1,$iconsize);  
//UZ NE//e($space);

//border(iconr("e=content;ee=profile;id=".useid,"profile_town","{profile_town}",$iconsize),$border2,$iconsize);
//e($space3);

//border(iconr("e=content;ee=rating","profile_user",lr('stat'),$iconsize),$border1,$iconsize);
//e($space);

border(iconr("e=content;ee=profile;submenu=2","profile_user",lr('stat'),$iconsize),$border1,$iconsize);
e($space);


//UZ NE//border(iconr('e=content;ee=create-create_master;submenu=3',"create_master_repair",lr('create_master_repair'),$iconsize),$border1,$iconsize);
//UZ NE//e($space);


border(iconr("e=content;ee=plus-index","res_gold",lr('title_plus'),$iconsize),$border2,$iconsize);
e($space);

if($GLOBALS['inc']['forum']){
border(iconr($GLOBALS['inc']['forum'],"help",lr('title_forum'),$iconsize),$border2,$iconsize);
e($space);
}

//$cooldown=-(time()-$GLOBALS['ss']["use_object"]->set->ifnot("automine_time",0)-(600));
//border(iconr("e=mine;ee=attack-automine;ref=towns","fx_automine","{automine}",$iconsize),$border2,$iconsize,NULL,NULL,($cooldown>0)?$cooldown:false);
//e(space);


/*$url="e=content;ee=text-messages;subpage=2;ref=chat;id=".useid;

            ob_start();    
            eval(subpage('chat_aac'));           
            $stream=ob_get_contents();
            ob_end_clean();

ahref($stream,$url);*/
//subref("chat_aac",3);

eval(subpage('chat_aac'));    


e($space);

//border(iconr("e=content;ee=quest-quest","quest","{title_quest}",$iconsize),$border3,$iconsize);
//e(nbsp3);


border(iconr('menu:map',"menu_show",lr('menu_show'),$iconsize),$border3,$iconsize);
//border(iconr(js2('if($(\'#expandarea\').css(\'display\')==\'block\'){$(\'#expandarea\').css(\'display\',\'none\')}else{$(\'#expandarea\').css(\'display\',\'block\')}1'),"expand","{expand}",$iconsize),$border3,$iconsize);
e($space);

//border(iconr("e=content;ee=help;page=index;page=tutorial_x",'help',"{help}",$iconsize),$border3,$iconsize); 
//e(nbsp3);

border(iconr("e=content;ee=settings;submenu=1","settings",lr('title_settings'),$iconsize),$border3,$iconsize);
e($space);


border(iconr("js=$(document).fullScreen(!$(document).fullScreen());","fullscreen",lr('fullscreen'),$iconsize),$border3,$iconsize);
e($space);

border(iconr(js2('logout()'),"logout",lr('logout'),$iconsize),$border3,$iconsize);   


e(nbsp3.nbsp);



}
if($GLOBALS['mobile']){
	e('</td></tr><tr><td valign="middle" align="center">');
}else{
	e('</td><td valign="middle">');
}



eval(subpage('surkey'));



//subref("surkey",3);
   

e(nbsp3);




//e('</td></tr><tr><td valign="middle" align="center" style="position:relative;top:-2px;left:-2px;background: rgba(0,0,0,1);border: 2px solid #222222;border-radius: 2px;">');
//subempty('cache_loading',lr('sdfg'));


?>


</td>
</tr>
</table>

</td>
</tr>
</table>


<?php } ?>
