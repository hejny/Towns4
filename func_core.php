<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/func_core.php

   Základní systémové funkce
*/
//==============================




//======================================================================================LEAVE
define("a_leave_help","");
function a_leave($id){
    sql_query('UPDATE [mpx]objects SET own=0 WHERE own='.useid.' AND id='.$id.' AND '.objt());
}
//======================================================================================LEAVE
define("a_dismantle_help","");
function a_dismantle($id){
    if(/*$id==*/sql_1data('SELECT id FROM [mpx]objects WHERE own='.useid.' AND id='.$id) or $id=$GLOBALS['ss']['candismantle']){
        $fc=new hold(sql_1data("SELECT fc FROM ".mpx."objects WHERE id='$id'"));
        $tmp=new object($id);
        //$tmp->update(true);
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
//echo(useid.','.logid.','.$GLOBALS['ss']['use_object']->name);
//a_dismantle(2000226);
//======================================================================================INFO

define("a_info_help","[q={use,log,id}]");
function a_info($q="use"){
    if($q!="use" and $q!="log"){
        //r($q);
        $GLOBALS['ss']["tmp_object"]= new object($q);
        if(!$GLOBALS['ss']["tmp_object"]->loaded){
            $GLOBALS['ss']["query_output"]->add("error","Neexistující objekt");
            return;
        }
        $q="tmp";
    }
    //echo(useid);
    //echo($q."_object");
    //$GLOBALS['ss'][$q."_object"]->xxx();
    if($GLOBALS['ss']["use_object"] and $GLOBALS['ss'][$q."_object"]){
    $GLOBALS['ss']["query_output"]->add("1",1);
    $GLOBALS['ss']["query_output"]->add("id",$GLOBALS['ss'][$q."_object"]->id);
    $GLOBALS['ss']["query_output"]->add("type",$GLOBALS['ss'][$q."_object"]->type);
    $GLOBALS['ss']["query_output"]->add("fp",$GLOBALS['ss'][$q."_object"]->fp);
    $GLOBALS['ss']["query_output"]->add("fs",$GLOBALS['ss'][$q."_object"]->fs);
    $GLOBALS['ss']["query_output"]->add("fr",$GLOBALS['ss'][$q."_object"]->fr);
    $GLOBALS['ss']["query_output"]->add("fx",$GLOBALS['ss'][$q."_object"]->fx);
    $GLOBALS['ss']["query_output"]->add("dev",$GLOBALS['ss'][$q."_object"]->dev);
    $GLOBALS['ss']["query_output"]->add("name",$GLOBALS['ss'][$q."_object"]->name);
    //$GLOBALS['ss']["query_output"]->add("password",$GLOBALS['ss'][$q."_object"]->password);
    $GLOBALS['ss']["query_output"]->add("func",$GLOBALS['ss'][$q."_object"]->func->vals2str());
    $GLOBALS['ss']["query_output"]->add("support",$GLOBALS['ss'][$q."_object"]->support());
    $GLOBALS['ss']["query_output"]->add("profile",$GLOBALS['ss'][$q."_object"]->profile->vals2str());
    $GLOBALS['ss']["query_output"]->add("hold",$GLOBALS['ss'][$q."_object"]->hold->vals2str());
    $GLOBALS['ss']["query_output"]->add("own",$GLOBALS['ss'][$q."_object"]->own);
    $GLOBALS['ss']["query_output"]->add("ownname",$GLOBALS['ss'][$q."_object"]->ownname);
    $GLOBALS['ss']["query_output"]->add("own2",$GLOBALS['ss'][$q."_object"]->own2);
    //r($GLOBALS['ss'][$q."_object"]->own2);
    $GLOBALS['ss']["query_output"]->add("in",$GLOBALS['ss'][$q."_object"]->in);
    $GLOBALS['ss']["query_output"]->add("inname",$GLOBALS['ss'][$q."_object"]->inname);
    //$GLOBALS['ss']["query_output"]->add("in2",$GLOBALS['ss'][$q."_object"]->in2);
    $GLOBALS['ss']["query_output"]->add("t",$GLOBALS['ss'][$q."_object"]->t);
    $GLOBALS['ss']["query_output"]->add("tasks",$GLOBALS['ss'][$q."_object"]->tasks);
    list($x,$y)=$GLOBALS['ss'][$q."_object"]->position();
    $GLOBALS['ss']["query_output"]->add("ww",$GLOBALS['ss'][$q."_object"]->ww);
    $GLOBALS['ss']["query_output"]->add("x",$x);
    $GLOBALS['ss']["query_output"]->add("y",$y);
    //r($GLOBALS['ss']["query_output"]->vals2str(),1);
    }
}
//=======================================================================================PROFILE
define("a_profile_edit_help","id,key,value");
function a_profile_edit($id,$key,$value){
    if($id==useid)$object=$GLOBALS['ss']["use_object"];
    if($id==logid)$object=$GLOBALS['ss']["log_object"];
    if(!$object){$object=new object($id);if($object->own!=useid){$GLOBALS['ss']["query_output"]->add("error",'no_own'); return;}$update=true;}
    if($key!="name"){
        $GLOBALS['ss']["query_output"]->add("1",1);
		if($object->profile->val($key)!=$value){
        	$GLOBALS['ss']["query_output"]->add("success",lr("profile_$key").' '.lr('settings_changed'));
		}
        //r();
        //$GLOBALS['ss']["use_object"]->xxx();
        //r($GLOBALS['ss']["use_object"]->profile->vals2list());

		//e($value);
		//e($object->name);
        $object->profile->add($key,$value);


        //r($GLOBALS['ss']["use_object"]->profile->vals2list());
    }else{
        //echo("SELECT 1 FROM objects WHERE name='".$value."' and id!='".useid."'");
        $q=name_error($value);
        if(!$q){
            $object->name=$value;
            $GLOBALS['ss']["query_output"]->add("success",lr('profile_'.($object->town).'name').' '.lr('settings_changed'));
        }else{
            //e('!');
            $GLOBALS['ss']["query_output"]->add("error",$q); 
        }
        //}else{
         //$GLOBALS['ss']["use_object"]->name=$value;
        //if(sql_1data("SELECT 1 FROM objects WHERE name='".$value."' and id!='".$GLOBALS['ss']["useid"]."'")){
        //    $GLOBALS['ss']["query_output"]->add("error","Jméno je už obsazené.");
        //}else{
        //    $GLOBALS['ss']["use_object"]->name=$value;
        //}
    }
    if($update)$object->update();
}
//===============================================================================================================ITEMS


/*define("a_items_help","");
function a_items(){
    //id,type,fp,fs,dev,name,password,func,set,res,profile,hold,own,in,t,x,y
    //r("SELECT * FROM objects WHERE `in`='".($GLOBALS['ss']["use_object"]->id)."' ORDER BY t");
    $csv=sql_csv("SELECT `id`,`type`,`fp`,`fs`,`dev`,`name`,NULL,`func`,`set`,NULL,`profile`,`hold`,`own`,`in`,`t`,`x`,`y` FROM ".mpx."objects WHERE `in`='".($GLOBALS['ss']["use_object"]->id)."' ORDER BY t desc");
    $GLOBALS['ss']["query_output"]->add("items",$csv);
}
//===============================================================================================================ITEM
define("a_item_help","id,action[,param,param2]");
function a_item($id,$action,$param=false,$param2=false){
    $item=new object($id);
    if($action=="drop"){
        $item->delete();
    }elseif($action=="status"){
        //r($item->profile->status);
        $item->profile->add("status",$param);
    }elseif($action=="hold"){
        //$GLOBALS['ss']["query_output"]->add("error",$param.$param2);
        $itemx=new object("","`in`='".($GLOBALS['ss']["use_object"]->id)."' and x='$param' and y='$param2'");
        if($itemx->loaded){
            $itemx->x=$item->x;
            $itemx->y=$item->y;
        }
        $item->x=$param;
        $item->y=$param2;
    }
}*/


//======================================================================================IMAGE
/*define("a_image_help","name,description,filepath");
function a_image($name,$description,$filepath){
    $q=name_error($name);
    if(!$q){
        if(file_exists($filepath)){
                $message=new object("create");
                $message->name=$name;
                $message->type="image";
                $message->in=$GLOBALS['ss']["use_object"]->id;
                $message->profile->add("author",$GLOBALS['ss']["use_object"]->id);
                $message->profile->add("description",$description);
                $message->resfile=true;
                $message->res=$filepath;
                //r($filepath);
                //r($filepath);
            $GLOBALS['ss']["query_output"]->add("success","Soubor byl nahrán.");
            $GLOBALS['ss']["query_output"]->add("1",1);
        }else{
             $GLOBALS['ss']["query_output"]->add("error","Soubor neexistuje.");
        }
    }else{
         $GLOBALS['ss']["query_output"]->add("error",$q);
    }
//$message=new object("create");
//$this->resfile
}/**/

//======================================================================================
/*function a_stat($while="1",$limit="10"){
    $array=sql_csv("SELECT `id`,`name`,`type`,`dev`,`fs`,`fp`,`fr`,`fx`,`own`,`in`,`x`,`y` FROM `".mpx."objects` WHILE $while LIMIT $limit");
    $GLOBALS['ss']["query_output"]->add("stat",$array);
}*/
?>
