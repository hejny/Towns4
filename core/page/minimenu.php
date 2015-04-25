<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2015
   _____________________________

   core/page/minimenu.php

   ovládací prvky u každé budovy
*/
//==============================

  
    $iconsize=50;
    $iconbrd=3;
    $space=' ';
//===============================================================

$terrains=array(
    "t0" => "000000",//temnota
    "t1" => "5299F9",//moře //Výbuchy v Blitz Stree
    "t2" => "545454",//dlažba
    "t3" => "EFF7FB",//sníh/led
    "t4" => "F9F98D",//písek
    "t5" => "878787",//kamení
    "t6" => "5A2F00",//hlína
    "t7" => "DCDCAC",//sůl
    "t8" => "2A7302",//tráva(normal)
    "t9" => "51F311",//tráva(toxic)
    "t10" => "535805",//les
    "t11" => "337EFA",//řeka
    "t12" => "8ABC02",//tráva(jaro)
    "t13" => "8A9002" //tráva(pozim)
);
//--------------------------
t("minimenu - start");



 //* ,create_lastused,create_lastobject,create2_lastused,create2_lastobject,create3_lastused,create3_lastobject,create4_lastused,create4_lastobject

$fields="`id`, `name`, `type`, `origin`, `fs`, `fp`, `fr`, `fx`, `fc`, `func`, `hold`, `res`, `profile`,  `set`, `hard`, `own`, `in`, `ww`, `x`, `y`, `t`,starttime,`readytime`";


if($_GET["xc"] and $_GET["yc"]){
    $x_=$_GET["xc"]+1;
    $y_=$_GET["yc"]+1;
    $dd=1;
    $sql="SELECT $fields FROM `[mpx]pos_obj` WHERE ".objt()." AND `ww`='".$GLOBALS['ss']['ww']."' AND `x`<".round($x_+$dd,2)." AND `x`>".round($x_-$dd,2)." AND `y`<".round($y_+$dd,2)." AND `y`>".round($y_-$dd,2)." AND (`type`='building' OR `type`='tree' OR `type`='rock') ORDER BY ABS(`x`-".round($x_,2).")+ABS(`y`-".round($y_,2).") LIMIT 1";
    t("minimenu - A1");
    
}else{
    t("minimenu - A2,1");
    if($_GET["contextid"]){
        $id=$_GET["contextid"];
        //$name=$_GET["contextname"];
    }elseif($GLOBALS['get']["contextid"]){
        $id=$GLOBALS['get']["contextid"];
    }else{
        
        $id=$GLOBALS['ss']['use_object']->set->ifnot('contextid',$GLOBALS['hl']);
    }

    t("minimenu - A2,2");
    if(!ifobject($id))$id=$GLOBALS['hl'];
    $sql=/*$id!=$GLOBALS['ss']['useid']?*/"SELECT $fields FROM `[mpx]pos_obj` WHERE id=$id";//:false;
    $x_=false;
}


