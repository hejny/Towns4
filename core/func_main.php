<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2015
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
function brr($q=1){$stream='';for($i=1;$i<=$q;$i++)$stream.=(br);return($stream);}
function br2($spacesize=4){br();imge('design/blank.png','',$spacesize,$spacesize);br();}
function hr($width=''){if(!$width){echo(hr);}else{echo('<hr width="'.$width.'">');}}
function hrr($width=''){if(!$width){return(hr);}else{return('<hr width="'.$width.'">');}}
function tab($q=50){for($i=1; $i<=$q; $i++)echo(nbsp);}
function e($a){echo($a);}
function ebr($a){echo($a);br();}






//======================================================================================================================

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
//@deprecated PH level objektu se nově počítá podle množství modulů
function fs2lvl($fs,$decimal=0){
    $decimal=pow(10,$decimal);
    //$lvl=ceil(sqrt($fs/10/*10*/)/*log($fs)*/*$decimal)/$decimal;
    
    /*if(!$GLOBALS['main_building_fs']){
        $GLOBALS['main_building_fs']=intval(sql_1data('SELECT fs FROM `[mpx]pos_obj` WHERE id='.register_building));
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
}//-----------------------------------------
function implodex($array,$delimiter=',',$enclosure='`'){
  $str=implode($enclosure.$delimiter.$enclosure,$array);
  $str=$enclosure.$str.$enclosure;
  return($str);
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
//===============================================================================================================fpc,fgc(staré file_put_contents2)
function fpc($file,$contents){
    //echo($file)url;
    //if(file_exists($file)){file_put_contents($file,"");}
    $fh = fopen($file, 'w');
    fwrite($fh, $contents);
    fclose($fh);
    chmod($file,0777);
}
//--------------------------------------------fgc
function fgc($file,$contents){
    return(file_get_contents($file));
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
//===============================================================================================================

define('w',$GLOBALS['inc']['world']);



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

if(!defined('noinc'))
if(!debug)error_reporting(0);

//======================================================================================================================timeplan

define("timestart", time()+microtime());
$GLOBALS['timeplan2sql']=array();

if(timeplan or is_array($GLOBALS['inc']['timeplan'])?$GLOBALS['inc']['timeplan']!=array():$GLOBALS['inc']['timeplan']=='*'){
    function t($key="",$text=""){
        $time=time()+microtime()-timestart;
        $plus=1000*round($time-$GLOBALS['lasttime'],6);
        //$plus='0.00'.substr($plus+'',2);
        $texte=round($time,3)."<b>(+".$plus.")</b> - ".htmlspecialchars($key).($text?' - '.$text:'');

        if(is_array($GLOBALS['inc']['timeplan'])?in_array($key,$GLOBALS['inc']['timeplan']):$GLOBALS['inc']['timeplan']=='*'){
            $GLOBALS['timeplan2sql'][]=array($key,$text,$plus);
        }


        $GLOBALS['lasttime']=$time;

        if(timeplan){
            echo("$texte<br/>");
        }
    }
}else{
    function t($key="",$text=""){}
}
t('start');
//sleep(1);
//t('start');


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
    header('Refresh: 5');
    echo('Omlouv&aacute;me se, ale na serveru pr&aacute;v&#283; prob&#283;hla &uacute;dr&#382;ba.
Server bude fungovat do p&aacute;r minut. Str&aacute;nka se automaticky obnov&iacute;... ');


	$restarturl=$GLOBALS['inc']['server_restart_url'];

	$file=root.cache.'/reboot.txt';
	//echo($file);
	$time=file_get_contents($file)-0;

	if($time+180<time()){
		echo('0');
    	file_get_contents($restarturl);
		file_put_contents($file,time());
		chmod($file,0777);
	}else{
		echo(time()-$time);
	}
    
    die();
}
//die('ok');
//---------------------sql, sqlx
function sql($text){return(/*mysqli_real_escape_string*/addslashes($text));}
function sqlx($text){return("'".sql($text)."'");}
function sql_mpx($text){
    //-------------------------------------------Prefix [mpx]
    $array=explode('[mpx]',$text);
    $i=1;
    while($array[$i]){
        
        $prefix=mpx;
        foreach($GLOBALS['inc']['mysql_global'] as $gtable){
            if(in_array(substr($array[$i],0,strlen($gtable)+1),array($gtable.' ',$gtable.'`',$gtable.'.'))){
                $prefix=$GLOBALS['inc']['mysql_global_prefix'];
                break;
            }
            
        }
        $array[$i]=$prefix.$array[$i];
        
        $i++;
    }
    $text=implode('',$array);
    //$text=str_replace('[mpx]',mpx,$text);
    //-------------------------------------------ROUND
    while(strpos($text,' ROUND(')){
        
        list($text1,$text)=explode(' ROUND(',$text,2);
        list($round,$text2)=explode(' ',$text,2);
        list($key,$value)=explode(')=',$round,2);
        $min=$value-0.5;
        $max=$value-(-0.5);
        $round=' '.$key.'<='.($max).' AND '.$key.'>'.($min).' ';
        $text=$text1.$round.$text2;
    }

    //-------------------------------------------
    return($text);
    
}
//--------------------------------------------sql_remove_mpx
function sql_remove_mpx($text){
    $text=str_replace(array($GLOBALS['inc']['mysql_global_prefix'],mpx,'[mpx]'),'',$text);
    return($text);
}
//--------------------------------------------sql_query
function sql_query($q,$w=false){
    $q=sql_mpx($q);
    if($w==1){r($q);}
    if($w==2){echo($q);}
    if($w==3){echo($q.';');br();}
    t();
    $response=$GLOBALS['pdo']->exec($q);
    t('sql_query',$q);
    //$err=($response->errorInfo());if($err=$err[2] and debug)e($err);
    //$error=mysql_error();
    /*if($error and debug){
        echo($q."<br>".$error."<br>");
    }*/
    
    return($response);
}
//--------------------------------------------sql_insert
/**
 * Provedení SQL insert dotazu pomocí asociovaného pole
 * abc' => sqlx(abc)
 *
 * @param (string) Tabulka bez [mpx]
 * @param (array) Asociované pole
 * @param (integer) Zobrazit?
 */
