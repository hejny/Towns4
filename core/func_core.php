<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2015
   _____________________________

   core/func_core.php

   Základní systémové funkce
*/
//==============================



//======================================================================================DISMANTLE
define("a_dismantle_help","");
function a_dismantle($id){
	
    if(/*$id==*/sql_1data('SELECT id FROM `[mpx]pos_obj` WHERE own='.$GLOBALS['ss']['useid'].' AND id='.$id) or $id=$GLOBALS['ss']['candismantle']){
        $fc=new hold(sql_1data("SELECT fc FROM `[mpx]pos_obj` WHERE id='$id'"));
        $tmp=new object($id);
        //$tmp->update(true);//s
        //$fc=new hold($tmp->fc);

        //print_r($fc);
        //$fc=$tmp->func->fs();
        $fc->multiply(1/-gr);
        //print_r($fc);
        
        $GLOBALS['ss']['use_object']->hold->takehold($fc);
        
        
        //$tmp->ww=-4;
        //$tmp->t=time();
        $tmp->deletex();
    }
}
//echo($GLOBALS['ss']['useid'].','.$GLOBALS['ss']['logid'].','.$GLOBALS['ss']['use_object']->name);
//a_dismantle(2000226);


//======================================================================================INFO

function a_list($cols,$where=0,$order=false,$limit=0){

    //----------------Injection

    if(strpos($cols,';')){
        $GLOBALS['ss']['query_output']->add('error','Char ; can not be in cols. ');
    }
    if(strpos($where,';')){
        $GLOBALS['ss']['query_output']->add('error','Char ; can not be in where. ');
    }
    if(strpos($order,';')){
        $GLOBALS['ss']['query_output']->add('error','Char ; can not be in order. ');
    }
    if(strpos($limit,';')){
        $GLOBALS['ss']['query_output']->add('error','Char ; can not be in limit. ');
    }


    //----------------allcols
    $allcols=array(
        'id',
        'name',
        'type',
        'origin',
        'func',
        'hold',
        'res',
        'profile',
        'set',
        'fp',
        'fs',
        'fc',
        'fr',
        'fx',
        'own',
        'superown',
        'ww',
        'x',
        'y'
    );
    //----------------cols

    $cols2=array();
    $cols=params($cols);
    foreach($cols as $col) {
        if(in_array($col,$allcols)) {
            $cols2[]=$col;
        }else{
            $GLOBALS['ss']['query_output']->add('error','Unknown col '.$col);
        }
    }
    $cols2=implode('`,`',$cols2);
    $cols2="`$cols2`";

    //----------------where - EDIT

    $where2=params($where);
    $where=array();
    $noww=true;

    foreach($where2 as $wher){

        if(substr($wher,0,2)=='ww') {
                 $noww=false;
        }elseif($wher=='building'){
                 $where[]="type='building'";
                //----------------
        }elseif($wher=='terrain'){
                 $where[]="type='terrain'";
                 //----------------
        }elseif($wher=='useid'){
                 $where[]="id=".useid;
                 //----------------
        }elseif($wher=='logid'){
                 $where[]="id=".logid;
                 //----------------
        }elseif($wher=='mainid'){
                 $where[]="name='".mainname()."'";
                 $where[]='own='.useid;
                 //----------------
        }elseif(substr($wher,0,4)=='box('){
                $wher=str_replace(array('box(',')'),'',$wher);

                list($minx,$miny,$maxx,$maxy)=explode(',',$wher);
                 $minx=intval($minx);
                 $miny=intval($miny);
                 $maxx=intval($maxx);
                 $maxy=intval($maxy);
                 $where[]="x>=$minx";
                 $where[]="y>=$miny";
                 $where[]="x<=$maxx";
                 $where[]="y<=$maxy";
                 //----------------
        }

    }

    if($noww){
        $where[]='ww=1';
    }

    //print_r($where);
    //----------------where

    $where2=array();
    foreach($where as $wher) {

        $wher=trim($wher);
        $q=false;

        foreach($allcols as $col){
            if(substr($wher,0,strlen($col))==$col){

                //$wher="`$col`".substr($wher,strlen($col));

                $q=true;
            }
        }
        $q=true;

        if($q){
          $where2[]=$wher;
        }else{
            $GLOBALS['ss']['query_output']->add('error','Unknown col in '.$wher);
        }



    }
    $where2=implode(' AND ',$where2);


    //----------------order
    if($order) {

        $order=params($order);
        $order2=array();
        foreach($order as $col) {
            if(in_array($col,$allcols)) {
                $order2[]=$col;
            }else{
                $GLOBALS['ss']['query_output']->add('error','Unknown col '.$col);
            }
        }
        $order2=implode('`,`',$order2);

        if($order2){
            $order2 = "ORDER BY `$order2`";
        }
    }
    //----------------limit

    if($limit){
        $limitnumber=intval($limit);
        $limit='LIMIT '.$limitnumber;

    }else{
        $limit='';
    }

    //----------------SELECT

    $objects=sql_array("SELECT $cols2 FROM [mpx]pos_obj_ter WHERE $where2 AND ".objt()." $order2 $limit");


    $GLOBALS['ss']['query_output']->add('objects',$objects);


}

