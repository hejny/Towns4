<?php
/* Towns4, www.towns.cz 
   © Pavol Hejný | 2011-2015
   _____________________________

   core/page/help.php

   Tento soubor slouží k zobrazování speciálních menu.
*/
//==============================


if(!$GLOBALS['menuid'])
$GLOBALS['menuid']=$_GET['menuid'];


if($GLOBALS['menuid']=='menu_chat'){
	eval(subpage("chat"));
}


if($GLOBALS['menuid']=='menu_towns') {


}



//----------------------------------------------------------------------------------------------------------------------Typy obsahu

if($GLOBALS['menuid']=='menu_types'){

    tfont(lr('menu_types'),17,'999999');
    br();

    ahref(tfontr(lr('title_apps_control'),17),'e=content;ee=apps-control');




}

//----------------------------------------------------------------------------------------------------------------------Komunikace

if($GLOBALS['menuid']=='menu_text'){

    tfont(lr('menu_text'),17,'999999');
    br();

    ahref(tfontr(lr('messages_unread'),17),'e=content;ee=text-messages;submenu=1');
    br();

    ahref(tfontr(lr('messages_all'),17),'e=content;ee=text-messages;submenu=2');
    br();


    ahref(tfontr(lr('messages_report'),17),'e=content;ee=text-messages;submenu=3');
    br();

    ahref(tfontr(lr('messages_new'),17),'e=content;ee=text-messages;submenu=4');
    br();


    if($GLOBALS['inc']['forum']) {

        ahref(tfontr(lr('title_forum'), 17), $GLOBALS['inc']['forum']);
    }


}
//----------------------------------------------------------------------------------------------------------------------Zobrazit

if($GLOBALS['menuid']=='menu_map'){

    tfont(lr('menu_map'),17,'999999');
    br();

    $url=centerurl($GLOBALS['hl'],$GLOBALS['hl_x'],$GLOBALS['hl_y'],$GLOBALS['hl_ww'],false);
    ahref(tfontr(lr('fx_center'),17),$url);
    br();

    ahref(tfontr(lr('menu_expand'),17),js2("turnmap('expand');"));
    br();

    ahref(tfontr(lr('menu_attack'),17),js2("turnmap('attack');"));
    br();

    ahref(tfontr(lr('menu_saybox'),17),js2('if($(\'.saybox\').css(\'display\')==\'block\'){$(\'.saybox\').css(\'display\',\'none\')}else{$(\'.saybox\').css(\'display\',\'block\')}1'));
    br();

    ahref(tfontr(lr('menu_grid'),17),js2("turnmap('grid');"));


}
//----------------------------------------------------------------------------------------------------------------------Uživatel

if($GLOBALS['menuid']=='menu_user'){

    tfont(lr('menu_user'),17,'999999');
    br();

    ahref(tfontr(lr('profile_user'),17),"e=content;ee=profile;submenu=1;id=".$GLOBALS['ss']['logid']);
    br();

    ahref(tfontr(lr('profile_town'),17),"e=content;ee=profile;submenu=1;id=".$GLOBALS['ss']['useid']);
    br();

    ahref(tfontr(lr('stat'),17),'e=content;ee=profile;submenu=2');
    br();

    ahref(tfontr(lr('title_settings'),17),"e=content;ee=settings;submenu=1");
    br();

    ahref(tfontr(lr('fullscreen'),17),"js=$(document).fullScreen(!$(document).fullScreen());");
    br();

    ahref(tfontr(lr('logout'),17),js2('logout()'));
    br();


}

//----------------------------------------------------------------------------------------------------------------------


?>
