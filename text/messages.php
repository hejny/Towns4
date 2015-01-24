<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/text/messages.php

   Okno zpráv
*/
//==============================




window(lr('title_messages')/*,400,400*/);
sg("textclass");


if(!$textclass)$q=submenu(array("content","text-messages"),array(/*"messages_public",*/chat?"messages_chat":false,"messages_unread","messages_all","messages_report","messages_new"),3,'messages');
$q=$GLOBALS['ss']['submenu'];
r('textclass: '.$textclass);


/*if($q==1){//r($q);
//===========================================================
contenu_a();    

e('aaaa');

contenu_b();    
//===========================================================
}elseif($q==1 and $GLOBALS['inc']){

contenu_a();
?>
<b><a href="<?php e($GLOBALS['inc']['forum']); ?>">{messages_forum;<?php e($GLOBALS['inc']['forum']); ?>}</a></b>
<?php
contenu_b();


}else*/if($q==1 || $q==2 || $q==3 || $q==4/* || $q==5*/){

//$textclass=$GLOBALS['get']['textclass'];
//sg("textclass");
//r($textclass);

if(!$textclass){
	
    /*if($q==1){$tmp='public';}else*/if($q==2){$tmp='new';}elseif($q==4){$tmp='report';}elseif($q==1){$tmp='chat';$textclass=0;}else{$tmp=$textclass;}
	//e($tmp);
}else{
    $tmp=$textclass;
}

//$max=sql_1data('SELECT COUNT(1) FROM [mpx]text WHERE `to`='.$GLOBALS['ss']['logid'].' OR `from`='.$GLOBALS['ss']['logid'].' OR `to`='.$GLOBALS['ss']['useid'].' OR `from`='.$GLOBALS['ss']['useid'].' OR `to`=0');
//$GLOBALS['limit']=limit(array('content',"messages"),'messages'.$q.$tmp,16,$max);
$response=xquery("text","list",$tmp);//`id` ,`idle` ,`type` ,`from` ,`to` ,`title` ,`text` ,`time` ,`timestop`
//r($tmp);
$texts=$response["list"];
if($textclass or $q==1){
    $col="222222";
    //r('hovno');
    if($q!=1 or $textclass){
    	infob(ahrefr(lr('message_back'),"e=content;ee=text-messages;textclass=0").(nbspo.nbspo).bhpr(lr('message_reply')));
    }else{
    	infob(lr('messages_chatinfo'));
    }
    
contenu_a();    
    
    //-----------------------
    hydepark();
    $q=count($texts)-1;
    
    //print_r($texts[$q]);
    if($texts[$q][5]==$GLOBALS['ss']['logid']){
        $q=$texts[$q][4];
    }else{
        $q=$texts[$q][5];
    }
    
    //e($q);
    //($texts[$q][5]?$texts[$q][4]:0)
    $url=("q=text send,$textclass,".$q.",-,[message_text]");//($texts[0][4]?$texts[0][3]:0)//[message_title]
    
    //$url=urlr("textclass=aa;q=text");
    form_a(urlr($url),'messages_tc');
    //textb("Nadpis:");
    //input_text("title",1,100,30);
    //br();
    $style='border: 2px solid #222222; background-color: #CCCCCC; width:100%;';
    //tableab(lr('message_subject').':',input_textr("message_title",'-',100,26,$style),"100%","30%");
    //br();
    input_textarea("message_text",'',45,6,$style);
    br();
    form_sb(lr('submit_message_reply'));
    ihydepark();
    form_js('content','?e=text-messages&'.$url,array('message_title','message_text'));
    
    //-----------------------
    echo("<table width=\"".((!$GLOBALS['mobile']?contentwidth:'96%'))."\" bgcolor=\"$col\" cellspacing=\"0\">");

    /*echo("<tr  bgcolor=\"#444444\">");
    echo("<td height=\"\" valign=\"top\" colspan=\"6\">");
    echo("</td></tr>");*/

    foreach($texts as $tmp){
            list($id,$idle,$type,$new,$from,$to,$title,$text,$time,$timestop,$count)=$tmp;
            /*if(!$f and $textclass){$f=1;
                echo("<h2>$title</h2>");
                ahref("zpět","textclass=0");
            }else*/{
                //r($id,$class,$title,$time,$author,$text);
                echo("<tr  bgcolor=\"#$col\">");
                //echo("<td width=\"120\">");
                $authorid=$from;
                //mprofile($author);br();br();
                
                
                //$fromto=ifobject($to)?(short(id2name($from),8).nbsp.'&gt;&gt;'.nbsp.short(id2name($to),8)):short(id2name($from),8);
                //$fromtoid=($from==$GLOBALS['ss']['logid']?$to:$from);
                
                
                //if($from!=$GLOBALS['ss']['useid'] and $from!=$GLOBALS['ss']['logid']){
                    $fromto=ahrefr(short(id2name($from),20),"e=content;ee=profile;page=profile;id=".$from,'',true);
                //}else{
                //    $fromto=ahrefr(short(id2name($to),20),"e=content;ee=profile;page=profile;id=".$to,'',true);
                //}
                
                
                //imge("id_".$author."_icon","",50,50);
                //echo("<b>".xx2x(contentlang($title))."</b>");
                echo("<td width=\"60\">");//</td>
                //e("e=content;ee=profile;page=profile;id=".$fromtoid);
                e(nbsp3);
                //ahref($fromto,"e=content;ee=profile;page=profile;id=".$fromtoid,"",true);
                e($fromto);
                
                echo("</td><td width=\"\" align=\"right\">");
                timee($time);
                e(nbspo);
                /*
                echo("</td><td width=\"22\">");
                e($authorid);
                if(($GLOBALS['ss']['logid']==$authorid or $GLOBALS['ss']['useid']==$authorid) and $textclass){iconp(lr('delete_message_prompt'),"e=content;ee=text-messages;q=text delete ".$id,"delete","Smazat");}
                echo("</td><td width=\"22\">");*/
                echo("</td></tr><tr  bgcolor=\"#000000\"><td align=\"left\" colspan=\"2\">");
               // e('<div style="width:'.(contentwidth-6).';overflow:none;">');
                //e(xx2x(contentlang($text)));
                
                //e($text);
                
                e(inteligentparse($text));
                //e(inteligentparse(xx2x($text)));
                
                //(str_replace(nbsp,' ',tr$text));
                //e('</div>');
                echo("<br><br></td></tr>");
            }
    }
    echo("</table>");
}else{
contenu_a();    
    
    e("<table width=\"".((!$GLOBALS['mobile']?contentwidth-6:'96%'))."\" cellspacing=\"0\">");
    /*e("<tr><td width=\"36%\">");
    e("Předmět");
    e("</td><td width=\"5%\">");
    e("Počet");
    e("</td><td width=\"20%\">");
    e("Od");
    e("</td><td width=\"30%\">");
    e("Datum");
    e("</td></tr>");*/
    $i=1;foreach($texts as $tmp){$i++;
            list($id,$idle,$type,$new,$from,$to,$title,$text,$time,$timestop,$count)=$tmp;
                //r($id,$class,$title,$time,$author,$text);
                
                if($from!=$GLOBALS['ss']['useid'] and $from!=$GLOBALS['ss']['logid']){
                    $fromto=ahrefr(short(id2name($from),12),"e=content;ee=profile;page=profile;id=".$from,'',true);
                }else{
                    $fromto=ahrefr(short(id2name($to),12),"e=content;ee=profile;page=profile;id=".$to,'',true);
                }
                
                //$fromto=//ifobject($to)?(short(id2name($from),8).nbsp.'&gt;&gt;'.nbsp.short(id2name($to),8)):short(id2name($from),8);
                
                e("<tr bgcolor=\"#".($i%2==1?'222222':'333333')."\"><td width=\"41%\">");
                
                if($title and $title!='-'){
                    $title=short(xx2x(contentlang($title)),30);
                }else{
                    $title=short(xx2x(contentlang($text)),30);  
                }
                
                if($new and $q!=1 and $to==$GLOBALS['ss']['useid'])$title=tcolorr(textbr($title),'ff7777');
                ahref($title,"e=content;ee=text-messages;textclass=".$idle,'',true);
                //e("</td><td width=\"5%\">");
                if($count!=1)e('('.$count.')');
                e("</td><td width=\"20%\">");
                //e("e=content;ee=profile;page=profile;id=".$from);
                e($fromto);
                e("</td><td width=\"30%\" align=\"right\">");
                timee($time);
                e("</td></tr>");
    }
    echo("</table>");  
    if($i==1){
	infob(lr('messages_none'));
    }

}
contenu_b();}elseif($q==5){
//===========================================================
    
    contenu_a();

    infob(lr('mesage_forum_info'));

    $url=("q=text,send,".($textclass?$textclass:'0').",[message_to],-,[message_text]");//[message_title]
    //$url=urlr("textclass=aa;q=text");
    form_a(urlr($url),'messages');
    //textb("Nadpis:");
    //input_text("title",1,100,30);
    //br();
    if($GLOBALS['ss']['get']['to']){
        $to=id2name($GLOBALS['ss']['get']['to']);
    }else{
        $to='';
    }
    
    $style='border: 2px solid #333333; background-color: #CCCCCC';
    tableab(lr('message_to').':',input_textr("message_to",$to,100,26,$style),"100%","30%");
    br();
    //tableab(lr('message_subject').':',input_textr("message_title",'',100,26,$style),"100%","30%");
    //br();
    input_textarea("message_text",'',52,6,$style);
    br();
    form_sb(lr('submit_message'));
    form_js('content','?e=text-messages&'.$url,array('message_to','message_title','message_text'));


    infob(lr('mesage_markdown_info'));
    ?>
   
    <?php
	/* <div style="background:#333333;" ><?php le('message_to_info'); ?></div>*/

//===========================================================
contenu_b(true);}

?>
