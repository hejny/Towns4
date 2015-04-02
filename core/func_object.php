<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2015
   _____________________________

   core/func_object.php

   Tento soubor slouží k deklaraci objektů a fukncí na mapě.
   deklarace object
   práce s daty world_objects, world_object_tmp a world_positions
*/
//==============================




/**
 *
 */
class object{
    //======================================================================================object->__construct
    /**
     * Inicializace objektu
     * @author PH
     *
     * @param id
     */
    function __construct($id=0,$where="",$type=''){
	t("object - create - start");
        //if($where==false){$where="";$this->noupdate=true;}else{$this->noupdate=false;}
        $this->noupdate=false;
        //r($id);
        $id=sql($id);
        //r($id);
        if(!$where){$where="id='$id' OR name='$id'";}
		if($type){
					$type=sql($type);
					if($type=='town'){$type="town' OR type='town2";}
					$where="($where) AND (type='".$type."')";
				}
        if($id!="create"){
            //r("nocreate");
            //$this->id=$id;
            //r(">".$id,"t");
		t("object - create - a");
            $sql_ownname="(SELECT name FROM `[mpx]pos_obj` as tmp WHERE tmp.id=`[mpx]pos_obj`.own) AS ownname";
            $result = sql_array("SELECT *,$sql_ownname FROM `[mpx]pos_obj` WHERE $where AND ".objt()." ORDER BY `id` LIMIT 1",0);
            $row = $result[0];
            if($row){
                //---------------
                //$this->own2=sql_csv("SELECT id,name FROM `[mpx]pos_obj` WHERE (`type`='user' OR `type`='unit') AND own='".$row["id"]."' ORDER BY name");
		        t("object - create - b");
                $this->loaded=true;
                $this->id=$row["id"];
                $this->type=$row["type"];
                $this->fp=$row["fp"];
                $this->fs=$row["fs"];
                $this->fr=$row["fr"];
                $this->fx=$row["fx"];
                $this->fc=$row["fc"];
                //OLD DEV//$this->dev=$row["dev"];
		$this->origin=explode(',',$row["origin"]);
                $this->name=$row["name"];
                //$this->password=$row["password"];
		t("object - create - before func");
                $this->func=new func($row["func"]);
		t("object - create - func");
                $this->set=new set($row["set"]);
		t("object - create - set");
                $this->res=($row["res"]);
		t("object - create - res");
                $this->profile=new profile($row["profile"]);
		t("object - create - profile");
                $this->hold=new hold($row["hold"]);
		t("object - create - hold");
                $this->hard=$row["hard"];
                $this->expand_=$row["expand"];
                $this->block_=$row["block"];
                $this->attack_=$row["attack"];
                $this->own=$row["own"];
				$this->superown=$row["superown"];
                $this->ownname=$row["ownname"];
                $this->ww=$row["ww"];
                $this->t=$row["t"];
                $this->pt=$row["pt"];
                $this->x=$row["x"];
                $this->y=$row["y"];
                        //$this->fs=$this->func->fs();
                        //$this->fr=$this->fp+$this->hold->fp();
		t("object - create - before sum");
                $this->orig_sum=$this->sum();
		t("object - create - sum");
                $this->orig_sum_=$this->sum_();
		t("object - create - sum_");
                $this->orig_sum2=$this->sum(true);
                if(!$this->x){$this->x=0;}
                if(!$this->y){$this->y=0;}

                t("object - create - c");


                return(true);
            }else{
                //echo('hovnooooooooooooooo'.$id);
                r('not loaded '.$id);
                $this->loaded=false;
                return(false);
            }
        }else{
            //r($id);
            $id=nextid();
            $name='object '.$id;


            //------------------INSERT objects
            sql_insert('objects',array(
                'id' => $id,
                'name' => $name,
                'type' => 'hybrid',
                'userid' => '',
                'origin' => '',
                'fp' => '',
                'func' => '',
                'hold' => '',
                'res' => '',
                'profile' => '',
                'set' => '',
                'own' => '',
                't' => '',
                'pt' => ''
            ));
            //------------------INSERT objects_tmp
            sql_insert('objects_tmp',array(
                'id' => $id,
                'fs' => '',
                'fc' => '',
                'fr' => '',
                'fx' => '',
                'superown' => '',
                'expand' => '',
                'block' => '',
                'attack' => '',
                'create_lastused' => '',
                'create_lastobject' => '',
                'create2_lastused' => '',
                'create2_lastobject' => '',
                'create3_lastused' => '',
                'create3_lastobject' => '',
                'create4_lastused' => '',
                'create4_lastobject' => ''
            ));
            //------------------INSERT positions
            sql_insert('positions',array(
                'id' => $id,
                'ww' => '',
                'x' => '0',
                'y' => '0',
                'traceid' => '',
                'starttime' => '',
                'readytime' => '',
                'stoptime' => ''
            ));
            //------------------

            /*sql_query("INSERT INTO `[mpx]pos_obj` (`id`,`name`, `type`, `fp`, `fs`, `origin`, `func`, `set`, `res`, `profile`, `hold`, `own`, `hard`, `expand`, `block`, `attack`, `ww`,`in`, `t`, `x`, `y`) VALUES ('$id','$name', 'hybrid', '', NULL,NULL, NULL, NULL,NULL, NULL, NULL, NULL, 0, 0,0, 0,'1', NULL, NULL, '0', '0')",1);*/


            $this->id=$id;
            $this->name=$name;
            r("creating ".$this->id);
            $this->loaded=true;
            $this->type="";
            $this->fp="";
            $this->fs="";
	    $this->origin='';
            $this->func=new func();
            $this->set=new set();
            $this->res="";
            $this->profile=new profile();
            $this->hold=new hold();
            $this->hard="";
            $this->expand_="";
            $this->block_="";
	    $this->attack_="";
            $this->own="";
			$this->superown="";
            $this->ownname="";
            $this->ww="1";
            $this->t="";
            $this->x="";
            $this->y="";
            $this->orig_sum="update";
        }
	
    }
    //======================================================================================object->sum
    /**
     * MD5 sumarizace objektu
     * @author PH
     *
     */
    /**
     * @param bool $image
     * @return string
     */
    function sum($image=false){
        $stream="";
        if(!$image){
	    $stream=serialize($this);
        }else{
            $stream.=",".$this->res;
            $stream.=",".$this->profile->vals2str();
        }
        $stream=md5($stream);
        return($stream);
    }
    function sum_($image=false){
        $stream="";
            $stream.=",".$this->type;
            $stream.=",".$this->fp;
	        $stream.=serialize($this->origin);//implode(',',$this->origin);
            $stream.=",".serialize($this->func);//$this->func->vals2str();
            $stream.=",".$this->res;
            $stream.=",".serialize($this->hold);//$this->hold->vals2str();
            $stream.=",".$this->own;
			$stream.=",".$this->superown;
        $stream=md5($stream);
        return($stream);
    }
    //======================================================================================object->update
    /**
     * Update objektu databáze
     * @author PH
     *
     * @$force bool
     */

