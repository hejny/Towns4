<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2015
   _____________________________

   core/memory.php

   Tento soubor slouží jako komunikátor API pro externí aplikace
*/
//==============================


if($_GET['token'] and $_GET['token']!='y') {
    /*    $token=$_POST['token'];
    }elseif($_GET['token'] and $_GET['token']!='y') {
        $token=$_GET['token'];
    }else{*/
}else{
    die('{ "error": "Invalid token!" }');
}

//----

//die(json_encode($_POST));

if($_POST['q']) {
    $query=$_POST['q'];
}elseif($_GET['q']) {
    $query=$_GET['q'];
}else{
    die('{ "error": "No query!" }');
}


//die(json_encode($query));

//--------------------------------------------
define("root", "");//todo: PH - je to divné
require(core."/func_main.php");
require(core."/func_vals.php");
require(core."/func_object.php");
require(core."/memory.php");
require(core."/func.php");
require(core."/func_core.php");
//---------------
require(core."/login/func_core.php");
require(core."/create/func_core.php");
require(core."/attack/func_core.php");
require(core."/text/func_core.php");
require(core."/hold/func_core.php");
require(core."/quest/func_core.php");
//---------------
require(core."/model/func_map.php");
//--------------------------------------------

$response=TownsApi($query);

if($_GET['pretty']) {
    $options = JSON_PRETTY_PRINT;
}else{
    $options = NULL;
}

if($response['1']=='1'){//@todo udělat přímo v interním API
    unset($response['1']);
    $response['ok']=1;
}


$response = json_encode($response,$options);



echo($response);


if(logged()){

    $GLOBALS['ss']['log_object']->update();
    $GLOBALS['ss']['use_object']->update();
    if(is_object($GLOBALS['ss']['aac_object']))$GLOBALS['ss']['aac_object']->update();

    unset($GLOBALS['ss']['log_object']);
    unset($GLOBALS['ss']['use_object']);
    unset($GLOBALS['ss']['aac_object']);
}

exit2();

?>
