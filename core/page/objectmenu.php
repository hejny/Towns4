<?php
/* Towns4, www.towns.cz 
   © Pavol Hejný | 2011-2015
   _____________________________

   core/page/objectmenu.php

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
t("objectmenu - start");








 //* ,create_lastused,create_lastobject,create2_lastused,create2_lastobject,create3_lastused,create3_lastobject,create4_lastused,create4_lastobject

$fields="`id`, `name`, `type`, `origin`, `fs`, `fp`, `fr`, `fx`, `fc`, `func`, `hold`, `res`, `profile`,  `set`, `hard`, `own`, `in`, `ww`, `x`, `y`, `t`,starttime,`readytime`";


if($_GET["xc"] and $_GET["yc"]){
    $x_=$_GET["xc"]+1;
    $y_=$_GET["yc"]+1;
    $dd=1;
    $sql="SELECT $fields FROM `[mpx]pos_obj` WHERE ".objt()." AND `ww`='".$GLOBALS['ss']['ww']."' AND `x`<".round($x_+$dd,2)." AND `x`>".round($x_-$dd,2)." AND `y`<".round($y_+$dd,2)." AND `y`>".round($y_-$dd,2)." AND `type`='building' ORDER BY ABS(`x`-".round($x_,2).")+ABS(`y`-".round($y_,2).") LIMIT 1";

    //OR `type`='tree' OR `type`='rock'
    t("objectmenu - A1");
    
}else{
    t("objectmenu - A2,1");
    if($_GET["contextid"]){
        $id=$_GET["contextid"];
        //$name=$_GET["contextname"];
    }elseif($GLOBALS['get']["contextid"]){
        $id=$GLOBALS['get']["contextid"];
    }else{
        
        $id=$GLOBALS['ss']['use_object']->set->ifnot('contextid',$GLOBALS['hl']);
    }

    t("objectmenu - A2,2");
    if(!ifobject($id))$id=$GLOBALS['hl'];
    $sql=/*$id!=$GLOBALS['ss']['useid']?*/"SELECT $fields FROM `[mpx]pos_obj` WHERE id=$id";//:false;
    $x_=false;
}


