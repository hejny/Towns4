<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2015
   _____________________________

   core/memory.php

   Tento soubor slouží jako komunikátor API pro externí aplikace
*/
//==============================


if(!$_GET['token'] or $_GET['token']=='y'){
    die('{ "error": "Invalid token!" }');
}
if(!$_GET['q']){
    die('{ "error": "No query!" }');
}


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
//--------------------------------------------

$q=$_GET['q'];
$response=TownsApi($q);

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
