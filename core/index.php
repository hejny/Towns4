<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2015
   _____________________________

   core/index.php

   Základní soubor vyvolávaný inicializací

   _____________________________
   Struktura systému:
     Towns4 / core [*][git] - jádro systému + moduly
              app  [*][git] - (Částečně) externí aplikace
              lib  [o][git] - Knihovny (např. jQuery)
              data [*][www] / image    - Obrázky
                            / lang     - Jazykové soubory
                            / help     - Nápověda + obrázky nápovědy
                            / userdata - Data od uživatelů
              tmp  [o]      - Pomocná složka - cache pro systém
              index.php   [o][www] - Inicializační soubor
              favicon.ico [o][git] - Logo
              .htaccess   [o][git] - Vše spadá do index.php

     [o]    Povolen přístup z www prohlížeče
     [*]    Přístup zablokovaný pomocí .htaccess
     [git]  Hlavní vývojová větev je na GITu
     [www]  Hlavní vývojová větev je na test.towns.cz/world1
*/
//==============================


//try {
//===============================================================================
//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_WARNING );
ini_set("register_globals","off");
ini_set("display_errors","on");

//======================================================================================================================INC


$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";


$uri=$_SERVER["REQUEST_URI"];
if(strpos($uri,'?'))$uri=substr($uri,0,strpos($uri,'?'));


$real_url=$protocol.$_SERVER['HTTP_HOST'].$uri;

//die($real_url);

$uri_keywords=
    array(
    'debug',
    'timeplan',
    //'edit',
    //'notmp',
    'onlymap',
    //'corex',
    'app',
    'api',
    'admin',
    'fblogin',
    'sitemap.xml'
);
$used_keywords=array();

//------------------------------------------------Rozebrání $uri


$uri=explode('/',$uri);
$uri=array_filter($uri);

//------------------------------------------------Světy

$GLOBALS['inc']['default_world']=$GLOBALS['inc']['world'];

foreach($GLOBALS['inc']['worlds'] as $world){

    if(in_array($world,$uri)){

        if($world!=$GLOBALS['inc']['default_world']) {///Defaultní svět
            $used_keywords[] = $world;
            $GLOBALS['inc']['world']=$world;
        }

        break;

    }
}

//------------------------------------------------Klíčová slova

foreach($uri_keywords as $keyword){

    if(in_array($keyword,$uri)){

        $used_keywords[]=$keyword;

    }
}

//------------------------------------------------name2permalink

function name2permalink($permalink){

    if(function_exists('contentlang'))
        $permalink=contentlang($permalink);
    $permalink=strtolower($permalink);

    //echo($permalink.'<br>');

    $hacky= array('ě','š','č','ř','ž','ý','á','í','é','ú','ů','ľ','ť','ď');
    $normal=str_split('escrzyaieuultd');

    //print_r($hacky);
    //print_r($normal);

    $permalink=str_replace($hacky,$normal,$permalink);

    //echo($permalink.'<br>');

    $cisla=str_split('0123456789.');
    $znaky=str_split('0123456789abcdefghijklmnopqrstuvwxyz-');

    $permalink=str_split($permalink);

    $cutfloat=false;
    foreach($permalink as &$char){

        if($char=='.'){
            $cutfloat=true;
        }elseif(!in_array($char,$cisla)){
            $cutfloat=false;
        }

        if($cutfloat) {
            $char = '';
        }else{
            if (!in_array($char, $znaky)) {
                $char = '-';
            }
        }
    }
    $permalink=join('',$permalink);
    $permalink=trim($permalink,'-');


    while(strpos($permalink,'--'))
        $permalink=str_replace('--','-',$permalink);

    return($permalink);

}


//------------------------------------------------Objekt



$other_keywords=array('towns');//@todo načíst automaticky

$GLOBALS['object_permalink']=array();

foreach($uri as $last) {
    if (!in_array($last, $uri_keywords) and !in_array($last, $other_keywords) and !in_array($last, $GLOBALS['inc']['worlds'])) {

        //if(substr($last,0,1)=='-' and){}
        $last = urldecode($last);
        $last = name2permalink($last);
        $used_keywords[] = $last;

        $GLOBALS['object_permalink'][] = $last;

    }
}

$GLOBALS['object_permalink']=implode('/',$GLOBALS['object_permalink']);