    function update($force=false,$fpfs=false){
        //echo("destructing ".$this->id);
        //echo("<br>");
        //echo($this->orig_sum);
        //echo("<br>");
        //echo($this->sum());
        //echo("<br>");
        //load_file('/path/to/file')
        if($this->loaded){
            /*if(!$this->resfile){
                 //$res="'".(addslashes($this->res))."'";
            }else{
                //r(file_get_contents($this->res));
                //$res="'".(addslashes(file_get_contents($this->res)))."'";
                //echo("copy(".$this->res.",".root."data/image/".$this->id.".jpg);");
                $file=root."data/image/".$this->id.".jpg";
                copy($this->res,$file);
                chmod($file,0777);
            }
            $res="''";*/
            //if($this->id==1){cleartmp($this->id);}
			
			//e($this->orig_sum.'!='.$this->sum());

            
                //PREVENCE REWRITE
                /*if(!$GLOBALS['objects_updated']){
                    $GLOBALS['objects_updated']=array();
                }
                if(in_array($this->id,$GLOBALS['objects_updated'])){
                    return;
                }
                $GLOBALS['objects_updated'][]=$this->id;*/
            
            if($this->orig_sum!=$this->sum() or $force){//$this->loaded=false;
                if($this->orig_sum2!=$this->sum(true)){cleartmp($this->id);}
                //echo("updating ".$this->id);
                //echo("<br>");
                //==================================================================ARYYD
                if(($this->orig_sum_!=$this->sum_() and !$this->noupdate) or $force){
                    r("updating sumaries - ".$this->id." + FS/FP");
                    //------------------------------FS + FC (FP - NONE)
                    $fc= new hold("");
                    $funcs=$this->func->vals2list();
                    foreach($funcs as $name=>$func){
                        $class=$func["class"];
			    //e($GLOBALS['config']["f"][$class]["create"]["q"]);br();e($GLOBALS['config']["f"][$class]["create"]["w"]);hr();
                        if($c1=$GLOBALS['config']["f"][$class]["create"]["q"] and $c2=$GLOBALS['config']["f"][$class]["create"]["w"]){//r('xxx');
			    //e('OK');br();
                            $constants=$func["params"];
                            foreach($constants as &$value_c)$value_c=$value_c[0]*$value_c[1];
                            //r($constants);
                            foreach($GLOBALS['config']["f"]["constant"] as $key=>$value){
                                if(!$constants[$key]){
                                    $constants[$key]=$value;
                                }
                            }
                            foreach($GLOBALS['config']["f"]["global"]["create"] as $key=>$value){$key="_".$key;
                                    //r("$key=>$value");
                                    if(!defined($key))define($key,$value);
                            }
                            //foreach($constants as $key=>$value){echo('$_'.$key."=$value;");br();}
                            //r($constants);
                            //foreach($params as $key=>$value){echo('$'.$key."=$value;");br();}
                            foreach($constants as $key=>$value)eval('$_'.$key.'=$value;');
                            //foreach($params as $key=>$value)eval('$'.$key.'=$value;');
                            
                            //r($GLOBALS['config']["f"]["create"]);
                            //e('$price='.$c1.";");br();
                            eval('$price='.$c1.";");
			    //e($price);br();
                            //--------------------------------
                            $count=0;
                            $c2=explode("+",$c2);
                            foreach($c2 as &$value){
                                $value=explode("*",$value);
                                //r($value);
                                if(!$value[1]){$value[1]=$value[0];$value[0]=1;}
                                $count=$count+$value[0];
                            }
                            foreach($c2 as &$value){
                                $resource=$value[1];
                                $hold=ceil($price*$value[0]/$count);
                                //r($resource.": $hold");
                                $fc->add($resource,$hold);
                            }
                            //--------------------------------
                    }
                    }
                    $this->fs=ceil($fc->fp());
		    if($fpfs)$this->fp=$this->fs;
                    $this->fc=($fc->vals2str());
                    //------------------------------FR
                    //$this->hold->show();
                    $this->fr=$this->hold->fp();  
                    //------------------------------FX
                    $this->fx=$this->fp+$this->fr;
                    //------------------------------HARD,EXPAND,block,ATTACK
                    //$tmp=$funcs["hard"]["params"]["hard"];$this->hard=$tmp[0]*$tmp[1];//r(444);r($tmp);
					$tmp=$funcs["expand"]["params"]["distance"];$this->expand_=$tmp[0]*$tmp[1];    
					$tmp=$funcs["block"]["params"]["block"];$this->block_=$tmp[0]*$tmp[1];
					$tmp=$funcs["attack"]["params"]["distance"];$this->attack_=$tmp[0]*$tmp[1];
                    //------------------------------TIME
                    $this->t=time();   
                    //------------------------------FP=FS
                    if($this->fp>$this->fs)$this->fp=$this->fs;
                    //------------------------------ORIGIN
		    sort($this->origin);
                    //------------------------------REPORT
                    /*r($this->fs);
                    r($this->fp);
                    r($this->fr);
                    r($this->fx);
                    r($this->fc);
                    r($this->hard);*/
                    //r($this->expand_);
                }else{
                    r("updating sumaries -".$this->id);
                }
                //==================================================================


                //------------------UPDATE objects
                sql_update('objects',"id='".$this->id."'",array(
                    //'id' => $this->xxx,
                    'name' => $this->name,
                    'type' => $this->type,
                    //'userid' => $this->xxx,
                    'origin' => implode(',',$this->origin),
                    'fp' => $this->fp,
                    'func' => ($this->func->vals2str()),
                    'hold' => ($this->hold->vals2str()),
                    'res' => $this->res,
                    'profile' => ($this->profile->vals2str()),
                    'set' => ($this->set->vals2str()),
                    'own' => $this->own,
                    't' => $this->t,
                    'pt' => $this->pt
                ));
                //------------------UPDATE objects_tmp
                sql_update('objects_tmp',"id='".$this->id."'",array(
                    //'id' => $this->xxx,
                    'fs' => $this->fs,
                    'fc' => $this->fc,
                    'fr' => $this->fr,
                    'fx' => $this->fx,
                    'superown' => $this->superown,
                    'expand' => $this->expand_,
                    'block' => $this->block_,
                    'attack' => $this->attack_
                    /*'create_lastused' => $this->xxx,
                    'create_lastobject' => $this->xxx,
                    'create2_lastused' => $this->xxx,
                    'create2_lastobject' => $this->xxx,
                    'create3_lastused' => $this->xxx,
                    'create3_lastobject' => $this->xxx,
                    'create4_lastused' => $this->xxx,
                    'create4_lastobject' => $this->xxx*/
                ));
                //------------------UPDATE positions
                sql_update('positions',"id='".$this->id."'",array(
                    //'id' => $this->xxx,
                    'ww' => $this->ww,
                    'x' => $this->x,
                    'y' => $this->y
                    //'traceid' => $this->xxx,
                    //'starttime' => $this->xxx,
                    //'readytime' => $this->xxx,
                    //'stoptime' => $this->xxx
                ));
                //------------------

                /*$query=("UPDATE  `[mpx]pos_obj` SET
                `type` =  '".($this->type)."',
                `fp` =  '".($this->fp)."',
                `fs` =  '".($this->fs)."',
                `fr` =  '".($this->fr)."',
                `fx` =  '".($this->fx)."',
                `fc` =  '".($this->fc)."',
                ".//`fx` =  ".($this->fr)."+(SELECT SUM(`fr`) FROM `objects` AS tmp WHERE tmp.`own`='".$this->id."' OR tmp.`in`='".$this->id."'),
                //UPDATE objects SET `fx` = 10+(SELECT 1 FROM objects AS xxxx)
                "
                `origin` =  '".implode(',',$this->origin)."',
                `name` =  '".($this->name)."',
                `func` =  '".($this->func->vals2str())."',
                `set` =  '".($this->set->vals2str())."',
                `res` =  '".$this->res."',
                `profile` =  '".($this->profile->vals2str())."',
                `expand` =  '".(($this->expand_))."',
                `block` =  '".(($this->block_))."',
                `attack` =  '".(($this->attack_))."',
                `own` =  '".(($this->own))."',
                `ww` =  '".(($this->ww))."',
                `t`=  '".($this->t)."',
                `pt`=  '".($this->pt)."',
                `x` =  '".($this->x)."',
                `y` =  '".($this->y)."'
                WHERE  id ='".($this->id)."'");*/


            }
        }else{
		//e('update - not loaded');
		r('update - not loaded');
	}
    }
    //======================================================================================object->resurkey
    /**
     * Přepočítat získávané suroviny u města
     * @author PH
     *
     */
    function resurkey(){

	$surkey=array();
	
	foreach(array('tree','rock') as $type){

                //ABS(A.x-B.x)+ABS(A.y-B.y)
                
		foreach(sql_array('SELECT `id`,`func`,x,y FROM `[mpx]pos_obj` WHERE `own`='.$this->id.' AND `func` LIKE \'%class[5]mine%limit[7]5[10]'.$type.'%\' AND '.objt()) as $row){

			list($id,$func,$x,$y)=$row;

			//-------------------------distance
			$dd=10;
			$distance=sql_1data('SELECT SQRT(POW('.$x.'-x,2)+POW('.$y.'-y,2)) FROM `[mpx]pos_obj` WHERE type=\''.$type.'\' AND '.objt().'  AND x<'.($x+$dd).' AND x>'.($x-$dd).' AND y<'.($y+$dd).' AND y>'.($y-$dd).' ORDER BY SQRT(POW('.$x.'-x,2)+POW('.$y.'-y,2))');

			//-------------------------

			$func=func2list($func);
			
			foreach(array('mine','mine2','mine3','mine4','mine5','mine6') as $fname){
			if($func[$fname]['profile']['limit']==$type){
				$cooldown=$func[$fname]['params']['cooldown'][0]*$func[$fname]['params']['cooldown'][1];
				break;
			}}

			foreach(($GLOBALS['config'][$type.'_surkey']) as $var=>$val){
				if(!$surkey[$var])$surkey[$var]=0;
				$surkey[$var]+=(1/$distance)*$val/($cooldown/3600);
			}
			//r($type,$cooldown,$distance);

		}

	}
	foreach($surkey as $var=>$val){
		$this->hold->vals['_'.$var]=round($val);
	}
    //----------------------------
	$surkey=array();
	foreach(sql_array('SELECT `func`FROM `[mpx]pos_obj` WHERE `own`='.$this->id.' AND `func` LIKE \'%class[5]holdx%\' AND'.objt()) as $row){
		list($func)=$row;
		$func=func2list($func);
		for($i=1;$i<50;$i++){
			$fname='holdx'.($i!=1?$i:'');
			$var=$func[$fname]['profile']['limit'];
			if(!$surkey[$var])$surkey[$var]=0;
			//e($var.'+'.$func[$fname]['params']['count'][0]);br();
			$surkey[$var]+=$func[$fname]['params']['count'][0]*$func[$fname]['params']['count'][1];
		}
	
	}

	foreach($surkey as $var=>$val){
		$this->hold->vals['_-'.$var]=round($val);
	}

	$this->hold->rebase();
	//----------------------------
	$eff=0;
	foreach(sql_array('SELECT `func`FROM `[mpx]pos_obj` WHERE `own`='.$this->id.' AND `func` LIKE \'%class[5]change%\' AND '.objt()) as $row){
		list($func)=$row;
		$func=func2list($func);
		foreach(array('change'/*,'change2','change3','change4','holdx5','holdx6'*/) as $fname){
			$tmp=$func[$fname]['params']['eff'][0]*$func[$fname]['params']['eff'][1];
			if($tmp>$eff)$eff=$tmp;
		}

	}
	$this->hold->vals['_change']=round($eff*100);

    }