function sql_insert($table,$array,$w=false){

    if(substr($table,0,8)!='townsapp'){
        $table='[mpx]'.$table;
    }

    $keys=array_keys($array);
    $vals=array_values($array);
    $keys=implodex($keys,',','`');
    $i=0;
    while(isset($vals[$i])){$vals[$i]=sql($vals[$i]);$i++;}
    $vals=implodex($vals,',',"'");
    $sql="INSERT INTO `$table` ($keys) VALUES ($vals)";
    return(sql_query($sql,$w));
}
//--------------------------------------------sql_reinsert
/**
 * Provedení SQL insert select dotazu pomocí asociovaného pole
 * true' => zkopírování
 * array[subtable,subkey]' =>  subselect (SELECT ... FROM ... WHERE ...)
 * abc' => abc
 * `abc`' => `abc`
 * 'abc' => `abc'
 *
 * @param (string) Tabulka bez [mpx]
 * @param (string) Podmínka
 * @param (array) Asociované pole
 * @param (integer) Zobrazit?
 */
function sql_reinsert($table,$table_where,$array,$w=false){
    //@todo reinserty nefungují pro townsapp

    $keys=array_keys($array);
    $vals=array_values($array);
    $i=0;
    while(isset($vals[$i])){
        if($vals[$i]===true){
            $vals[$i]='`'.$keys[$i].'`';
        }elseif(is_array($vals[$i])){
            list($sub_table,$sub_key)=$vals[$i];
            $vals[$i]="(SELECT `[mpx]$sub_table`.`$sub_key` FROM `[mpx]$sub_table` WHERE $table_where)";
        }
        $i++;
    }
    $keys=implodex($keys,',','`');
    $vals=implode($vals,',');
    $vals=str_replace(',,',",'',",$vals);
    $sql="INSERT INTO `[mpx]$table` ($keys) SELECT $vals FROM `[mpx]$table` WHERE $table_where LIMIT 1";
    return(sql_query($sql,$w));
}
//--------------------------------------------sql_update
/**
 * Provedení SQL update dotazu pomocí asociovaného pole
 * abc' => sqlx(abc)
 *
 * @param (string) Tabulka bez [mpx]
 * @param (string) Podmínka
 * @param (array) Asociované pole
 * @param (integer) Zobrazit?
 */
