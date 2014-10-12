<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/create/func_core.php

   Stavící funkce systému
*/
//==============================




define('a_create_cooldown',true);
function a_create($id,$x=0,$y=0,$rot=0,$test=false,$tmaster=false){
    //e("$id,$x,$y,$rot");
     //if(!$id){$test=1;}else{$test=0;}
     if($test){$GLOBALS['ss']["query_output"]=new vals();}


    if(substr($x,0,1)=='+'){
	$x=$GLOBALS['hl_x']-(-floatval(substr($x,1)));
    }
    if(substr($y,0,1)=='+'){
	$y=$GLOBALS['hl_y']-(-floatval(substr($y,1)));
    }

    //r("$id,$x,$y,$rot,".$GLOBALS['hl_x'].','.floatval(substr($x,0)));
    //require(root."control/func_map.php");
    //if(/*sql_1data("SELECT hard FROM ".mpx."map WHERE x=ROUND(".($x).") AND y=ROUND(".($y).") LIMIT 1")-0==0 or */true){

$res=sql_1data("SELECT res FROM ".mpx."objects WHERE id='$id'");
//mail('ph@towns.cz','tmp',$res);

if(substr($res,0,1)=='{' or strpos($res,'{}')){           
$x=round($x);
$y=round($y);
$GLOBALS['ss']["query_output"]->add("nocd",1);
}
$rx=round($x);
$ry=round($y);    
    
    if(/*!floatval(sql_1data("SELECT COUNT(1) FROM `".mpx."objects`  WHERE `ww`=".$GLOBALS['ss']["ww"]." AND  `x`=$rx AND `y`=$ry AND `own`!='".useid."' LIMIT 1"))*/true){    
    
    //OLDHARD//sql_1data("SELECT hard FROM ".mpx."map WHERE x=ROUND(".($x).") AND y=ROUND(".($y).") LIMIT 1"))
    $hard=hard($rx,$ry);
    
    //e($id);br();
    //e("hard=$hard");br();
    //e("resistance=".supportF($id,'resistance','hard'));br();
    
    if($x>=0 and $y>=0 and $x<=mapsize and $y<=mapsize and $hard<supportF($id,'resistance','hard')){
    
    //print_r(sql_array("SELECT id,name FROM ".mpx."objects WHERE own!='".$GLOBALS['ss']['useid']."'AND `ww`=".$GLOBALS['ss']["ww"]." AND POW($x-x,2)+POW($y-y,2)<=POW(collapse,2)"));
    if(intval(sql_1data("SELECT COUNT(1) FROM ".mpx."objects WHERE own!='".$GLOBALS['ss']['useid']."'AND `ww`=".$GLOBALS['ss']["ww"]." AND POW($x-x,2)+POW($y-y,2)<=POW(collapse,2)"))==0){
       
    if(intval(sql_1data("SELECT COUNT(1) FROM ".mpx."objects WHERE own='".$GLOBALS['ss']['useid']."'AND `ww`=".$GLOBALS['ss']["ww"]." AND POW($x-x,2)+POW($y-y,2)<=POW(expand,2)"))>=1){
        

        $fc=new hold(sql_1data("SELECT fc FROM ".mpx."objects WHERE id='$id'"));
        if((!$test)?($GLOBALS['ss']["use_object"]->hold->takehold($fc)):($GLOBALS['ss']["use_object"]->hold->testchange($fc))){
            
            if($rot and strpos($res,'/1.png'))$res=str_replace('1.png',(($rot/15)+1).'.png',$res);
            
	    if(substr($res,0,1)!='{' and !strpos($res,'{}')){
		$res=explode(':',$res);$res=$res[0].':'.$res[1].':'.$res[2];
	    }

	
	    $tol=sqrt(2)/2;
	    //define('create_error',"SELECT id FROM `".mpx."objects`  WHERE `ww`=".$GLOBALS['ss']["ww"]." AND  `x`>$rx-$tol AND `y`>$ry-$tol AND  `x`<$rx+$tol AND `y`<$ry+$tol AND `own`='".useid."' ORDER BY POW(`x`-$rx,2)+POW(`y`-$ry,2) LIMIT 1");
	    
	   //foreach(sql_array("SELECT id,name,own FROM `".mpx."objects`  WHERE `ww`=".$GLOBALS['ss']["ww"]." AND `own`='".useid."' AND  `x`>$rx-$tol AND `y`>$ry-$tol AND  `x`<$rx+$tol AND `y`<$ry+$tol ORDER BY POW(`x`-$rx,2)+POW(`y`-$ry,2)") as $row){print_r($row);br();}


	    $func=func2list(sql_1data('SELECT func FROM [mpx]objects WHERE id='.$id));
            list(list($jid,$jname,$jown,$jfs,$jfp,$jfunc,$jorigin,$jres))=sql_array("SELECT id,name,own,fs,fp,func,origin,res FROM `".mpx."objects`  WHERE `ww`=".$GLOBALS['ss']["ww"]."  AND `own`='".$GLOBALS['ss']['useid']."' AND type='building' AND `x`>$rx-$tol AND `y`>$ry-$tol AND  `x`<$rx+$tol AND `y`<$ry+$tol ORDER BY POW(`x`-$rx,2)+POW(`y`-$ry,2) LIMIT 1");// AND `own`='".useid."'
	    if(!$jid){//e('ahoj');
            
		
		if($func['join']['profile']['type']==2){
			define('object_build',true);
			define('create_error',lr('create_error_join_type2'));
			$GLOBALS['ss']["query_output"]->add("error",lr("create_error_join_type2"));
			return;
		}else{

		if(!$test){
			$nextid=nextid();
			define('object_id',$nextid);
			$GLOBALS['object_ids']=array($nextid);
			sql_query("INSERT INTO `".mpx."objects` (`id`, `name`, `type`, `dev`, `origin`, `fs`, `fp`, `fc`, `fr`, `fx`, `func`, `hold`, `res`, `profile`, `set`, `hard`, `expand`, `own`, `in`, `ww`, `x`, `y`, `t`) 
SELECT ".$nextid.", `name`, `type`, `dev`, `origin`, `fs`, `fp`, `fc`, `fr`, `fx`, `func`, `hold`, CONCAT('$res',':$rot'), `profile`, 'x', `hard`, `expand`,'".$GLOBALS['ss']['useid']."', `in`, ".$GLOBALS['ss']["ww"].", $x, $y, ".time()." FROM `".mpx."objects` WHERE id='$id'");
		}

		$GLOBALS['ss']["query_output"]->add("1",1);

		}
		//define('create_ok','{create_ok_place}');

	    }else{
		//e('bhoj');

		$jfunc=func2list($jfunc);

		if($func['join']['profile']['type']==3){
			define('object_build',true);//die(1);
			define('create_error',lr('create_error_join_type3'));
			$GLOBALS['ss']["query_output"]->add("error",lr('create_error_join_type3'));
			return;
		}elseif($jfunc['join']['profile']['type']==3){
			define('object_build',true);//die(2);
			define('create_error',lr('create_error_join_type3x'));
			$GLOBALS['ss']["query_output"]->add("error",lr('create_error_join_type3x'));
			return;
		}elseif($func['join']['profile']['type']==1 or $func['join']['profile']['type']==4){
			define('object_build',true);//die(3);
			define('create_error',lr('create_error_join_type1'));
			$GLOBALS['ss']["query_output"]->add("error",lr('create_error_join_type1'));
			return;
		}elseif($jfunc['join']['profile']['type']==4 and strpos($jres,'[-4,-4,')===false){
			define('object_build',true);//die(4);
			define('create_error',lr('create_error_join_type4'));
			$GLOBALS['ss']["query_output"]->add("error",lr('create_error_join_type4'));
			return;
		}elseif(!$jorigin){
			define('object_build',true);//die(5);
			define('create_error',lr('create_error_join_noorigin'));
			$GLOBALS['ss']["query_output"]->add("error",lr('create_error_join_noorigin'));
			return;
		}elseif($jown!=$GLOBALS['ss']['useid']){
			define('object_build',true);
			define('create_error',lr('create_error_join_noown'));
			$GLOBALS['ss']["query_output"]->add("error",lr('create_error_join_noown'));
			return;
		}elseif($jname==id2name($GLOBALS['config']['register_building'])){
			define('object_build',true);
			define('create_error',lr('create_error_join_main'));
			$GLOBALS['ss']["query_output"]->add("error",lr('create_error_join_main'));
			return;
		}elseif($tmaster?$jid==$tmaster:$jid==$GLOBALS['ss']["aac_object"]->id){
			define('object_build',true);
			define('create_error',lr('create_error_join_self'));
			$GLOBALS['ss']["query_output"]->add("error",lr('create_error_join_self'));
			return;
		}elseif($jfs!=$jfp){
			define('object_build',true);
			define('create_error',lr('create_error_join_fsfp'));
			$GLOBALS['ss']["query_output"]->add("error",lr('create_error_join_fsfp'));
			return;
		}else{
		

			define('create_ok',contentlang(lr('create_ok_join',$jname)));
			if(!$test){
				define('join_id',$jid);
				define('object_id',$jid);
				$GLOBALS['object_ids']=array($jid);
				$joint=new object($jid);
				$joint->join($id,$res.':'.$rot,$rot);
				$joint->update(true,true);
				unset($joint);
			}
			$GLOBALS['ss']["query_output"]->add("1",1);

			

		}


	    }
		
            //POZDEJI//changemap($x,$y);

        if(!$test){
//==============================OPRAVA SPOJů
$res=trim($res);
if(substr($res,0,1)=='{'){
//mail('ph@towns.cz','tmp',$res);
//---------------------------------------------------------------

//print_r($res);
$name=sql_1data("SELECT name FROM ".mpx."objects WHERE id='$id'");
$pres=$res;

foreach(array(array($x,$y),array($x+1,$y),array($x,$y-1),array($x-1,$y),array($x,$y+1)) as $tmp){list($xx,$yy)=$tmp;


$originname=sql_1data("SELECT `name` FROM ".mpx."objects WHERE ww='".$GLOBALS['ss']["ww"]."' AND x=$xx AND y=$yy");
$originres =sql_1data("SELECT `res` FROM ".mpx."objects WHERE ww='0' AND `name`='$originname'");
if($originres){
	$res=$originres;
//}else{
	//$res=$pres;
//}

$res='start'.$res.'stop';
$res=str_replace(array('start{','}stop'),'',$res);
$res=explode('}{',$res);



     
$near=array();
$groupsame="(`name`='$name' ".($func['join']['profile']['group']?"OR `func` LIKE '%group[7]5[10]".$func['join']['profile']['group']."%'":'').")";
$near[0]=intval(sql_1data("SELECT COUNT(1) FROM ".mpx."objects WHERE own='".$GLOBALS['ss']['useid']."' AND $groupsame AND ww='".$GLOBALS['ss']["ww"]."' AND x=$xx+1 AND y=$yy"))>=1?1:0;
$near[1]=intval(sql_1data("SELECT COUNT(1) FROM ".mpx."objects WHERE own='".$GLOBALS['ss']['useid']."' AND $groupsame AND ww='".$GLOBALS['ss']["ww"]."' AND x=$xx AND y=$yy-1"))>=1?1:0;
$near[2]=intval(sql_1data("SELECT COUNT(1) FROM ".mpx."objects WHERE own='".$GLOBALS['ss']['useid']."' AND $groupsame AND ww='".$GLOBALS['ss']["ww"]."' AND x=$xx-1 AND y=$yy"))>=1?1:0;
$near[3]=intval(sql_1data("SELECT COUNT(1) FROM ".mpx."objects WHERE own='".$GLOBALS['ss']['useid']."' AND $groupsame AND ww='".$GLOBALS['ss']["ww"]."' AND x=$xx AND y=$yy+1"))>=1?1:0;

    if($near==array(0,0,0,0)){$i=0;$rot=0;}
elseif($near==array(1,0,0,0)){$i=1;$rot=0;}
elseif($near==array(0,1,0,0)){$i=1;$rot=90;}
elseif($near==array(0,0,1,0)){$i=1;$rot=180;}
elseif($near==array(0,0,0,1)){$i=1;$rot=270;}
elseif($near==array(1,1,0,0)){$i=2;$rot=0;}
elseif($near==array(1,0,1,0)){$i=3;$rot=0;}
elseif($near==array(1,0,0,1)){$i=2;$rot=270;}
elseif($near==array(0,1,1,0)){$i=2;$rot=90;}
elseif($near==array(0,1,0,1)){$i=3;$rot=90;}
elseif($near==array(0,0,1,1)){$i=2;$rot=180;}//Problém
elseif($near==array(1,1,1,0)){$i=4;$rot=0;}
elseif($near==array(1,1,0,1)){$i=4;$rot=270;}
elseif($near==array(1,0,1,1)){$i=4;$rot=180;}
elseif($near==array(0,1,1,1)){$i=4;$rot=90;}
elseif($near==array(1,1,1,1)){$i=5;$rot=0;}

$resx=$res[$i];
//e($resx);
$resx=explode(':',$resx);
if(!$resx[3]){$resx[3]=0;}else{$resx[3]=intval($resx[3]);}
$resx[3]=$resx[3]+$rot;
if($resx[3]>=360)$resx[3]=$resx[3]-360;
if($resx[3]<0)$resx[3]=$resx[3]+360;
$resx=implode(':',$resx);


sql_query("UPDATE ".mpx."objects SET res='$resx' WHERE own='".$GLOBALS['ss']['useid']."' AND $groupsame AND ww='".$GLOBALS['ss']["ww"]."' AND x=$xx AND y=$yy");

$GLOBALS['object_ids'][]=sql_1data("SELECT `id` FROM ".mpx."objects WHERE own='".$GLOBALS['ss']['useid']."' AND $groupsame AND ww='".$GLOBALS['ss']["ww"]."' AND x=$xx AND y=$yy");

}
}
//die();
//define('object_build',true);
//---------------------------------------------------------------
}elseif(strpos($res,'{}')){
//---------------------------------------------------------------
//$res='start'.$res.'stop';
//$res=str_replace(array('start(',')stop'),'',$res);


foreach(array(array($x,$y),array($x+1,$y),array($x,$y-1),array($x-1,$y),array($x,$y+1)) as $tmp){list($xx,$yy)=$tmp;
$name=sql_1data("SELECT name FROM ".mpx."objects WHERE id='$id'");
     
$near=array();
$groupsame="(`name`='$name' OR `func` LIKE '%group[7]5[10]".$func['join']['profile']['group']."%')";
$near[0]=intval(sql_1data("SELECT COUNT(1) FROM ".mpx."objects WHERE own='".$GLOBALS['ss']['useid']."' AND $groupsame AND ww='".$GLOBALS['ss']["ww"]."' AND x=$xx+1 AND y=$yy"))>=1?1:0;
$near[1]=intval(sql_1data("SELECT COUNT(1) FROM ".mpx."objects WHERE own='".$GLOBALS['ss']['useid']."' AND $groupsame AND ww='".$GLOBALS['ss']["ww"]."' AND x=$xx AND y=$yy-1"))>=1?1:0;
$near[2]=intval(sql_1data("SELECT COUNT(1) FROM ".mpx."objects WHERE own='".$GLOBALS['ss']['useid']."' AND $groupsame AND ww='".$GLOBALS['ss']["ww"]."' AND x=$xx-1 AND y=$yy"))>=1?1:0;
$near[3]=intval(sql_1data("SELECT COUNT(1) FROM ".mpx."objects WHERE own='".$GLOBALS['ss']['useid']."' AND $groupsame AND ww='".$GLOBALS['ss']["ww"]."' AND x=$xx AND y=$yy+1"))>=1?1:0;

    if($near==array(1,1,1,1)){$i=1;}
elseif($near==array(1,1,0,1)){$i=2;}
elseif($near==array(1,0,1,1)){$i=3;}
elseif($near==array(1,0,0,1)){$i=4;}
elseif($near==array(1,1,1,0)){$i=5;}
elseif($near==array(1,1,0,0)){$i=6;}
elseif($near==array(1,0,1,0)){$i=7;}
elseif($near==array(1,0,0,0)){$i=8;}
elseif($near==array(0,1,1,1)){$i=9;}
elseif($near==array(0,1,0,1)){$i=10;}
elseif($near==array(0,0,1,1)){$i=11;}
elseif($near==array(0,0,0,1)){$i=12;}
elseif($near==array(0,1,1,0)){$i=13;}
elseif($near==array(0,1,0,0)){$i=14;}
elseif($near==array(0,0,1,0)){$i=15;}
elseif($near==array(0,0,0,0)){$i=16;}

$resx=str_replace('{}',$i,$res);
r($resx);
sql_query("UPDATE ".mpx."objects SET res='$resx' WHERE own='".$GLOBALS['ss']['useid']."' AND $groupsame AND ww='".$GLOBALS['ss']["ww"]."' AND x=$xx AND y=$yy");

$GLOBALS['object_ids'][]=sql_1data("SELECT `id` FROM ".mpx."objects WHERE own='".$GLOBALS['ss']['useid']."' AND $groupsame AND ww='".$GLOBALS['ss']["ww"]."' AND x=$xx AND y=$yy");

}
//define('object_build',true);

//---------------------------------------------------------------  
}
changemap($x,$y);
$GLOBALS['ss']['use_object']->resurkey();
	}
//==============================

    
         }else{
            define('object_build',true);
            define('create_error',lr('create_error_price'));
            $GLOBALS['ss']["query_output"]->add("error",lr('create_error_price'));
        }/**/
    }else{
        define('object_build',true);
        define('create_error',lr('create_error_expand'));
        $GLOBALS['ss']["query_output"]->add("error",lr('create_error_expand'));
    }}else{
        define('object_build',true);
        define('create_error',lr('create_error_collapse'));
        $GLOBALS['ss']["query_output"]->add("error",lr('create_error_collapse'));
    }}else{
        define('object_build',true);
        //$sql="SELECT (SELECT IF(`terrain`='t1' OR `terrain`='t11',1,0) FROM `".mpx."map`  WHERE `".mpx."map`.`ww`=".$GLOBALS['ss']["ww"]." AND  `".mpx."map`.`x`=$y AND `".mpx."map`.`y`=$x)+(SELECT SUM(`".mpx."objects`. `hard`) FROM `".mpx."objects` WHERE `".mpx."objects`.`ww`=".$GLOBALS['ss']["ww"]." AND  ROUND(`".mpx."objects`.`x`)=$y AND ROUND(`".mpx."objects`.`y`)=$x)";
        //$hard=sql_1data($sql);// WHERE `ww`=".$GLOBALS['ss']["ww"]." AND `x`=$x AND `y`=$y");
        define('create_error',lr('create_error_resistance'));
        $GLOBALS['ss']["query_output"]->add("error",lr('create_error_resistance'));
    }}else{
        define('object_build',true);
        define('create_error',lr('create_error_duplicite'));
        $GLOBALS['ss']["query_output"]->add("error",lr('create_error_duplicite'));
    }
}

//================================================================================================================
function a_replace($id,$x=0,$y=0,$rot=0){
    r("$id,$x=0,$y=0,$rot=0");
    //require(root."control/func_map.php");
    //if(/*sql_1data("SELECT hard FROM ".mpx."map WHERE x=ROUND(".($x).") AND y=ROUND(".($y).") LIMIT 1")-0==0 or */true){

$res=sql_1data("SELECT res FROM ".mpx."objects WHERE id='$id'");

$rx=round($x);
$ry=round($y);    
    
    if(!floatval(sql_1data("SELECT COUNT(1) FROM `".mpx."objects`  WHERE `ww`=".$GLOBALS['ss']["ww"]." AND `id`!='".$GLOBALS['ss']['aac_object']->id."' AND `x`=$rx AND `y`=$ry LIMIT 1"))){    
    
    $hard=hard($rx,$ry);
    if($hard<supportF($id,'resistance','hard')){
    
    if(intval(sql_1data("SELECT COUNT(1) FROM ".mpx."objects WHERE own!='".$GLOBALS['ss']['useid']."'AND `ww`=".$GLOBALS['ss']["ww"]." AND `id`!='".$GLOBALS['ss']['aac_object']->id."' AND POW($x-x,2)+POW($y-y,2)<=POW(collapse,2)"))==0){
       
    if(intval(sql_1data("SELECT COUNT(1) FROM ".mpx."objects WHERE own='".$GLOBALS['ss']['useid']."' AND `ww`=".$GLOBALS['ss']["ww"].""))<=1/* and intval(sql_1data("SELECT COUNT(1) FROM ".mpx."objects WHERE own='".$GLOBALS['ss']['useid']."'AND `ww`=".$GLOBALS['ss']["ww"]." AND `id`!='".$GLOBALS['ss']['aac_object']->id."' AND POW($x-x,2)+POW($y-y,2)<=POW(expand,2)"))>=1*/){
        
        $GLOBALS['ss']['aac_object']->x=$x;
        $GLOBALS['ss']['aac_object']->y=$y;
        $GLOBALS['ss']['aac_object']->res=$res.':'.$rot;
         $GLOBALS['ss']['aac_object']->update();
        //sql_query("UPDATE`".mpx."objects` (`id`, `name`, `type`, `dev`, `fs`, `fp`, `fr`, `fx`, `func`, `hold`, `res`, `profile`, `set`, `hard`, `expand`, `own`, `in`, `ww`, `x`, `y`, `t`) 
        //SELECT ".nextid().", `name`, `type`, `dev`, `fs`, `fp`, `fr`, `fx`, `func`, `hold`, CONCAT('$res',':$rot'), `profile`, 'x', `hard`, `expand`,'".$GLOBALS['ss']['useid']."', `in`, ".$GLOBALS['ss']["ww"].", $x, $y, ".time()." FROM `".mpx."objects` WHERE id='$id'");
         
        
        $GLOBALS['ss']["query_output"]->add("1",1);
    }else{
        define('object_build',true);
        define('create_error',lr('replace_error_only'));
        $GLOBALS['ss']["query_output"]->add("error",lr('replace_error_expand'));
    }}else{
        define('object_build',true);
        define('create_error',lr('replace_error_collapse'));
        $GLOBALS['ss']["query_output"]->add("error",lr('replace_error_collapse'));
    }}else{
        define('object_build',true);
        //$sql="SELECT (SELECT IF(`terrain`='t1' OR `terrain`='t11',1,0) FROM `".mpx."map`  WHERE `".mpx."map`.`ww`=".$GLOBALS['ss']["ww"]." AND  `".mpx."map`.`x`=$y AND `".mpx."map`.`y`=$x)+(SELECT SUM(`".mpx."objects`. `hard`) FROM `".mpx."objects` WHERE `".mpx."objects`.`ww`=".$GLOBALS['ss']["ww"]." AND  ROUND(`".mpx."objects`.`x`)=$y AND ROUND(`".mpx."objects`.`y`)=$x)";
        //$hard=sql_1data($sql);// WHERE `ww`=".$GLOBALS['ss']["ww"]." AND `x`=$x AND `y`=$y");
        define('create_error',lr('replace_error_resistance'));
        $GLOBALS['ss']["query_output"]->add("error",lr('replace_error_resistance'));
    }}else{
        define('object_build',true);
        define('create_error',lr('replace_error_duplicite'));
        $GLOBALS['ss']["query_output"]->add("error",lr('replace_error_duplicite'));
    }
}


//================================================================================================================
define('a_repair_cooldown',true);
function a_repair(){

            //$price=new hold($GLOBALS['ss']["aac_object"]->fc);
            //$price->multiply($GLOBALS['ss']["aac_object"]->fp/$GLOBALS['ss']["aac_object"]->fs);
		//$price=ceil(($GLOBALS['ss']["aac_object"]->fs-$GLOBALS['ss']["aac_object"]->fp)/count($GLOBALS['ss']["aac_object"]->origin));

		$repair_fuel=repair_fuel($GLOBALS['ss']["aac_object"]->id);
		$price=round((1-($GLOBALS['ss']["aac_object"]->fp/$GLOBALS['ss']["aac_object"]->fs))*$repair_fuel);

		//echo($price);
		$price=new hold(/*$fc*/'fuel='.$price);
		//$GLOBALS['ss']["use_object"]->hold->showimg();
            if($GLOBALS['ss']["use_object"]->hold->takehold($price)){
		//$GLOBALS['ss']["use_object"]->hold->showimg();
                $GLOBALS['ss']["aac_object"]->fp=$GLOBALS['ss']["aac_object"]->fs;
                 $GLOBALS['ss']["query_output"]->add("success",lr('repair_success'));
                 $GLOBALS['ss']["query_output"]->add("1",1);
            }else{
                $GLOBALS['ss']["query_output"]->add("error",lr('repair_error_price'));
        }
    
    
}
//================================================================================================================
/*define('a_repair_cooldown',true);
function a_upgrade($funcname,$paramname,$value){
	//e("$funcname,$paramname,$value");

	if($GLOBALS['ss']["aac_object"]->fp==$GLOBALS['ss']["aac_object"]->fs){
	
	$price=new hold($GLOBALS['ss']["aac_object"]->func->fc()->vals2str());
	$funcx=new func($GLOBALS['ss']["aac_object"]->func->vals2str());
	$funcx->addF($funcname,$paramname,$value);
	$pricex=$funcx->fc();
	//$pricex->showimg(true);
	//$price->showimg(true);
	$pricex->takehold($price,true);
	//$pricex->showimg(true);

	if($GLOBALS['ss']["use_object"]->hold->takehold($pricex)){
		$GLOBALS['ss']["aac_object"]->func->addF($funcname,$paramname,$value);//->func=$funcx;
		//r('a');
		$GLOBALS['ss']["aac_object"]->update(true);
		//r('b');
		$GLOBALS['ss']["aac_object"]->fp=$GLOBALS['ss']["aac_object"]->fs;
		$GLOBALS['ss']["aac_object"]->loaded=true;
		//$GLOBALS['ss']["aac_object"]->fs=$funcx->fs();
		//$GLOBALS['ss']["aac_object"]->fc=$funcx->fc();
		//r('c');
		$GLOBALS['ss']["aac_object"]->update(true);
		//r('d');
                $GLOBALS['ss']["query_output"]->add("success",lr('upgrade_success'));
                $GLOBALS['ss']["query_output"]->add("1",1);
        }else{
                $GLOBALS['ss']["query_output"]->add("error",lr('upgrade_error_price'));
        }
	}else{
                $GLOBALS['ss']["query_output"]->add("error",lr('upgrade_error_fpnotfs'));
        }

}*/
function a_upgrade($prime,$count=1){
	$joint=sql_1data('SELECT fc FROM `[mpx]objects` WHERE `type`=\'prime\' and `id`=\''.$prime.'\' ');//new object($prime);
	$fc=new hold($joint);
	$fc->multiply(20*$count);
	if(!$GLOBALS['ss']['use_object']->hold->testhold($fc)){
		if(!$GLOBALS['ss']['use_object']->hold->testchange($fc)){
			$GLOBALS['ss']["query_output"]->add('error',lr('upgrade_error_price'));
			$q=false;
			//return();
		}else{$q=true;
			//info(lr('upgrade_change'));
			//ahref(lr('add_prime'),'e=content;ee=create-upgrade;q='.$id.'.upgrade './*$funcname.','.$paramname.','.($value-(-$step))*/.$id);
		}
	}else{$q=true;
		//ahref(lr('add_prime'),'e=content;ee=create-upgrade;q='.$id.'.upgrade './*$funcname.','.$paramname.','.($value-(-$step))*/.$id);

	}
	if($q){
		
		$GLOBALS['ss']['use_object']->hold->takehold($fc);
		$i=1;
		while($i<=$count){$i++;$GLOBALS['ss']['aac_object']->join($prime,NULL,NULL,true);}
		$GLOBALS['ss']['aac_object']->update(true,true);
		//$GLOBALS['ss']['use_object']->update(/*true,true*/);
		$GLOBALS['ss']['use_object']->resurkey();
		$GLOBALS['ss']["query_output"]->add("success",lr('upgrade_success'));
		$GLOBALS['ss']["query_output"]->add("1",1);
	}

}
?>