    //======================================================================================object->deletex
    /**
     * Zneplatnění objektu - (z hráčského pohledu smazání)
     * @author PH
     *
     */

    function deletex(){
        sql_query("UPDATE `[mpx]pos_obj` SET `stoptime` = '".time()."' WHERE `id` = '".$this->id."'");
        $this->loaded=false;
    }


    //======================================================================================object->support
    /**
     * Přepočítání func se všemi podporami - potřeba obnovit
     * @author PH
     *
     */
    function support(){
        if($this->loaded){
            $funcs=$this->func->vals2list();
            $newfuncs=$funcs;
            $support=array();
            /*
             * @todo PH vymyslet, co se support systémem?
             * $in2=sql_array("SELECT `id`,`type`,`fp`,`fs`,`name`,NULL,`func`,`set`,NULL,`profile`,`hold`,`hard`,`expand`,`block`,`attack`,`own`,`in`,`t`,`x`,`y` FROM `[mpx]pos_obj` WHERE `in`='".($this->id)."' AND ".objt()." ORDER BY t desc");
            foreach($in2 as $item){
                list($_id,$_type,$_fp,$_fs,$_name,$_password,$_func,$_set,$_res,$_profile,$_hold,$_hard,$_expand,$_block,$_attack,$_own,$_in,$_t,$_x,$_y)=$item;
                $_x=intval($_x);$_y=intval($_y);
                if(!$_x)$_x="";
                foreach($funcs["hold$_x"]["params"] as $param=>$value){
                    list($qqe1,$e2)=$value;
                    if($param!="q"){
                        foreach(func2list($item[7]) as $funci){
                            if($funci["class"]==$param){
                                foreach($funci["params"] as $parami=>$valuei){
                                    list($e1i,$e2i)=$valuei;
                                    $e1i=$e1i*$e2;
                                    $e2i=pow($e2i,$e2);//2^0.2
                                    if(!$support[$funci["class"]])$support[$funci["class"]]=array();
                                    if(!$support[$funci["class"]][$parami])$support[$funci["class"]][$parami]=array(0,1);
                                    $support[$funci["class"]][$parami][0]=$support[$funci["class"]][$parami][0]+$e1i;
                                    $support[$funci["class"]][$parami][1]=$support[$funci["class"]][$parami][1]*$e2i;
    
                                }
                            }
                        }
                    }
                }
            }
            */
            foreach($newfuncs as $name=>$func){
                $class=$func["class"];
                $params=$func["params"];
                $profile=$func["profile"];
                foreach($params as $fname=>$param){
                    $e1=$param[0];$e2=$param[1];
                    $support1=$support[$class][$fname];
                    if($support1){
                        list($se1,$se2)=$support1;
                        $q=($se1+$e1)*($se2*$e2);
                        $newfuncs[$name]["params"][$fname][0]=$q;
                        $newfuncs[$name]["params"][$fname][1]=1;
                    }
                }
            }
            //r($newfuncs);
            return($newfuncs);
        }else{return(array());}
    }
    //--------------------------------------------supportF
    function supportF($function,$value=""){
        if(!$value)$value=$function;
        $funcs=$this->support();
        $func=$funcs[$function];
        if(!$func["params"][$value] and $GLOBALS['config']['f']['default'][$value]){
            return($GLOBALS['config']['f']['default'][$value]);
        }
        if($func){
            list($a,$b)=$func["params"][$value];
            return($a*$b);
        }else{
            return(0);
        }
    }
    //======================================================================================object->position
    /**
     * Vrácení aktuální pozice objektu
     * @author PH
     *
     * @return array ($x,$y)
     *
     */
    function position(){
        $position=array($this->x,$this->y);
        return($position);
    }
    //======================================================================================object->join
    function join($id,$res=false,$rot=false,$onlyfunc=false){
	if(!defined("func_map"))require(root.core."/func_map.php");


	$joinobject = new object($id); 

	//------------origin
	$origin=array_merge($this->origin,$joinobject->origin);
	sort($origin);
	$this->origin=$origin;

	$reference=sql_array("SELECT `name`,`profile`,`res`,`own` FROM `[mpx]pos_obj` WHERE `ww`=0 AND `origin`='".implode(',',$origin)."' AND ".objt()." ORDER BY RAND()");
	if($reference){
		list($name,$profile,$res,$own)=$reference[0];
		$this->name=$name;
		$this->profile=new profile($profile);
		if($rot===false){
			$this->res=$res;
		}else{
			$res=explode(':',$res);
			$this->res=explode(':',$this->res);
			$this->res=$res[0].':'.$res[1].':'.$res[2].':'.$rot;
		}
		$this->profile->add('author',$own);
	}else{

		if($this->name!=$joinobject->name and strpos($this->res,'[-4,-4,')===false)define('object_hybrid',$this->id);
		//------------name,model
		if(!$onlyfunc){
			$this->name=name2name($this->name,$joinobject->name);
			$this->res=model2model($this->res,$res);
		
		//------------description
			$description1=$this->profile->val('description');
			$description2=$joinobject->profile->val('description');
			$description=$description1.nln.$description2;
			$this->profile->add('description',$description);

		//------------author
		$this->profile->add('author',-1);
		}
	}

	$this->set=new set();
	$this->set->add('auto_name',$this->name);
	$this->set->add('auto_description',$this->profile->val(description));
	$this->set->add('auto_res',$this->res);
	//$originx=$this->profile->ifnot('originx',$this->id);
	//$this->profile->add('originx',$originx.','.$id);
	//------------func
        $this->func->join($joinobject->func);


	unset($joinobject);
    }

