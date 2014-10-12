<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/page/debug.php

   debug menu
*/
//==============================




/*if(logged){
    //icon("e=content;ee=help;","help","{help}",30);
    icon("q=logout","logout","{logout}",30);
    icon("js=$(document).fullScreen(true);","fullscreen","{fullscreen}",30);
}


$lang=$GLOBALS['ss']["lang"];
 if(!$lang){$lang=lang;}
if($GLOBALS['get']["lang"]){$lang=$GLOBALS['get']["lang"];}
$GLOBALS['ss']["lang"]=$lang;*/
//============================================================ADMIN
if(useid==1){
 br();
	 //ahref("createuser","e=createuser","none","x");
}
//============================================================DEBUG
if(debug){
 /*br();
 //submenu(array("čeština","english"),"lang",true);
 ahref("logout","q=logout","none","x");
 br
 


if($lang=="cz"){
ahref("English","lang=en","none","x");
}else{
ahref("Čeština","lang=cz","none","x");
}();*/

?>
<a href="?">Refresh</a>
<?php

//--------------------------------------
br();
?>
<a href="?resetwindow=1">ResetW</a>
<?php
//--------------------------------------
br();
?>
<a href="?resetmemory=1">ResetM</a>
<?php
//--------------------------------------
//br();
//ahref("CTable","e=ctable","none","x");


br();
ahref("Output","e=output","none","x");
br();

/*
$tmp=$_SERVER["REQUEST_URI"];
if(strpos($tmp,'?'))$tmp=substr($tmp,0,strpos($tmp,'?'));
?>
<a href="<?php e($tmp); ?>/admin">Admin</a><br>
<<a href="<?php e(url); ?>/compile.php?return=1">Compile</a>
<a href="<?php e(url); ?>/compile.php?return=1&amp;push=1">Push</a>



<?php */} ?>
