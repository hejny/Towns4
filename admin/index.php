<?php
/* Towns4Admin, www.towns.cz 
   © Pavel Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================


//session_cache_expire(9999);
//session_start();
if(!function_exists('t')){function t($tmp){}}
if(!function_exists('require2')){function require2($url){require(root.core.'/'.$url);}}
if(!function_exists('require2_once')){function require2_once($url){require_once(root.core.'/'.$url);}}

//error_reporting(E_ALL);
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_WARNING );
define("timestart", time()+microtime());
define("root", "");
define("adminroot", $GLOBALS['inc']['core'].'/admin/'/*"app/admin/"*/);
define("adminfile", "app/admin/");
//define("root", "../../");
//define("core",core);
//require(root.'inc.php');
$dir=root.'world';
if(!file_exists($dir)){mkdir($dir);chmod($dir,0777);}

//$worldfile=root.'world/'.$GLOBALS['inc']['world'].'.txt';
//if(file_exists($worldfile)){
require2("func_vals.php");
require2("func_object.php");
require2("func_main.php");
require2("memory.php");
$GLOBALS['ss']["lang"]='cz';


/*if($GLOBALS['ss']["page"]!='config' and $GLOBALS['ss']["page"]!='unique' and $GLOBALS['ss']["page"]!'lang'){
	require2("output.php");
}*/

//}

