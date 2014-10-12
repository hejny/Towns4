<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/func_main.php

   Základní php funkce a definice
*/
//==============================




define('gr',1.618033);
define('e',2.71828);

define("nln", "
");
define("br", "<br>");
define("hr", "<hr>");
define("nbsp", "&nbsp;");
define("nbsp2", "&nbsp;&nbsp;");
define("nbsp3", "&nbsp;&nbsp;&nbsp;");
define("nbspo", "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
function br($q=1){for($i=1;$i<=$q;$i++)echo(br);}
function br2($spacesize=4){br();imge('design/blank.png','',$spacesize,$spacesize);br();}
function hr($width=''){if(!$width){echo(hr);}else{echo('<hr width="'.$width.'">');}}
function tab($q=50){for($i=1; $i<=$q; $i++)echo(nbsp);}
function e($a){echo($a);}

//===============================================================================================================

function s($key,$value=""){
	if(is_array($key))$key=join('_',$key);
if($value){
	$GLOBALS['ss'][$key]=$value;
}else{
	return($GLOBALS['ss'][$key]);
}
}
//========================================
function ss($key,$value="",$deafult=""){
    if($value)s($key,$value);
    if(!s($key))s($key,$deafult);
    return(s($key));
}
//========================================
function backup(&$value,$deafult=""){
    $key=$deafult;
    if($value){
        s("backup_$key",$value);
        //r("save $key");
    }else{
        if(s("backup_$key")){
            $value=s("backup_$key");
            //r("load $key");
        }else{
            $value=$deafult;
            //r("deafult $key");
        }
    }
    return($key);
}

//======================================================================================
function rr(){return(false);}
//======================================..::::==============================================
function mod($a,$b){return(round((($a/$b)-intval($a/$b))*$b));}
function div($a,$b){return(intval($a/$b));}
function diff($a,$b){return(mod($a,$b));}
//--------------------------------------------
function divarray($q,$array){
    $array=array_reverse($array);
    foreach($array as &$div){
        $tmp=$div;
        $div=div($q,$tmp);
        $q=$q-($div*$tmp);
    }
    $array=array_reverse($array);
    return($array);
}
//print_r(array(1,2,7));echo("<br>");
//print_r(divarray(100,array(1,3,8)));
//--------------------------------------------
function multi5($a1,$b1,$c1,$a2,$c2){
    if($b1<=$a1){return($a2);}
    if($b1>=$c1){return($c2);}
    $p1=($b1-$a1)/($c1-$a1);
    $b2=(($c2-$a2)*$p1)+$a2;
    return($b2);
}
//--------------------------------------------
function time5($a1,$c1,$a2,$c2){
    return(multi5($a1,time(),$c1,$a2,$c2));
}
//-----------------------------------------
function fs2lvl($fs,$decimal=0){
    $decimal=pow(10,$decimal);
    //$lvl=ceil(sqrt($fs/10/*10*/)/*log($fs)*/*$decimal)/$decimal;
    
    /*if(!$GLOBALS['main_building_fs']){
        $GLOBALS['main_building_fs']=intval(sql_1data('SELECT fs FROM [mpx]objects WHERE id='.register_building));
    }*/
    /*$fs=$fs-$GLOBALS['main_building_fs']+gr;*/
    
    //$lvl=ceil(log($fs,2)*$decimal)/$decimal;
    //$lvl=ceil(($fs/$GLOBALS['main_building_fs'])*$decimal)/$decimal;
    $lvl=ceil(log(($fs/1000)+1,2)*10*$decimal)/$decimal;
    //$lvl=$fs;
 return($lvl);   
}
//-----------------------------------------
function nn($tmp){
  if($tmp){
        return($tmp); 
    }else{
        return(lr('null'));
    }
}
//===============================================================================================================
function rebase($url){
	$url=preg_replace('(\/[^\/]*\/\.\.\/)', '/', $url);
	$i=strpos($url,'/'.w.'/');$c=strlen(w);
	$url=substr($url,0,$i).substr($url,$i+$c+1);
	return($url);
}
//echo('ahoj/www/../debile/index.php');
//die(rebase('ahoj/www/../debile/index.php'));
//===============================================================================================================
function file_put_contents2($file,$contents){
    //echo($file)url;
    //if(file_exists($file)){file_put_contents($file,"");}
    $fh = fopen($file, 'w');
    fwrite($fh, $contents);
    fclose($fh);
    chmod($file,0777);
}
//--------------------------------------------
function mkdir2($dir){
    if(substr($dir,0,1)=='/')$dir=substr($dir,1);
    if(!file_exists($dir)){mkdir($dir);chmod($dir,0777);}
    //die($dir);
}
//--------------------------------------------
function mkdir3($dirs){$dirx='';foreach(explode('/',$dirs) as $dir){$dirx.='/'.$dir;mkdir2($dirx);}}
//--------------------------------------------
function unlink2($file){
    if(!unlink($file)){r($file." not deleted");}
}
//-------------------------------------------
/*function emptydir($dir) {
    if(file_exists($dir)){
        $handle=opendir($dir);
        while (($file = readdir($handle))!==false) {
            //echo "$file <br>";
            if(is_dir($file))emptydir($dir.'/'.$file); 
            @unlink($dir.'/'.$file);
        }
        closedir($handle);
    }
}*/
function emptydir($dir,$delete=false){
    if(substr($dir,-1,1)!='/')$dir=$dir.'/';
    if ($handle = opendir($dir)){
    $array = array();
    
    while (false !== ($file = readdir($handle))) {
    if ($file != "." && $file != "..") {
    
    if(is_dir($dir.$file)){
    if(!@rmdir($dir.$file)){ // Empty directory? Remove it
    emptydir($dir.$file.'/',true); // Not empty? Delete the files inside it
    }
    }else{
    @unlink($dir.$file);
    }
    }
    }
    closedir($handle);
    
    if($delete)@rmdir($dir);
    }
} 
//-------------------------------------------
//======================================================================================
//===============================================================================================================ZASTARALE
function astream($stream,$multi=true){//echo($stream);
    $array=array();
    $arraytmp=array();
    $br=array("\n","\r");
    $br2="
    ";
    $stream=" $stream ";
    //----
    while(strpos($stream,"/*")){
        $a=strpos($stream,"/*");
        $b=strpos(substr($stream,$a),"*/");
        $stream=substr_replace($stream,"",$a,$b+2);
    }
    //----
    while(strpos($stream,"//")){
        $a=strpos($stream,"//");
        $b=strpos(substr($stream,$a),nln);
        $stream=substr_replace($stream,"",$a,$b+1);
    }
    //----
    $stream=str_replace($br," ",$stream);
    $arraytmp=explode("; ",$stream);
    foreach($arraytmp as $tmp){
        list($a,$b)=explode("=",$tmp,2);
        //r($a);
        $a=trim($a);
        $b=trim($b);
	$b=str_replace('\\','',$b);
        if($a/* and substr($a,0,2)!="//"*/){
            if($multi)$a=str_replace(":","']['",$a);
            $a="\$array['$a']";
            $a=$a."=\$b;";
	    //e($a);
            eval($a);
            //r($b);
            /*$a=explode(":",$a);
            //$a=array_reverse($a);
            foreach($a as $key){r($key);
                $array[$a]=$b;
            }*/
        }
        //die();
    }
    return($array);
}
/*$str=("
a:b:c:d=ss;
c=qqq;
x:b=i;
x:m=1;
t=55;
");*/
//===============================================================================================================ZASTARALE
function estream($e){
    $stream="";
    foreach($e as $key => $value){
        //$stream=$stream."$key=\"$value\"\n";
        $stream=$stream."$key=$value;\n";
    }
    return($stream);
}
//===============================================================================================================

define('w',$GLOBALS['inc']['world']);


/*$file=(root."world/".w.".txt");
if(!file_exists($file)){
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>World Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>The requested world '<?php echo(w); ?>' was not found on this server.</p>
<hr>
<?php echo($_SERVER["SERVER_SIGNATURE"]); ?>
</body></html>

<?php
exit2();  
} */
 
//ZASTARALE  
/*$stream=file_get_contents($file);
$stream=astream($stream);
//$file=(root."world/".w.".ini");
//echo(file_get_contents($file));
//$stream = parse_ini_file($file,true);
//print_r($stream);
//die('konec');
foreach($stream as $key=>$value){
    if(!defined($key))define($key,$value);
    $GLOBALS['config'][$key]=$value;
}*/

if(!$GLOBALS['inc']['mysql_prefix'])$GLOBALS['inc']['mysql_prefix']='[world]_';


if(!defined('url'))define('url',$GLOBALS['inc']['url']);
if(!defined('cache'))define('cache',str_replace('[world]',w,$GLOBALS['inc']['cache']));
if(!defined('mysql_host'))define('mysql_host',$GLOBALS['inc']['mysql_host']);
if(!defined('mysql_user'))define('mysql_user',$GLOBALS['inc']['mysql_user']);
if(!defined('mysql_password'))define('mysql_password',$GLOBALS['inc']['mysql_password']);
if(!defined('mysql_db'))define('mysql_db',str_replace('[world]',w,$GLOBALS['inc']['mysql_db']));
if(!defined('mysql_prefix'))define('mysql_prefix',str_replace('[world]',w,$GLOBALS['inc']['mysql_prefix']));
if(!defined('lang'))define('lang',$GLOBALS['inc']['lang']);
if(!defined('debug') and $GLOBALS['inc']['debug'])define('debug',1);
if(!defined('fb_appid') and $GLOBALS['inc']['fb_appid'])define('fb_appid',$GLOBALS['inc']['fb_appid']);
if(!defined('fb_secret') and $GLOBALS['inc']['fb_secret'])define('fb_secret',$GLOBALS['inc']['fb_secret']);
if(!defined('analytics') and $GLOBALS['inc']['analytics'])define('analytics',$GLOBALS['inc']['analytics']);
if(!defined('fb_page') and $GLOBALS['inc']['fb_page'])define('fb_page',$GLOBALS['inc']['fb_page']);

if(!defined('debug'))define('debug',0);
if(!defined('debug'))define('edit',0);
//if(!defined('debug'))define('notmp',0);
if(!defined('notmpimg'))define('notmpimg',0);
if(!defined('timeplan'))define('timeplan',0);
//if(!defined('lem'))define('lem',0);


define("mpx",mysql_prefix);
/**/

if(!debug)error_reporting(0);


//die(root.cache);
mkdir3(root.cache);
//mkdir2(root.'userdata');
//mkdir2(root.'world');

//print_r($GLOBALS['config']);

//==========================================================================================mailx

function mailx($to,$subject,$body1,$body2='',$body3='',$body4=''){
    if($to and $to!='@'){
        $url=url.'/corex/?e=mailx&to='.urlencode($to).'&subject='.urlencode($subject).'&body1='.urlencode($body1).'&body2='.urlencode($body2).'&body3='.urlencode($body3).'&body4='.urlencode($body4);
        //e($url);
        get_headers($url);
    }

}
//===========================================================================================================================
/*         __   __       
 |\/| \ / /__` /  \ |    
 |  |  |  .__/ \__X |___*/
//===========================================================================================================================
//error_reporting(E_ALL ^ E_NOTICE);



foreach(array(1,2) as $tmp){
    
    //e('aaa');
    try {
        $error=false;
        $GLOBALS['pdo'] = new PDO('mysql:host='.mysql_host.';dbname='.mysql_db, mysql_user, mysql_password, array(PDO::ATTR_PERSISTENT => true));
        $GLOBALS['pdo']->exec("set names utf8");
    } catch (PDOException $e) {
        if(!defined('nodie')){
    	//require(root.core."/output.php");
    	$error=true;
    	
    	
    	//shell_exec('service mysql restart');
    	//sleep(2);
    	
    	//$error=('The server is currently unavailable. Please try again later.');
    	//die('<!--Could not connect: ' . $e->getMessage().'-->');
    
    
    }
    }

    if(!$error){break;}
}
if($error){
    //mailx($GLOBALS['inc']['log_mail'],'SERVER!','SERVER!');
    header('Refresh: 1');
    echo('Omlouv&aacute;me se, ale na serveru pr&aacute;v&#283; prob&#283;hla &uacute;dr&#382;ba.
Server bude fungovat do p&aacute;r vte&#345;in. Str&aacute;nka se automaticky obnov&iacute;...');
    file_get_contents('https://api.digitalocean.com/droplets/2655391/reboot?client_id=48e241a825673f8a3a765af6fbdb9a29&api_key=772bad5a245c2afecb679ace70e58bd6');
    
    die();
}
//die('ok');
//---------------------
function sql($text){return(/*mysqli_real_escape_string*/addslashes($text));}
function sql_mpx($text){return(str_replace('[mpx]',mpx,$text));}
//--------------------------------------------
function sql_query($q,$w=false){
    $q=sql_mpx($q);
    if($w==1){r($q);}
    if($w==2){echo($q);}
    if($w==3){echo($q);br();}
    $response=$GLOBALS['pdo']->exec($q);
    //$err=($response->errorInfo());if($err=$err[2] and debug)e($err);
    //$error=mysql_error();
    /*if($error and debug){
        echo($q."<br>".$error."<br>");
    }*/
    
    return($response);
}
//--------------------------------------------
function sql_1data($q,$w=false){
    $q=sql_mpx($q);
	if(!strpos($q,'LIMIT') and !strpos($q,'COUNT') and !strpos($q,'MAX') and !strpos($q,'MIN')){$q.=' LIMIT 1';}
    if($w==1){r($q);}
    if($w==2){echo($q);}
    if($w==3){echo($q);br();}
    //$result=sql_query($q);
    $response= $GLOBALS['pdo']->prepare($q);
    $response->execute();
    $err=($response->errorInfo());if($err=$err[2] and debug)e($err);
    $response = $response->fetchAll();
    //print_r($response);
    while(is_array($response))$response=$response[0];
    //echo($response);
    return($response);
}
//--------------------------------------------
function sql_array($q,$w=false){
    $q=sql_mpx($q);
    if($w==1){r($q);}
    if($w==2){echo($q);}
    if($w==3){echo($q);br();}
    $array= $GLOBALS['pdo']->prepare($q);
    $array->execute();
    $err=($array->errorInfo());if($err=$err[2] and debug)e($err);
    $array = $array->fetchAll();
    return($array);
}
//--------------------------------------------
function sql_csv($q,$w=false){
    $q=sql_mpx($q);
    if($w==1){r($q);}
    if($w==2){echo($q);}
    if($w==3){echo($q);br();}
    $array= $GLOBALS['pdo']->prepare($q);
    $array->execute();
    $err=($array->errorInfo());if($err=$err[2] and debug)e($err);
    $array = $array->fetchAll();
    //r($array);
    $array=array2csv($array);
    //r($array);
    return($array);
}
//---------------------NOVe KONFIGURACE
$array=sql_array('SELECT `key`,`value` FROM [mpx]config');

if(!$array){

/*$tmp=$_SERVER["REQUEST_URI"];
$tmp=str_replace(w,$GLOBALS['inc']['world'],$tmp);
die($tmp);
header('Location: '.$tmp);
exit;*/
	if(!$GLOBALS['admin']){die('!config');}
	//if(!$GLOBALS['admin']){header('Location: '.$GLOBALS['inc']['urld'].$ref);exit;}
/*
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>World Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>The requested world '<?php echo(w); ?>' was not found on this server.</p>
<hr>
<?php echo($_SERVER["SERVER_SIGNATURE"]); ?>
</body></html>

<?php
exit2();
*/
} 


$GLOBALS['config']=array();
foreach($array as $row){
    list($key,$value)=$row;
    if(!strpos($key,':')){
    	if(!defined($key))define($key,$value);
	$GLOBALS['config'][$key]=$value;
	//e($key."='$value'");br();
    }else{
	$key=str_replace(':',"']['",$key);
	$key="['config']['$key']";
	eval('$GLOBALS'.$key."='$value';");
	//e('$GLOBALS'.$key."='$value';");br();
	//$GLOBALS['config'][$key]=$value;
    }

}

//echo('cc');exit;
//--------------------------------------------
//r(sql_csv("SELECT * from objects"));
//===============================================================================================================
//--------------------------------------------XXX
function str_replace2($from,$to,$text){
    $x="nwijofnurelnr";
    $a="a".$x;
    $b="b".$x;
    $between=$from;
    $i=0;while($between[$i]){
        $between[$i]=$a.$i.$b;
    $i++;}
    $text=str_replace($from,$between,$text);
    $text=str_replace($between,$to,$text);
    return($text);
}
//--------------------------------------------
/**
 * @param $text
 * @return mixed
 */
function x2xx($text){//,$vals_a=vals_a,$vals_b=vals_b
    //$ptext=$text;
    //$text=str_replace("*","xxxstarxxx",$text);
    $from=$GLOBALS['ss']["vals_a"];
    $to=$GLOBALS['ss']["vals_bb"];
    $text=str_replace2($from,$to,$text);
    //$text=str_replace("xxxstarxxx","[star]",$text);
    //r($ptext." >> ".$text);
    return($text);
}
//--------------------------------------------
 function xx2x($text){
    //$ptext=$text;
    //$text=str_replace("[star]","xxx*xxx",$text);
    $from=$GLOBALS['ss']["vals_b"];
    $to=$GLOBALS['ss']["vals_a"];
    $text=str_replace2($from,$to,$text);
    $from=$GLOBALS['ss']["vals_bb"];
    $text=str_replace2($from,$to,$text);
    //$text=str_replace("xxx*xxx","*",$text);
    //r($ptext." >> ".$text);
    return($text);
}
//--------------------------------------------
define('dnln',debug?nln:'');
?>
