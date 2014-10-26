<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/page/help.php

   Tento soubor slouží k zobrazování speciálních menu.
*/
//==============================


if($_GET['menuid']=='menu_chat'){
	eval(subpage("chat"));
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