t("objectmenu - A - end");
//--------------------------
//die($sql);
//echo($sql);
//echo($GLOBALS['hl']);
if($sql and $id?ifobject($id):true){
        //t('before sql');
        $array=sql_array($sql);
        //t('after sql');
        //list($id, $name, $type, $origin, $fs, $fp, $fr, $fx, $fc, $func, $hold, $res, $profile, $profileown, $set, $hard, $own, $ownname, $owntype, $owncount, $in, $ww, $x, $y, $t,$starttime,$readytime,$creating_id,$creating_function,$creating_starttime,$creating_readytime)=$array[0];


        list($id, $name, $type, $origin, $fs,$fp , $fr, $fx, $fc, $func, $hold, $res, $profile, $set, $hard, $own, $in, $ww, $x, $y, $t,$starttime,$readytime,$creating_id,$creating_function,$creating_starttime,$creating_readytime)=$array[0];

        //e("($fs,$fp)");
        //e("($starttime,$readytime)");


        if($type=='building' or $type=='tree' or $type=='rock')
        js('mapselected='.$id.';');




        if($own) {
            $profileown = sql_1data("SELECT `profile` from `[mpx]pos_obj` WHERE `id`=$own AND " . objt());
            $ownname = sql_1data("SELECT `name` from `[mpx]pos_obj` WHERE `id`=$own AND " . objt());
            $owncount = sql_1number("SELECT count(1) from `[mpx]pos_obj` WHERE `own`=$own AND " . objt());
            $owntype = sql_1data("SELECT `type` from `[mpx]pos_obj` WHERE `id`=$own AND " . objt());
        }


        /*$fileMM=tmpfile2("$id,$t,".timeplan,'html','objectmenu');
        if(!file_exists($fileMM)){//e('creating');
            
            ob_start();*/
        
            //----------------------------------------------    
            if(is_numeric($name))$name=lr($type);
            if($x_){


                $dist=sqrt(pow($x_-$x,2)+pow($y_-$y,2));
                if($dist>1 or $_GET['terrain']==1){
                        //e(intval($_GET["x"]).','.intval($_GET["y"]));	
                        /*$terrain=sql_1data('SELECT `res` FROM `[mpx]pos_obj` WHERE `type`=\'terrain\' AND `ww`='.$GLOBALS['ss']["ww"].' AND `x`='.intval($_GET["xc"]).' AND `y`='.intval($_GET["yc"]).' ');


                        textb(lr('terrain_'.$terrain));
                        //tee(lr('terrain_'.$terrain),13,2);
                        //list($r,$g,$b)=t2rgb($terrain);
                        $color=$terrains[$terrain];


                        icon('e=text-storywrite;x='.x2xx($_GET["xc"]).';y='.x2xx($_GET["yc"]),
                            'fx_storywrite',lr('fx_storywrite'),22);



                        ?>
                        <script type="text/javascript">
                            mapselectedcolor='<?php e($color); ?>';
                        </script>
                        <?php*/


                        /*js(''

                            );*/

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
            t("objectmenu - B");

            $profileown=str2list($profileown);

            //----------------------------------------------barva objektu

            if($own and $own!=$GLOBALS['ss']['useid'])ahref(trr(id2name($own),11,2),"e=content;ee=profile;id=".$own,"none",true);

            t("objectmenu - C");


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

            t("objectmenu - D");


            js("mapselectedcolor='$color2';");


            //----------------------------------------------Zobrazení obrázku objektu

            icon('','id_'.$id.'_icon',$name,44,1,0,array(3,$color,2));
            br();
            //$profilec=$profile;

            //e("<div style=\"position: relative;top: -10px;left: 0px;z-index:2;\">".trr($name,13,2)."</div>");

            //----------------------------------------------Zobrazení jména objektu
            /*
            //----------------------------------------------Příběhy
            if(!$own or $own==$GLOBALS['ss']['useid'])
            $writeicon=iconr('e=text-storywrite;x='.x2xx($_GET["xc"]).';y='.x2xx($_GET["yc"]),'fx_storywrite',lr('fx_storywrite'),22);


            // PH tenhle 'divný' způsob je proto, aby se klikání na jméno neprovedlo omylem na mobilech a tabletech
            e('<span id="objectmenunamea" style="display:block;">'.trr($name,13,2).$writeicon.'</span>');


            e('<span id="objectmenunameb" style="display:none;">'.ahrefr(trr($name,13,2).$writeicon,"e=content;ee=profile;id=".$id,"none",true).'</span>');
            click(js2('$("#objectmenunamea").css("display","none");$("#objectmenunameb").css("display","block");'),0.2);

            */

            //----------------------------zobrazení zdraví + level
            e("<div style=\"position: relative;top: -4px;left: 2px;Font-size:12px;Color:#ffffff;z-index:2;\">".trr($level,10,2)."</div>");
            e("<div style=\"position: relative;top: -15px;left: 0px;height:4px;width:100%;Background-color:#000000;z-index:1;\">");
            e("<div style=\"height:4px;width:".(($fp/$fs<=1)?$fp/$fs*100:100)."%;Background-color:#".$color.";z-index:3;\"></div></div>");
            /**/


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

            t("objectmenu - E");





            //==========================================================================================================Zjištění, zda jde objekt rozebrat
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
            //==========================================================================================================Zobrazení rozebíracího tlačítka
            if($own==$GLOBALS['ss']['useid'] or $candismantle){


                if($_GET['q']){$GLOBALS['object_ids']=array($GLOBALS['get']['dismantle']);define('onlyremove',true);aac();}
                if(id2name($GLOBALS['config']['register_building'])!=$name){


                    border(
                        iconr(($timetoready?'':'e=objectmenu;ee=objectmenu;prompt='.lr('f_dismantle_prompt').';dismantle='.$id.';q=dismantle '.$id.';'.jsa2('removeobject('.$id.');')),
                            'f_dismantle',lr('f_dismantle'),$iconsize,NULL,$timetoready)
                        ,0,$iconsize,NULL,NULL,$timetoready?$timetoready:lr('f_dismantle'));


                    //border(iconr('e=map_context;ee=objectmenu;prompt={f_leave_prompt};q=leave '.$id,'f_leave','{f_leave}',$iconsize),0,$iconsize);
                    //e($space);
                }
            }
            //==========================================================================================================Zobrazení odkazu nepřátelského profilu


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



            //==========================================================================================================FUNC
            if($own==$GLOBALS['ss']['useid']){
                 $functionlist=array('attack','teleport','portal','repair','upgrade','design','replace','change','terrain','create');
            }else{
                 $functionlist=array('portal');   
            }    
            t("objectmenu - F");

            //Parse error: syntax error, unexpected '' in /media/towns4/towns4/core/page/objectmenu.php on line 177
            $set=new set($set);
            $set=$set->vals2list();

            t("objectmenu - G");

            $q=1;$yes=0;
            //e($func);
            $funcs=func2list($func,array('attack','teleport','portal','repair','upgrade','design','replace','change',/*'terrain',*/'create'));

            /*e('<pre>');
            print_r($funcs);
            e('</pre>');*/



        t("objectmenu - H");
        //r($funcs);
        //r();
        //die();


        foreach($functionlist as $qq_class){
            foreach($funcs as $fname=>$func){
                $class=$func["class"];
                if($class==$qq_class){
                        t("objectmenu $class - start");

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
                                                $ahref='e=mine;ee=attack-mine;attack_mafu='.$set_value.';attack_limit='.$profile['limit'];
                                            }

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
                                            //$stream="$('#map_context').css('display','none');";
                                   }
                                break;
                                case 'upgrade':
                                   if($fs==$fp and $origin/* and !((substr($name,0,1)=='{' and substr($name,-1)=='}') and !(strpos(substr($name,1),'{')))*/  ){
                                        if(id2name($GLOBALS['config']['register_building'])!=$name){
                                            $ahref='e=content;ee=create-upgrade;submenu=1;id='.$id;
                                            //$stream="$('#map_context').css('display','none');";
                                   }}
                                break;
                                case 'design':
                                   if($fs==$fp and $origin and !((substr($name,0,1)=='{' and substr($name,-1)=='}') and !(strpos(substr($name,1),'{')))  ){
                                        if(id2name($GLOBALS['config']['register_building'])!=$name){
                                            $ahref='e=content;ee=create-design;submenu=1;id='.$id;
                                            //$stream="$('#map_context').css('display','none');";
                                   }}
                                break;

                                case 'change':
                                        $gold=$GLOBALS['ss']['use_object']->hold->vals2list();
                                        $gold=$gold['gold'];
                                        if(id2name($GLOBALS['config']['register_building'])!=$name or $gold){
                                            $ahref='e=content;ee=hold-change;id='.$id;
                                            //$stream="$('#map_context').css('display','none');";
                                        }
                                break;
                                case 'create':



                                    /*if($func['profile']['group']!='master'/*is_array($func['profile']['limit']) or !$func['profile']['limit']){
                                        $ahref='e=content;ee=create-unique;type=building;master='.$id.';func='.$fname;
                                        //$stream="$('#map_context').css('display','none');";
                                    }else{
                                        $stream="build($id,1000003,'$fname');";//$('#map_context').css('display','none');";
                                    }
                                    //$stream=("attackfunc='$name';".borderjs($name,'attack',iconbrd));
                                    //if($settings["create"]==$name)$yes=1;

                                    hr();*/
                                    //print_r($func);

                                    $group=$func['profile']['group'];
                                if($group) {

                                    if ($groupx != 'extended') {

                                        $group = '`group`=' . sqlx($group) . ' AND ww=0';
                                        $groupby = '';
                                    } else {
                                        $group = ' own=' . $object->own . " AND name!='" . mainname() . "'";
                                        $groupby = 'GROUP BY name';
                                    }


                                    $order = "fs";
                                    //$max=sql_1data("SELECT COUNT(1) FROM `[mpx]pos_obj` WHERE ".$GLOBALS['where'].' AND '.objt());


                                    $objects = sql_array("SELECT `id`,`name` FROM `[mpx]pos_obj` WHERE $group AND " . objt() . " $groupby ORDER BY $order");
                                    //`id`,`name`,`type`,`fs`,`fp`,`fr`,`fx`,`fc`,`res`,`profile`,`own`,(SELECT `own`  FROM `[mpx]pos_obj` as `Y` WHERE `Y`.`id`=(SELECT `own` FROM `[mpx]pos_obj` as `X` WHERE `X`.`name`=`[mpx]pos_obj`.`name` ORDER BY ww,t LIMIT 1) LIMIT 1) AS `rown`,`in`,`x`,`y`,`ww`


                                    ?>

                                    <div class="noselect" style="width: 50px;height: 20px;"
                                         onclick="
                                             $( '#objectmenu_scroll<?= $fname ?>' ).stop(true,true);
                                             $( '#objectmenu_scroll<?= $fname ?>' ).animate({
                                             top: '+=200'
                                             }, 300, function() {
                                             if(parseInt($( '#objectmenu_scroll<?= $fname ?>' ).css('top'))>0)
                                             $( '#objectmenu_scroll<?= $fname ?>' ).animate({
                                             top: '0'
                                             }, 100);
                                             });
                                             ">
                                        <svg width="50" height="20" style="display: inline-block;">
                                            <polygon points="25,0,50,20,0,20"
                                                     style="fill:#151515;stroke:rgba(100,100,100,0.2);stroke-width:2;"/>
                                        </svg>

                                    </div>


                                    <div class="noselect"
                                        style="width: 50px;height: calc(100vh - 300px);min-height:70px;overflow: hidden;color: #ffffff; backgrund-color: #000011;border 2px solod #444444;">
                                        <div id="objectmenu_scroll<?= $fname ?>"
                                             style=" width: 50px;overflow-x: hidden;overflow-y: hidden;text-align: center;"
                                             class="scroll">
                                            <?php

                                            foreach ($objects as $object) {

                                                //imge('id_'.$object['id'].'_icon');
                                                e('<div class="dragbuild" objectid="' . $object['id'] . '" masterid="' . $id . '" masterfunc="' . $fname . '">' . iconr(js2("build($id,{$object['id']},'$fname');"), 'id_' . $object['id'] . '_icon', contentlang($object['name']), $iconsize, 1, 0, array(2, '000000', 6)) . '</div>');

                                            }




                                            ?>
                                        </div>
                                    </div>


                                    <div class="noselect" style="width: 50px;height: 30px;"
                                         onclick="
                                             $( '#objectmenu_scroll<?= $fname ?>' ).stop(true,true);
                                             $( '#objectmenu_scroll<?= $fname ?>' ).animate({
                                             top: '+=-200'
                                             }, 300, function() {
                                             // Animation complete.
                                             });
                                             ">
                                        <svg width="50" height="20" style="display: inline-block;">
                                            <polygon points="25,20,50,0,0,0"
                                                     style="fill:#151515;stroke:rgba(100,100,100,0.2);stroke-width:2;"/>
                                        </svg>
                                    </div>



                                    <script>
                                        $('#objectmenu_scroll<?=$fname?>').draggable({
                                            'axis': 'y',
                                            'distance': 10

                                        });

                                    </script>

                                <?php
                                }




                                    break;
                        }
                        t("objectmenu $class - middle");

                        //---------------------------------------------------------------Vykreslení tlačítka u objectmenu
                        if($stream or $ahref){

                                if($yes and $stream){echo("<script type=\"text/javascript\">$stream</script>");}
                                //echo("<span class=\"functiondrag\" id=\"fd_$name\" style=\"position: relative;top:0px;left:0px;z-index:2;\">");
                                //echo("<a onclick=\"$stream\">".nln);
                                //imge("icons/$icon.png",$xname,22,22);
                                //echo(nln."</a>");
                                if($yes){$brd=$iconbrd;}else{$brd=0;}
                                br();

                                t("objectmenu $class - A");

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

                                t("objectmenu $class - B");

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
                                br();


                         }
                        //---------------------------------------------------------------

                         t("objectmenu $class - end");
                }
            }
        }

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




    //br(5);
    //e('<div class="dragbuild">'.iconr(js2("build(13742398,1000003,'create');"),"profile_user2",lr('build'),$iconsize,1,0,array(2,'cccccc',7)).'<div>');

    ?>
    <script>
    $('.dragbuild').draggable({
        'distance':10,
        //'opacity': 0.7,
        //'helper': 'clone',
        'start':function(){


            $(this).animate({
                opacity: 0
            }, 200);


            //turnmap('expand',true);
            build($(this).attr('masterid'),$(this).attr('objectid'),$(this).attr('masterfunc'));

            xpp=$(this).css('left');
            ypp=$(this).css('top');

        },
        'drag':function(){

            pos=$(this).offset();
            $("#create-build").css("left",parseInt(pos.left)-(83/2)+(<?=$iconsize?>/2));
            $("#create-build").css("top",parseInt(pos.top)-(158/2)-(83/2)+(<?=$iconsize?>/2));

        },
        'stop':function(){


            $(this).animate({
                opacity: 1
            }, 100);


            $(this).css('left',xpp);
            $(this).css('top',ypp);

            //alert('aaa');
            //-----------------------------------------------------@todo sjednotit kopie
            bx=parseFloat($("#create-build").css("left"));
            by=parseFloat($("#create-build").css("top"));
            offset =  $("#map_canvas").offset();
            xt=(bx-offset.left);/*pozice myši px*/
            yt=(by-offset.top);
            tmp=pos2pos(xt,yt);
            xxc=xxc+4.57;
            yyc=yyc+3.67;
            build_x=xxc;
            build_y=yyc;


            $('#create-build_message').html(nacitacihtml);


            $.get('?token=<?php e($_GET['token']); ?>&e=create-build_message&id='+window.build_id+'&master='+window.build_master+'&xx='+build_x+'&yy='+build_y, function(vystup){


                $('#create-build_message').html(vystup);
                //alert('ccc');

                if($('#build_button').css('display')=='block')
                    $('#buildbutton').trigger('click');


                //(window.build_master,window.build_id,'create',build_x,build_y,_rot);

            });

            //-----------------------------------------------------



        }


    });
    </script>
    <?php


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