//------------------------------------------------Případné přesměřování


$virtual_url=$GLOBALS['inc']['url'].join('/',$used_keywords);


$ref=$_GET['ref']?'?ref='.$_GET['ref']:'';//@todo Vyřešit reference
$virtual_url.=$ref;



/**
echo($real_url);
echo('<br>');
echo($virtual_url);
die();
/**/
//echo('<br>');
//echo("$real_url!=$virtual_url.'/'");
//exit;

if($real_url!=$virtual_url/* and $real_url!=$virtual_url.'/'*/){

    header('Location: '.$virtual_url);
    //echo('Location: '.$virtual_url);
    exit;

}



//die();
//------------------------------------------------------------------------------------Vyhodnocení, co dál

/*$admin=false;
$app=false;
$debug=false;
$edit=false;
$notmp=false;
$timeplan=false;
$onlymap=false;
$api=false;
$sitemap=false;
$speciale=false;*/



define('core',$GLOBALS['inc']['core']);
define('base',$GLOBALS['inc']['base']);


if(!in_array('debug',$used_keywords))define('debug',0);
if(in_array('timeplan',$used_keywords)){define('timeplan',1);}else{define('timeplan',0);}
if(in_array('onlymap',$used_keywords)){define('onlymap',1);}else{define('onlymap',0);}
if(in_array('fblogin',$used_keywords)){define('fblogin',1);}else{define('fblogin',0);}
if(in_array('sitemap.xml',$used_keywords)){$sitemap=true;}else{$sitemap=false;}


define('edit',0);
define('notmp',0);


/*foreach($uri as $x){
    if($x){
        if(
            $x!='admin' AND
            $x!='app' AND
            $x!='api' AND
            $x!='debug' AND
            $x!='edit' AND
            $x!='notmp' AND
            $x!='timeplan' AND
            $x!='onlymap' AND
            $x!='sitemap.xml' AND
            $x!='corex' AND
            !is_numeric(substr($x,0,1)) AND substr($x,0,1)!='-')
        {$world=$x;}
        elseif($x=='admin'){$admin=true;}
        elseif($x=='app'){$app=true;}
        elseif($x=='timeplan'){$timeplan=true;}
        elseif($x=='onlymap'){$onlymap=true;}
        elseif($x=='notmp'){$notmp=true;}
        elseif($x=='edit'){$edit=true;}
        elseif($x=='corex'){$corex=true;}
        elseif($x=='debug'){$debug=true;}
        elseif($x=='api'){$api=true;}
        elseif($x=='sitemap.xml'){$sitemap=true;}
        elseif(is_numeric(substr($x,0,1))){
            $speciale=true;
            if(!strpos($x,',')){//echo($x);
                $GLOBALS['mapgtid']=$x;
            }else{//echo(345);
                list($GLOBALS['mapgtx'],$GLOBALS['mapgty'])=explode(',',$x);
            }

        }
        else{$speciale=true;$GLOBALS['url_param']=substr($x,1);}

    }}*//*


$GLOBALS['inc']['urld']=str_replace('[world]',$GLOBALS['inc']['world'],$GLOBALS['inc']['url']);
$GLOBALS['inc']['url']=str_replace('[world]',$world,$GLOBALS['inc']['url']);
if(!$world or str_replace(array('.','?'),'',$world)!=$world){header('Location: '.$GLOBALS['inc']['urld'].$ref);exit;}


$gooduri=str_replace('/'.'/','',$GLOBALS['inc']['url']);
$gooduri=substr($gooduri,strpos($gooduri,'/'));
//echo('Location: '.$GLOBALS['inc']['url'].$ref);
//die($gooduri);
if(!$admin and
    !$app and
    !$debug and
    !$api and
    !$edit and
    !$speciale and
    !$notmp and
    !$timeplan and
    !$onlymap and
    !$sitemap and
    !$corex)
    if($tmp!=$gooduri){header('Location: '.$GLOBALS['inc']['url'].$ref);exit;}

$GLOBALS['inc']['default_world']=$GLOBALS['inc']['world'];
$GLOBALS['inc']['world']=$world;
//$GLOBALS['inc']['url']=str_replace('[world]',$world,$GLOBALS['inc']['url']);
define('core',$GLOBALS['inc']['core']);
define('base',$GLOBALS['inc']['base']);

if(!$debug)define('debug',0);
if(!$edit)define('edit',0);
if($notmp){define('notmp',1);}else{define('notmp',0);}
if($timeplan){define('timeplan',1);}else{define('timeplan',0);}
if($onlymap){define('onlymap',1);}else{define('onlymap',0);}
if($corex){define('corex',1);define('corexx','corex/');}else{define('corex',0);define('corexx','');}*/