t("minimenu - A - end");
//--------------------------
//die($sql);
//echo($sql);
//echo($GLOBALS['hl']);
if($sql and $id?ifobject($id):true){
        //t('before sql');
        $array=sql_array($sql);
        //t('after sql');
        //list($id, $name, $type, $origin, $fs, $fp, $fr, $fx, $fc, $func, $hold, $res, $profile, $profileown, $set, $hard, $own, $ownname, $owntype, $owncount, $in, $ww, $x, $y, $t,$starttime,$readytime,$creating_id,$creating_function,$creating_starttime,$creating_readytime)=$array[0];


        list($id, $name, $type, $origin, $fs, $fp, $fr, $fx, $fc, $func, $hold, $res, $profile, $set, $hard, $own, $in, $ww, $x, $y, $t,$starttime,$readytime,$creating_id,$creating_function,$creating_starttime,$creating_readytime)=$array[0];



        if($own) {
            $profileown = sql_1data("SELECT `profile` from `[mpx]pos_obj` WHERE `id`=$own AND " . objt());
            $ownname = sql_1data("SELECT `name` from `[mpx]pos_obj` WHERE `id`=$own AND " . objt());
            $owncount = sql_1number("SELECT count(1) from `[mpx]pos_obj` WHERE `own`=$own AND " . objt());
            $owntype = sql_1data("SELECT `type` from `[mpx]pos_obj` WHERE `id`=$own AND " . objt());
        }


        /*$fileMM=tmpfile2("$id,$t,".timeplan,'html','minimenu');
        if(!file_exists($fileMM)){//e('creating');
            
            ob_start();*/
        
            //----------------------------------------------    
            if(is_numeric($name))$name=lr($type);
            if($x_){
                $dist=sqrt(pow($x_-$x,2)+pow($y_-$y,2));
                if($dist>1 or $_GET['terrain']==1){
                        //e(intval($_GET["x"]).','.intval($_GET["y"]));	
                        $terrain=sql_1data('SELECT `res` FROM `[mpx]pos_obj` WHERE `type`=\'terrain\' AND `ww`='.$GLOBALS['ss']["ww"].' AND `x`='.intval($_GET["xc"]).' AND `y`='.intval($_GET["yc"]).' ');


                        textb(lr('terrain_'.$terrain));
                        //tee(lr('terrain_'.$terrain),13,2);
                        //list($r,$g,$b)=t2rgb($terrain);
                        $color=$terrains[$terrain];
                        /*border(
                        iconr('e=text-storywrite;x='.x2xx($_GET["xc"]).';y='.x2xx($_GET["yc"]),
                            'fx_storywrite',lr('fx_storywrite'),$iconsize)
                            ,0,$iconsize);*/

                        icon('e=text-storywrite;x='.x2xx($_GET["xc"]).';y='.x2xx($_GET["yc"]),
                            'fx_storywrite',lr('fx_storywrite'),22);



                        ?>
                        <script type="text/javascript">
                        //alert(1);
                        $('#map_context').css('border-color','#<?php e($color); ?>');
                        </script>
                        <?php
                        exit2();
                }
            }
            //----------------------------------------------Level objektu - počet modulů
                $origin=explode(',',$origin);
                $level=count($origin);
            //----------------------------------------------Ready time - když objekt ještě nestojí
                //@todo PH potřeba přidělat i do API
                if($readytime>time()){
                    $timetoready=$readytime-time();
                    $readypp=100-ceil(100*($readytime-time())/($readytime-$starttime));
                }else{
                    $timetoready=0;
                }
            //----------------------------------------------
            t("minimenu - B");

            $profileown=str2list($profileown);

            //$profilec=$profile;

            //e("<div style=\"position: relative;top: -10px;left: 0px;z-index:2;\">".trr($name,13,2)."</div>");

            //----------------------------------------------Zobrazení jména objectu

            //----------------------------------------------Příběhy
            if(!$own or $own==$GLOBALS['ss']['useid'])
            $writeicon=iconr('e=text-storywrite;x='.x2xx($_GET["xc"]).';y='.x2xx($_GET["yc"]),'fx_storywrite',lr('fx_storywrite'),22);


            // PH tenhle 'divný' způsob je proto, aby se klikání na jméno neprovedlo omylem na mobilech a tabletech
            e('<span id="minimenunamea" style="display:block;">'.trr($name,13,2).$writeicon.'</span>');


            e('<span id="minimenunameb" style="display:none;">'.ahrefr(trr($name,13,2).$writeicon,"e=content;ee=profile;id=".$id,"none",true).'</span>');
            click(js2('$("#minimenunamea").css("display","none");$("#minimenunameb").css("display","block");'),0.2);


            //----------------------------------------------

            if($own and $own!=$GLOBALS['ss']['useid'])ahref(trr(id2name($own),11,2),"e=content;ee=profile;id=".$own,"none",true);

            t("minimenu - C");


            if($profileown['color']){
                $color=$profileown['color'];//$own_=('{xtype_own}');
                $color2=$color;
            }elseif($own==$GLOBALS['ss']['useid']){ 
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

            t("minimenu - D");


        ?>
        <script type="text/javascript">
        $('#map_context').css('border-color','#<?php e($color2); ?>');
        </script>
        <?php
            //----------------------------zobrazení zdraví + level
            e("<div style=\"position: relative;top: -4px;left: 2px;Font-size:12px;Color:#ffffff;z-index:2;\">".trr($level,10/*15*/,2)."</div>");
            e("<div style=\"position: relative;top: -15px;left: 0px;height:4px;width:100%;Background-color:#000000;z-index:1;\">");
            e("<div style=\"height:4px;width:".(($fp/$fs<=1)?$fp/$fs*100:100)."%;Background-color:#".$color.";z-index:3;\"></div></div>");

            //----------------------------tlačítko dostavět
            if($timetoready) {
                e("<div style=\"position: relative;top: -15px;left: 0px;height:4px;width:100%;Background-color:#000000;z-index:1;\">");
                e("<div style=\"height:4px;width:".($readypp)."%;Background-color:#ffffff;z-index:3;\"></div></div>");

				$building_count=sql_1data('SELECT count(1) FROM `[mpx]pos_obj` WHERE own='.$GLOBALS['ss']['useid'].' AND type=\'building\' AND readytime<'.time().' AND'.objt())-1;

				
				if(defined('finish_free') and finish_free>=$building_count){
					ahref(buttonr(lr('finish_immediately',$building_count.'/'.finish_free),15),js2("qbuffer='$id.finish';"),NULL,NULL,'finish_immediately');
					br(2);
				}
				
            }
            //----------------------------



            $GLOBALS['ss']['use_object']->set->add('contextid',$id);

            t("minimenu - E");

            //===============================================================GOLD
                /*if(id2name($GLOBALS['config']['register_building'])==$name and $own==$GLOBALS['ss']['useid']){ 
                        //border(iconr("e=content;ee=plus-index","res_".plus_res,"{title_plus}",$iconsize),0,$iconsize);
                        border(iconr("e=content;ee=help;page=index;page=tutorial_x",'help',"{help}",$iconsize),$border3,$iconsize); 
                }*/
            //===============================================================FUNC
            if($own==$GLOBALS['ss']['useid']){
                 $functionlist=array('attack','create','teleport','portal','repair','upgrade','design','replace','change','terrain');   
            }else{
                 $functionlist=array('portal');   
            }    
            t("minimenu - F"); 
        //Parse error: syntax error, unexpected '' in /media/towns4/towns4/core/page/minimenu.php on line 177 
        $set=new set($set); 
        $set=$set->vals2list();   

        t("minimenu - G");

        $q=1;$yes=0;
        //e($func);
        $funcs=func2list($func,array('attack','create','teleport','portal','repair','upgrade','design','replace','change','terrain'));

        t("minimenu - H");
        //r($funcs);
        //r();
        //die();


        foreach($functionlist as $qq_class){
            foreach($funcs as $fname=>$func){
                $class=$func["class"];
                if($class==$qq_class){
                        t("minimenu $class - start");

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

                                       if($func['profile']['group']!='master'/*is_array($func['profile']['limit']) or !$func['profile']['limit']/* or true*/){
                                            $ahref='e=content;ee=create-unique;type=building;master='.$id.';func='.$fname;
                                            $stream="$('#map_context').css('display','none');";
                                       }else{
                                            $stream="build($id,1000003,'$fname');$('#map_context').css('display','none');";
                                       }                        
                                        //$stream=("attackfunc='$name';".borderjs($name,'attack',iconbrd));
                                        //if($settings["create"]==$name)$yes=1;
                                break;
                                case 'replace':
                                       /*if(intval(sql_1data("SELECT COUNT(1) FROM `[mpx]pos_obj` WHERE own='".$GLOBALS['ss']['useid']."' AND `ww`=".$GLOBALS['ss']["ww"]))<=1){
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
                        t("minimenu $class - middle");

                        //---------------------------------------------------------------Vykreslení tlačítka u minimenu
                        if($stream or $ahref){

                                if($yes and $stream){echo("<script type=\"text/javascript\">$stream</script>");}
                                //echo("<span class=\"functiondrag\" id=\"fd_$name\" style=\"position: relative;top:0px;left:0px;z-index:2;\">");
                                //echo("<a onclick=\"$stream\">".nln);
                                //imge("icons/$icon.png",$xname,22,22);
                                //echo(nln."</a>");
                                if($yes){$brd=$iconbrd;}else{$brd=0;}
                                e(nln);

                                t("minimenu $class - A");                      

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

                                t("minimenu $class - B");

                                //-----------time u tlačítka
                                if($timetoready){
                                    $buttontext=$timetoready;
                                    $countdown=$timetoready;
                                    $ahref='';// @todo PH Promyslet, zda tlačítko attack opravdu zneaktivnit, dokud se budova staví
                                    $stream='';
                                }elseif($countdown>0){
                                    $buttontext=$countdown;
                                }else{
                                    $buttontext=$xname;
                                }
                                //-----------

                                border(iconr(
                                (($countdown and $class!='attack')?'':
                                (($ahref?$ahref.';':'').($stream?"js=".x2xx($stream).';':''))),
                                $icon,$xname,$iconsize,NULL,$countdown),$brd,$iconsize,$set_value,$set_key,$buttontext);
                                $countdown=0;
                                e($space);


                         }
                        //---------------------------------------------------------------

                         t("minimenu $class - end");
                }
            }
        }

    //---------------------------------------------------------------Zjištění, zda jde objekt rozebrat
                if($own!=$GLOBALS['ss']['useid']){
                //if($name)
                $originres=sql_1data("SELECT `res` FROM `[mpx]pos_obj` WHERE (`ww`='0' OR `ww`='-1') AND `name`='$name' LIMIT 1");
                //e('ggg');
                if(substr($originres,0,1)=='{' and substr($originres,-1)=='}'){//e('oiok');
                        $nnn=sql_1data("SELECT count(1) FROM `[mpx]pos_obj` WHERE `ww`='".$GLOBALS['ss']['ww']."' and `own`='".$GLOBALS['ss']['useid']."' AND `expand`>=SQRT(POW(`x`-$x,2)+POW(`y`-$y,2)) AND `name`!='$name' LIMIT 1");
                        if($nnn){//e('ee1');
                                $nnn=sql_1data("SELECT count(1) FROM `[mpx]pos_obj` WHERE `ww`='".$GLOBALS['ss']['ww']."' and `own`='$own' AND `expand`>=SQRT(POW(`x`-$x,2)+POW(`y`-$y,2)) AND `name`!='$name' LIMIT 1");
                                if(!$nnn){//e('ee2');
                                        $GLOBALS['ss']['candismantle']=$id;
                                        $candismantle=true;
                                }
                        }
                }
                }else{
                        $candismantle=false;
                }
    //---------------------------------------------------------------Zobrazení rozebíracího tlačítka
            if($own==$GLOBALS['ss']['useid'] or $candismantle){ 


                if($_GET['q']){$GLOBALS['object_ids']=array($GLOBALS['get']['dismantle']);define('onlyremove',true);aac();}
               if(id2name($GLOBALS['config']['register_building'])!=$name){


                   border(
                       iconr(($timetoready?'':'e=map_context;ee=minimenu;prompt='.lr('f_dismantle_prompt').';dismantle='.$id.';q=dismantle '.$id),
                       'f_dismantle',lr('f_dismantle'),$iconsize,NULL,$timetoready)
                       ,0,$iconsize,NULL,NULL,$timetoready?$timetoready:lr('f_dismantle'));


                    //border(iconr('e=map_context;ee=minimenu;prompt={f_leave_prompt};q=leave '.$id,'f_leave','{f_leave}',$iconsize),0,$iconsize);
                    //e($space);
               }
            }
    //---------------------------------------------------------------Zobrazení odkazu nepřátelského profilu


                if($own and $own!=$GLOBALS['ss']['useid']){
                //$own_=($ownname);
                   //Zatím nezobrazovat profil města//border(iconr("e=content;ee=profile;id=".$own,"profile_town2","{profile_town2}",$iconsize),0,$iconsize);
                   //Zatím nezobrazovat profil města//e($space);
                $ownown=sql_1data('SELECT `own` FROM `[mpx]pos_obj` WHERE `id`=\''.$own.'\'');
                if($ownown){border(iconr("e=content;ee=profile;id=".$ownown,"profile_user2",lr('profile_user2'),$iconsize),0,$iconsize,NULL,NULL,lr('profile_user2'));e($space);}
            }/*elseif($type=='tree' or $type=='rock'){        
                //$own_=('příroda');
            }else{
                //$own_=('opuštěná budova');
            }*/
    //---------------------------------------------------------------


        if($own!=$GLOBALS['ss']['useid']){
        if( $type!='tree' and $type!='rock') {

            if(/*id2name($GLOBALS['config']['register_building'])!=$name*/1){ 
            if($GLOBALS['settings']['attack_mafu']){
                list($attack_master,$attack_function,$attack_function_name,$attack_function_icon)=explode('-',$GLOBALS['settings']['attack_mafu']);
                if(ifobject($attack_master)){

                    $set=new set(sql_1data("SELECT `set` FROM `[mpx]pos_obj` WHERE `id`='$attack_master'"));
                    $set=$set->vals2list();
                    $func=new func(sql_1data("SELECT `func` FROM `[mpx]pos_obj` WHERE `id`='$attack_master'"));
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

                //e($owntype);
                //e($owncount);
                if($name!=mainname() or $owncount==1 or $owntype=='town2'){
                border(iconr('e=content;ee=attack-attack;set=attack_id,'.$id,'f_attack_window',lr('f_attack_window'),$iconsize),0,$iconsize,NULL,NULL,lr('f_attack_window'));
            e($space);
                }

        }
        }

        }


    /*    $contents=ob_get_contents();
        ob_end_clean(); 
        file_put_contents2($fileMM, $contents);
         $contents='b'.$fileMM;

    }else{
        $contents='a'.$fileMM;//file_get_contents($fileMM);

    }
    e($contents);*/


}
 
 
 


?>
