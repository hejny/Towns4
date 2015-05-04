<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2015
   _____________________________

   core/page/aac.php

   Tlačítko zprávy
*/
//==============================

/*$tmp=urlr($url);
echo($tmp);br(2);*/


    //$GLOBALS['js']=true;
    //echo("alert('aac');");
	if(!logged()){
		e(/*'window.location.replace(\'?q=logout\');'*/'reloc();logged=false;');
	}else{

		//------------------- statistika, playtime
		$tdiff=time()-$GLOBALS['ss']['log_object']->t;
		
		if($tdiff<=60*4){
		    $GLOBALS['ss']['log_object']->pt+=$tdiff;
		}
		
	    $GLOBALS['ss']['log_object']->t=time();




        //------------------- 
/*if(!document.nochatref){;$("#chatscroll").scrollTop(10000);} alert('aac');*/
?>
logged=true;

if(apptime!=<?php e(filemtime(core.'/page/aac.php')); ?>){
	$('#window_topinfox').css('display','block');	
}


apptime=<?php e(filemtime(core.'/page/aac.php')); ?>;

<?php if(chat){
    subjs('chat_text'); ?>
    $('#form_chat').submit(document.chatsubmit);
    
<?php } ?>


<?php

//subjs('dockbuttons',false,false,true);
//-------------------obnovení mapy

    $xc_=$GLOBALS['ss']['use_object']->set->ifnot("map_xc",false);
	$yc_=$GLOBALS['ss']['use_object']->set->ifnot("map_yc",false);
	
    //----
    
    $xcu=0;
    $ycu=0;
    if($GLOBALS['ss']["map_xc"])$xcu=$GLOBALS['ss']["map_xc"];
    if($GLOBALS['ss']["map_yc"])$ycu=$GLOBALS['ss']["map_yc"];
    //echo($xcu.','.$ycu);
    
    $xu=($ycu+$xcu)*5+1;
    $yu=($ycu-$xcu)*5+1;
    //----
    
	//if(!mobile){
	$range="(x-y)>($xu-$yu)-20 AND (x+y)>($xu+$yu)+5 AND (x-y)<($xu-$yu)+35 AND (x+y)<($xu+$yu)+60";
	//}else{
	//$range="(x-y)>($xu-$yu)-20 AND (x+y)>($xu+$yu)+5 AND (x-y)<($xu-$yu)+10 AND (x+y)<($xu+$yu)+50";
	//}
    //----
    
	if($_GET['map_units_time']!=-1){

		if($_GET['map_units_time'])$map_units_time=sql($_GET['map_units_time']);
		
		//mainname()
		$count=sql_1data("SELECT count(1) FROM `[mpx]pos_obj` WHERE (ww=".$GLOBALS['ss']["ww"]." OR ww=-4) AND `type`='building' AND t>$map_units_time AND ".$range);
		if($count){
		    //e('alert(123);');
		    e('map_units_time='.time().';');
		    
				subexec('map_units');		
				subjs('units_stream',$GLOBALS['units_stream']);
				subjs('expandarea',$GLOBALS['area_stream']);
				subjs('attackarea',$GLOBALS['attack_stream']);
				echo('$(\'#units_new\').html("");');
				subjs('dockbuttons');
			
			
		}
	}

//-----------------------------
?>


<?php /*if(!$_GET['onlyc'])*/subjs('surkey');/*$GLOBALS['ss']['use_object']->hold->showjs();*/ ?>
<?php subjs('chat_aac'); ?>


<?php

//------------------------------------------------Při dotzu do API
//---------------------------Obnovení něčeho po provedení api dotazu
if($GLOBALS['ui']['click']){
	url($GLOBALS['ui']['click']);
}
//---------------------------Mapa - jednotky jednotlivě

if($_GET['q'] and !$_GET['onlyc']){
	//e('alert("...");');
	subjs('quest-mini');

	//e('alert(1);');
	//e('alert('.object_id.');');
	if(defined('object_id') or ($GLOBALS['object_ids'] and $GLOBALS['object_ids']!=array())){
		$GLOBALS['map_units_ids']=/*object_id*/$GLOBALS['object_ids'];
		//e('alert('.implode(',',$GLOBALS['object_ids']).');');
		if(/*defined('join_id') or */count($GLOBALS['object_ids'])){
			foreach($GLOBALS['object_ids'] as $id){
				//e('alert('.$id.');');
				echo('$(\'#object'./*object_id*/$id.'\').remove();');
				echo('$(\'#expand'./*object_id*/$id.'\').remove();');
				echo('$(\'#collapse'./*object_id*/$id.'\').remove();');
			}
		}
		//e('alert(1);');
		if(!defined('onlyremove')){
			subexec('map_units');		
			subjs('units_stream',$GLOBALS['units_stream'],true);
			subjs('expandarea',$GLOBALS['area_stream'],true);
			echo('$(\'#units_new\').html("");');
			e('aac_clickset();');
		}
	}

}
?>
            <?php
                /*if(defined('object_hybrid')){
                    e('alert("'.object_hybrid.'");');
                }*/
                if(defined('object_build')){
                    e('build('.$GLOBALS['ss']['master'].','.$GLOBALS['ss']['object_build_id'].',\''.$GLOBALS['ss']['object_build_func'].'\');');
                }
                if(defined('create_error')){
                    e('alert("'.create_error.'");');
                }

		if(defined('object_hybrid')){
		    $tmp=urlr('e=content;ee=create-design;submenu=1;start=1;id='.object_hybrid);
		    if(strpos("x".$tmp,"javascript: ")){$onclick=str_replace("javascript: ","",$tmp);$tmp="";}
		    if($tmp){refresh($url);}
		    if($onclick){e($onclick);}
		    
		}
/*if(!$_GET['onlyc']){
?>
aac_clickset();
<?php
}*/


}

?>


