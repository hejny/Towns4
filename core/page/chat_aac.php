<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2015
   _____________________________

   core/page/chat_aac.php

   Chat -obsah
*/
//==============================

$iconsize=30;
$border3=array(2,'665544');


 $iconsize=30;


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
    $stream.=imgr("icons/f_text_new.png",lr('f_text_new',$q),$iconsize);
    $streamx=lr('f_text_new',$q);
    $url="e=content;ee=text-messages;subpage=2;ref=chat;id=".$GLOBALS['ss']['useid'];
    //$streamx=(movebyr(textcolorr('<span style="display:block;">'.lr('f_text_new',$q).'</span>','dddddd'),-$iconsize,$iconsize,NULL,'z-index:2001'));
	//border(iconr($url,'f_text_new',lr('f_text_new',$q),$iconsize),$border3,$iconsize,NULL,NULL,lr('f_text_new',$q));  
    //echo($q);
 }else{
    $stream.=imgr("icons/f_text.png",lr('f_text'),$iconsize);
    $url="e=content;ee=text-messages;subpage=3;ref=chat;id=".$GLOBALS['ss']['useid'];
	//border(iconr($url,'f_text',lr('f_text'),$iconsize),$border3,$iconsize); 
 }
 

//print_r(str2list($url));

border(ahrefr($stream,$url),$border3,$iconsize,NULL,NULL,$streamx); 


if(debug)echo(rand(1111,9999));
 ?>