ini_set("max_execution_time","1000");
ini_set('memory_limit','128M');
//-----------------------------------
if($_POST['changeworld'] and $GLOBALS['ss']["logged_new"] and $GLOBALS['ss']["logged_new"]!='public'){
   $GLOBALS['ss']["ww"]=intval($_POST['changeworld']);
}
if(!$GLOBALS['ss']["ww"])$GLOBALS['ss']["ww"]=1;
//-----------------------------------
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>Towns4Admin</title>
<style type="text/css">
<!--
body,td,th {
color: #111111;
font-size: 16px;
font-family: "trebuchet ms";
}
body {
	background-color: #FFFFFF;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
a:link {
	color: #000000;
	text-decoration:none;
}
a:visited {
	color: #000000;
	text-decoration:none;
}
a:hover {
	color: #000000;
	text-decoration:none;
}
a:active {
	color: #000000;
	text-decoration:none;
}
-->
</style>
<?php

function script_($script){
    e('<script type="text/javascript" src="'.rebase(url.base.'/'.$script).'"></script>');
}
function css_($css){
    e('<link rel="stylesheet" href="'.rebase(url.base.'/'.$css).'" type="text/css" />');
}
script_('lib/jquery/js/jquery-1.6.2.min.js');
script_('lib/jquery/js/jquery-ui-1.8.16.custom.min.js');
//script_('lib/jquery/kemayo-maphilight-4cdc2e2/jquery.maphilight.min.js');
//script_('lib/jquery/jquery.tinyscrollbar.min.js');
script_('lib/jquery/jquery.fullscreen-min.js');
script_('lib/jquery/jquery.mousewheel.js');
script_('lib/jquery/jquery.scrollbar.js');
script_('lib/jquery/colorpicker/js/colorpicker.js');
//script_('lib/jquery/colorpicker/js/eye.js');
//script_('lib/jquery/colorpicker/js/utils.js');
//script_('lib/jquery/colorpicker/js/layout.js');
css_('lib/jquery/colorpicker/css/colorpicker.css');
script_('lib/jquery/jquery.ui.touch-punch.min.js');/**/
?>
</head>
<body>

<?php

//=============================================================================

//echo('('.trim(file_get_contents(adminroot.'password')).')');
//if(file_exists(adminroot.'password')){

$alert='';

if($_POST["password_new"]){

	if($GLOBALS['inc']['admin'][$_POST["username_new"]]['password']==$_POST["password_new"]){
		$GLOBALS['ss']["logged_new"]=$_POST["username_new"];
	}else{
		$alert=("Nesprávné heslo!<br/>");
		//echo($_POST["password_new"].'-'.file_get_contents(adminroot.'password'));
	}
}

if($_GET["password"]){
	if($GLOBALS['inc']['admin'][$_GET["username"]]['password']==$_GET["password"]){
		$GLOBALS['ss']["logged_new"]=$_GET["username"];
	}else{
		$alert=("Nesprávné heslo!<br/>");
		//echo($_POST["password_new"].'-'.file_get_contents(adminroot.'password'));
	}
}



if($GLOBALS['inc']['admin']['public'] and $_GET['public']){
	$GLOBALS['ss']["logged_new"]='public';
}

if($_GET["logout"]){$GLOBALS['ss']["logged_new"]=false;}
if($GLOBALS['ss']["logged_new"]!=true){


/*<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Towns4Admin - přihlášení</title>
</head>

<body>*/
?>
<table border="0" cellspacing="0" cellpadding="0" width="100%" height="100%">
<tr>
<td align="center" valign="middle">

<form name="login" method="POST" action="?page=none">


<table border="0" cellspacing="4" cellpadding="2" width="10" height="10" bgcolor="#eeeeee">
<tr><td align="center" valign="middle" colspan="2" bgcolor="#dddddd">

<b>Towns4Admin</b> - přihlášení

</td></tr>
<?php if($alert){ ?>
<tr><td align="center" valign="middle" colspan="2" bgcolor="#dd9966">
<?php e($alert); ?>
</td></tr>
<?php } ?>
<tr><td><b>Jméno:&nbsp;&nbsp;</b></td><td><input type="text" name="username_new" value="" /></td></tr>
<tr><td><b>Heslo:&nbsp;&nbsp;</b></td><td><input type="password" name="password_new" value="" /></td></tr>
<tr><td align="right" valign="middle" colspan="2"><input type="submit" value="OK" /></td></tr>
<?php
if($GLOBALS['inc']['admin']['public']){
	e('<tr><td align="center" valign="middle" colspan="2"height="50" >');
	e('<a href="?public=1&amp;page=none"><u><b>vstup bez přihlášení</b></u></a>');
	e('</td></tr>');
}

?>
<!--<tr><td align="left" valign="middle" colspan="2">
Pokud sem chcete dostat plný přístup, zkontaktujte mě na <a href="mailto:ph@towns.cz">ph@towns.cz</a>
</td></tr>-->
</table>



</form>
</td></tr>
</table>


</body>
</html>
<?php
exit2();
}
//}


$permissions=$GLOBALS['inc']['admin'][$GLOBALS['ss']["logged_new"]]['permissions'];
$GLOBALS['tlang']=$GLOBALS['inc']['admin'][$GLOBALS['ss']["logged_new"]]['tlang'];

/*if($GLOBALS['ss']["page"]!='export'){
require2("output.php");
}*/
//=============================================================================
//if($_GET["world"])$GLOBALS['ss']["world"]=$_GET["world"];
//if($GLOBALS['ss']["world"]){
	define("nodie",1);	
	//define("w", $GLOBALS['ss']["world"]);
	/*if(file_exists($worldfile)){*/require2("func.php");require2_once("func_components.php");/*}*/
	$GLOBALS['ss']["url"]=url;
//}

//----------------
if($_GET["page"])$GLOBALS['ss']["page"]=$_GET["page"];
if($_POST["page"])$GLOBALS['ss']["page"]=$_POST["page"];
//--------------------------------------------

?>
<div style="width:100%;height:100%;overflow:hidden;">
<table width="100%" height="100%" border="0" cellpadding="3" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td width="150" height="100%" align="left" valign="top" bgcolor="060606">
<div style="width:150;height:100vh;overflow:hidden;">
<div style="width:170;height:100vh;overflow:auto;color:#bbbbbb;">
<div style="width:100%;text-align:left;color:#eeeeee;"><h3><?php /*imge('logo/50.png','',30,30);*/ ?>Towns4Admin</h3></div><hr>

<?php /*
#C0C6D9

Přihlášen <?php e($GLOBALS['ss']["logged_new"]); ?>
<br>
<?php echo(short(ssid,8)*/e('<i>'.$GLOBALS['ss']["logged_new"].'@'.w.'</i>'); ?> <br>
<a href="?" style="color:#bbbbbb;">Obnovit</a><br> 
<a href="?logout=1" style="color:#bbbbbb;">Odhlásit se</a><br>
<?php
$tmp=$_SERVER["REQUEST_URI"];
if(strpos($tmp,'?'))$tmp=substr($tmp,0,strpos($tmp,'?'));
$tmp=str_replace('admin','',$tmp);
?>
<a href="<?php echo($tmp); ?>" target="_blank" style="color:#bbbbbb;">Hrát</a>
<hr/>
<?php
/*foreach(glob(root.'world/*txt') as $world){
	$world=basename($world,".txt");
	if($world==w){
		echo('<b>'.$world.'</b>');
	}else{
		echo('<a href="'.str_replace(w,$world,$_SERVER["REQUEST_URI"]).'">'.$world.'</a>');
	}
	echo('<br>');
}<hr/>*/

$worlds=array();
foreach(sql_array('SHOW TABLES') as $table){
	$table=$table[0];
	list($world,$tmp)=explode('_',$table,2);
	if(strpos($tmp,'config')!==false){	
		$worlds[$world]=true;
	}
}

foreach($worlds as $world=>$tmp){
	$url=$GLOBALS['inc']['url'];
	$url=str_replace(array('[world]',w),$world,$url);
	$url.='/admin';
	if(w==$world){	
		e('<b><u>'.$world.'</u></b>');br();
	}else{
		e('<a href="'.$url.'" style="color:#bbbbbb;">'.$world.'</a>');br();
	}
}


hr();

?>
<?php if(function_exists('input_text') and $GLOBALS['ss']["logged_new"]!='public'){ ?>
<form id="changeworld" name="login" method="POST" action="?page=<?php echo($GLOBALS['ss']["page"]); ?>">
Podsvět:<br>
<?php input_text("changeworld",$GLOBALS['ss']["ww"],NULL,4); ?>
<input type="submit" value="OK" />
</form>
<hr/>
<?php } ?>
<?php

if($GLOBALS['config']!=array()){
$links=array(
'none'=>'Úvod',
'info'=>'Info',
'adminer'=>'Adminer',
'cron'=>'Cron',
'cronbot'=>'CronBot',
'mail'=>'E-mail',
'config'=>'Config',
'lang'=>'Lang',
'translate'=>'Translate',
'object'=>'Object',
'key'=>'Key',
//'refill'=>'Refill T/R',
'multibuild'=>'Multibuild',
'html'=>'Html',
'users'=>'Users',
'botx'=>'BotX',
'mainres'=>'MainRES',
'register'=>'Spawn',
'registermap'=>'SpawnMap',
'service'=>'Chaos',
'update'=>'Update FP/FS',
//'createuser'=>'CreateUser',
'terrainx'=>'TerrainX',
//'patchmap'=>'PatchMap',
'unique'=>'Unique',
'unique_text'=>'UniqueText',
'createunique'=>'CreateUnique',
'acceptunique'=>'AcceptUnique',
'showunique'=>'ShowUnique',
'createimg'=>'CreateImg',
'createmap'=>'CreateMap',
'createtmp'=>'CreateTmp',
'createglob'=>'CreateGlob',
'createworld'=>'CreateWorld',
//'deleteworld'=>'DeleteWorld',
'deletetmp'=>'DeleteTmp',
/*'setdefault'=>'SetDefault',*/
'corex'=>'Push CORE',
'backup'=>'Pull DB',
//'export'=>'Export(old)',
//'import'=>'Import(old)',
'sqlx'=>'Import',
'dump'=>'Export'
//'sync'=>'Sync'
//'push'=>'Push'
);
}else{
$links=array('none'=>'Úvod','sqlx'=>'Import');
}

if($permissions=='*'){$permissions=array('*');}else{
$permissions=array_merge(array('none'),$permissions);}
//print_r($permissions);

foreach($permissions as $permission){

foreach($links as $key=>$value){
	
	if($key==$permission or $permission=='*'){

	if($key==$GLOBALS['ss']["page"] or ($key=='none' and ''==$GLOBALS['ss']["page"])){
		echo('<b><u>'.$value.'</u></b>');
	}else{
		echo('<a href="?page='.$key.'" style="color:#bbbbbb;">'.$value.'</a>');
	}
	if($key=='adminer')echo('<a href="../'.$GLOBALS['inc']['app'].'/adminer" target="_blank" style="color:#bbbbbb;">(W)</a>');
	echo('<br>');

	
}}}

br(2);

?>	
</div></div>
</td>

<!--x
<td align="left" valign="top" bgcolor="#C0C6D9" width="2"><div style="width:2px;height:100%; background-color: #222222;overflow:hidden;"></div></td>-->
<td align="left" valign="top" bgcolor="#111111" width="2"><div style="width:2px;height:100%; background-color: #C0C6D9;overflow:hidden;"></div></td>

    <td align="left" valign="top" bgcolor="#FFFFFF">
	
<div style="width:100%;height:100vh;overflow:scroll;">
        <?php 


foreach($permissions as $permission){
//echo($GLOBALS['ss']["page"].','.$permission.'<br/>');
if($GLOBALS['ss']["page"]==$permission or $permission=='*'){

	
	
if($GLOBALS['ss']["page"] and $GLOBALS['ss']["page"]!='none' and (defined('w') or $GLOBALS['ss']["page"]=='createworld' or $GLOBALS['ss']["page"]=='res')){

$wwhere="ww='".$GLOBALS['ss']["ww"]."'";

ob_end_flush();
//echo(adminroot.$GLOBALS['ss']["page"].".php");

if($_GET['cron']){
//textb('<u>'.$_GET['page'].'</u>');
$cron=/*$GLOBALS['ss']['cron'];*/unserialize($_GET['cron']);
//e($_GET['cron']);
//r($cron);
//print_r($GLOBALS['ss']['cronuz']);
//die();
$separator='';
foreach(array_merge($GLOBALS['ss']['cronuz'],$cron) as $tmp){
	$tmp.='&';
	$tmp=substr2($tmp,'page=','&');
	//e('('.$tmp.','.$_GET['page'].')');
	if(trim($tmp)==$_GET['page']){$a='<b><u>';$b='</u></b>';}else{$a='';$b='';}
	if($tmp=='cron')$tmp='finish';
	e($separator.$a.$tmp.$b);
	$separator=nbsp.'-&gt;'.nbsp;
}
//hr();
//-----------------
$next=$cron[0].'&cron=';
$adtocronuz=array_shift($cron);
//$GLOBALS['ss']['cron']=$cron;
$next=str_replace('&amp;','&',$next);
$next=str_replace(array("\r","\n"),'',$next);
$next.=addslashes(urlencode(serialize($cron)));

$croncron=('<script language="javascript">
setTimeout(function(){window.location.replace(\''.$next.'\');},100);
</script>');
//$croncron=$next;

}

include(adminroot.$GLOBALS['ss']["page"].".php");

if($_GET['cron'] and $croncron){
$GLOBALS['ss']['cronuz'][]=$adtocronuz;
e($croncron);
}else{
}


}else{
?>
<h3>Towns4Admin</h3>
Administrační prostředí hry Towns4<br/>

<?php if($GLOBALS['ss']["logged_new"]=='public'){ ?>
<br/>
Pokud sem chcete dostat plný přístup, zkontaktujte mě na <a href="mailto:ph@towns.cz">ph@towns.cz</a>
<?php } ?>

  <?php }}} ?>	
</div></td>
  </tr>
</table>
</div>
</body>
</html>
<?php
exit2();
?>