function sql_update($table,$table_where,$array,$w=false){

    if(substr($table,0,8)!='townsapp'){
        $table='[mpx]'.$table;
    }

    $set=array();
    foreach($array as $key=>$row){
        $set[]="`$key`=".sqlx($row);

    }
    $set=implode(',',$set);

    $sql="UPDATE `$table` SET $set WHERE $table_where";
    return(sql_query($sql,$w));
}
//--------------------------------------------sql_1data
function sql_1data($q,$w=false){
    $q=sql_mpx($q);
	if(!strpos($q,'LIMIT') and !strpos($q,'COUNT') and !strpos($q,'MAX') and !strpos($q,'MIN')){$q.=' LIMIT 1';}
    if($w==1){r($q);}
    if($w==2){echo($q);}
    if($w==3){echo($q.';');br();}
    //$result=sql_query($q);
    $response= $GLOBALS['pdo']->prepare($q);
    t();
    $response->execute();
    t('sql_1data',$q);
    $err=($response->errorInfo());if($err=$err[2] and debug)e($err);
    $response = $response->fetchAll();
    //print_r($response);
    while(is_array($response))$response=$response[0];
    //echo($response);
    return($response);
}
//--------------------------------------------sql_1number
function sql_1number($q,$w=false){
    return(sql_1data($q,$w)-1+1);
}
//--------------------------------------------sql_array
function sql_array($q,$w=false){
    $q=sql_mpx($q);
    if($w==1){r($q);}
    if($w==2){echo($q);}
    if($w==3){echo($q.';');br();}
    $array= $GLOBALS['pdo']->prepare($q);
    t();
    $array->execute();
    t('sql_array',$q);
    $err=($array->errorInfo());if($err=$err[2] and debug)e($err);
    $array = $array->fetchAll(/*PDO::FETCH_NUM*/);
    return($array);
}
//--------------------------------------------sql_array
function sql_assoc($q,$w=false){
    $q=sql_mpx($q);
    if($w==1){r($q);}
    if($w==2){echo($q);}
    if($w==3){echo($q.';');br();}
    $array= $GLOBALS['pdo']->prepare($q);
    t();
    $array->execute();
    t('sql_assoc',$q);
    $err=($array->errorInfo());if($err=$err[2] and debug)e($err);
    $array = $array->fetchAll(PDO::FETCH_ASSOC);
    return($array);
}
//--------------------------------------------sql_row
function sql_row($q,$w=false){
    if(!strpos($q,'LIMIT') and !strpos($q,'COUNT') and !strpos($q,'MAX') and !strpos($q,'MIN')){$q.=' LIMIT 1';}
    $row=sql_array($q,$w);
    $row=$row[0];
    return($row);

}
//--------------------------------------------sql_csv
function sql_csv($q,$w=false){
    $q=sql_mpx($q);
    if($w==1){r($q);}
    if($w==2){echo($q);}
    if($w==3){echo($q.';');br();}
    $array= $GLOBALS['pdo']->prepare($q);
    t();
    $array->execute();
    t('sql_csv',$q);
    $err=($array->errorInfo());if($err=$err[2] and debug)e($err);
    $array = $array->fetchAll();
    //r($array);
    $array=array2csv($array);
    //r($array);
    return($array);
}
//--------------------------------------------objt
function objt($alt=false,$showtime=false){
	if($alt){$alt="`$alt`.";}else{$alt='';}
	if(!$showtime){
		return($alt.'`stoptime`=0');

	}else{

		return($showtime.'>='.$alt.'`starttime` AND ('.$showtime.'<'.$alt.'`stoptime` OR '.$alt.'`stoptime`=0)');
	}
}
//======================================================================================================================LANG 2011

//----------------------------------------------------------------langload

if($GLOBALS['ss']["lang"]){
    $lang=$GLOBALS['ss']["lang"];
}else{
    $lang=lang;
}
if($GLOBALS['get']["lang"]){$lang=$GLOBALS['get']["lang"];}
if($_GET['locale']){$lang=$_GET['locale'];}//@todo Nahradit v celé aplikaci zastaralý název "lang"
if($_GET['rvscgo']==1){$lang='rv';}
if($lang=='cz'){$lang='cs_CZ';}
$GLOBALS['ss']["lang"]=$lang;

//----------------------------------------------------------------langinit gettext

setlocale(LC_ALL, $lang.'.UTF-8');
setlocale(LC_NUMERIC, 'en_US.UTF-8');

bindtextdomain('towns', $GLOBALS['inc']['base'].'ui/locale');
//bind_textdomain_codeset("towns", 'UTF-8');
textdomain("towns");

//----------------------------------------------------------------lr



function lr($key,$params=''){
    //----------------
    if($GLOBALS['inc']['lang_record']){


        $file=$GLOBALS['inc']['base'].'ui/locale/towns.pot';
        if(!file_exists($file)){
            $contents=<<<EOF
msgid ""
msgstr ""
"Project-Id-Version: Towns\\n"
"POT-Creation-Date: 2015-02-24 08:33+0100\\n"
"PO-Revision-Date: 2015-02-26 00:39+0100\\n"
"MIME-Version: 1.0\\n"
"Content-Type: text/plain; charset=UTF-8\\n"
"Content-Transfer-Encoding: 8bit\\n"
"Language: cs\\n"
"X-Generator: Towns lr()\\n"
EOF;


        }else{
            $contents=file_get_contents($file);
        }

        $msgidx='msgid "'.addslashes($key).'"';

        if(!strpos($contents,$msgidx)) {

            $plus = <<<EOF
\n
$msgidx
msgstr ""
EOF;

            $contents .= $plus;

            file_put_contents($file, $contents);
            chmod($file,0777);
        }

    }
    //----------------

    $text=gettext($key);


    if($text==$key){
        $params=str2list($params);
        //print_r($params);
        if($params['alt']){

            $altplus=$params['alt'];
            $alt=str_replace('+','',$altplus);

            $plus=substr($altplus,strlen($alt));

            $text=lr($alt).$plus;
        }
	}


    $text=valsintext($text,$params);
    return($text);
    //return(nl2br(htmlspecialchars($text)));
}


