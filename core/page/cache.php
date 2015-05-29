<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2015
   _____________________________

   core/page/cache.php

   Optimalizace
*/
//==============================





/*==================================================================================================cache_minimenu_[id]=*/
//e('SELECT `id` FROM `[mpx]pos_obj` WHERE `ww`=\''.$GLOBALS['ss']['ww'].'\' AND `type`=\'building\' AND `own`='.$GLOBALS['ss']['useid']);

$fileMM=tmpfile2($GLOBALS['ss']['useid'].round(time()/(3600*24)),'html','minimenu');
if(!file_exists($fileMM)){
    
    $pages=array();
    //$GLOBALS['get']["contextid"]=$GLOBALS['hl'];
    $array=sql_array('SELECT `id` FROM `[mpx]pos_obj` WHERE `ww`=\''.$GLOBALS['ss']['ww'].'\' AND `type`=\'building\' AND `own`='.$GLOBALS['ss']['useid']);
    foreach($array as $row){
        list($id)=$row;
        t('cache minimenu '.$id);
        $GLOBALS['get']["contextid"]=$id;


        $key='minimenu_'.$id;
        $value=subescape("minimenu");
        //e(unescape($value));br();
        $pages[$key]=$value;



    }
    
    fpc($fileMM, serialize($pages));

}else{
    $pages=unserialize(file_get_contents($fileMM));

}


/*==================================================================================================cache_create-unique_[id]=*/




$groups=array('master','main','wall','bridge','path','terrain');

foreach($groups as $group){
    $master=sql_1data("SELECT `id` FROM `[mpx]pos_obj` WHERE `own`='".$GLOBALS['ss']['useid']."' AND `group`=".sqlx($group)." AND `type`='building' LIMIT 1 ");
    
    if($master){
    
        $GLOBALS['ss']['unique_master']=$master;
        $GLOBALS['ss']['unique_func']='create';
        
        
        $key='create-unique_'.$group;
        $value=subescape("create-unique");
        $pages[$key]=$value;
        /*?>
        <div id="cache_create-unique_<?php e($group); ?>" style="display:none;">
        <?php eval(subpage("create-unique")); ?>
        </div>
        <?php*/
    
    }
    
}

/*==================================================================================================cache_create-build_[id]=*/



//cache_create-build_1000003
$buildings=sql_array("SELECT `id` FROM `[mpx]pos_obj` WHERE `name` LIKE '{%}' AND `type`='building' AND `ww`=0 ");
//$buildings=sql_array("SELECT `id` FROM `[mpx]pos_obj` WHERE `id`='1000003' ");
foreach($buildings as $building){
        $id=$building[0];
        
        $GLOBALS['ss']["object_build_id"]=$id;
        $GLOBALS['ss']["object_build_func"]='create';
        $GLOBALS['ss']["object_build_master"]=1234;
        $GLOBALS['ss']["master"]=1234;

        
        $key='create-build_'.$id;
        $value=subescape("create-build");
        $pages[$key]=$value;
        /*?>
        <div id="cache_create-build_<?php e($id); ?>" style="display:none;">
        <?php eval(subpage("create-build")); ?>
        </div>
        <?php*/
    
}

//================================================================================


$js1=nln.'cache=function(xkey){'.nln;
$js2=nln.'ifcache=function(xkey){'.nln;

$i=0;
foreach($pages as $key=>$value){
    //$key=str_replace('-','_',$key);
    
    $js1.=($i==0?'':'else')." if(xkey=='$key'){".nln;
    $js1.="return(unescape('$value'));".nln;
    $js1.='}';
    
    $js2.=($i==0?'':'else')." if(xkey=='$key'){".nln;
    $js2.="return(true);".nln;
    $js2.='}';
    
    
    $i++;
}
$js1.='else{'.nln;
$js1.="return(false);".nln;
$js1.='}};'.nln;

$js2.='else{'.nln;
$js2.="return(false);".nln;
$js2.='}};'.nln;

js(/*'alert(123);'.*/$js2.$js1);



?>










