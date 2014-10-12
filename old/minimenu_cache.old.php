<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/page/minimenu.php

   ovládací prvky u každé budovy - přednačtení
*/
//==============================

  
    $iconsize=50;
    $iconbrd=3;
    $space=' ';
//===============================================================

//--------------------------
$fields="`id`, `name`, `type`, `dev`, `origin`, `fs`, `fp`, `fr`, `fx`, `fc`, `func`, `hold`, `res`, `profile`, (SELECT `profile` from ".mpx."objects as x WHERE x.`id`=".mpx."objects.`own`) as `profileown`, `set`, `hard`, `own`, (SELECT `name` from ".mpx."objects as x WHERE x.`id`=".mpx."objects.`own`) as `ownname`, `in`, `ww`, `x`, `y`, `t`";

    $sql="SELECT $fields FROM ".mpx."objects WHERE `own`='".useid."' AND `ww`='".$GLOBALS['ss']['ww']."' AND `type`='building'";


    $array=sql_array($sql);
    
foreach($array as $row){
    list($id, $name, $type, $dev, $origin, $fs, $fp, $fr, $fx, $fc, $func, $hold, $res, $profile, $profileown, $set, $hard, $own, $ownname, $in, $ww, $x, $y, $t)=$row;

	?>
	<div id="cache_minimenu_<?php e($id); ?>" style="display:block;">
	<?php

    //----------------------------------------------  
    t('mm - a');
    $profileown=str2list($profileown);

    //$profilec=$profile;
    //e("<div style=\"position: relative;top: -10px;left: 0px;z-index:2;\">".trr($name,13,2)."</div>");
    
    e('<span id="minimenunamea" style="display:block;">'.trr($name,13,2).'</span>');
    e('<span id="minimenunameb" style="display:none;">'.ahrefr(trr($name,13,2),"e=content;ee=profile;id=".$id,"none",true).'</span>');
    click(js2('$("#minimenunamea").css("display","none");$("#minimenunameb").css("display","block");'),1);
    if($own and $own!=useid)ahref(trr(id2name($own),11,2),"e=content;ee=profile;id=".$own,"none",true);

    t('mm - b');
    if($profileown['color']){ 
        $color=$profileown['color'];//$own_=('{xtype_own}');
        $color2=$color;
    }elseif($own==useid){ 
        $color='699CFE';//$own_=('{xtype_own}');
        $color2=$color;
    }elseif($own){
        $color='ee1111';//$own_='město '.($ownname);
        $color2=$color;
    }elseif($type=='tree'){        
        $color='229933';//$own_=('{xtype_nature}');
        $color2=$color;
    }elseif($type=='rock'){        
        $color='555555';//$own_=('{xtype_nature}');
        $color2=$color;
    }else{
        $color='cccccc';//$own_=('{xtype_noown}');
        $color2=$color;
    }     
    
?>
<script>
$('#map_context').css('border-color','#<?php e($color2); ?>');
</script>
<?php
    t('mm - c');
	trr('15');
		t('mm - trr');
	fs2lvl(4596);
		t('mm - fs2lvl');
    e("<div style=\"position: relative;top: -4px;left: 2px;Font-size:12px;Color:#ffffff;z-index:2;\">".trr(fs2lvl($fs),10/*15*/,2)."</div>"); 
	t('mm - c1');  
    e("<div style=\"position: relative;top: -15px;left: 0px;height:4px;width:100%;Background-color:#000000;z-index:1;\">");
	t('mm - c2');
    e("<div style=\"height:4px;width:".(($fp/$fs<=1)?$fp/$fs*100:100)."%;Background-color:#".$color.";z-index:3;\"></div></div>");
    	t('mm - c3');


    //===============================================================FUNC
    if($own==useid){
         $functionlist=array('attack','create','teleport','portal','repair','upgrade','design','replace','change','terrain');   
    }else{
         $functionlist=array('portal');   
    }    
    t('mm - d');
        
//Parse error: syntax error, unexpected '' in /media/towns4/towns4/core/page/minimenu.php on line 177 
$set=new set($set); 
$set=$set->vals2list();   
    
$q=1;$yes=0;
//e($func);
$funcs=func2list($func);
//r($funcs);
//r();
//die();
    t('mm - e');
foreach($functionlist as $qq_class){
    foreach($funcs as $fname=>$func){
        $class=$func["class"];
        if($class==$qq_class){
                $params=$func["params"];
                $stream="";$ahref='';$yes=0;
                //--------------------------------------------
                $profile=$func["profile"];
                if($profile["icon"]){
                    $icon=$profile["icon"];
                }else{
                        $icon="f_$class";
                }
                $xname=$profile["name"];
                if(!$xname){$xname=lr("f_$class");}
                //--------------------------------------------
                switch ($class) {
                        /*case "move":
                                $movefunc=$name;
                                $movespeed=$params["speed"][0]*$params["speed"][1];
                                $stream=("movespeed=$movespeed;movefunc='$movefunc';".borderjs($movefunc,'move',iconbrd));
                                if($settings["move"]==$movefunc)$yes=1;
                        break;*/
                        case 'attack':
                        
                                     $set_key='attack_mafu';
                                    $set_value=$id.'-'.$fname;
                                    $set_value2=$id.'-'.$fname.'-'.$xname.'-'.$icon;
                                    
                                    if(!($profile['limit']=='tree' or $profile['limit']=='rock')){
                                        //if(id2name($GLOBALS['config']['register_building'])!=$name){
                                        //$ahref='e=attack-attack;function='.$fname.';master='.$id;
                                        //echo($set_value2);
                                        $stream=($set_key."='$set_value2".'\';'.borderjs($set_value,$set_value2,$set_key,$iconbrd));
                                        //echo('('.$GLOBALS['settings'][$set_key].'='.$set_value2.')');
                                        list($a,$b)=explode('-',$GLOBALS['settings'][$set_key]);                             
                                        if($a.'-'.$b==$set_value)$yes=1;
                                        //echo($yes);
                                        //r($GLOBALS['settings'][$set],$fname);
                                        //r($stream);
                                    //}
                                    }else{
                                        $ahref='e='.(!$GLOBALS['mobile']?'mine':'mine').';ee=attack-mine;attack_mafu='.$set_value.';attack_limit='.$profile['limit'];
                                    }
                                
                        break;
                        case 'create':
                               if(is_array($func['profile']['limit']) or !$func['profile']['limit']/* or true*/){
                                    $ahref='e=content;ee=create-unique;type=building;master='.$id.';func='.$fname;
                                    $stream="$('#map_context').css('display','none');";
                               }else{
                                    $stream="build($id,".$func['profile']['limit'].",'$fname');$('#map_context').css('display','none');";
                               }                        
                                //$stream=("attackfunc='$name';".borderjs($name,'attack',iconbrd));
                                //if($settings["create"]==$name)$yes=1;
                        break;
                        case 'replace':
                               /*if(intval(sql_1data("SELECT COUNT(1) FROM [mpx]objects WHERE own='".useid."' AND `ww`=".$GLOBALS['ss']["ww"]))<=1){
                                    $stream="build($id,".$GLOBALS['config']['register_building'].",'$fname')";
                               }*/
                        break;
                        case 'terrain':
                           $stream="terrain($id,'".$func['profile']['terrain']."','$fname')";
			   if($icon=="f_$class"){$icon='terrain/'.$func['profile']['terrain'].'.png';}
			   if($xname==lr("f_$class")){$xname=lr("f_$class"."_".$func['profile']['terrain']);}
                        break;
                        case 'teleport':
                        case 'portal':
                               $ahref=centerurl($func['profile']['id']);

                        break;
                        case 'repair':
                           if($fs!=$fp){
				//if(id2name($GLOBALS['config']['register_building'])!=$name)
                                    $ahref='e=content;ee=create-repair;id='.$id;
                                    $stream="$('#map_context').css('display','none');";
                           }
                        break;
                        case 'upgrade':
                           if($fs==$fp and $origin/* and !((substr($name,0,1)=='{' and substr($name,-1)=='}') and !(strpos(substr($name,1),'{')))*/  ){
                                if(id2name($GLOBALS['config']['register_building'])!=$name){
				    $ahref='e=content;ee=create-upgrade;submenu=1;id='.$id; 
                                    $stream="$('#map_context').css('display','none');";
                           }}
                        break;
                        case 'design':
                           if($fs==$fp and $origin and !((substr($name,0,1)=='{' and substr($name,-1)=='}') and !(strpos(substr($name,1),'{')))  ){
                                if(id2name($GLOBALS['config']['register_building'])!=$name){
				    $ahref='e=content;ee=create-design;submenu=1;id='.$id; 
                                    $stream="$('#map_context').css('display','none');";
                           }}
                        break;

                        case 'change':
				$gold=$GLOBALS['ss']['use_object']->hold->vals2list();
				$gold=$gold['gold'];
				if(id2name($GLOBALS['config']['register_building'])!=$name or $gold){
                           	    $ahref='e=content;ee=hold-change;id='.$id;
                                    $stream="$('#map_context').css('display','none');";
				} 
                        break;
                }
                if($stream or $ahref){

                    
                    //te($xname);        
                    //br();
                        if($yes and $stream){echo("<script>$stream</script>");}
                        //echo("<span class=\"functiondrag\" id=\"fd_$name\" style=\"position: relative;top:0px;left:0px;z-index:2;\">");
                        //echo("<a onclick=\"$stream\">".nln);
                        //imge("icons/$icon.png",$xname,22,22);
                        //echo(nln."</a>");
                        if($yes){$brd=$iconbrd;}else{$brd=0;}
                        e(nln);
                             
                                              
                        
                        if(defined("a_".$class.'_cooldown')){//$fname
			    $lastused=$set['lastused_'.$fname];
			    $coolround=$params['coolround'][0]*$params['coolround'][1];
			    if($coolround){
				    $time=-(coolround($coolround)-$lastused); 
			    }else{
		                    $cooldown=$params['cooldown'][0]*$params['cooldown'][1];
		                    if(!$cooldown)$cooldown=$GLOBALS['config']['f']['default']['cooldown'];
		                    $time=($cooldown-time()+$lastused);
			    }           
                            if($time>0){
                                $countdown=$time;
                            }
                        }  




                        
                        //Parse error: syntax error, unexpected ',' in /media/towns4/towns4/core/page/minimenu.php on line 191
			//e($countdown);
                        border(iconr(
                        (($countdown and $class!='attack')?'':
                        (($ahref?$ahref.';':'').($stream?"js=".x2xx($stream).';':''))),
                        $icon,$xname,$iconsize,NULL,$countdown),$brd,$iconsize,$set_value,$set_key,$countdown>0?$countdown:$xname/*$countdown/*class*/);
                        $countdown=0;
			e($space);
                        
                        
                 }
        }
    }
}   
    t('mm - f');


    if($own==useid){ 


	if($_GET['q']){$GLOBALS['object_ids']=array($GLOBALS['get']['dismantle']);define('onlyremove',true);aac();}
       if(id2name($GLOBALS['config']['register_building'])!=$name){
            border(iconr('e=map_context;ee=minimenu;prompt='.lr('f_dismantle_prompt').';dismantle='.$id.';q=dismantle '.$id,'f_dismantle',lr('f_dismantle'),$iconsize),0,$iconsize,NULL,NULL,lr('f_dismantle'));
	    e($space);
            //border(iconr('e=map_context;ee=minimenu;prompt={f_leave_prompt};q=leave '.$id,'f_leave','{f_leave}',$iconsize),0,$iconsize);
            //e($space);
       }
    t('mm - g');
//----------------------------------------------
        //$own_=('vlastní budova');
    }elseif($own){
        //$own_=($ownname);
           //Zatím nezobrazovat profil města//border(iconr("e=content;ee=profile;id=".$own,"profile_town2","{profile_town2}",$iconsize),0,$iconsize);
           //Zatím nezobrazovat profil města//e($space);
        $ownown=sql_1data('SELECT `own` FROM [mpx]objects WHERE `id`=\''.$own.'\'');
        if($ownown){border(iconr("e=content;ee=profile;id=".$ownown,"profile_user2",lr('profile_user2'),$iconsize),0,$iconsize,NULL,NULL,lr('profile_user2'));e($space);}
    }/*elseif($type=='tree' or $type=='rock'){        
        //$own_=('příroda');
    }else{
        //$own_=('opuštěná budova');
    }*/



if($own!=useid){
if( $type!='tree' and $type!='rock') {
    
    if(/*id2name($GLOBALS['config']['register_building'])!=$name*/1){ 
    if($GLOBALS['settings']['attack_mafu']){
        list($attack_master,$attack_function,$attack_function_name,$attack_function_icon)=explode('-',$GLOBALS['settings']['attack_mafu']);
        if(ifobject($attack_master)){
            
            $set=new set(sql_1data("SELECT `set` FROM [mpx]objects WHERE `id`='$attack_master'"));
            $set=$set->vals2list();
            $func=new func(sql_1data("SELECT `func` FROM [mpx]objects WHERE `id`='$attack_master'"));            
            $func=$func->vals2list();                    
            
            
           //e($name.'aaa');
           

			//======================RYCHLé útoky bez hovna - zatím neaktivní
                        /*if(defined("a_".$class.'_cooldown')){//$fname
			    $lastused=$set['lastused_'.$fname];
			    $coolround=$func[$attack_function]['params']['coolround'][0]*$func[$attack_function]['params']['coolround'][1];
			    if($coolround){
				    $time=-(coolround($coolround)-$lastused); 
			    }else{
		                    $cooldown=$func[$attack_function]['params']['cooldown'][0]*$func[$attack_function]['params']['cooldown'][1];
		                    if(!$cooldown)$cooldown=$GLOBALS['config']['f']['default']['cooldown'];
		                    $time=($cooldown-time()+$lastused);
			    }           
                            if($time>0){
                                $countdown=$time;
                            }
			    //e($countdown);
			    border(iconr($countdown?'':'e=content;ee=attack-attack;set=attack_id,'.$id.';noshit=1',$attack_function_icon,contentlang("$attack_function_name (".id2name($attack_master).")"),$iconsize,NULL,$countdown),0,$iconsize,NULL,NULL,$countdown>0?$countdown:contentlang($attack_function_name));
				e($space);
                		$countdown=0;
                        }  */
			//======================


               /*if(defined('a_attack_cooldown')){//$fname
                    $cooldown=$func[$attack_function]['params']['cooldown'][0]*$func[$attack_function]['params']['cooldown'][1];
                    if(!$cooldown)$cooldown=$GLOBALS['config']['f']['default']['cooldown'];
                    $lastused=$set['lastused_'.$attack_function]; 
                    $time=($cooldown-time()+$lastused);
                    //r("$cooldown-time()+$lastused");  
                    //r($time);       
                    if($time>0){
                        $countdown=$time;
                    }
   
                

            }*/
            
            
            
        }
    
    }   
    t('mm - h'); 
    border(iconr('e=content;ee=attack-attack;set=attack_id,'.$id,'f_attack_window',lr('f_attack_window'),$iconsize),0,$iconsize,NULL,NULL,lr('f_attack_window'));
    e($space);
}
}

}

e('</div>');
 }
 //e('</td></tr></table>'); 
?>