if(in_array('app',$used_keywords)){
    //@todo spouštění aplikací přes core + bezpečnost


    /*$tmp=$_SERVER["REQUEST_URI"];
    $tmp=explode('app/',$tmp);
    $tmp=$tmp[1];
    if(substr($tmp,-9)!='index.php'){
       $tmp.='index.php';
    }

    $tmp=explode('/',$tmp);
    list($folder,$file)=$tmp;

    chdir($GLOBALS['inc']['app'].'/'.$folder);

    require($file);*/

    echo('APP NOT FOUND!');
    exit;

}elseif(in_array('api',$used_keywords)){

    require($GLOBALS['inc']['core'].'/api.php');
    exit;

}elseif(in_array('admin',$used_keywords)){

    require($GLOBALS['inc']['core'].'/admin/index.php');
    exit;

}




header("Connection: Keep-alive");


////====================================================================================================================

//--------------------------------------------inicializace

define("root", "");//todo: PH - je to divné

//-------------

require_once(root.core."/func_main.php");
require_once(root.core."/func_vals.php");
require_once(root.core."/func_object.php");
require_once(root.core."/memory.php");


//-------------

require_once(root.core."/func.php");
require_once(root.core."/func_core.php");


//-------------

require_once(root.core."/login/func_core.php");
require_once(root.core."/create/func_core.php");
require_once(root.core."/attack/func_core.php");
require_once(root.core."/text/func_core.php");
require_once(root.core."/hold/func_core.php");
require_once(root.core."/quest/func_core.php");

//-------------

require_once(root.core.'/model/func_map.php');

//--------------------------------------------
define("single", true);

//------------------------------------------------------------------
if(defined('service') and service==1)die(lr('world_in_service'));
if(defined('service') and service==2 and !debug)die(lr('world_in_service'));
//------------------------------------------------------------------
//die(url);
$GLOBALS['ss']["url"]=url;
//require("control/cron.php");
t("start");
//------------
//if($GLOBALS['get']["session_destroy"]){session_destroy();}
//------------------------------
if(!$GLOBALS['ss']['useid'])$GLOBALS['ss']['useid']=$GLOBALS['ss']['logid'];

//PH// Už nepoužívat - define("$GLOBALS['ss']['useid']", $GLOBALS['ss']['useid']);
//PH// Už nepoužívat - define("$GLOBALS['ss']['logid']", $GLOBALS['ss']['logid']);

////if($GLOBALS['ss']['logid'] and $GLOBALS['ss']['useid']){
//if(!$GLOBALS['ss']['log_object']->loaded/* or !$GLOBALS['ss']['use_object']->loaded*/){session_destroy();refresh();}}


//------------------------------------------------------------------QUERY
if($_GET["q"]){$q=($_GET["q"]);}
//if($GLOBALS['get']["query"]){$q=($GLOBALS['get']["query"]);}
if($q){
    $q=valsintext($q,$_POST,true);
    $q=valsintext($q,$_GET,true);
    if(!$post["login_permanent"])r($q);
    xquery($q);

    //xreport();
    //xreport();
}
//r('set0'.$GLOBALS['ss']['use_object']->x.','.$GLOBALS['ss']['use_object']->y);
//r($GLOBALS['config']);
//-------------------------- MAIN BUILDING

if(!$GLOBALS['hl'] and logged()){
    if($GLOBALS['config']['register_building']){
//if(1){

        if($hl=sql_array('SELECT id,ww,x,y FROM `[mpx]pos_obj` WHERE ww='.$GLOBALS['ss']['ww'].' AND own='.$GLOBALS['ss']['useid'].' AND type=\'building\' and TRIM(name)=\''.id2name($GLOBALS['config']['register_building']).'\' LIMIT 1')){
            //print_r($hl);
            list($GLOBALS['hl'],$GLOBALS['hl_ww'],$GLOBALS['hl_x'],$GLOBALS['hl_y'])=$hl[0];
        }else{//e(1);
            $GLOBALS['hl']=0;
        }
    }else{//e(2);
        $GLOBALS['hl']=0;
    }
}
function mainname(){
    return(id2name($GLOBALS['config']['register_building']));
}
//------------------------------------------------------------------FBLOGIN -> REDIRECT
if(fblogin){
    //echo('aaa');
    eval(subpage_('login-fb_redirect'));
    exit2;
    //die();
    //echo('ddd');
}
if($GLOBALS['ss']['fbid']){
    eval(subpage_('login-fb_process'));
}
//print_r($GLOBALS['get']);


