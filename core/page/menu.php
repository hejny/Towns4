<?php
/* Towns4, www.towns.cz 
   © Pavol Hejný | 2011-2015
   _____________________________

   core/page/help.php

   Tento soubor slouží k zobrazování speciálních menu.
*/
//==============================


if($_GET['menuid']=='menu_chat'){
	eval(subpage("chat"));
}


if($_GET['menuid']=='menu_towns') {

    td(trr(lr('menu_towns'),array(13,'999999')));
    tr();


    td(ahrefr(trr(lr('stat'),12),'e=content;ee=profile;submenu=2'));
    tr();

    //UZ NE//border(iconr('e=content;ee=create-create_master;submenu=3',"create_master_repair",lr('create_master_repair'),$iconsize),$border1,$iconsize);
    //UZ NE//e($space);


    td(ahrefr(trr(lr('title_plus'),12),'e=content;ee=plus-index'));
    tr();


    if($GLOBALS['inc']['forum']){

        td(ahrefr(trr(lr('title_forum'),12),$GLOBALS['inc']['forum']));
        tr();

    }


    td(ahrefr(trr(lr('title_settings'),12),'e=content;ee=settings;submenu=1'));
    tr();


    td(ahrefr(trr(lr('fullscreen'),12),'js=$(document).fullScreen(!$(document).fullScreen());'));
    tr();


    td(ahrefr(trr(lr('logout'),12),js2('logout()')));
    tr();
    table(150,'',1,'border:0px;border-spacing:2px;');
}





if($_GET['menuid']=='menu_map'){

	e('<table border="0" cellpadding="2" cellspacing="0"><tr><td colspan="1">');
	tee(lr('menu_show'),array(13,'999999'));
	e("</td></tr><tr><td>");


	ahref(trr(lr('menu_expand'),12),js2("turnmap('expand');"));

	e("</td></tr><tr><td>");

	ahref(trr(lr('menu_attack'),12),js2("turnmap('attack');"));


	//tee('x',20,3,NULL,'x');
	//e("</td></tr><tr><td>");
	//ahref(trr('{menu_mapbox}',12),js2('if($(\'.mapbox\').css(\'display\')==\'block\'){$(\'.mapbox\').css(\'display\',\'none\')}else{$(\'.mapbox\').css(\'display\',\'block\')}1'));
	
	e("</td></tr><tr><td>");

	ahref(trr(lr('menu_saybox'),12),js2('if($(\'.saybox\').css(\'display\')==\'block\'){$(\'.saybox\').css(\'display\',\'none\')}else{$(\'.saybox\').css(\'display\',\'block\')}1'));
	e("</td></tr><tr><td>");

	ahref(trr(lr('menu_grid'),12),js2("turnmap('grid');"));


	e("</td></tr></table>");
}


?>