    //======================================================================================object->resc
    /**
     * Zdrojový kód modelu s uživatelskou barvou
     * @author PH
     *
     * @param integer vlastník
     */
    function resc($own=false){
	if(!$own)$own=$this->own;
	$res=$this->res;
        $profileown=sql_1data('SELECT `profile` from `[mpx]pos_obj` WHERE `id`='.$own.' AND '.objt());
    	$profileown=str2list($profileown);
    	if($profileown['color']){
		$res=str_replace('000000',$profileown['color'],$res);
	}
	return($res);
    }
}
//============================================================================================================================================================================
//======================================================================================resc
//--------------------------------------------resc out
    function resc($res,$own){
        $profileown=sql_1data('SELECT `profile` from `[mpx]pos_obj` WHERE `id`='.$own.' AND '.objt());
    	$profileown=str2list($profileown);
    	if($profileown['color']){
		$res=str_replace('000000',$profileown['color'],$res);
	}
	return($res);
    }

//--------------------------------------------
//======================================================================================supportF

function supportF($id,$function,$value=""){
    $object=new object($id);
    return($object->supportF($function,$value));
}

//======================================================================================nextid
function nextid($id){
    $id=sql_1data('SELECT max(id) FROM `[mpx]pos_obj`')-1+101;
    return($id);
}