/*if($GLOBALS['get']['fb_select_id'] and $GLOBALS['get']['fb_select_key']){
    //e($GLOBALS['get']['fb_select_id'].$GLOBALS['get']['fb_select_key']);
    xquery('login',$GLOBALS['get']['fb_select_id'],'facebook',$GLOBALS['get']['fb_select_key']);
*/
if($GLOBALS['get']['login_select_userid'] and $GLOBALS['get']['login_select_id'] and $GLOBALS['get']['login_select_key'] and $GLOBALS['get']['login_select_method']){
    //e($GLOBALS['get']['login_select_id'].$GLOBALS['get']['login_select_key']);
    xquery('login',$GLOBALS['get']['login_select_userid'],$GLOBALS['get']['login_select_method'],$GLOBALS['get']['login_select_key'],$GLOBALS['get']['login_select_id']);
}

//--------------------------------------------
//r($_COOKIE);
//r($post);


/*Přes SESSIDif(!logged() and $_COOKIE["towns_login_username"] and !$GLOBALS['get']["logout"]){
    xquery("login",$_COOKIE["towns_login_username"],$_COOKIE["towns_login_password"]);
}*/


if(logged() and !$GLOBALS['ss']['useid']){//e('log1');
    /*Přes SESSIDif($post["login_permanent"]){//e('log2');
      setcookie('towns_login_username',$post["login_username"],cookietime);
      setcookie('towns_login_password',$post["login_password"],cookietime);
      //die('ahoj');
    }*/

    reloc();
    //define('$GLOBALS['ss']['useid']',$GLOBALS['ss']['useid']);
    //define('$GLOBALS['ss']['logid'],$GLOBALS['ss']['logid']);
    //?PROČ//reloc();

    //refresh("page=main");
}
//---------------------------------------------------------------------------------------------
if(logged() and $_GET['e']!="none"/**/){//Udělat přímo VVV

    //$GLOBALS['ss']['terminal_nolog']=true;

    //r("t");
    t("xxx");
    $info=xquery("info");//Udělat přímo přes OBJECT
    t("xxx");
    //r("t");
    $info["func"]=new func($info["func"]);
    $funcs=$info["func"]->vals2list();
    $support=$info["support"];
    $tasks=csv2array($info["tasks"]);
    //r($tasks);
    $info["set"]=new set($info["set"]);
    $info["profile"]=new profile($info["profile"]);
    $info["hold"]=new hold($info["hold"]);

    //------------------
    $info2=xquery("info","log");//Udělat přímo přes OBJECT
    //print_r($info2);
    $info2["func"]=new func($info2["func"]);
    $info2["set"]=new set($info2["set"]);
    $info2["profile"]=new profile($info2["profile"]);
    $info2["hold"]=new hold($info2["hold"]);
    $own2=csv2array($info2["own2"]);
    //r($own2);
    //print_r($own2);
    if(!$GLOBALS['ss']['useid']){$GLOBALS['ss']['useid']=$info["id"];}
    if(!$GLOBALS['ss']['logid']){$GLOBALS['ss']['logid']=$info2["id"];}

    //$GLOBALS['ss']['terminal_nolog']=false;
}
//-------------------------------RESETS
if($_GET["resetwindow"]){
    $GLOBALS['ss']['log_object']->set->delete("interface");
    reloc();
}
if($_GET["resetmemory"]){
    sql_query('DROP TABLE [mpx]memory');
    reloc();
}
//------------------------------------NOPASSWORD
/*$nofb=true;
$nopass=true;
foreach(sql_array('SELECT `method`,`key` FROM `[mpx]login` WHERE `id`=\''.($GLOBALS['ss']['log_object']->id).'\'') as $row){
    list($method,$key)=$row;
    if($key){
        if($method=='towns')$nopass=false;
        if($method=='facebook')$nofb=false;
    }
}
define('nofb',$nofb);
define('nopass',$nopass);*/
$tmp=sql_array("SELECT password,fbid FROM `[mpx]users` WHERE id=".$GLOBALS['ss']["userid"]." AND aac=1 LIMIT 1");
$tmp=$tmp[0];
define('nopass',$tmp['password']?0:1);
define('nofb',$tmp['fbid']?0:1);