//=======================================================================================PROFILE
define("a_profile_edit_help","id,key,value");
function a_profile_edit($id,$key,$value){
    if($id==$GLOBALS['ss']['useid'])$object=$GLOBALS['ss']['use_object'];
    if($id==$GLOBALS['ss']['logid'])$object=$GLOBALS['ss']['log_object'];
    if(!$object){$object=new object($id);if($object->own!=$GLOBALS['ss']['useid']){$GLOBALS['ss']['query_output']->add('error','no_own'); return;}$update=true;}
    if($key!="name"){
        $GLOBALS['ss']['query_output']->add("1",1);
        if($object->profile->val($key)!=$value){
            $GLOBALS['ss']['query_output']->add("success",lr("profile_$key").' '.lr('settings_changed'));
        }
        //r();
        //$GLOBALS['ss']['use_object']->xxx();
        //r($GLOBALS['ss']['use_object']->profile->vals2list());

        //e($value);
        //e($object->name);
        $object->profile->add($key,$value);


        //r($GLOBALS['ss']['use_object']->profile->vals2list());
    }else{
        //echo("SELECT 1 FROM objects WHERE name='".$value."' and id!='".$GLOBALS['ss']['useid']."'");
        $q=name_error($value);
        if(!$q){
            $object->name=$value;
            $GLOBALS['ss']['query_output']->add("success",lr('profile_'.($object->town).'name').' '.lr('settings_changed'));
        }else{
            //e('!');
            $GLOBALS['ss']['query_output']->add('error',$q);
        }
        //}else{
        //$GLOBALS['ss']['use_object']->name=$value;
        //if(sql_1data("SELECT 1 FROM objects WHERE name='".$value."' and id!='".$GLOBALS['ss']['useid']."'")){
        //    $GLOBALS['ss']['query_output']->add('error',"Jméno je už obsazené.");
        //}else{
        //    $GLOBALS['ss']['use_object']->name=$value;
        //}
    }
    if($update)$object->update();
}


//======================================================================================INFO

//@todo PH Odstranit tuhle zastaralou funkci a nahradit za list

define("a_info_help","[q={use,log,id}]");
function a_info($q="use"){
    if($q!="use" and $q!="log"){
        //r($q);
        $GLOBALS['ss']["tmp_object"]= new object($q);
        if(!$GLOBALS['ss']["tmp_object"]->loaded){
            $GLOBALS['ss']['query_output']->add('error',"Neexistující objekt");
            return;
        }
        $q="tmp";
    }
    //echo($GLOBALS['ss']['useid']);
    //echo($q."_object");
    //$GLOBALS['ss'][$q."_object"]->xxx();
    if($GLOBALS['ss']['use_object'] and $GLOBALS['ss'][$q."_object"]){
    $GLOBALS['ss']['query_output']->add("1",1);
    $GLOBALS['ss']['query_output']->add("id",$GLOBALS['ss'][$q."_object"]->id);
    $GLOBALS['ss']['query_output']->add("type",$GLOBALS['ss'][$q."_object"]->type);
    $GLOBALS['ss']['query_output']->add("fp",$GLOBALS['ss'][$q."_object"]->fp);
    $GLOBALS['ss']['query_output']->add("fs",$GLOBALS['ss'][$q."_object"]->fs);
    $GLOBALS['ss']['query_output']->add("fr",$GLOBALS['ss'][$q."_object"]->fr);
    $GLOBALS['ss']['query_output']->add("fx",$GLOBALS['ss'][$q."_object"]->fx);
    $GLOBALS['ss']['query_output']->add("name",$GLOBALS['ss'][$q."_object"]->name);
    $GLOBALS['ss']['query_output']->add("origin",implode(',',$GLOBALS['ss'][$q."_object"]->origin));
    //$GLOBALS['ss']['query_output']->add("password",$GLOBALS['ss'][$q."_object"]->password);
    $GLOBALS['ss']['query_output']->add("func",$GLOBALS['ss'][$q."_object"]->func->vals2str());
    $GLOBALS['ss']['query_output']->add("support",$GLOBALS['ss'][$q."_object"]->support());
    $GLOBALS['ss']['query_output']->add("profile",$GLOBALS['ss'][$q."_object"]->profile->vals2str());
    $GLOBALS['ss']['query_output']->add("hold",$GLOBALS['ss'][$q."_object"]->hold->vals2str());
    $GLOBALS['ss']['query_output']->add("own",$GLOBALS['ss'][$q."_object"]->own);
    $GLOBALS['ss']['query_output']->add("superown",$GLOBALS['ss'][$q."_object"]->superown);
    $GLOBALS['ss']['query_output']->add("ownname",$GLOBALS['ss'][$q."_object"]->ownname);
    $GLOBALS['ss']['query_output']->add("own2",$GLOBALS['ss'][$q."_object"]->own2);
    //r($GLOBALS['ss'][$q."_object"]->own2);
    //$GLOBALS['ss']['query_output']->add("in2",$GLOBALS['ss'][$q."_object"]->in2);
    $GLOBALS['ss']['query_output']->add("t",$GLOBALS['ss'][$q."_object"]->t);
    $GLOBALS['ss']['query_output']->add("tasks",$GLOBALS['ss'][$q."_object"]->tasks);
    $GLOBALS['ss']['query_output']->add("ww",$GLOBALS['ss'][$q."_object"]->ww);
    $GLOBALS['ss']['query_output']->add("x",$GLOBALS['ss'][$q."_object"]->x);
    $GLOBALS['ss']['query_output']->add("y",$GLOBALS['ss'][$q."_object"]->y);
    //r($GLOBALS['ss']['query_output']->vals2str(),1);
    }
}
?>
