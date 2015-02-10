<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný, Přemysl Černý | 2011-2013
   _____________________________

   core/login/login_select.php

   Přihlašování přes Facebook
*/
//==============================




if(!$GLOBALS['ss']['login_select_userid'] or !$GLOBALS['ss']['login_select_ids'] or !$GLOBALS['ss']['login_select_key'] or !$GLOBALS['ss']['login_select_method']){
	w_close('login-login_select');
}else{
	window(lr('login_select'),100,300);

	infob(lr('login_select_question'));
	foreach($GLOBALS['ss']['login_select_ids'] as $tmpid){
		br();
		ahref(id2name($tmpid),'login_select_id='.$tmpid.';login_select_method='.$GLOBALS['ss']['login_select_method'].';login_select_key='.$GLOBALS['ss']['login_select_key'].';login_select_userid='.$GLOBALS['ss']['login_select_userid'],"none",true);		
	}
	
	$GLOBALS['ss']['login_select_ids']=false;
    $GLOBALS['ss']['login_select_key']=false;
    $GLOBALS['ss']['login_select_method']=false;
}
?>
