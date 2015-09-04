<?php
/* Towns4, www.towns.cz 
   © Pavol Hejný | 2011-2015
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
    //die('{ "error": "Invalid token!" }');
}

//----

//die(json_encode($_POST));


if($_POST['q1']) {

    $query=array();
    $qi=1;
    while(isset($_POST['q'.$qi])){

        $query[]=$_POST['q'.$qi];
        $qi++;
    }

}if($_POST['q']) {
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

if(is_array($query)){

    $response=array();
    foreach($query as $query1){
        $respons[]=TownsApi($query1);
    }
}else{
    $response=TownsApi($query);
}



if(isset($response['url']) and $_GET['output']!='json'){
    //rebase(url.worldmap(0,0,$ww,$top))
    //die($response['url']);

    header('Content-Type: '.mime_content_type($response['url']));
    header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' , filemtime($response['url'])) . ' GMT' );
    readfile($response['url']);
    exit2();
}


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