//-----------------------------------WINDOWS
$nwindows=$_GET["i"];
if(logged() and $nwindows){
    //r($nwindows);
    $nwindows=explode(";",$nwindows);
    foreach($nwindows as $nwindow){if($nwindow){
        list($w_name,$w_content,$w_x,$w_y)=explode(",",$nwindow);
        //r($w_name);
        $interface=xx2x($GLOBALS['ss']['log_object']->set->val("interface"));
        $interface=new windows($interface);
        list($ow_content,$ow_x,$ow_y)=explode(",",$interface->val($w_name));
        if(!$w_content)$w_content=$ow_content;
        if(!$w_x)$w_x=$ow_x;
        if(!$w_y)$w_y=$ow_y;
        $nwindow="$w_content,$w_x,$w_y";
        //r($interface->val($w_name));
        if($w_content!="none"){
            $interface->add($w_name,$nwindow);
        }else{
            $interface->delete($w_name);
        }
        //r($interface->val($w_name));
        $interface=$interface->vals2str($interface);
        $interface=str_replace(nln,"",$interface);
        //echo(nl2br($interface));
        $GLOBALS['ss']['log_object']->set->add("interface",x2xx($interface));
    }}
}
//-------------------------------SET
//r('set2'.$GLOBALS['ss']['use_object']->x.','.$GLOBALS['ss']['use_object']->y);
$nsets=$_GET["set"];
if(logged() and $nsets){
    //e("alert('$nsets');");
    //r($nwindows);
    $nsets=explode(";",$nsets);
    foreach($nsets as $nset){if($nset){
        list($s_key,$s_value)=explode(",",$nset);
        //r($w_name);
        $set=xx2x($GLOBALS['ss']['use_object']->set->val("set"));
        $set=new windows($set);
        $ss_value=$set->val($s_key);
        if(!$s_value)$s_value=$ss_value;
        $nset=$s_value;
        //r($interface->val($w_name));
        if($s_value!="none"){
            $set->add($s_key,$nset);
        }else{
            $set->delete($s_key);
        }
        //r($interface->val($w_name));
        $set=$set->vals2str();//$interface
        $set=str_replace(nln,"",$set);
        //echo(nl2br($interface));
        $GLOBALS['ss']['use_object']->set->add("set",x2xx($set));
    }}
}
//r($GLOBALS['ss']['use_object']->id);
if(logged() and $_GET['e']!="none"/**/){
    if($GLOBALS['ss']['use_object']->loaded){
        $settings=str2list(xx2x($GLOBALS['ss']['use_object']->set->val("set")));
        $GLOBALS['settings']=$settings;

    }else{
        $GLOBALS['ss']['useid']=false;
        $GLOBALS['ss']['logid']=false;
        refresh();
        exit2();
    }
}
//r($settings);


//-------------------------------

//e(23456);
//r('set3'.$GLOBALS['ss']['use_object']->x.','.$GLOBALS['ss']['use_object']->y);
t("before content");

//print_r($_GET);
//---------------------mobil
if(!is_bool($GLOBALS['ss']['mobile'])){

    if($GLOBALS['mobile_detect']->isMobile() && !$GLOBALS['mobile_detect']->isTablet() ){
        $GLOBALS['ss']['mobile']=true;
    }else{
        $GLOBALS['ss']['mobile']=false;
    }
}
//---------------------mobilni zarizeni
if(!is_bool($GLOBALS['ss']['mobilex'])){

    if($GLOBALS['mobile_detect']->isMobile()){
        $GLOBALS['ss']['mobilex']=true;
    }else{
        $GLOBALS['ss']['mobilex']=false;
    }
}
//---------------------IE
if(!is_bool($GLOBALS['ss']['isie'])){

    if(ae_detect_ie()){
        $GLOBALS['ss']['isie']=true;
    }else{
        $GLOBALS['ss']['isie']=false;
    }
}
//---------------------
if($_GET['mobile']){
    $GLOBALS['ss']['mobile']=true;
    if($_GET['mobile']==2){
        $GLOBALS['ss']['android']=true;
    }else{
        $GLOBALS['ss']['android']=false;
    }
}
if($_GET['mobile']=='0'){
    $GLOBALS['ss']['mobile']=false;
    $GLOBALS['ss']['android']=false;
}
$GLOBALS['mobile']=$GLOBALS['ss']['mobile'];
$GLOBALS['mobilex']=$GLOBALS['ss']['mobilex'];
$GLOBALS['isie']=$GLOBALS['ss']['isie'];
$GLOBALS['android']=$GLOBALS['ss']['android'];
define('mobile',$GLOBALS['mobile']);
define('android',$GLOBALS['android']);

