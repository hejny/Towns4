<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2015
   _____________________________

   core/page/help.php

   Tento soubor slouží k zobrazování speciálních menu.
*/
//==============================


if($_GET['menuid']=='menu_chat'){
	eval(subpage("chat"));
}


if($_GET['menuid']=='menu_towns') {

    $iconsize=30;
    $border0=array(2,'999CFE');//logo
    $border1=array(2,'699CFE');//profiles
    $border2=array(2,'998822');//gold
    $border3=array(2,'665544');//messages
    $border4=array(2,'444444');//surkey
    $space=nbsp3;

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





    border(iconr("e=content;ee=settings;submenu=1","settings",lr('title_settings'),$iconsize),$border3,$iconsize);

    e($space);





    border(iconr("js=$(document).fullScreen(!$(document).fullScreen());","fullscreen",lr('fullscreen'),$iconsize),$border3,$iconsize);

    e($space);



    border(iconr(js2('logout()'),"logout",lr('logout'),$iconsize),$border3,$iconsize);

}





if($_GET['menuid']=='menu_map'){

	e('<table border="0" cellpadding="2" cellspacing="0"><tr><td colspan="1">');
	tee(lr('menu_show'),array(13,'999999'));
	e("</td></tr><tr><td>");


	ahref(trr(lr('menu_expand'),12),js2('if($(\'#expandarea\').css(\'display\')==\'block\'){$(\'#expandarea\').css(\'display\',\'none\')}else{$(\'#expandarea\').css(\'display\',\'block\')}1'));

	e("</td></tr><tr><td>");

	ahref(trr(lr('menu_attack'),12),js2('if($(\'#attackarea\').css(\'display\')==\'block\'){$(\'#attackarea\').css(\'display\',\'none\')}else{$(\'#attackarea\').css(\'display\',\'block\')}1'));


	//tee('x',20,3,NULL,'x');
	//e("</td></tr><tr><td>");
	//ahref(trr('{menu_mapbox}',12),js2('if($(\'.mapbox\').css(\'display\')==\'block\'){$(\'.mapbox\').css(\'display\',\'none\')}else{$(\'.mapbox\').css(\'display\',\'block\')}1'));
	
	e("</td></tr><tr><td>");

	ahref(trr(lr('menu_saybox'),12),js2('if($(\'.saybox\').css(\'display\')==\'block\'){$(\'.saybox\').css(\'display\',\'none\')}else{$(\'.saybox\').css(\'display\',\'block\')}1'));
	e("</td></tr><tr><td>");

	ahref(trr(lr('menu_grid'),12),js2('if($(\'#grid\').css(\'display\')==\'block\'){$(\'#grid\').css(\'display\',\'none\')}else{$(\'#grid\').css(\'display\',\'block\')}1'));


	e("</td></tr></table>");
}


?>
