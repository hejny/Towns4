<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný, Přemysl Černý | 2011-2013
   _____________________________

   core/login/fb_select.php

   Přihlašování přes Facebook
*/
//==============================




if(!$GLOBALS['ss']['fb_select_ids'] or !$GLOBALS['ss']['fb_select_key']){
	w_close('login-fb_select');
}else{
	window(lr('fb_select'),100,300);

	infob(lr('fb_select_question'));
	foreach($GLOBALS['ss']['fb_select_ids'] as $tmpid){
		br();
		ahref(id2name($tmpid),'fb_select_id='.$tmpid.';fb_select_key='.$GLOBALS['ss']['fb_select_key'],"none",true);		
	}
	
	$GLOBALS['ss']['fb_select_ids']=false;
    $GLOBALS['ss']['fb_select_key']=false;
}
?>