//======================================================================================id2name

function id2name($id){
    $name=sql_1data("SELECT name FROM `[mpx]pos_obj` WHERE id='$id'".' AND '.objt());
    if(!$name)$name=lr('unknown');
    return($name);
}
//======================================================================================id2own

function id2own($id){
    $own=sql_1data("SELECT own FROM `[mpx]pos_obj` WHERE id='$id'".' AND '.objt());
    if(!$own)$own=false;
    return($own);
}

//======================================================================================townid2xy

function townid2xy($id){
    $xy=sql_array("SELECT x,y FROM `[mpx]pos_obj` WHERE type='building' AND own='$id' AND name='".mainname()."' AND ".objt());
    if(!$xy){
        $xy=false;
    }else{
        $xy=$xy[0];
    }
    return($xy);
}

//======================================================================================town2name

function town2name($id){
    $town=id2name($id);
    $id=id2own($id);
    $user=id2name($id);
    
    if($town==$user){
        return($town);
    }else{
        return($town.'('.lr('user').' '.$user.')');
    }
}       
                
//======================================================================================name2id
function name2id($name){
    if(!is_numeric($name)){
        $id=sql_1data("SELECT id FROM `[mpx]pos_obj` WHERE name='$name'".' AND '.objt());
    }else{
        $id=$name;
    }
    return($id);
}
//======================================================================================id2unique
function id2unique($id){
    if(!$id)return('');
    $name=sql_1data("SELECT name FROM `[mpx]pos_obj` WHERE id='$id'");
    $count=sql_1data("SELECT COUNT(1) FROM `[mpx]pos_obj` WHERE name='$name'".' AND '.objt())-1+1;
    if($count>1)$name.="($id)";
    return($name);
}

