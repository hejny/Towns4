<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/func.php

   Pokročilé funkce
*/
//==============================




require_once(root.core."/func_vals.php");
require_once(root.core."/func_object.php");
require_once(root.core."/func_main.php");
require_once(root.core."/memory.php");
require_once(root.core."/mobile_detect.php");



//=============================================================

  eval('req'.'uire_once(root."lib/facebook_sdk/base_facebook.php");');
  eval('req'.'uire_once(root."lib/facebook_sdk/facebook.php");');

  $fb_config = array();
  $fb_config['appId'] = fb_appid;
  $fb_config['secret'] = fb_secret;

  $facebook = new Facebook($fb_config);
  


  eval('req'.'uire_once(root."lib/facebook_sdk/base_facebook.php");');
  eval('req'.'uire_once(root."lib/facebook_sdk/facebook.php");');

  $fb_config = array();
  $fb_config['appId'] = fb_appid;
  $fb_config['secret'] = fb_secret;

  $GLOBALS['facebook'] = new Facebook($fb_config);
  
//-----------------------------------------fb_notify
  
function fb_notify($user,$template,$print_r=0){
    //print_r($print_r);
    //e("($user,$template)");
    //if($user===true)$user=fb_user();
    if($user and $template){
        
        try {
    
        $app_access_token = $GLOBALS['inc']['fb_appid'] . '|' . $GLOBALS['inc']['fb_secret'];
        $response = $GLOBALS['facebook']->api( '/'.$user.'/notifications', 'POST', array(
                    'template' => $template,
                    'href' => url,
                    'access_token' => $app_access_token
                ) );    
         
         if($print_r){print_r($response);br();}
         
        } catch (Exception $e) {
            //echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
   
}

//-----------------------------------------fb_user
function fb_user($id){//$GLOBALS['ss']['logid']
    $key=sql_1data('SELECT `key` FROM [mpx]login WHERE `method`=\'facebook\' AND `id`='.$id);
    return($key);
    
}
//=============================================================
//(4.5*6+5*6+4.5*6+3*2+5*7+1*2)/(6+6+6+2+7+2)
define("notmp", false);
//define("notmp", true);
if($_GET["output"]=="js"){
    define("noreport", true);
}else{
    define("noreport", false);
}
define("imgext", "jpg");
//$GLOBALS['ss']["useid"]=$GLOBALS['ss']["useid"];
//$GLOBALS['ss']["logid"]=$GLOBALS['ss']["logid"];

//===============================================================================================================
if(!defined('mapsize')){
    $mapsize1=sql_1data('SELECT max(x) FROM [mpx]map WHERE ww=\''.$GLOBALS['ss']["ww"].'\'');
    $mapsize2=sql_1data('SELECT max(y) FROM [mpx]map WHERE ww=\''.$GLOBALS['ss']["ww"].'\'');
    $mapsize1=intval($mapsize1)+1;
    $mapsize2=intval($mapsize2)+1;
    //echo("($mapsize1,$mapsize2)");
    if($mapsize1>$mapsize2){
        $mapsize=$mapsize1;
    }else{
        $mapsize=$mapsize2;
    }
    define('mapsize',$mapsize);
}
//===============================================================================================================
define('cookietime',time()+60*60*24*30*12);
/*if($GLOBALS['ss']["setcookie"] and array()!=$GLOBALS['ss']["setcookie"]){
    print_r($GLOBALS['ss']["setcookie"]);die();
    $t=time()+60*60*24*30;
    foreach($GLOBALS['ss']["setcookie"] as $a=>$b)
    setcookie($a,$b,$t);
}
$GLOBALS['ss']["setcookie"]=array();*/

//=============================================================
function changemap($x,$y,$files=false){
    //v total onMap verzi nemá smysl parametr $files -> nastavit na pevno na true
    //$files=true;
    
    if($files and $files!=2){
    //r($x.','.$y);
    if(!defined("func_map"))require(root.core."/func_map.php");
    //$gx=(intval(($x-1)/5)*5)+1;
    //$gy=(intval(($y-1)/5)*5)+1;
    //r($gx.",".$gy);
    //$file=tmpfile2("map2,".size.",".zoom.",".$gx.",".$gy.",".w.",".gird.",".t_sofb.",".t_pofb.",".t_brdcc.",".t_brdca.",".t_brdcb.$GLOBALS['ss']["ww"],"png","map");e("<img src=\"$file\" width=\"100\"/>");unlink($file);
    
    
    
    //-------------------
    
    //r('changemap');
    //$gx=floor((($y-1)/-10)+(($x-1)/10));
    $gy=floor((($y-1)/10)+(($x-1)/10)-0.5);
    $gx_=round((($y-1)/-10)+(($x-1)/10));
    $gy_=round((($y-1)/10)+(($x-1)/10)-0.5);
    $gs=array(/*array($gx,$gy),array($gx_,$gy),*/array($gx,$gy_),array($gx_,$gy_));
    //r($gx.",".$gy.",".$gx_.",".$gy_);
    $x=round($x);
    $y=round($y);
    
    foreach($gs as $g){list($gx,$gy)=$g;
        //$x=($gy+$gx)*5+1;
        //s$y=($gy-$gx)*5+1;
        //2NOCACHE//$file=tmpfile2("map2,".size.",".zoom.",".$x.",".$y.",".w.",".gird.",".t_sofb.",".t_pofb.",".t_brdcc.",".t_brdca.",".t_brdcb.$GLOBALS['ss']["ww"],"png","map");e("<img src=\"$file\" width=\"100\"/>");/**/unlink2($file);
        //---      
        //r("outimgunits,".size.",".zoom.",".$gx.",".$gy.",".w.",".gird.",".t_sofb.",".t_pofb.",".t_brdcc.",".t_brdca.",".t_brdcb.','.$GLOBALS['ss']["ww"]);
        //$file=tmpfile2("outimgunits,".size.",".zoom.",".$gx.",".$gy.",".w.",".gird.",".t_sofb.",".t_pofb.",".t_brdcc.",".t_brdca.",".t_brdcb.','.$GLOBALS['ss']["ww"],"png","map");/*if(debug){e("<img src=\"$file\" width=\"200\"/>");}*/
        //r($gx,$gy);        
        //htmlmap($gx,$gy);
        $file=htmlmap($gx,$gy,2,true);        
        unlink2($file);
        //---
        //NOCACHE//$file=tmpfile2("output6,".root.",$gx,$gy,".$GLOBALS['ss']["ww"],"txt","map");unlink2($file);
    }
    }elseif($files==2){

	    if(!defined("func_map"))require(root.core."/func_map.php");

	    
	    //$gy=floor((($y-1)/10)+(($x-1)/10)-0.5);
	    //$gx_=(($y-1)/-10)+(($x-1)/10);
	    //$gy_=(($y-1)/10)+(($x-1)/10);
 	    $x=round($x);
	    $y=round($y);
		 $gx1=floor((($y-1)/-10)+(($x-1)/10));
   		 $gy1=floor((($y-1)/10)+(($x-1)/10));
		 $gx2=ceil((($y-1)/-10)+(($x-1)/10));
   		 $gy2=ceil((($y-1)/10)+(($x-1)/10));
		 $gx3=ceil((($y-1)/-10)+(($x)/10));
   		 //$gy3=ceil((($y-1)/10)+(($x-1)/10));    



	    $gs=array(array($gx1,$gy1),array($gx2,$gy1),array($gx3,$gy1),array($gx1,$gy2),array($gx2,$gy2),array($gx3,$gy2));
	   
	    
	    foreach($gs as $g){list($gx,$gy)=$g;
		
		//e("$gx,$gy");br();
		$file=htmlmap($gx,$gy,1,true);   
		define('terrain_error',$file);  
		unlink2($file);


	    }

    }
    //sql_query("UPDATE `".mpx."map` SET  `hard` =  IF(`terrain`='t1' OR `terrain`='t11',1,0)+(SELECT SUM(`".mpx."objects`. `hard`) FROM `".mpx."objects` WHERE `".mpx."objects`.`ww`=`".mpx."map`.`ww` AND  ROUND(`".mpx."objects`.`x`)=`".mpx."map`.`x` AND ROUND(`".mpx."objects`.`y`)=`".mpx."map`.`y`) WHERE `ww`=".$GLOBALS['ss']["ww"]." AND `x`=$x AND `y`=$y");
}
//------------------------

//Funkce HARD je zastaralá
/*function hard($rx,$ry,$w=false){
    if(!$w)$w=$GLOBALS['ss']["ww"];
    $hard1=sql_1data("SELECT IF(`terrain`='t1' OR `terrain`='t11',1,0) FROM `".mpx."map`  WHERE `".mpx."map`.`ww`=".$w." AND  `".mpx."map`.`x`=$rx AND `".mpx."map`.`y`=$ry");// WHERE `ww`=".$GLOBALS['ss']["ww"]." AND `x`=$x AND `y`=$y");
    $hard2=sql_1data("SELECT SUM(`".mpx."objects`. `hard`) FROM `".mpx."objects` WHERE `".mpx."objects`.`ww`=".$w." AND  ROUND(`".mpx."objects`.`x`)=$rx AND ROUND(`".mpx."objects`.`y`)=$ry AND `own`!='".useid."'");// WHERE `ww`=".$GLOBALS['ss']["ww"]." AND `x`=$x AND `y`=$y");
    $hard=floatval($hard1)+floatval($hard2);
    return($hard);
}*/
  
//======================================================================================
//CONFIG
if($_GET["w"]){
    $GLOBALS['get']=$GLOBALS['ss'][$_GET["w"]];
}
if($GLOBALS['get']){
    $GLOBALS['ss']["get"]=$GLOBALS['get'];
}
//print_r($GLOBALS['ss']["get"]);

function get($key){return($GLOBALS['ss']["get"][$key]);}
//$GLOBALS['ss']["getvars"]=array();
//---------------------------------------------------------
$post=$_POST;
//---------------------------------------------------------
function sg($value,$d=false){
global $$value;
if(!$GLOBALS['ss']['sg_'.$value])$GLOBALS['ss']['sg_'.$value]=$d;
if($GLOBALS['ss']["get"][$value]){
    $GLOBALS['ss']['sg_'.$value]=$GLOBALS['ss']["get"][$value];
}
if($_GET[$value]){
    $GLOBALS['ss']['sg_'.$value]=$_GET[$value];
}
if($GLOBALS['ss']["get"][$value]==="0"){
    $GLOBALS['ss']['sg_'.$value]=$d;
}
$$value=$GLOBALS['ss']['sg_'.$value];
return($GLOBALS['ss']['sg_'.$value]);
}
//---------------------------------------------------------
/*$i=0;foreach($md5 as $a){
$md5[$i]=hexdec($a);
$i++;}*/
function md52($text){
    $md5=md5($text);
    $md5=str_split($md5,4);
    $count=0;
    foreach($md5 as $a){
        $count=$count+hexdec($a);
    }
    return($count);
}
//---------------------------------------------------------
function md5t($text){
    $md5=md5($text);
    $md5=str_split($md5,4);
    $i=intval(hexdec($md5[0])/(256*256)*100000);
    //echo($i);
    $names=explode(",",$GLOBALS['config']["names"]);
    $i1=mod($i,count($names));
    $i=div($i,count($names));
    $i2=mod($i,count($names));
    $i=div($i,count($names));
    $i=$names[$i1].$names[$i2];//.dechex($i)
    return($i);
    //return($names[$i]);
}
//===================================================URSL, SUBPAGE, WINDOWS
function target($sub,$w="",$ee="",$q,$only=false,$rot="",$noi=false,$prompt='',$set='',$cache=''){
    //newwindow
    if($q)$q="&q=$q";
    if($w)$w="&w=$w";
    if($rot)$rot="&rot=$rot";
    if(!$ee)$ee=$sub;
    if($set)$set="&set=$set";
    if($prompt)$prompt="pokracovat = confirm('$prompt');if(pokracovat)";
    $apart=("w_open('$sub','$ee','$w$q$set');");
    //oldwindow
    $vi="
if(typeof event === 'undefined'){1;}else{
\$('#loading').css('display','block');
\$('#loading').css('left',event.pageX-10);
\$('#loading').css('top',event.pageY-10);
}
";
    $iv="\$('#loading').css('display','none');";
    if(!$noi){$inter="&i=$sub,$ee";}else{$inter="";}
    $bpart=("\$(function(){\$.get('?y=".$_GET['y']."&e=$ee$w$q$rot$inter$set', function(vystup){\$('#$sub').html(vystup);$iv});$vi});");
    if($cache){
        $bpart="if(ifcache('$cache')){ \$('#$sub').html(cache('$cache')); $bpart }else{ $bpart }";
        
        
    }
    if($GLOBALS['mobile'] and $sub=='content')$bpart.="\$('#content').html('<table width=\'100%\' height=\'50%\'><tr><td align=\'center\' valign=\'center\'>".lr('loading')."</td></tr></table>');\$('#mobilecontent').css('display','block');"."\$('#map_context').css('display','none');";
    //-------
    //return("if(getElementById('$sub')){alert(1);};");
    if(!$only and $sub!='map'){
        return($prompt."{if($('#$sub').html()){1;$bpart}else{1;$apart}}");
    }else{
	
	/*if($sub=='map'){
		$before='';
		$bpart
	}else{
		$before='';
	}*/
        return($prompt."{if($('#$sub').html()){1;$bpart}}");
    }
}
//---------------------------------------------------------
function subempty($sub,$html=''){
    if(!$html)$html=nbsp;
    echo('<span id="'.$sub.'">'.$html.'</span>');
}
//---------------------------------------------------------

function subpage($sub,$ee=""){
    if(!$ee)$ee=$sub;
    list($dir,$ee)=explode('-',$ee);
    if(!$ee){$ee=$dir;$dir='page';}
    $eval='echo("<span id=\"'.$sub.'\">");
    include(core."/'.$dir.'/'.$ee.'.php");
    echo("</span>");';
    return($eval);
    //? ><script><?php echo(target($sub)); ? ></script><?php
}
//---------------------------------------------------------
function aac(){
        ob_start();
        include(core.'/page/aac.php');
        $buffer = ob_get_contents();
        ob_end_clean();
	//e($buffer);
	js($buffer);
}
//---------------------------------------------------------
function subpage_($sub,$ee=""){
    if(!$ee)$ee=$sub;
    list($dir,$ee)=explode('-',$ee);
    if(!$ee){$ee=$dir;$dir='page';}
    $eval='include(core."/'.$dir.'/'.$ee.'.php");';
    return($eval);
    //? ><script><?php echo(target($sub)); ? ></script><?php
}
//---------------------------------------------------------
function subref($sub,$period=false){$period=$period*1000;
if($period){
    ?>
    <script>
    setInterval(function() {
    <?php echo(target($sub,"","","",true,"",true)); ?>
    }, <?php echo($period); ?>);
    </script>
    <?php
}else{
    ?>
    <script>
    <?php echo(target($sub,"","","",true,"",true)); ?>
    </script>
    <?php
}
}
//---------------------------------------------------------
/*function subdelay($sub,$delay=false){$delay=$delay*1000;
if($delay){
    ?>
    <script>
    setTimeout(function() {
    alert(567);
    <?php echo(target($sub,"","","",true,"",true)); ?>
    }, <?php echo($delay); ?>);
    </script>
    <?php
}else{
    ?>
    <script>
    <?php echo(target($sub,"","","",true,"",true)); ?>
    </script>
    <?php
}
}*/
//---------------------------------------------------------
function subescape($sub){
    if(!$ee)$ee=$sub;
    list($dir,$ee)=explode('-',$ee);
    if(!$ee){$ee=$dir;$dir='page';}
    if(!$buffer){
        ob_start();
        include(core.'/'.$dir.'/'.$ee.'.php');
        $buffer = ob_get_contents();
        ob_end_clean();
    }
     //-------
        //$buffer=contentlang($buffer);
        $bufferx="";
        foreach(str_split($buffer) as $char){
            if(strtr($char,"ěščřžýáíéúůĚŠČŘŽÝÁÍÉÚŮqwertyuiopasdfghjkl","0000000000000000000000000000000000000000000000000000000000")==$char){
                $char=dechex(ord($char));
                if(strlen($char)==1){ $char=("0".$char); }
                $char="%".$char;
            }
            $bufferx=$bufferx.$char;
        }     
     //-------
     //$bufferx=aacute($bufferx);
     
     
     return($bufferx);
}
//---------------------------------------------------------

function subjsr($sub,$buffer=false,$plus=false,$alert=false){
    if(!$ee)$ee=$sub;
    list($dir,$ee)=explode('-',$ee);
    if(!$ee){$ee=$dir;$dir='page';}
    if(!$buffer){
        ob_start();
        include(core.'/'.$dir.'/'.$ee.'.php');
        $buffer = ob_get_contents();
        ob_end_clean();
    }
     //-------
        //$buffer=contentlang($buffer);
        $bufferx="";
        foreach(str_split($buffer) as $char){
            if(strtr($char,"ěščřžýáíéúůĚŠČŘŽÝÁÍÉÚŮqwertyuiopasdfghjkl","0000000000000000000000000000000000000000000000000000000000")==$char){
                $char=dechex(ord($char));
                if(strlen($char)==1){ $char=("0".$char); }
                $char="%".$char;
            }
            $bufferx=$bufferx.$char;
        }     
     //-------
    $return='';
 
     $return.=('tmp=unescape("'.($bufferx).'");if(typeof(last'.str_replace('-','__',$sub).')=="undefined")last'.str_replace('-','__',$sub).'="";if(last'.str_replace('-','__',$sub).'!=tmp){last'.str_replace('-','__',$sub).'=tmp;');
     if($alert)$return.=('alert("'.$sub.'");');
     if($alert)$return.=('alert($("#'.$sub.'").html());');
     if($alert)$return.=('alert(tmp);');
     $return.=($plus?'tmp=$("#'.$sub.'").html()+tmp;':'');
     if($alert)$return.=('alert(tmp);');
     $return.=('$("#'.$sub.'").html(tmp);}');
     
     return($return);
}
//--------
function subjs($sub,$buffer=false,$plus=false,$alert=false){
    e(subjsr($sub,$buffer,$plus,$alert));   
}
//---------------------------------------------------------
function subexec($sub){
    if(!$ee)$ee=$sub;
    list($dir,$ee)=explode('-',$ee);
    if(!$ee){$ee=$dir;$dir='page';}
        ob_start();
        include(core.'/'.$dir.'/'.$ee.'.php');
        ob_end_clean();
   }
//---------------------------------------------------------
function urlr($tmp){//$tmpx="&amp;tmp=".$tmp;$tmpxx="&tmp=".$tmp;
    //r($tmp);
    if(str_replace("http://","",$tmp)==$tmp){
        if(logged()){
            //echo("rand");
            $md5=md52(session_id().$tmp);
        }else{
            $md5=md52($tmp);
        }
        $GLOBALS['ss'][$md5]=array();
        $tmp=explode(";",$tmp);
        foreach($tmp as $row){
            list($a,$b)=explode("=",$row);
            $GLOBALS['ss'][$md5][$a]=$b;
        }
        $e=$GLOBALS['ss'][$md5]["e"];
        $q=$GLOBALS['ss'][$md5]["q"];$qq=$q;
        $ee=$GLOBALS['ss'][$md5]["ee"];
        $js=$GLOBALS['ss'][$md5]["js"];
        $ref=$GLOBALS['ss'][$md5]["ref"];
        $rot=$GLOBALS['ss'][$md5]["rot"];
        $i=$GLOBALS['ss'][$md5]["i"];
        $set=$GLOBALS['ss'][$md5]["set"];
        if($q){$q="&amp;q=$q";}else{$q="";}
        if($rot){$rot="&amp;rot=$rot";}else{$rot="";}
        if($i){$i="&amp;i=$i";}else{$i="";}
        if($set){$set="&amp;set=$set";}else{$set="";}
        if(!$prompt)$prompt='';        
        if(!$e and !$js  and !$ref){
            //r("outling(!$e and !$js  and !$ref)");
            return(/*$GLOBALS['ss']["url"]*/url."?y=".$_GET['y']."&amp;w=".$md5.$q.$rot.$i.$set);//.$tmpx
        }else{
            if($e=="s"){$e=$GLOBALS['ss']["page"];}
            if($ee=="s"){$ee=$GLOBALS['ss']["page"];}
            if($js)$js=xx2x($js).";";
            $js=str_replace("[semicolon]",";",$js);
            $rot=$GLOBALS['ss'][$md5]["rot"];
            $noi=$GLOBALS['ss'][$md5]["noi"];
            $prompt=$GLOBALS['ss'][$md5]["prompt"];
            $set=$GLOBALS['ss'][$md5]["set"];
            $cache=$GLOBALS['ss'][$md5]["cache"];
            //r($GLOBALS['ss'][$md5]);
            if($e)$js=$js.target($e,$md5,$ee,$qq,false,$rot,$noi,$prompt,$set,$cache);//.$tmpxx
            if($ref)$js=$js.target($ref);
            return("javascript: ".($js));//addslashes
        }
    }else{
        return($tmp);
    }
}
//------------------------
function url($tmp){
    echo(urlr($tmp));
}
//------------------------
function urlxr($url,$script=true){
    $url=urlr($url);
    //r('urlx: '.$url);
    if(strpos($url,'javascript:')!==false){
        $url=str_replace('javascript:', '', $url);
        $url=trim($url);
        if($script){
            return('<script>'.$url.'</script>');
        }else{
            return($url);
        }
        
    }
}
function urlx($url,$script=true){e(urlxr($url,$script));if($script){exit2();}}
//------------------------
function js2($js){
	return("js=".x2xx($js));
}
//======================================================================================
/*function file2hex($file){
    $string=file_get_contents($file);
    $hex='';
    for ($i=0; $i < strlen($string); $i++)
    {
        $hex .= dechex(ord($string[$i]));
    }
    return $hex;
}*/
//======================================================================================
function logged(){
    if($GLOBALS['ss']["logid"]){
	if($GLOBALS['url_param']!='fbonly'){
	    //if($GLOBALS['ss']["log_object"]->loaded and $GLOBALS['ss']["use_object"]->loaded){
            return(true);
	    //}else{
	    //    return(false);
	    //}
	}else{
        return(false);
	}
    }else{
        return(false);
    }
}
define("logged",logged());
//===============================================================================================================
function short($text,$len){
    //r($text);
    if(substr($text,0,1)=='{')$text=contentlang($text);
    $text2=substr($text,0,$len-3);
    if($text!=$text2){$text2=$text2."...";}
    return($text2);
}
function shortx($text,$len){
    //r($text);
    if(substr($text,0,1)=='{')$text=contentlang($text);
    $text2=substr($text,0,$len);
    return($text2);
}
//===============================================================================================================
function substr2($input,$a,$b,$i=0,$change=false,$startstop=true){if(rr()){echo("<br/>substr2($input,$a,$b,$i,$change)<br/>");echo($input);}
            if(!$startstop){
                $start=strlen($a);
                $stop=strlen($b); 
            }else{
                $start=0;
                $stop=0;
            }
    //$begin=$a;$end=$b;
    $string=$input;
    $aa=strlen($a);
    $p=0;
    for($ii=0;$ii<$i;$ii++){$pp=strpos($string,$a)+1;$p=$p+$pp;$string=substr($string,$pp);}
    //$inner=$string;
    $a=strpos($string,$a);
    if($a!==false){if(rr())echo("<br/>".$a);
        $string=substr($string,$a+$aa);
        //echo(htmlspecialchars($string));
        $b=strpos($string,$b);
        if(rr())echo("/".$b);
        $string=substr($string,0,$b);
        if(rr())echo("<br/>".$change);
        if($change!=false){
            //$input=substr_replace($input,$change,,1);
            //echo("<br/>substr_replace($input,$change,$a+$aa+$p,$b);");
            if(rr())echo("<br/>input: ".$input);
            $inner=substr($input,$a+$aa+$p,$b);
            $input=substr_replace($input,$change,$a+$aa+$p-$start,$b+$stop+$start);//$b-$a-$aa
            if(rr())echo("<br/>return: ".$input);
        }//průser v akcentu
        //$input=substr($input,$a+$aa+$b);
        if(rr())echo("<br/>return($string)");
        
        $input=str_replace("[]",$inner,$input);
        
        if($change)return($input);
        return($string);
    }else{
        if($change)return($input);
        return(false);
    }
}
//$endshow="radb{x1}ewsdf{ff}erds{x2}ss";
//substr2($endshow,"{","}",2,"_");
//--------------------------------------------
function part3($input,$aa,$bb){
    if(strpos($input,$aa)){
        list($a,$input)=explode($aa,$input);
        list($b,$c)=explode($bb,$input);
        return(array($a,$b,$c));
    }else{
        return(array("",$input,""));
    }
}
//print_r(part3("-sfd6sf8d-","6","8"));
//--------------------------------------------
//define("vals_a",array("*",",",";",":","=","(","[","{","}","]",")","\"","\'","\\"," ",nln));
//define("vals_b",array("[star]","[comma]","[semicolon]","[colon]","[equate]","[aabracket]","[babracket]","[cabracket]","[babracket]","[bbbracket]","[cbbracket]","[doublequote]","[quote]","[slash]","[space]","[nln]"));
$GLOBALS['ss']["vals_a"]=array("*",",",";",":","=","(","[","{","}","]",")","\"","\'","\\"," ",nln);
$GLOBALS['ss']["vals_bb"]=array("[1]","[2]","[3]","[4]","[5]","[6]","[7]","[8]","[9]","[10]","[11]","[12]","[13]","[14]","[15]","[16]");
$GLOBALS['ss']["vals_b"]=array("[star]","[comma]","[semicolon]","[colon]","[equate]","[aabracket]","[babracket]","[cabracket]","[cbbracket]","[bbbracket]","[abbracket]","[doublequote]","[quote]","[slash]","[space]","[nln]");
//r($GLOBALS['ss']["vals_a"]);

//--------------------------------------------
 /*function smiles($text){
    //---------objects 
    $stream="";
    $text=str_replace("**","[star]",$text);
    $array=explode("*",$text);
    $i=-1;
    foreach($array as $part){$i++;
        if($i%2){
            //$stream=$stream.$part;
            list($img,$width)=explode("[star]",$part);
            $img=x2xx($img);
            if(!$width){$width=/*"100%"* /false;}
            //echo($img."<br>");
            if(substr($img,0,1)!='%'){
                $img=name2id($img);
                $stream=$stream.iprofiler($img);//mprofile($img).br.br;//imgr("id_".$img."_icon",$img,$width);
            }else{
                $img=substr($img,1);
                $img=name2id($img);
                $stream=$stream.iprofiler($img,45,2);
            }
        }else{
            $stream=$stream.$part;
        }
    } /** /
    $stream=str_replace("[star]","*",$stream);
    //---------dtree
    $stream=str_replace("%0%",imgr('dtree/x0001.png',lr('dtree'),50),$stream);
    $stream=str_replace("%1%",imgr('dtree/x0002.png',lr('dtree'),50),$stream);
    $stream=str_replace("%2%",imgr('dtree/x0003.png',lr('dtree'),50),$stream);
    $stream=str_replace("%3%",imgr('dtree/x0004.png',lr('dtree'),50),$stream);
    $stream=str_replace("%4%",imgr('dtree/x0005.png',lr('dtree'),50),$stream);
    $stream=str_replace("%5%",imgr('dtree/x0006.png',lr('dtree'),50),$stream);
    $stream=str_replace("%6%",imgr('dtree/x0007.png',lr('dtree'),50),$stream);
    $stream=str_replace("%7%",imgr('dtree/x0008.png',lr('dtree'),50),$stream);
    $stream=str_replace("%8%",imgr('dtree/x0009.png',lr('dtree'),50),$stream);
    $stream=str_replace("%9%",imgr('dtree/x0010.png',lr('dtree'),50),$stream);
    $stream=str_replace("%10%",imgr('dtree/x0011.png',lr('dtree'),50),$stream);
    $stream=str_replace("%11%",imgr('dtree/x0012.png',lr('dtree'),50),$stream);
    $stream=str_replace("%12%",imgr('dtree/x0013.png',lr('dtree'),50),$stream);
    $stream=str_replace("%13%",imgr('dtree/x0014.png',lr('dtree'),50),$stream);
    $stream=str_replace("%14%",imgr('dtree/x0015.png',lr('dtree'),50),$stream);
    $stream=str_replace("%15%",imgr('dtree/x0016.png',lr('dtree'),50),$stream);
    
    //---------smiles
    //zatím není
    //---------
    return($stream);
}
//r(xx2x("own2=2[comma]hybrid[comma]0.000[comma]0.000[comma][comma]qw[comma][comma]login[equate]1[semicolon]use[equate]1[semicolon]info[equate]1[semicolon]profile_edit[equate]1[semicolon]set_edit[equate]1[comma][comma][comma]realname[equate]Beze JmĂ©na[semicolon]gender[equate]m[semicolon]age[equate][semicolon]mail[equate]@[semicolon]showmail[equate][semicolon]web[equate]www.towns.cz[semicolon]description[equate]asdaszdas[semicolon]join[equate][comma][equate]0[comma]1[comma]0[comma]1314648565[comma]0[comma]0;in=0;in2=41[comma]message[comma]0.000[comma]0.000[comma][comma][comma][comma]login[equate]1[semicolon]use[equate]1[semicolon]info[equate]1[semicolon]profile_edit[equate]1[semicolon]set_edit[equate]1[comma][comma][comma]realname[equate][semicolon]gender[equate][semicolon]age[equate][semicolon]mail[equate]@[semicolon]showmail[equate][semicolon]web[equate][semicolon]description[equate][semicolon]join[equate][semicolon]text[equate][comma][equate]0[comma]0[comma]1[comma]1314613091[comma]0[comma]0[semicolon]40[comma]message[comma]0.000[comma]0.000[comma][comma]subject[comma][comma]login[equate]1[semicolon]use[equate]1[semicolon]info[equate]1[semicolon]profile_edit[equate]1[semicolon]set_edit[equate]1[comma][comma][comma]realname[equate][semicolon]gender[equate][semicolon]age[equate][semicolon]mail[equate]@[semicolon]showmail[equate][semicolon]web[equate][semicolon]description[equate][semicolon]join[equate][semicolon]text[equate]text[comma][equate]0[comma]0[comma]1[comma]1314352818[comma]0[comma]0;t=1313699204;x=15;y=0"),2);

*/

//--------------------------------------------
 function array2csv($array){
     $i=0;
     $array_new=array();
    foreach($array as $row){
        $array_new[$i]=array();
        $ii=0;
        foreach($row as $key=>$a){
            //if(is_int($key)){
            $array_new[$i][$ii]=x2xx($a);
            //r($array_new[$i][$ii]);
            //}
            //r($array2[$i][$ii]);
            $ii++;
        }//echo("<br>");
       $array_new[$i]=join(",",$array_new[$i]);
       $i++;
    }
    $array_new=join(";",$array_new);
    //r($array_new);
    return($array_new);
}
//--------------------------------------------
 function csv2array($string){
    $string=explode(";",$string);
    $i=0;
    foreach($string as $row){
        $string[$i]=explode(",",$string[$i]);
        $ii=0;
        foreach($string[$i] as $tmp){
             $string[$i][$ii]=xx2x($string[$i][$ii]);
             $ii++;
        }
        $i++;
    }
    return($string);
}
//die(xx2x("[babracket]"));
//die(xx2x(x2xx("{abc}")));
//print_r(csv2array(array2csv(array(array("{abc}")))));
//exit;
//==========================================================================================

if(!function_exists('centerurl')){
function centerurl($id,$x='x',$y=0,$ww=1,$noclose=false){//echo('bbb');
    if($x=='x'){//echo('aaaaa');echo($id);
        $destinationobject=new object($id);
        if(!$destinationobject->loaded)return('');
        $x=$destinationobject->x;
        $y=$destinationobject->y;
        $ww=$destinationobject->ww;
        unset($destinationobject);  
    }

if(!$GLOBALS['mobile']){
	$posuv=0;
}else{
	$posuv=400;//530;
}
    $tmp=3;
    $xc=(-(($y-1)/10)+(($x-1)/10));
    $yc=((($y-1)/10)+(($x-1)/10));
    $xx=(($xc-intval($xc))*-414)-($posuv);
    $yy=(($yc-intval($yc)+$tmp)*-211);
    $xx=round($xx/$GLOBALS['mapzoom']);
    $yy=round($yy/$GLOBALS['mapzoom']);
    $xc=intval($xc);
    $yc=intval($yc)-$tmp;
    $posuv=($posuv/$GLOBALS['mapzoom'])+(2.5*(424-(424/$GLOBALS['mapzoom'])));
    //js2("wm_close();"
    $url='e=map;xc='.$xc.';yc='.$yc.';xx='.$xx.';yy='.$yy.';ww='.$ww.';posuv='.round($posuv).';center='.$id.';noi=1;'.((mobile and !$noclose)?js2("wm_close();"):'');
    //echo($url);
    return($url);
}}
//==========================================================================================building

 function building($name){
    $q=sql_1data('SELECT count(1) FROM [mpx]objects WHERE own=\''.useid.'\' AND name=\''.$name.'\'')-1+1;
    return($q);
}
//==========================================================================================rand_color

function rand_color() {
    return str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}
//die(rand_color());

//==========================================================================================create_zip
function create_zip($files,$zipfile){
    //e($files); e($zipfile);

	if(is_string($files))$files=array($files);

	$zip = new ZipArchive();
	$zip->open($zipfile,ZIPARCHIVE::OVERWRITE);

	foreach($files as $file) {
		$zip->addFile($file,basename($file));
	}

	$zip->close();
	
	foreach($files as $file) {
		unlink($file);
	}


	chmod($zipfile,0777);
	return file_exists($zipfile);
}
//==========================================================================================create_zip
function extract_zip($zipfile,$to){

	$zip = new ZipArchive;
	$res = $zip->open($zipfile);
	if ($res === TRUE) {
	    //echo 'ok';
	    $zip->extractTo($to);
	    $zip->close();
	} else {
	    //echo 'failed, code:' . $res;
	}

}

//==========================================================================================post_request

function post_request($url,$data){
    //$url = 'http://server.com/path';
    //$data = array('key1' => 'value1', 'key2' => 'value2');
    
    // use key 'http' even if you send the request to https://...
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ),
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    
    //var_dump($result);
    return($result);

}

//==========================================================================================
require(root.core."/func_components.php");
require(root.core."/func_api.php");
//require(root.core."/func_map.php");
//r(astream($str));
?>
