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

    if(/*$id==*/sql_1data('SELECT id FROM `[mpx]pos_obj` WHERE (`own`='.$GLOBALS['ss']['useid'].' OR `own`='.$GLOBALS['ss']['logid'].') AND id='.sqlx(intval($id))) or $id==$GLOBALS['ss']['candismantle']){


        if(sql_1data("SELECT name FROM `[mpx]pos_obj` WHERE id='$id'")==mainname()){
            $GLOBALS['ss']['query_output']->add('error',lr('dismantle_error_main_building'));
        }else{

            $fc = new hold(sql_1data("SELECT fc FROM `[mpx]pos_obj` WHERE id='$id'"));
            $tmp = new object($id);

            //$tmp->update(true);//s
            //$fc=new hold($tmp->fc);

            //print_r($fc);
            //$fc=$tmp->func->fs();
            $fc->multiply(1 / -gr);
            //print_r($fc);

            $GLOBALS['ss']['use_object']->hold->takehold($fc);


            //$tmp->ww=-4;
            //$tmp->t=time();
            $tmp->deletex();
        }

    }else{
        $GLOBALS['ss']['query_output']->add('error',lr('dismantle_error_not_allowed'));
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
    //$object['func']['group']['profile']['group']

    $allcols=array(
        'id',
        'name',
        '_name',
        'type',
        'origin',
        'func',
        'group',
        'expand',
        'block',
        'attack',
        'hold',
        'res',
        'resurl',
        'profile',
        'fp',
        'fs',
        'fc',
        'fr',
        'fx',
        'own',
        'superown',
        'ww',
        'x',
        'y',
        'traceid',
        'starttime',
        'readytime',
        'stoptime'
    );
    //----------------cols

    $cols2=array();
    $cols=params($cols);
    foreach($cols as $col) {
        if(in_array($col,$allcols)) {

            if($col=='resurl'){
                $cols2[]="`res` AS `resurl`";
                if(!in_array("`type`",$cols2))$cols2[]="`type`";
                if(!in_array("`x`",$cols2))$cols2[]="`x`";
                if(!in_array("`y`",$cols2))$cols2[]="`y`";

            }elseif($col=='_name'){
                $cols2[]="`name` AS `_name`";
            }else{
                if(!in_array("`$col`",$cols2))
                    $cols2[]="`$col`";
            }


        }else{
            $GLOBALS['ss']['query_output']->add('error','Unknown col '.$col);
        }
    }
    $cols2=implode(',',$cols2);

    //----------------where - EDIT

    $where2=params($where);
    $where=array();
    $noww=true;

    foreach($where2 as $wher){

        if(substr($wher,0,2)=='ww') {
            $noww = false;
        }

            //----------------------------------------------------------------Typy
        if($wher=='unique') {//Šablony
            $noww = false;
            $where[] = "type='building'";
            $where[] = "ww='0'";
            //----------------
        }elseif($wher=='building'){//Všechny budovy
             $where[]="type='building'";
            //----------------
        }elseif($wher=='terrain'){//Všechny terény
             $where[]="type='terrain'";
             //----------------

        }elseif($wher=='rock'){//Všechny skály
            $where[]="type='rock'";
            //----------------

        }elseif($wher=='tree'){//Všechny stromy
            $where[]="type='tree'";
            //----------------

        }elseif($wher=='visible'){//Vše, co je vidět
            $where[]="res!=''";
            //----------------

        }elseif($wher=='town'){//Všechna města
            $where[]="type='town'";
            //----------------

        }elseif($wher=='users'){//Všichni uživatelé
            $where[]="type='user'";
            //----------------------------------------------------------------Moje ..

        }elseif($wher=='mytowns'){//Vaše města
            $where[]="own=".$GLOBALS['ss']['logid'];
            $where[]="type='town'";
            //----------------

        }elseif($wher=='mytown'){//Aktuální město
             $where[]="id=".$GLOBALS['ss']['useid'];
             //----------------

        }elseif($wher=='me'){//Já - Aktuální hráč
            $where[]="id=".$GLOBALS['ss']['logid'];
            //----------------

        }elseif($wher=='mybuildings'){//Budovy aktuálního města
            $where[]='own='.$GLOBALS['ss']['useid'];
            $where[]="type='building'";

        }elseif($wher=='notmybuildings'){//NE Budovy aktuálního města //@todo CO s tím
            $where[]='own!='.$GLOBALS['ss']['useid'];
            $where[]="type='building'";

        }elseif($wher=='mainbuilding'){//Hlavní budova aktuálního města
            $where[]='own='.$GLOBALS['ss']['useid'];
            $where[]="type='building'";
            $where[]="name='".mainname()."'";

            //----------------------------------------------------------------Funkce

        }elseif(substr($wher,0,4)=='box('){//Ohraničení
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
        }elseif($wher){
            $where[]=$wher;
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

                $wher="`$col`".substr($wher,strlen($col));

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
                $order2[]="`$col`";
            }elseif($col=='rand'){
                $order2[]='RAND()';
            }else{
                $GLOBALS['ss']['query_output']->add('error','Unknown col '.$col);
            }
        }
        $order2=implode(',',$order2);

        if($order2){
            $order2 = "ORDER BY $order2";
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

    $query="SELECT $cols2 FROM [mpx]pos_obj WHERE $where2 AND ".objt()." $order2 $limit";
    $GLOBALS['ss']['query_output']->add('query',$query);
    $objects=sql_assoc($query);

    /*$query1="SELECT $cols2 FROM [mpx]pos_obj WHERE $where2 AND ".objt()." $order2 $limit";
    $query2="SELECT $cols2 FROM [mpx]pos_obj_ter WHERE $where2 AND ".objt()." $order2 $limit";

    $GLOBALS['ss']['query_output']->add('query1',$query1);
    $GLOBALS['ss']['query_output']->add('query2',$query2);

    $objects1=sql_assoc($query1);
    $objects2=sql_assoc($query2);

    $objects=array_merge($objects1,$objects2);

    if($limitnumber)
    array_splice($objects, $limitnumber);*/

    foreach($objects as &$object){
        foreach($object as $key=>&$value){

            if($key=='resurl') {



                if($object['type']=='terrain')
                    $value="$value:{$object['x']}:{$object['y']}";

                    $value = modelx($value);

            }elseif($key=='_name') {

                    $value = contentlang($value);

            }elseif($key=='func') {
                $value = func2list($value);

            }elseif(in_array($key,array('x','y','expand','block','attack'))){
                if($value==ceil($value)){
                    $value=intval($value);
                }

            }elseif($key=='hold' or $key=='profile' or $key=='fc'){
                $value=str2list($value);

            }


        }
    }

    /*hr();
    print_r($objects);*/

    $GLOBALS['ss']['query_output']->add('objects',$objects);
    $GLOBALS['ss']['query_output']->add('useid',$GLOBALS['ss']['useid']);
    $GLOBALS['ss']['query_output']->add('logid',$GLOBALS['ss']['logid']);

}

//=======================================================================================Worldmap

function a_worldmap($ww,$top){



    $url=rebase(url.worldmap(0,0,$ww,$top));

    $GLOBALS['ss']['query_output']->add('url',$url);

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
