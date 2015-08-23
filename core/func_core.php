<?php
/* Towns4, www.towns.cz 
   © Pavol Hejný | 2011-2015
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

/* @deprecated
 *
 * */
function a_list($cols,$where=0,$order=false,$limit=0,$imagewidth=0){

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
        'permalink',
        'func',
        'group',
        'expand',
        'block',
        'attack',
        'hold',
        'res',
        'resurl',
        'profile',
        'set',
        'fp',
        'fs',
        'fc',
        'fr',
        'fx',
        'own',
        'own_name',
        'own_avatar',
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
            }elseif($col=='own_name'){
                $cols2[]="(SELECT name FROM [mpx]pos_obj AS X WHERE X.id=[mpx]pos_obj.own LIMIT 1) AS `own_name`";
            }elseif($col=='own_avatar'){
                $cols2[]="(SELECT email FROM [mpx]users WHERE aac=1 AND [mpx]users.id=(SELECT userid FROM [mpx]pos_obj AS X WHERE X.id=[mpx]pos_obj.own LIMIT 1) LIMIT 1) AS `own_avatar`";
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

        }elseif($wher=='story'){//Všechny příběhy
            $where[]="type='story'";

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

            //----------------------------------------------------------------box

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
            //----------------------------------------------------------------radius

        }elseif(substr($wher,0,4)=='radius('){//Ohraničení
            $wher=str_replace(array('radius(',')'),'',$wher);

            list($midx,$midy,$radius)=explode(',',$wher);
            $midx=intval($midx);
            $midy=intval($midy);
            $radius=intval($radius);

            $where[]='x>='.($midx-$radius);
            $where[]='y>='.($midy-$radius);
            $where[]='x<='.($midx+$radius);
            $where[]='y<='.($midy+$radius);

            $where[]='POW(x,2)+POW(y,2)<='.($radius*$radius);

            //----------------
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

            $col=strtolower($col);
            if(strpos($col,'desc')){
                $col=str_replace('desc','',$col);
                $desc=' DESC';
            }else{
                $desc='';
            }
            $col=trim($col);

            if(in_array($col,$allcols)) {
                $order2[]="`$col`$desc";
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
    $GLOBALS['ss']['query_output']->add('query',sql_mpx($query));
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

    //-----------------------------------------------------$imagewidth
    $imagewidth=strtolower($imagewidth);
    if($imagewidth=='hd')$imagewidth=1366;
    if($imagewidth=='fullhd')$imagewidth=1920;

    $imagewidth=intval($imagewidth);
    if(!$imagewidth)$imagewidth=450;
    if($imagewidth>1920)$imagewidth=1920;
    //-----------------------------------------------------


    foreach($objects as &$object){
        foreach($object as $key=>&$value){

            //-----------------------------------------------------RES u STORY
            if($key=='res' and $object['type']=='story') {


                if(strpos($value,'<img')) {

                    $i=0;
                    while($img = substr2($value, '<img', '>',$i)){

                        $src = substr2($img, 'src="', '"',0);
                        $src=imgresizewurl(html_entity_decode($src),$imagewidth);
                        $img=substr2($img, 'src="', '"',0,$src);
                        $value=substr2($value, '<img', '>',$i,$img);

                        $i++;
                    }


                }


            }
            //-----------------------------------------------------RESURL

            if($key=='resurl') {
                //-----------------------------------------------------RESURL u STORY
                if($object['type']=='story'){

                    if(strpos($object['res'],'<img')) {

                        $value = substr2($value,'<img','>');
                        $value = substr2($value, 'src="', '"');
                        $value=html_entity_decode($value);
                        $value=imgresizewurl($value,$imagewidth);

                    }else{

                        $value='';

                    }
                //-----------------------------------------------------RESURL u ostatních


                }else {

                    if ($object['type'] == 'terrain')
                        $value = "$value:{$object['x']}:{$object['y']}";

                    $value = modelx($value);

                }
                //-----------------------------------------------------own_avatar
            }elseif($key=='own_avatar') {

                $value = gravatar($value);

                //-----------------------------------------------------_name
            }elseif($key=='_name') {

                    $value = contentlang($value);

                //-----------------------------------------------------func
            }elseif($key=='func') {
                $value = func2list($value);

                //-----------------------------------------------------'x','y','expand','block','attack'
            }elseif(in_array($key,array('x','y','expand','block','attack'))){
                if($value==ceil($value)){
                    $value=intval($value);
                }

                //-----------------------------------------------------hold, profile, set a fc
            }elseif($key=='hold' or $key=='profile' or $key=='set' or $key=='fc'){
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



    $url=worldmap(0,0,$ww,$top);

    $GLOBALS['ss']['query_output']->add('url',$url);

}

//=======================================================================================Edit

function a_edit($key,$value){

    if($GLOBALS['ss']['aac_object']->own!=$GLOBALS['ss']['useid'])
    {$GLOBALS['ss']['query_output']->add('error','no_own'); return;}

    $changable=array('name','res');
    if(in_array($key,$changable)){
        $GLOBALS['ss']['query_output']->add("1",1);
        //todo vyrobit lidskou hlášku o změně

        $GLOBALS['ss']['query_output']->add('eval','$GLOBALS[\'ss\'][\'aac_object\']->'.$key.'=$value;');
        eval('$GLOBALS[\'ss\'][\'aac_object\']->'.$key.'=$value;');

        $GLOBALS['ss']['aac_object']->update();
    }else{

        $GLOBALS['ss']['query_output']->add('error',lr('edit_error_not_allowed',implode(', ',$changable)));//todo lang

    }

}

//=======================================================================================PROFILE

//@todo Aktualizovat tuhle funkci, aby fungovala jako edit_set

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

        //e($value);define("a_profile_edit_help","id,key,value");
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
//=======================================================================================SET

function a_edit_set($key,$value){

    if($GLOBALS['ss']['aac_object']->own!=$GLOBALS['ss']['useid'])
        {$GLOBALS['ss']['query_output']->add('error','no_own'); return;}

    if($key){
        $GLOBALS['ss']['query_output']->add("1",1);
        if($GLOBALS['ss']['aac_object']->profile->val($key)!=$value){
            $GLOBALS['ss']['query_output']->add("success",lr("set_$key").' '.lr('settings_changed'));
        }


        $GLOBALS['ss']['aac_object']->set->add($key,$value);

        $GLOBALS['ss']['aac_object']->update();
    }
}


//======================================================================================INFO

//@todo PH Odstranit tuhle zastaralou funkci a nahradit za list

define("a_info_help","[q={use,log,id}]");
function a_info($q="use")
{
    if ($q != "use" and $q != "log") {
        //r($q);
        $GLOBALS['ss']["tmp_object"] = new object($q);
        if (!$GLOBALS['ss']["tmp_object"]->loaded) {
            $GLOBALS['ss']['query_output']->add('error', "Neexistující objekt");
            return;
        }
        $q = "tmp";
    }
    //echo($GLOBALS['ss']['useid']);
    //echo($q."_object");
    //$GLOBALS['ss'][$q."_object"]->xxx();
    if ($GLOBALS['ss']['use_object'] and $GLOBALS['ss'][$q . "_object"]) {
        $GLOBALS['ss']['query_output']->add("1", 1);
        $GLOBALS['ss']['query_output']->add("id", $GLOBALS['ss'][$q . "_object"]->id);
        $GLOBALS['ss']['query_output']->add("type", $GLOBALS['ss'][$q . "_object"]->type);
        $GLOBALS['ss']['query_output']->add("fp", $GLOBALS['ss'][$q . "_object"]->fp);
        $GLOBALS['ss']['query_output']->add("fs", $GLOBALS['ss'][$q . "_object"]->fs);
        $GLOBALS['ss']['query_output']->add("fr", $GLOBALS['ss'][$q . "_object"]->fr);
        $GLOBALS['ss']['query_output']->add("fx", $GLOBALS['ss'][$q . "_object"]->fx);
        $GLOBALS['ss']['query_output']->add("name", $GLOBALS['ss'][$q . "_object"]->name);
        $GLOBALS['ss']['query_output']->add("origin", implode(',', $GLOBALS['ss'][$q . "_object"]->origin));
        //$GLOBALS['ss']['query_output']->add("password",$GLOBALS['ss'][$q."_object"]->password);
        $GLOBALS['ss']['query_output']->add("func", $GLOBALS['ss'][$q . "_object"]->func->vals2str());
        $GLOBALS['ss']['query_output']->add("support", $GLOBALS['ss'][$q . "_object"]->support());
        $GLOBALS['ss']['query_output']->add("profile", $GLOBALS['ss'][$q . "_object"]->profile->vals2str());
        $GLOBALS['ss']['query_output']->add("hold", $GLOBALS['ss'][$q . "_object"]->hold->vals2str());
        $GLOBALS['ss']['query_output']->add("own", $GLOBALS['ss'][$q . "_object"]->own);
        $GLOBALS['ss']['query_output']->add("superown", $GLOBALS['ss'][$q . "_object"]->superown);
        $GLOBALS['ss']['query_output']->add("ownname", $GLOBALS['ss'][$q . "_object"]->ownname);
        $GLOBALS['ss']['query_output']->add("own2", $GLOBALS['ss'][$q . "_object"]->own2);
        //r($GLOBALS['ss'][$q."_object"]->own2);
        //$GLOBALS['ss']['query_output']->add("in2",$GLOBALS['ss'][$q."_object"]->in2);
        $GLOBALS['ss']['query_output']->add("t", $GLOBALS['ss'][$q . "_object"]->t);
        $GLOBALS['ss']['query_output']->add("tasks", $GLOBALS['ss'][$q . "_object"]->tasks);
        $GLOBALS['ss']['query_output']->add("ww", $GLOBALS['ss'][$q . "_object"]->ww);
        $GLOBALS['ss']['query_output']->add("x", $GLOBALS['ss'][$q . "_object"]->x);
        $GLOBALS['ss']['query_output']->add("y", $GLOBALS['ss'][$q . "_object"]->y);
        //r($GLOBALS['ss']['query_output']->vals2str(),1);
    }
}

//=======================================================================================View

function a_view($id=false,$s=1,$xsize=3,$ysize=4){

    if(!$id){

        if(rand(1,4)!=1){

            $terrains=array(2,3,4,5,6,7,10,11);
            shuffle($terrains);
            $terrain='t'.$terrains[0];
            $sql="SELECT x,y FROM [mpx]pos_obj WHERE `type`='terrain' AND ww='".$GLOBALS['ss']['ww']."' AND res='$terrain' ORDER BY rand() LIMIT 1";

        }else{

            $sql="SELECT x,y FROM [mpx]pos_obj WHERE `type`='building' AND ww='".$GLOBALS['ss']['ww']."' ORDER BY rand() LIMIT 1";

        }

        list(list($x,$y))=sql_array($sql);


    }

    /*$x = 50;
    $y = 50;*/

    /*$xsize = 3;
    $ysize = 4;*/


    $s = $s-1+1;
    $sf = 's' . str_replace('.', 'o', $s) . 'xs' . $xsize . 'ys' . $ysize;


    //---------------------------------------Zobrazovaná pozice

    if (!$x and !$y) {//pokud není pozice v $x a $y doplní se z $id
        $destinationobject = new object($id);
        if (!$destinationobject->loaded) exit('chyba!');
        $x = $destinationobject->x;
        $y = $destinationobject->y;
        //$ww=$destinationobject->ww;
        unset($destinationobject);
    }
    //r($x,$y);
    $x = round($x);
    $y = round($y);
    $xonmap = $x;
    $yonmap = $y;


    $file=tmpfile2(array(w,$GLOBALS['ss']["ww"],$sf,$x,$y,date('j.n.Y')),'png','view');

    //--------------------------------------------------------------------
    if(!file_exists($file)){

        //---------------------------------------Přepočítání do jiného SS
        $tmp = 2;
        $xc = (-(($y - 1) / 10) + (($x - 1) / 10));
        $yc = ((($y - 1) / 10) + (($x - 1) / 10));
        $xc = intval($xc) - 1;
        $yc = intval($yc) - $tmp;
        //---------------------------------------

        $size = 424;//424


        $img = imagecreatetruecolor($xsize * $size * $s * 1.3, $ysize * $size * $s * 0.5 * 1.185);
        //$img=imagecreate(($xm+$xm+1)*$size,($ym+1)*$size*0.5);
        //$img=imagecreatetruecolor(500,500);

        $yy = 0;
        for ($y = $yc; $y <= $yc + $ysize; $y++) {
            $xx = 0;
            for ($x = $xc; $x <= $xc + $xsize; $x++) {

                //for($y=0; $y<=$ym; $y++){$xx=0;
                //    for ($x=-$xm; $x<=$xm; $x++) {


                $file1 = htmlmap($x, $y, 1, true);
                $file2 = htmlmap($x, $y, 2, true);
                unlink($file2);

                //r($file2);
                //$file=tmpfile2("outimg,".size.",".zoom.",".$x.",".$y.",".w.",".gird.",".t_sofb.",".t_pofb.",".t_brdcc.",".t_brdca.",".t_brdcb.','.$GLOBALS['ss']["ww"],'jpg','map');

                //die($file."aaa<br>");
                //$file=file_get_contents($file);

                $posuv = htmlunitc - htmlbgc + $top;
                foreach (array(array($file1, 0), array($file2, $posuv)) as $tmp) {
                    list($file_u, $posuv) = $tmp;
                    $part = imagecreatefromstring(file_get_contents($file_u));
                    imagecopyresampled($img, $part,

                        //((($x*$size)+(imagesx($img)/2)+($size)))*$s+(imagesx($img)*$s*0.5),
                        $xx,
                        $yy + ($posuv * $s),

                        0, 0, ceil($size * $s), ceil(($size * 0.5 + 1) * $s), imagesx($part), imagesy($part) /*$size ,  $size*0.5 */);
                    imagedestroy($part);

                }
                /*width="<? echo(ceil($size)); ?>" border="0" style="position: absolute;top:<? echo($y*$size*0.5); ?>px;left:<? echo(($x*$size)+($screen/2)-($size/2)); ?>px;"/>*/
                $xx += ceil($size * $s);
            }
            $yy += ceil($size * $s * 0.5);
        }
        /*header("Content-type: image/png");
        imagepng($img);*/
        //r($img);


        //r($img);

        imagepng($img, $file, 9);
        chmod($file, 0777);
        imagedestroy($img);

    }

    $GLOBALS['ss']['query_output']->add('url',$file);
    return($file);

}

//=======================================================================================Ad
function a_ad($w=160,$h=160,$new=false){


    $filecount=tmpfile2(array($w,$h),'txt','ad');

    if(file_exists($filecount))
        $count=fgc($filecount);

    if(!$count)$count=1;

    if($new){
        $count++;
        fpc($filecount,$count);
        $seed=$count;
    }else{
        $seed=rand(1,$count);
    }

    //$s=1;
    $s=0.5;


    $file=tmpfile2(array($w,$h,/*date('j.n.Y'),*/$seed),'png','ad');

    //--------------------------------------------------------------------
    if(!file_exists($file)/** or 1/**/){


        $w1=(1653/3)*$s;
        $h1=(1004/4)*$s;

        //die("a_view(false,$s,ceil($w/$w1),ceil($h/$h1));");
        $file_w=a_view(false,$s,ceil($w/$w1),ceil($h/$h1));

        $img = imagecreatefromstring(fgc($file_w));


        /*$width  = imagesx($img);
        $height = imagesy($img);

        $centreX = round($width / 2);
        $centreY = round($height / 2);

        $cropWidth  = $w;
        $cropHeight = $h;
        $cropWidthHalf  = round($cropWidth / 2); // could hard-code this but I'm keeping it flexible
        $cropHeightHalf = round($cropHeight / 2);

        $x1 = max(0, $centreX - $cropWidthHalf);
        $y1 = max(0, $centreY - $cropHeightHalf);

        $x2 = min($width, $centreX + $cropWidthHalf);
        $y2 = min($height, $centreY + $cropHeightHalf);


        $width = $x2 - $x1;
        $height = $y2 - $y1;*/

        $temp = imagecreatetruecolor($w, $h);

        //imageantialias($temp,true);

            //die("$w, $h");
        imagecopy($temp, $img, 0,0,(imagesx($img)/2-($w/2)),(imagesy($img)/2-($h/2)), $w, $h);

        //for($i=0;$i<2;$i++)
        imagefilter($temp, IMG_FILTER_SELECTIVE_BLUR);

        //for($i=0;$i<2;$i++)
        //imagefilter($temp,IMG_FILTER_GAUSSIAN_BLUR);


        $info = imagecreatefrompng('ui/image/ad/vertical.png');


        //imagefilter($info, IMG_FILTER_SMOOTH, 5);

        if($w>$h){


            $hh=$h;
            $ww=imagesx($info)*($h/imagesy($info));
            $yy=0;
            $xx=(imagesx($temp)-$ww)*(0.1);


        }else{

            $ww=$w;
            $hh=imagesy($info)*($w/imagesx($info));
            $xx=0;
            $yy=(imagesy($temp)-$hh)*(0.1);

        }

        imagecopyresized($temp, $info, $xx,$yy,0,0, $ww, $hh,imagesx($info),imagesy($info));



        /*r($img);
        r($temp);
        exit;*/


        imagepng($temp, $file, 9);
        chmod($file, 0777);
        imagedestroy($img);

    }

    $GLOBALS['ss']['query_output']->delete('url');
    $GLOBALS['ss']['query_output']->add('url',$file);

}
//=======================================================================================Ad
function a_model($res,$s=1,$rot=0,$slnko=1.5,$ciary=0,$fpfs=1,$hore=0,$usercolor=false){

    model($res,$s,$rot,$slnko,$ciary,$fpfs,$hore,true,$usercolor);

    $file=(rebase(url.$GLOBALS['model_file']));

    $GLOBALS['ss']['query_output']->delete('url');
    $GLOBALS['ss']['query_output']->add('url',$file);

}


//=======================================================================================Ad


function a_move($x2,$y2){

    list($x,$y)=$GLOBALS['ss']['aac_object']->position();


    $GLOBALS['ss']['query_output']->add('x1',$GLOBALS['ss']['aac_object']->x);
    $GLOBALS['ss']['query_output']->add('y1',$GLOBALS['ss']['aac_object']->y);
    $GLOBALS['ss']['query_output']->add('x2',$GLOBALS['ss']['aac_object']->x2);
    $GLOBALS['ss']['query_output']->add('y2',$GLOBALS['ss']['aac_object']->y2);
    $GLOBALS['ss']['query_output']->add('x',$x);
    $GLOBALS['ss']['query_output']->add('y',$y);



    if(substr($x2,0,1)=='+'){
        $x2=$x-(-floatval(substr($x2,1)));
    }
    if(substr($y2,0,1)=='+'){
        $y2=$y-(-floatval(substr($y2,1)));
    }



    $speed=paramvalue($GLOBALS['ss']["aac_func"]['params']['speed']);
    $distance=sqrt(pow($x-$x2,2)+pow($y-$y2,2));

    trackposition($GLOBALS['ss']['aac_object']->id,time()+ceil($distance/($speed/3600)));




    $GLOBALS['ss']['aac_object']->x=$x;
    $GLOBALS['ss']['aac_object']->y=$y;

    $GLOBALS['ss']['aac_object']->x2=$x2;
    $GLOBALS['ss']['aac_object']->y2=$y2;


}

//=======================================================================================

?>