//print_r(android);
/*if($_GET['e']=='-html_fullscreen'){
print_r($_GET);
print_r($GLOBALS['mobile']);print_r($GLOBALS['android']);
die();
}*/

//-------------------------------
//==============================
if($GLOBALS['mobile']){
    $GLOBALS['dragdistance']=19;
}else{
    $GLOBALS['dragdistance']=11;
}
//==============================
if(!$GLOBALS['mapzoom']){
    if(!$GLOBALS['mobile']){
        $GLOBALS['mapzoom']=1;
    }else{
        $GLOBALS['mapzoom']=round(pow(gr,(1/2))*100)/100;
    }
}
//==============================
//-------------------------------
//exit;
//e('d');


if($sitemap){
    require(core."/sitemap.php");
    exit2();
}


if($_GET['e']){
    if(logged() or $_GET['e']=='-html_fullscreen' or $_GET['e']=='test' or $_GET['e']=='text-email' or $_GET['e']=='-html_fullscreen_nologin' or $_GET['e']=='map' or $_GET['e']=='map_units' or substr($_GET['e'],0,6)=='login-' or $_GET['e']=='help' or  substr($_GET['e'],0,5)=='text-' or  substr($_GET['e'],0,12)=='plus-paypal-' or $_GET['e']=='create-editor' or $_GET['e']=='attack-cron' or $_GET['e']=='aac'){



        //if($_GET["ee"]){$e=$_GET["ee"];}else{$e=$_GET['e'];}
        $e=$_GET['e'];
        define("subpage", $e);

        //echo($e);
        list($dir,$e)=explode('-',$e,2);
        //echo($dir.','.$e);
        $e=str_replace('-','/',$e);
        if(!$e){$e=$dir;$dir='page';}
        if($e!="none"){
            $file=core."/$dir/".$e.".php";
            if(file_exists($file)){
                require($file);
            }else{
                refresh();
            }
        }


    }else{
        //die('aaa');
        refresh();
    }
}else{
    define("subpage", false);

    //if($_GET['inframe']){
    if($GLOBALS['url_param']!='fbonly'){
        require(core."/html.php");
    }else{
        require(core."/page/frame.php");
    }

}


if($endshow){echo($endshow);}

//e('c');

if(!debug){t("ob_end_flush");ob_end_flush();}

//e('a');

if(logged() and $_GET['e']!="none"/**/){
//e($GLOBALS['ss']['log_object']->id.','.$GLOBALS['ss']['use_object']->id);

    $GLOBALS['ss']['log_object']->update();

//if(!$GLOBALS['ss']['use_object']->loaded){$GLOBALS['ss']['use_object']=new object($GLOBALS['ss']['useid']);}
//$GLOBALS['ss']['use_object']->hold->showimg();
    $GLOBALS['ss']['use_object']->update();
    $GLOBALS['ss']['aac_object']->update();

    unset($GLOBALS['ss']['log_object']);
    unset($GLOBALS['ss']['use_object']);
    unset($GLOBALS['ss']['aac_object']);
}
//die2();
//cleartmp(1);

//================================================SESSIONEND

//================================================
//echo(contentlang("abc{nature}sdf{nature}saddsd{0}sdfzsdg{nature}"));
t("end");
exit2();
//---------------------------------------------
//} catch (Exception $e) {
//    echo 'Caught exception: ',  $e->getMessage(), "\n";
//}
//===============================================================================CATCH
/*}catch(Exception $e){
    echo(''.$e->getMessage().'<br>');
}*/
?>