//----------------------------------------------------------------le

function le($key){
    echo(lr($key));
}

//----------------------------------------------------------------contentlang

function contentlang($buffer){//if(rr())r();

    $buffer=str_replace(array("{0}","{}"),"",$buffer);
    $buffer=str_replace("x{","languageprotectiona",$buffer);
    $buffer=str_replace("}x","languageprotectionb",$buffer);
    //-------------
    for($i=0;($tmp=substr2($buffer,"{","}",$i) and $i<200);$i++){

        list($key,$params)=explode(";",$tmp,2);


        $text=lr($key,$params);

        $buffer=substr2($buffer,"{","}",$i,$text);

    }
    $buffer=str_replace(array("{",";}","}"),"",$buffer);
    $buffer=str_replace("languageprotectiona","{",$buffer);
    $buffer=str_replace("languageprotectionb","}",$buffer);


    return($buffer);
}



//======================================================================================================================-NOVe KONFIGURACE
if(!defined('noinc')){
$array=sql_array('SELECT `key`,`value` FROM [mpx]config');

if(!$array){

    if(!$GLOBALS['admin']){

        $url='../'.$GLOBALS['inc']['default_world'];
        ?>
        <!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
        <html><head>
        <title>World Not Found</title>
        <meta http-equiv="refresh"
   content="4; url=<?php e($url); ?>">
        </head><body>
        <h1>World Not Found</h1>
        <p>The requested world '<?php echo(w); ?>' was not found on this server.</p>
        <p>Redirecting to default world <a href="<?php e($url); ?>"><?php e($url); ?></a> in 4 seconds.</p>
        <hr>
        <?php echo($_SERVER["SERVER_SIGNATURE"]); ?>
        </body></html>
        <?php
        die();  

    }
}else{

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
}
}
//===============================================================================================================Vytváření struktury SQL
function create_sql($table=false){
    
    $sql=fgc(core.'/create.sql');
    if($table){
        //@todo Fungování i pro pohledy
        $a=strpos($sql,'CREATE TABLE `[mpx]'.$table.'`');
        $sql=substr($sql,$a);
        $b=strpos($sql,';');
        $sql=substr($sql,0,$b);
    }
    $sql=str_replace('CREATE TABLE','CREATE TABLE IF NOT EXISTS',$sql);
    $sql=str_replace('CREATE VIEW','CREATE OR REPLACE VIEW',$sql);
    //PH - SQL NEVALIDNI $sql=str_replace('CREATE VIEW','CREATE VIEW IF NOT EXISTS',$sql);
    
    return($sql);
    
}


//die(create_sql('objects'));

//======================================================================================================================Strings
//--------------------------------------------str_remove
/* Odstranění podřetězce
 *
 * @param string haystack
 * @param string needle
 * @return string
 * */
function str_remove($haystack,$needle){
    $haystack=str_replace($needle,'',$haystack);
    return($haystack);
}


//--------------------------------------------str_replace2
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
//--------------------------------------------x2xx
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
//--------------------------------------------xx2x
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
//======================================================================================================================check_email

function check_email($email) {
    $atom = '[-a-z0-9!#$%&\'*+/=?^_`{|}~]'; // znaky tvořící uživatelské jméno
    $domain = '[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])'; // jedna komponenta domény
    return eregi("^$atom+(\\.$atom+)*@($domain?\\.)+$domain\$", $email);
}
//======================================================================================================================Načtení knihoven

function script_($script){
   e('<script type="text/javascript">');
   readfile($script);
   e('</script>');
}
function css_($css){
   e('<style>');
   readfile($css);
   e('</style>');
}

function htmllibs(){
	script_('lib/jquery/jquery-1.6.2.min.js');//Načtení jQuery
	script_('lib/jquery/jquery-ui.min.js');

	script_('lib/jquery/plugins/fullscreen-min.js');//Funkce plné obrazovky, která neblbne
	script_('lib/jquery/plugins/mousewheel.js');//Potřeba pro mousewheel
	script_('lib/jquery/plugins/scrollbar.js');//Ona s vlastním scrollbarem
	script_('lib/jquery/plugins/touch-punch.min.js');//Pro fungování tahání v mobilech a tabletech
	script_('lib/jquery/plugins/colorpicker/colorpicker.js');
	css_('lib/jquery/plugins/colorpicker/colorpicker.css');

    e('<script src="../lib/tinymce/tinymce.min.js"></script>');

}
//======================================================================================================================

define('dnln',debug?nln:'');
?>
