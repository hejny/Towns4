<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2015
   _____________________________

   core/page/chat_text.php

   Chat -obsah
*/
//==============================




if(logged()){
	$q=19;
    //r($GLOBALS['ss']['useid']);
    $stream='';
    // `id`,`from`,`to`,`text`,`time`,`timestop`
    $sql="SELECT  `id`,`type`,`from`,`to`,`title`,`text`,`time` FROM `[mpx]text` WHERE (`to`='' OR `to`='".$GLOBALS['ss']['useid']."' OR (`from`='".$GLOBALS['ss']['useid']."' AND `type`!='message') OR `to`='".$GLOBALS['ss']['logid']."' OR (`from`='".$GLOBALS['ss']['logid']."' AND `type`!='message')) ORDER BY time DESC LIMIT $q";
    //HOVNO  AND (`type`='report')
    //r($sql);
    $array=sql_array($sql);
    if(count($array)<$q)br($q-count($array));
    $i=1;
    foreach($array as $row){
    	$stream='</span>'.$stream;

        list($id,$type,$from,$to,$title,$text,$time)=$row;
        //echo($type);
        if($type=='chat'){
	    //$stream=trr("[".timer($time)."]",0,1).ahrefr(trr("[".id2name($from)."]:",0,1),"e=content;ee=profile;page=profile;id=".$from).trr(nbsp.tr($text),0,1).br.nln.nln.$stream;
            $stream=("[".timer($time)."]"./*ahrefr(*/tr("[".id2name($from)."]",0,1)/*,"e=content;ee=profile;page=profile;id=".$from)*/.":".nbsp.tr($text)).br.$stream;
        }elseif($type=='report' or $type=='message'){

			if(substr($title,0,1)=='{' and substr($title,-1)=='}'){
				//$title='SSS';
				$title=substr($title,1,strlen($title)-2);
				$title=explode(';',$title,2);
				$title=lr($title[0],$title[1]);
			}
			
	    //$stream=trr("[".timer($time)."]",0,1).ahrefr(trr("[".id2name($from)."]:",0,1),"e=content;ee=profile;page=profile;id=".$from).trr(nbsp.tr($title),0,1).br.nln.nln.$stream;
            //$stream=trr("[".timer($time)."][".id2name($from)."]:".nbsp.tr($title),0,1).br.nln.nln.$stream;
            $stream=("[".timer($time)."]"./*ahrefr(*/tr("[".id2name($from)."]",0,1)/*,"e=content;ee=profile;page=profile;id=".$from)*/.":".nbsp.short(tr($title.(($type=='message')?' '.$text:'')),100)).br.$stream;
	    //$stream=($title);
        }

    	$stream='<span id="chat_first'.$i.'" >'.$stream;
    	$i++;
    }
    $stream='<span id="chat_new" style="display:none;">['.timer(time())."]["./*liner*/id2name($GLOBALS['ss']['logid'])."]:".nbsp.'[text]'.br.'</span>'.$stream;
    //echo($stream);
    echo(contentlang($stream));
    //echo(rand(1,9999));
}else{
    refresh();
}
?>

<script type="text/javascript">
chat_first=<?php e($q); ?>;
//$('#say').focus();
//$('#say').blur();
</script>
