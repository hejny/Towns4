<?php
/* Towns4, www.towns.cz 
   © Pavol Hejný | 2011-2015
   _____________________________

   core/page/chat_aac.php

   Chat -obsah
*/
//==============================

/*$iconsize=27;
$border3=array(2,'665544');*/



 $stream='';
 $streamx='';

 $add0='(`to`='.$GLOBALS['ss']['useid'].' OR `to`='.$GLOBALS['ss']['logid'].')';
 $add1='`from`!='.$GLOBALS['ss']['useid'].' AND `from`!='.$GLOBALS['ss']['logid'].'';//' OR `to`='.$GLOBALS['ss']['logid'].'';
 $add2="`type`='message' OR `type`='report' ";
 $q=sql_1data("SELECT COUNT(`from`) FROM `[mpx]text` WHERE `new`=1 AND ($add0) AND ($add1) AND ($add2)");
 //$q=textbr($q);
 //$stream.=movebyr($q,-27,-4,'','z-index:2000;');
 //ahref($stream,$url);
//e($q); 



 if($q){


    icon('menu:text','f_text_new',lr('f_text_new'),$iconsize,0,0,$border3);


 }else{


     //$url="e=content;ee=text-messages;subpage=2;id=".$GLOBALS['ss']['useid'];
     icon('menu:text','f_text',lr('f_text'),$iconsize,0,0,$border3);

 }
 


if(debug)echo(rand(1111,9999));
 ?>
