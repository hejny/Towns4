<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2015
   _____________________________

   core/create/unique.php

   Seznam budov na postavení
*/
//==============================




window(lr('title_build')/*,520,500*/);

//r($GLOBALS['get']);



if($GLOBALS['get']['master']){
$GLOBALS['ss']['unique_master']=$GLOBALS['get']['master'];
}
if($GLOBALS['get']['func']){
$GLOBALS['ss']['unique_func']=$GLOBALS['get']['func'];
}


if($GLOBALS['ss']['unique_master'] and $GLOBALS['ss']['unique_func']){
$unique_func=$GLOBALS['ss']['unique_func'];
$object=new object($GLOBALS['ss']['unique_master']);
$GLOBALS['ss']['master']=$object->id;


$GLOBALS['description']=$object->func->profile($unique_func,'description');
$maxfs=$object->supportF($unique_func,'maxfs');
$func=$object->func->vals2list();
$limit=$func[$unique_func]['params']['limit'][0]*$func[$unique_func]['params']['limit'][1];

$group=$func[$unique_func]['profile']['group'];
$groupx=$group;

$GLOBALS['groupby']='';
if($group){
	infob(contentlang($object->name));
	
	if($groupx!='extended'){
	    $group="func LIKE '%group=class[5]group[3]1[5]profile[3]profile[5]group[7]5[10]$group%'";//" AND name!='".$object->name."'";
    }else{
        $group=/*"func LIKE '%group=class[5]group[3]1[5]profile[3]profile[5]group[7]5[10]$group%'".*/' own='.$object->own." AND name!='".mainname()."'";//" AND name!='".$object->name."'";
		$GLOBALS['groupby']='GROUP BY name';
    }
}else{
	$group="func NOT LIKE '%group=class[5]group[3]1[5]profile[3]profile[5]group[7]5[10]%'";
}


/*if($limit<11){
	infob(lr('unique_from',$object->name));

	$limit=$func[$unique_func]['profile']['limit'];

	if($limit){
	if(is_array($limit)){
		$limit='(id='.implode(' OR id=',$limit).')';
	}else{
		$limit='(id='.$limit.')';
	}
	}

	$GLOBALS['where']="own=0 AND ww=0 AND fs<=".$maxfs.' AND '.$limit.' AND '.$group;
}else*/if(1){
	
	//$GLOBALS['where']="own='".$GLOBALS['ss']['useid']."' AND name!='".id2name($GLOBALS['config']['register_building'])."' AND fs<=".$maxfs.' GROUP BY name'/**/;

	//$q=submenu(array("content","create-unique"),array(/*"unique_help",*/"unique_basic",/*"unique_own","unique_user"*/),1,'create');
	$GLOBALS['where']=($groupx=='extended'?'':" ww=0 AND ").$group;

	/*if($q==1){
	    contenu_a();
	    $GLOBALS['ss']["helppage"]='unique';
	    $GLOBALS['nowidth']=true;
	    eval(subpage('help'));
	    contenu_b();
	}*/
	if($q==1){$GLOBALS['where']="ww='0' AND name!='".id2name($GLOBALS['config']['register_building'])."' AND fs<=".$maxfs.' AND '.$group/**/;}
	//elseif($q==3){$GLOBALS['where']="own='".$GLOBALS['ss']['useid']."' AND name!='".id2name($GLOBALS['config']['register_building'])."' AND fs<=".$maxfs.' GROUP BY CONCAT(name,fs)'/**/;}
	elseif($q==2){$GLOBALS['author']=true;$GLOBALS['where']="`profile` LIKE '%public=y%' AND name!='".id2name($GLOBALS['config']['register_building'])."' AND fs<=".$maxfs.' GROUP BY CONCAT(name,fs)'/**/;}

} 

/*if($q!=1)*/eval(subpage("stat2"));


}else{
error('!id');
w_close('create-unique');
}
?>