//======================================================================================unique2id
function unique2id($unique){
    $unique=trim($unique);
    list($name,$id)=explode('(',$unique);
    $name=trim($name);
    if($id){
        $id=trim(str_replace(')','',$id));
    }else{
        $id=sql_1data("SELECT id FROM `[mpx]pos_obj` WHERE name='$name'".' AND '.objt());
    }
    return($id);
    
}

//======================================================================================id2info

function id2info($id,$rows){
    $info=sql_array("SELECT $rows FROM `[mpx]pos_obj` WHERE id='$id'".' AND '.objt());
    foreach ($info as &$value) {
    $info = $info[0];
    }
    return($info);
}


/**
 * @param string $where
 * @param string $order
 * @param string $limit
 * @return array
 */
//======================================================================================objects
function objects($where="",$order="",$limit=""){
    if($where){$where="WHERE ".$where.' AND '.objt();}
    if($order){$order="ORDER BY ".$order;}
    if($limit){$limit="LIMIT ".$limit;}
    $result = sql_query("SELECT * FROM `[mpx]pos_obj` $where $order $limit");
    $objects=array();
    while($object = mysql_fetch_array ($result)){
        $objects=array_merge($objects,array($object));
    }
    mysql_free_result($result);
    return($objects);
}
//======================================================================================coolround


function coolround($i){

$time=$GLOBALS['ss']['use_object']->set->ifnot('coolround',0);
return($time);
}
/*$mapunitstime=intval(file_get_contents(tmpfile2("mapunitstime","txt","text")));
return($mapunitstime);
return(time()-600);*/

//======================================================================================trackobject


function trackobject($id){
    $nextid=nextid();
    //------------------REINSERT objects
    sql_reinsert('objects',"id='$id'",array(
        'id' => $nextid,
        'name' => true,
        'type' => true,
        'userid' => true,
        'origin' => true,
        'fp' => true,
        'func' => true,
        'hold' => true,
        'res' => true,
        'profile' => true,
        'set' => true,
        'own' => true,
        't' => true,
        'pt' => true
    ));
    //------------------REINSERT objects_tmp
    sql_reinsert('objects_tmp',"id='$id'",array(
        'id' => $nextid,
        'fs' => true,
        'fc' => true,
        'fr' => true,
        'fx' => true,
        'superown' => true,
        'expand' => true,
        'block' => true,
        'attack' => true,
        'create_lastused' => true,
        'create_lastobject' => true,
        'create2_lastused' => true,
        'create2_lastobject' => true,
        'create3_lastused' => true,
        'create3_lastobject' => true,
        'create4_lastused' => true,
        'create4_lastobject' => true
    ));
    //------------------REINSERT positions
    sql_reinsert('positions',"id='$id' AND ".objt(),array(
        'id' => $nextid,
        'ww' => true,
        'x' => true,
        'y' => true,
        'traceid' => $id,
        'starttime' => true,
        'readytime' => true,
        'stoptime' => time()
    ));
    //------------------

    /*sql_query("
    INSERT INTO `[mpx]pos_obj`
    (`id`, `name`, `type`, `origin`, `fs`, `fp`, `fr`, `fx`, `fc`, `func`, `hold`, `res`, `profile`, `set`, `hard`, `expand`, `block`, `attack`, `own`, `superown`, `in`, `ww`, `x`, `y`, `t`, `pt`, `traceid`, `starttime`, `readytime`, `stoptime`)
    SELECT
    ".nextid().", `name`, `type`, `origin`, `fs`, `fp`, `fr`, `fx`, `fc`, `func`, `hold`, `res`, `profile`, `set`, `hard`, `expand`, `block`, `attack`, `own`, `superown`, `in`, `ww`, `x`, `y`, `t`, `pt`, ".sql($id).", `starttime`, `readytime`, ".$time."
    FROM
    `[mpx]pos_obj`
    WHERE id='".sql($id)."' ");*/

    sql_query("UPDATE `[mpx]pos_obj` SET `starttime`='".time()."' WHERE id='".sql($id)."' ");




}


