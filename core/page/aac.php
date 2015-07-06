<?php
/* Towns4, www.towns.cz 
   © Pavol Hejný | 2011-2015
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

		e(/*'window.location.replace(\'?q=logout\');'*/'alert(\'Chyba!!!!\');logged=false;');//reloc();
	}else{

		//------------------- statistika, playtime
		$tdiff=time()-$_SESSION['log_object']->t;
		
		if($tdiff<=60*4){
		    $_SESSION['log_object']->pt+=$tdiff;
		}
		
	    $_SESSION['log_object']->t=time();




        //------------------- 
/*if(!document.nochatref){;$("#chatscroll").scrollTop(10000);} alert('aac');*/
?>
logged=true;

if(apptime!=<?php e(filemtime(core.'/page/aac.php')); ?>){
	$('#window_topinfox').css('display','block');	
}


apptime=<?php e(filemtime(core.'/page/aac.php')); ?>;


<?php

//subjs('dockbuttons',false,false,true);
//-------------------obnovení mapy

    $xc_=$_SESSION['use_object']->set->ifnot("map_xc",false);
	$yc_=$_SESSION['use_object']->set->ifnot("map_yc",false);
	
    //----
    
    $xcu=0;
    $ycu=0;
    if($_SESSION["map_xc"])$xcu=$_SESSION["map_xc"];
    if($_SESSION["map_yc"])$ycu=$_SESSION["map_yc"];
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
		$count=sql_1data("SELECT count(1) FROM `[mpx]pos_obj` WHERE (ww=".$_SESSION["ww"]." OR ww=-4) AND `type`='building' AND t>$map_units_time AND ".$range);
		if($count){
		    //e('alert(123);');
		    e('map_units_time='.time().';');
		    
				/*subexec('map_units');		
				subjs('units_stream',$GLOBALS['units_stream']);
				subjs('expandarea',$GLOBALS['area_stream']);
				subjs('attackarea',$GLOBALS['attack_stream']);
				echo('$(\'#units_new\').html("");');
				subjs('dockbuttons');*/
				e('refreshMap();');
			
			
		}
	}/**/
	

//-----------------------------
?>


<?php /*if(!$_GET['onlyc'])*/subjs('surkey');/*$_SESSION['use_object']->hold->showjs();*/ ?>


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



		/*$GLOBALS['map_units_ids']=/*object_id* /$GLOBALS['object_ids'];
		//e('alert('.implode(',',$GLOBALS['object_ids']).');');
		if(/*defined('join_id') or * /count($GLOBALS['object_ids'])){
			foreach($GLOBALS['object_ids'] snesas $id){
				//e('alert('.$id.');');
				echo('$(\'#object'./*object_id* /$id.'\').remove();');
				echo('$(\'#expand'./*object_id* /$id.'\').remove();');
				echo('$(\'#collapse'./*object_id* /$id.'\').remove();');
			}
		}
		//e('alert(1);');*/
		if(!defined('onlyremove')){
			/*subexec('map_units');


			//subjs('units_stream',$GLOBALS['units_stream'],true);


			subjs('expandarea',$GLOBALS['area_stream'],true);
			echo('$(\'#units_new\').html("");');
			e('aac_clickset();');*/

            e("zaloha_a=$('#create-build').css('display');");
            subjs('map');
            e("if(zaloha_a=='block')build(window.build_master,window.build_id,window.build_func);");

            //e("alert('".addslashes($GLOBALS['tmp'])."');");
		}
	}

}
?>
            <?php
                /*if(defined('object_hybrid')){
                    e('alert("'.object_hybrid.'");');
                }*/
                if(defined('object_build')){
                    e('build('.$_SESSION['master'].','.$_SESSION['object_build_id'].',\''.$_SESSION['object_build_func'].'\');');
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


