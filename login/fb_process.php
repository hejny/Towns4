<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný, Přemysl Černý | 2011-2013
   _____________________________

   core/login/fb_process.php

   Přihlašování přes Facebook
*/
//==============================




if($GLOBALS['ss']['fbid']!=-1){
    $fbid=$GLOBALS['ss']['fbid'];
	
	if(!logged()){
	$tmpids=sql_array('SELECT `id` FROM `[mpx]users` WHERE `fbid`=\''.$fbid.'\' AND `aac`=\'1\'');
	if(count($tmpids)==0){
		//echo('createuser');
		//$GLOBALS['fb_createuser']=$fbid;


		//$key=rand(1111111,9999999);
		//$GLOBALS['ss']['register_key']=$key;
		a_register('new','','','','',$GLOBALS['user_profile']);
                
		
	}elseif(count($tmpids)>=1){
		$tmpid=$tmpids[0][0];
		xquery('login',$tmpid,'facebook',$fbid);
	}/*else{
		/*$tmpid=$tmpids[0][0];
		xquery('login',$tmpid,'facebook',$fbid);* /


		$GLOBALS['ss']['fb_select_ids']=array();
		$GLOBALS['ss']['fb_select_key']=$fbid;
		foreach($tmpids as $tmpid){
			$tmpid=$tmpid[0];
			$GLOBALS['ss']['fb_select_ids'][count($GLOBALS['ss']['fb_select_ids'])]=$tmpid;
			//echo($tmpid);
		}
	}*/
	}else{
            a_register('','','','','',$GLOBALS['user_profile']);
		//sql_query("INSERT INTO `[mpx]login` (`id`,`method`,`key`,`text`,`time_create`,`time_change`,`time_use`) VALUES ('".(logid)."','facebook','".$fbid."','".serialize($GLOBALS['user_profile'])."','".time()."','".time()."','".time()."')");
	}
	
}else{
    xerror(lr('f_login_nofblogin'));
    //$GLOBALS['ss']["query_output"]->add("error","{f_login_nologin}");
}
$GLOBALS['ss']['fbid']=false;
?>

