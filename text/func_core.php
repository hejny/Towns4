<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/text/func_core.php

   Textové(zprávy, diskuse, chat) funkce systému
*/
//==============================





//======================================================================================
define("a_text_help","action{list,send,delete}[idle][idle,to,title,text][,id]");
function a_text($action,$idle,$to="",$title="",$text=""){
    //$add="(SELECT 1 FROM `".mpx."textqw` WHERE `".mpx."textqw`.`textclass`=`".mpx."text`.`class` AND `".mpx."textqw`.`object`='".($GLOBALS['ss']["log_object"]->id)."')";
    $add1='(`to`='.$GLOBALS['ss']['logid'].' OR `from`='.$GLOBALS['ss']['logid'].' OR `to`='.$GLOBALS['ss']['useid'].' OR `from`='.$GLOBALS['ss']['useid'].') AND `to`!=0';
    $add2="`type`='message'";
    if($action=="list"){
        if(($idle and $idle!='new' and $idle!='public' and $idle!='report') or $idle=='chat'){
            $add1='`to`='.$GLOBALS['ss']['logid'].' OR `from`='.$GLOBALS['ss']['logid'].' OR `to`='.$GLOBALS['ss']['useid'].' OR `from`='.$GLOBALS['ss']['useid'].' OR `to`=0';
            $add2="`type`='message' OR `type`='report' ";
	     if($idle=='chat'){$add1='1';$add2="`type`='chat'";}
            $array=sql_array("SELECT `id` ,`idle` ,`type` ,`new` ,`from` ,`to` ,`title` ,`text` ,`time` ,`timestop` FROM `".mpx."text` WHERE `idle`='$idle' AND ($add1) AND ($add2) ORDER BY `time` DESC ".($GLOBALS['limit']?'LIMIT '.$GLOBALS['limit']:'')."",1);
            if($array[0][3]==1){
                r('notnew');
                $add1='`to`='.$GLOBALS['ss']['logid'].' OR `to`='.$GLOBALS['ss']['useid'].'';
                sql_query("UPDATE   `".mpx."text` SET `new`='0' WHERE `idle`='$idle' AND ($add1) AND ($add2)");
            }
            //print_r($array);
            $GLOBALS['ss']["query_output"]->add("list",$array);
        }else{
            if($idle=='new'){$add3='`new`=1 AND (`from`!='.$GLOBALS['ss']['useid'].' AND `from`!='.$GLOBALS['ss']['logid'].')';$add2.=" OR `type`='report'";/*$add3='`time`>'.(time()-(3600*24*7));*/}else{$add3='1';}
            if($idle=='public'){$add1='`to`=0';}
            if($idle=='report'){$add2="`type`='report'";}
	  
            $array=sql_array("SELECT `id` ,`idle` ,`type` ,`new` ,`from` ,`to` ,`title` ,`text` ,MAX(`time`) ,`timestop`, COUNT(`idle`) FROM `".mpx."text` WHERE ($add1) AND ($add2) AND ($add3) GROUP BY `idle` ORDER BY `time` DESC ".($GLOBALS['limit']?'LIMIT '.$GLOBALS['limit']:'')."");
            //print_r($array);            
            $GLOBALS['ss']["query_output"]->add("list",$array);
        }
    }elseif($action=="send"){
        if(!$idle)$idle=sql_1data("SELECT MAX(idle) FROM `".mpx."text`")-(-1);
        if(trim($title) and trim($text)){
            if(/*$to=='0' OR */$to=ifobject($to)){
                if(!sql_1data("SELECT 1 FROM `".mpx."text` WHERE `to`='$to' AND `title`='$title' AND `text`='$text'")){
                    $no=0;
                    if($GLOBALS['ss']['message_limit'][$to]){if($GLOBALS['ss']['message_limit'][$to]+5>time()){$no=1;}}
                    $GLOBALS['ss']['message_limit'][$to]=time();
                    if(!$no){
                        
                        $to_=topobject($to);
                                             
                        //sql_query("INSERT INTO `".mpx."text`(`id` ,`idle` ,`type` ,`new` ,`from` ,`to` ,`title` ,`text` ,`time` ,`timestop`) VALUES(NULL,'$idle','message',1,'".$GLOBALS['ss']['logid']."','".$to_."','$title','$text','".(time())."','')");
                        send_message($GLOBALS['ss']['logid'],$to_,$title,$text,$idle);


                    if($to_==$to){
                        $GLOBALS['ss']["query_output"]->add("success",lr('send_success'));
                    }else{
                        $GLOBALS['ss']["query_output"]->add("success",lr('send_success_to',id2name($to_)));   
                    }
                    $GLOBALS['ss']["query_output"]->add('1',1);
                    //js(target('aa',"e=content;ee=text-messages;submenu=1").'alert(1);');
                    }else{
                        $GLOBALS['ss']["query_output"]->add("error",lr('message_limit'));
                    }
                }else{
                    $GLOBALS['ss']["query_output"]->add("error",lr('same_message'));
                }
            }else{
                $GLOBALS['ss']["query_output"]->add("error",lr('unknown_logr'));
            }
        }else{
            $GLOBALS['ss']["query_output"]->add("error",lr('no_message'));
        }
   
    }elseif($action=="delete"){  
        sql_query("DELETE FROM `".mpx."text` WHERE `id`= '$idle' AND `from`='".$GLOBALS['ss']['logid']."'");
    }
}
//======================================================================================
function backup_text($text){
    $file=tmpfile2($text,'txt','backup');
    file_put_contents2($file,$text);
    
}
//======================================================================================
function send_report($from,$to,$title="",$text="",$idle=false){
    if(!$idle)$idle=sql_1data("SELECT MAX(idle) FROM `".mpx."text`")-(-1);
    $from=topobject($from);
    $to=topobject($to);
    
    
    $time=sql_1data("SELECT time FROM `".mpx."text` WHERE `to`='".$to."' AND type='report' ORDER BY time DESC LIMIT 1");
    
    sql_query("INSERT INTO `".mpx."text`(`id` ,`idle` ,`type` ,`from` ,`to` ,`title` ,`text` ,`time` ,`timestop`) VALUES(NULL,'$idle','report','".sql($from)."','".sql($to)."','".sql($title)."','".sql($text)."','".(time())."','')");
    
    //-----------
    
    
    if($time-(-3600*4)<time()){
    
    $too=new object($to);

	//OLDSYS//$mail=sql_1data('SELECT email FROM [mpx]users WHERE id=(SELECT useid FROM [mpx]objects WHERE id='.($too->id).' LIMIT 1) LIMIT 1');
	$mail=sql_1data('SELECT userid FROM [mpx]objects WHERE id='.($too->id));
    //OLDSYS//$mail=$too->profile->val('mail');
    $sendmail2=$too->profile->ifnot('sendmail3',1);
    $sendmail5=$too->profile->ifnot('sendmail5',1);
    
	//print_r($too->profile->vals2list());
    unset($too);
    //print_r($sendmail3);
    if($sendmail2){
        mailx($mail,lr('mail_new_report'),lr('mail_new_report_body1',id2name($from)).'<br/>'.contentlang($title).'<br/>'.inteligentparse($text).'<br/>'.lr('mail_new_report_body4'));
        //e('mail');
    }
   // print_r($sendmail5);
    if($sendmail5){
        fb_notify(fb_user($to),lr('fb_new_report',id2name($from)));
    }
    
    }else{
        //e('time');
    }
}
//fb_notify(fb_user($to),lr('fb_new_report','text'));
//======================================================================================
function send_message($from,$to,$title="",$text="",$idle=false){
    if(!$idle)$idle=sql_1data("SELECT MAX(idle) FROM `".mpx."text`")-(-1);
    //$from=topobject($from);
    $to=topobject($to);
    //e($to);
    
    
    $time=sql_1data("SELECT time FROM `".mpx."text` WHERE `to`='".$to."' AND type='message' ORDER BY time DESC LIMIT 1");
    
    
    sql_query("INSERT INTO `".mpx."text`(`id` ,`idle` ,`type` ,`from` ,`to` ,`title` ,`text` ,`time` ,`timestop`) VALUES(NULL,'$idle','message','".sql($from)."','".sql($to)."','".sql($title)."','".sql($text)."','".(time())."','')");
    
    //-----------
    
    $too=new object($to);
	$mail=sql_1data('SELECT userid FROM [mpx]objects WHERE id='.($too->id));
    //OLDSYS//$mail=$too->profile->val('mail');
    $sendmail2=$too->profile->ifnot('sendmail2',1);
    //$sendmail4x=$too->profile->val('sendmail4');
    $sendmail4=$too->profile->ifnot('sendmail4',1);
    //print_r($too->profile->vals2list());
    unset($too);
    //e($sendmail2);
    if($sendmail2){
        mailx($mail,lr('mail_new_message'),lr('mail_new_message_body1',id2name($from)).'<br/>'.inteligentparse($text).'<br/>'.lr('mail_new_message_body4'));
        
        if($from==$GLOBALS['inc']['write_id']){
            blue('send mail '.$mail);
        }
    }
    
    /*$data=array(
    'access_token'   => $GLOBALS['inc']['fb_secret'],//fb_secret
    'href'           => url,
    'template'       => inteligentparse($text));
    $response=post_request('https://www.facebook.com/hejny/notifications',$data);*/
    
    //e($sendmail4x);
    //e($sendmail4);
    if($time-(-3600*4)<time()){
        if($sendmail4){
            fb_notify(fb_user($to),lr('fb_new_message',id2name($from)),($from==$GLOBALS['inc']['write_id']?1:0));
        }else{
            if($from==$GLOBALS['inc']['write_id']){
                blue('fb no notify');
            }  
        }
    }else{
        if($from==$GLOBALS['inc']['write_id']){
            blue('fb time');
        }
    }
    
}
//======================================================================================CHAT
define("a_chat","text");
function a_chat($text){
    if(trim($text)){
    if($text!="."){
        sql_query("INSERT INTO `".mpx."text` (`id`, `from`, `to`, `text`, `time`, `timestop`) VALUES (NULL, '".$GLOBALS['ss']['logid']."', '', '$text', '".time()."', '')");
    }else{
        sql_query("UPDATE `".mpx."text` SET timestop='".time()."' WHERE `from`='".$GLOBALS['ss']['logid']."' ORDER BY time DESC LIMIT 1");
    }
    }
}
?>