//trackobject(2289241);
//die();
//======================================================================================ifobject
/**
 * @param $id
 * @return array|bool
 */
function ifobject($id){
	$id=trim($id);
    //r("SELECT id FROM objects WHERE id='$id' OR name='$id'");
    $result = sql_1data("SELECT id FROM `[mpx]pos_obj` WHERE ".(is_numeric($id)?'id':'name')."='$id' ".' AND '.objt());// OR profile LIKE '%mail=$id;%'
    //r($result);
    if($result){
        return($result);
    }else{
        return(0);
    }
}
//======================================================================================topobject
function topobject($id,$i=0){
    if($i>8)return(false);
    //r("SELECT id FROM objects WHERE id='$id' OR name='$id'");
    $result = sql_1data("SELECT own FROM `[mpx]pos_obj` WHERE id='$id' OR name='$id' AND ".objt()." LIMIT 1");
    //e("($i,$result)");
    if($result==$id)return($id);
    if($result){
        return(topobject($result,$i+1));
    }else{
        return($id);
    }
}
//======================================================================================superown
function superown(){
    foreach(sql_array('SELECT id FROM `[mpx]pos_obj` WHERE superown IS NULL AND '.objt()) as $id){
        $id=$id[0];
        $top=topobject($id);
        sql_query('UPDATE `[mpx]pos_obj` SET superown='.$top.' WHERE id='.$id.' AND '.objt());
    }
}

//================================================resolve

/*$a=array(1,1,2,2,3,3,4);
$b=array(1,2,3);
$c=array_diff($a,$b);
print_r($c);
die();*/
function resolve_sort($row1,$row2){
	list($id1,$origin1)=$row1;
	list($id2,$origin2)=$row2;
	$origin1=explode(',',$origin1);
	$origin2=explode(',',$origin2);
	$origin1=count($origin1);
	$origin2=count($origin2);
	if($origin1>$origin2){return(-1);}
	if($origin1<$origin2){return(1);}
	return(0);
}

function resolve($origin){

	if(!$originx)$originx=array();
	if(!$GLOBALS['resolve_originxs']){
		$GLOBALS['resolve_originxs']=sql_array('SELECT id,origin FROM '.mpx.'objects WHERE ww=0 AND '.objt().' ORDER BY id');
		usort($GLOBALS['resolve_originxs'],'resolve_sort');
		
	}

	sort($origin);
	

	//if(debug){e('<b>resolving('.$level.')</b> ('.implode(',',$origin).') resolved ('.implode(',',$originx).')');br();}
	
	$i=0;

	$limit=0;while($limit<1000){$limit++;
		
		$row=$GLOBALS['resolve_originxs'][$i];
		$i++;
		list($id,$tmporigin)=$row;
		if(debug){e('testing '.$id.'('.$tmporigin.')');br();}
		$origin_=array_count_values($origin);
		$tmporigin=explode(',',$tmporigin);
		sort($tmporigin);		
		//---------------To same
		/*if($origin==$tmporigin){
			$originx=array_merge($originx,array($id));
			return($originx);	

		}*/
		//---------------Soucasti
		$tmporigin_=array_count_values($tmporigin);
		if(debug){
		print_r($origin_);br();	
		print_r($tmporigin_);br();}
		$no=false;
		foreach($tmporigin_ as $key=>$value){
			//e(($origin_[$key]).">=".$value);br();
			if($origin_[$key] and $origin_[$key]>=$value){
				//e('goya');
				$origin_[$key]-=$value;
			}else{
				$no=true;
			}
		}
		if(!$no){
			//if(debug){e('<b>obsahuje</b>');br();print_r($origin_);br();}
			$origin=array();
			foreach($origin_ as $key=>$value){
				for($ii=1;$ii<=$value;$ii++)$origin[]=$key;
			}
			

			$originx=array_merge($originx,array($id));

			if($origin==array()){
				return($originx);
			}else{
				$i--;
				//$originx=resolve($origin,$originx,$level+1);
			}
		}
		//---------------
	}

	return(false);

}


//======================================================================================EFFINDEX

