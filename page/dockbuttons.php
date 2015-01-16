<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/page/dockbuttons.php

   tlačítka
*/
//==============================

//dockbutton(-100,0,12,imgr('design/loading.gif',lr('loading'),25,25).lr('cache_loading')/*,showhide('window_quest-mini'),4,'dockbutton_tutorial'*/);

    if(chat)
    dockbutton(0,-155-(0*185)+5,-12,'{title_chat}',showhide('window_chat'),4);
    dockbutton(0,-155-(1*185)+5+(chat?0:140),-12,'{title_write}',showhide('window_write'),4);
	if(!$GLOBALS['mobile'])
    dockbutton(0,-155-(2*185)+5+(chat?0:140),-12,'{title_tutorial}',showhide('window_quest-mini'),4,'dockbutton_tutorial');


    $url2=centerurl($GLOBALS['hl'],$GLOBALS['hl_x'],$GLOBALS['hl_y'],$GLOBALS['hl_ww'],false);
    
    if(sql_1data('SELECT count(1) FROM [mpx]objects WHERE type=\'building\' AND own='.$GLOBALS['ss']['useid'].' AND attack!=0 ')){
    	$url3=js2('if($(\'#attackarea\').css(\'display\')==\'block\'){$(\'#attackarea\').css(\'display\',\'none\')}else{$(\'#attackarea\').css(\'display\',\'block\')}1');
	$noattack=0;
    }else{
	$url3=js2("alert('".lr('attack_nomaster')."');");
	$noattack=1;
    }

	if(!$GLOBALS['mobile']){
		if($noattack){

			dockbutton('%',-31,14,
			array('{title_create_building}','{fx_center}'),
			array('e=content;ee=create-create_master;submenu=1',$url2),
			4,
			array('dockbutton_building','dockbutton_center'),
			array(140,160),
			array(-70,90),
			array('rgba(10,10,40,0.9)','rgba(20,10,20,0.9)'),
			array('#222299','#552255')
			);

		}else{

			dockbutton('%',-31,14,
			array('{title_create_building}','{fx_center}','{title_attack_building}'),
			array('e=content;ee=create-create_master;submenu=1',$url2,$url3),
			4,
			array('dockbutton_building','dockbutton_center','dockbutton_attack'),
			array(140,160,140),
			array(-160,0,180),
			array('rgba(10,10,40,0.9)','rgba(20,10,20,0.9)','rgba(40,10,20,0.9)'),
			array('#222299','#552255','#992222')
			);
		}
	}else{
		if($noattack){

			dockbutton('%',-31,14,
			'{title_create_building}',
			'e=content;ee=create-create_master;submenu=1',
			4,
			'dockbutton_building',
			0,
			0,
			'rgba(10,10,40,0.9)',
			'#222299'
			);

		}else{

			dockbutton('%',-31,14,
			array('{title_create_building}','{title_attack_building}'),
			array('e=content;ee=create-create_master;submenu=1',$url3),
			4,
			array('dockbutton_building','dockbutton_attack'),
			array(140,160),
			array(-70,90),
			array('rgba(10,10,40,0.9)','rgba(40,10,20,0.9)'),
			array('#222299','#992222')
			);
		}
	}

    //subref('dockbuttons',1);
    //dockbutton('-400',-31,14,,$url,4,'dockbutton_center',160,'rgba(40,10,20,0.9)','#992222');



