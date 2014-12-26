<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/login/buttons.php

   Úvodní přihlašovací obrazovka - tlačítka
*/
//==============================


if(!logged()){//e('logged');
    //======================================================LOG
    if($GLOBALS['url_param']!='fbonly' or 1){
    
    	
    	bhp(trr(lr('register_ok'),16,3,'style="background: rgba(30,30,30,0.9);border: 3px solid #222222;border-radius: 2px;"'),/*"e=-html_fullscreen;q=register new,$key;login_try=1"js2('register()')*/'reg');
    
    	//e(nbsp3.nbsp3);
    	e(nbsp);
    
    	bhp(trr(lr('login'),16,3,'style="background: rgba(30,30,30,0.9);border: 3px solid #222222;border-radius: 2px;"'),'log');
    
    
    }
    
    //br();

	if(!android){
		if(defined('fb_appid') and defined('fb_secret')){
			eval(subpage('login-fb_login'));
		}
    }
    
    //======================================================
}else{//e('nologged');
    //======================================================Už je uživatel přihlášený
    
    ahref(trr(lr('logout_ok'),16,3,'style="background: rgba(30,30,30,0.9);border: 3px solid #222222;border-radius: 2px;"'),js2('logout();'));
    e(nbsp);
    ahref(trr(lr('continue_ok',short(id2name($GLOBALS['ss']['logid']),9)),16,3,'style="background: rgba(30,30,30,0.9);border: 3px solid #222222;border-radius: 2px;"'),js2('reloc();'));
    
    //======================================================
}

?>