function effindex($id){
	if($origin=sql_1data("SELECT origin FROM `[mpx]pos_obj` WHERE id='$id' OR name='$id'".' AND '.objt())){
		$origin=explode(',',$origin);
		$ids=resolve($origin);
		//if(debug){print_r($ids);}
		$ids_=array_count_values($ids);
		$effi=0;$effii=0;
		foreach($ids_ as $id=>$count){
			if($id-1+1>1000){
				$effi+=sqrt($count);
			}else{
				$effii+=sqrt($count);
			}
			//e(id2name($id));br();
		}
		$effi+=sqrt($effii);
		$effi=pow($effi,2);
		return($effi);
	}else{
		if(ifobject($id)){
			return(1);
		}else{
			return(false);
		}
	}
}

//print_r(effindex(1890727));
//die();

//================================================repair_fuel

function repair_fuel($id){
	$effindex=effindex($id);
	$fuel=ceil($effindex*f_repair_fuel);
	return($fuel);
}
//e(effindex(1871627));
//e(effindex(1789955));
//die();
//======================================================================================name_error
/**
 * @param $id
 * @return bool|string
 */
function name_error($id){
    /*$id=xx2x($id);
    if($GLOBALS['ss']['use_object']->name==$id){
            return(lr('name_error_same'));//"Toto jméno právě používáte.");
    }*/
    
    if($id!=trim($id)){
            return(lr('name_error_space'));//"Jméno nesmí začínat ani končit mezerou.");
    }
    if(!$id){
            return(lr('name_error_noname'));//"Musíte zadat jméno.");//K+M+B
    }
    if(strlen($id)<4){
            return(lr('name_error_minchars',4));//"Jméno musí mít alespoň 3 znaky.");
    }
    if(strlen($id)>37){
            return(lr('name_error_maxchars',37));//"Jméno nesmí mít víc než 100 znaků");
    }
    if(str_replace(str_split('`!@#$%^&*()+{}[]=:"|<>?;\'¨\\,/;=´§,'),'',$id)!=$id){
            return(lr('name_error_specialchars'));//"Jméno by nemělo nesmí speciální znaky.");
    }
    if($ifobject=ifobject($id) and $ifobject!=$GLOBALS['ss']['logid'] and $ifobject!=$GLOBALS['ss']['useid']){
            return(lr('name_error_used'));//"Jméno je už obsazené.");
    }
    if(($id-1+1).""==$id){
            return(lr('name_error_number'));//"Jméno nesmí být pouze číslo");
    }
    /*$disable=array();
    if(ifobject($id)){
            return("Jméno je obsazené");
    }*/
    return(false);
}
//$a=new object("create");
//$a->delete();
//======================================================================================name2name
/*function name2name($name1,$name2){
	$name='';

	if(!strpos($name1,'{and}') and !strpos($name2,'{and}')){
		     if(!$name2){
			$name=($name1);
		}elseif(!$name1){
			$name=($name2);
		}elseif($name1==$name2){
			$name=$name1.'+';
		}else{
			$name=$name1.' {and} '.$name2;

		}
	}else{
		$name1=explode('{and}',$name1);
		$name2=explode('{and}',$name2);
		$joint='';
		$array=array_merge($name1,$name2);
		$i=0;
		while($array[$i]){
			$name.=$joint.$array[$i];
			$joint=' , ';
			if($i==count($array)-1)$joint=' {and} ';
			$i++;
		}
		

	}

	//}elseif(){
	return($name);
}*/


function name2name($name1,$name2){
	$buildings=array();

	$name1=str_replace('{and}',',',$name1);
	$name1=explode(',',$name1);
	$name2=str_replace('{and}',',',$name2);
	$name2=explode(',',$name2);
	$name12=array_merge($name1,$name2);
	//print_r($name12);echo('<br>');

	$i=0;while($name12[$i]){
		$tn=$name12[$i];
		$count=strlen($tn);
		$tn=str_replace('+','',$tn);
		$count=$count-strlen($tn)+1;
		$tn=trim($tn);
		//echo($tn.$count.'<br>');
		
		if(strpos($tn,'_count')){
			//e($tn);
			$tn=substr($tn,1,strlen($tn)-2);//str_replace(array('{','}'),'',$tn);
			$tn=explode(';',$tn);
			//print_r($tn);
			$tn=$tn[1];
			//e($tn);
			$tn=str2list($tn);
			$tn=$tn['alt'];
			$tn=trim($tn);
			$tn='{'.$tn.'}';
			//e($tn);
		}
		//br();
		
		if(!$buildings[$tn])$buildings[$tn]=0;
		$buildings[$tn]+=$count;
		//print_r($buildings);		
		$i++;
	}
	//print_r($buildings);echo('<br>');

	$name='';
	$i=0;foreach($buildings as $tn=>$count){
		
		if($i==count($buildings)-1){
			$separator='';
		}elseif($i==count($buildings)-2){
			$separator=' {and} ';
		}else{
			$separator=', ';
		}
		$plus='';
		$ii=1;while($ii<=$count-1){$plus.='+';$ii++;}
		
		if($count>1 and substr($tn,0,1)=='{' and substr($tn,-1)=='}'){
			$tn=substr($tn,0,strlen($tn)-1);
			$tn.='_count'.$count.';alt='.substr($tn,1).$plus.'}';
		}else{
			$tn=$tn.$plus;		
		}
		$name.=$tn.$separator;
		//echo($name.$count.'-'..'<br>');
		$i++;
	}


	return($name);
}


//======================================================================================
?>
