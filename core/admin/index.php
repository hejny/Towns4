<?php/* Towns4Admin, www.towns.cz    © Pavel Hejný | 2011-2014   _____________________________   admin/...   Towns4Admin - Nástroje pro správu Towns*///==============================//error_reporting(E_ALL);error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_WARNING );define("timestart", time()+microtime());define("root", "");define("core", "core");define("adminroot", $GLOBALS['inc']['core'].'/admin/'/*"app/admin/"*/);define("adminfile", "app/admin/");//define("root", "../../");//define("core",core);//require_once(root.'inc.php');$dir=root.'world';if(!file_exists($dir)){mkdir($dir);chmod($dir,0777);}//$worldfile=root.'world/'.$GLOBALS['inc']['world'].'.txt';//if(file_exists($worldfile)){require_once(root.core."/func_vals.php");require_once(root.core."/func_object.php");require_once(root.core."/func_main.php");require_once(root.core."/memory.php");require_once(root.core."/func.php");require_once(root.core."/func_components.php");$GLOBALS['ss']["lang"]='cz';$GLOBALS['protected']=array();/*if($GLOBALS['ss']["page"]!='config' and $GLOBALS['ss']["page"]!='unique' and $GLOBALS['ss']["page"]!'lang'){	require_once(root.core."/output.php");}*///}ini_set("max_execution_time","1000");ini_set('memory_limit','128M');//-----------------------------------//if(is_numeric($_GET['changeworld']))e($_GET['changeworld']);if(is_numeric($_GET['changeworld']) and $GLOBALS['ss']["logged_new"] and $GLOBALS['ss']["logged_new"]!='public'){   $GLOBALS['ss']["ww"]=intval($_GET['changeworld']);}if(!$GLOBALS['ss']["ww"])$GLOBALS['ss']["ww"]=1;//-----------------------------------?><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head><meta http-equiv="content-type" content="text/html; charset=UTF-8" /><title>Towns4Admin</title><style type="text/css">body,td,th {color: #111111;font-size: 16px;font-family: "trebuchet ms";}body {	background-color: #FFFFFF;	margin-left: 0px;	margin-top: 0px;	margin-right: 0px;	margin-bottom: 0px;}a:link {	color: #000000;	text-decoration:none;}a:visited {	color: #000000;	text-decoration:none;}a:hover {	color: #000000;	text-decoration:none;}a:active {	color: #000000;	text-decoration:none;}/*Basic reset*/* {margin: 0; padding: 0;}#accordian {	background: #004050;	width: 250px;	margin: 0px auto 0 auto;	color: white;	/*Some cool shadow and glow effect*/	box-shadow: 		0 5px 15px 1px rgba(0, 0, 0, 0.6), 		0 0 200px 1px rgba(255, 255, 255, 0.5);}/*heading styles*/#accordian h3 {	font-size: 12px;	line-height: 34px;	padding: 0 10px;	cursor: pointer;	/*fallback for browsers not supporting gradients*/	background: #003040; 	background: linear-gradient(#003040, #002535);}/*heading hover effect*/#accordian h3:hover {	text-shadow: 0 0 1px rgba(255, 255, 255, 0.7);}/*iconfont styles*/#accordian h3 span {	font-size: 16px;	margin-right: 10px;}/*list items*/#accordian li {	list-style-type: none;}/*links*/#accordian ul ul li a {	color: white;	text-decoration: none;	font-size: 11px;	line-height: 27px;	display: block;	padding: 0 15px;	/*transition for smooth hover animation*/	transition: all 0.15s;}/*hover effect on links*/#accordian ul ul li a:hover {	background: #003545;	border-left: 5px solid lightgreen;}/*Lets hide the non active LIs by default*/#accordian ul ul {	display: none;}.normal  {	background: linear-gradient(90deg,#111122,#002222 )!important;}.normal2  {	background: linear-gradient(#000000, #001525)!important;}.normal3  {	background: linear-gradient(#000000, #001525)!important;}</style><?php htmljscss(1); ?></head><body><div id="fb-root"></div><?php//=============================================================================//echo('('.trim(file_get_contents(adminroot.'password')).')');//if(file_exists(adminroot.'password')){$alert='';if($_POST["password_new"]){	if($GLOBALS['inc']['admin'][$_POST["username_new"]]['password']==$_POST["password_new"]){		$GLOBALS['ss']["logged_new"]=$_POST["username_new"];	}else{		$alert=("Nesprávné heslo!<br/>");		//echo($_POST["password_new"].'-'.file_get_contents(adminroot.'password'));	}}if($_GET["password"]){	if($GLOBALS['inc']['admin'][$_GET["username"]]['password']==$_GET["password"]){		$GLOBALS['ss']["logged_new"]=$_GET["username"];	}else{		$alert=("Nesprávné heslo!<br/>");		//echo($_POST["password_new"].'-'.file_get_contents(adminroot.'password'));	}}if($GLOBALS['inc']['admin']['public'] and $_GET['public']){	$GLOBALS['ss']["logged_new"]='public';}if($_GET["logout"]){$GLOBALS['ss']["logged_new"]=false;}if($GLOBALS['ss']["logged_new"]!=true){/*<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>Towns4Admin - přihlášení</title></head><body>*/?><table border="0" cellspacing="0" cellpadding="0" width="100%" height="100%"><tr><td align="center" valign="middle"><form name="login" method="POST" action="?page=none"><table border="0" cellspacing="4" cellpadding="2" width="10" height="10" bgcolor="#eeeeee"><tr><td align="center" valign="middle" colspan="2" bgcolor="#dddddd"><b>Towns4Admin</b> - přihlášení</td></tr><?php if($alert){ ?><tr><td align="center" valign="middle" colspan="2" bgcolor="#dd9966"><?php e($alert); ?></td></tr><?php } ?><tr><td><b>Jméno:&nbsp;&nbsp;</b></td><td><input type="text" name="username_new" value="" /></td></tr><tr><td><b>Heslo:&nbsp;&nbsp;</b></td><td><input type="password" name="password_new" value="" /></td></tr><tr><td align="right" valign="middle" colspan="2"><input type="submit" value="OK" /></td></tr><?phpif($GLOBALS['inc']['admin']['public']){	e('<tr><td align="center" valign="middle" colspan="2"height="50" >');	e('<a href="?public=1&amp;page=none"><u><b>vstup bez přihlášení</b></u></a>');	e('</td></tr>');}?><!--<tr><td align="left" valign="middle" colspan="2">Pokud sem chcete dostat plný přístup, zkontaktujte mě na <a href="mailto:ph@towns.cz">ph@towns.cz</a></td></tr>--></table></form></td></tr></table></body></html><?phpexit2();}//}$permissions=$GLOBALS['inc']['admin'][$GLOBALS['ss']["logged_new"]]['permissions'];$GLOBALS['tlang']=$GLOBALS['inc']['admin'][$GLOBALS['ss']["logged_new"]]['tlang'];/*if($GLOBALS['ss']["page"]!='export'){require_once(root.core."/output.php");}*///=============================================================================define("nodie",1);require_once(root.core."/func.php");require_once(root.core."/func_components.php");$GLOBALS['ss']["url"]=url;//----------------if($_GET["page"])$GLOBALS['ss']["page"]=$_GET["page"];if($_POST["page"])$GLOBALS['ss']["page"]=$_POST["page"];//--------------------------------------------?><div style="width:100%;height:100%;overflow:hidden;"><table width="100%" height="100%" border="0" cellpadding="3" cellspacing="0" bgcolor="#CCCCCC">  <tr>    <td width="150" height="100%" align="left" valign="top" bgcolor="060606"><div style="width:150;height:100vh;overflow:hidden;"><div style="width:170;height:100vh;overflow:auto;color:#bbbbbb;"><div id="accordian"><div style="text-align:center;width:60%;background: #cccccc;"><a href="?page=none"><?php imge('logo/128xA.png'); ?></a><hr></div><h3 class="normal2"><? e(nbsp3.nbsp3); ?>Towns4Admin</h3><h3 class="normal"><?php e('<i>'.$GLOBALS['ss']["logged_new"].'@'.w.'</i>'); ?> </h3><h3 class="normal"><a href="?" style="color:#bbbbbb;">Obnovit</a></h3><h3 class="normal"><a href="?logout=1" style="color:#bbbbbb;">Odhlásit se</a></h3><?php$tmp=$_SERVER["REQUEST_URI"];if(strpos($tmp,'?'))$tmp=substr($tmp,0,strpos($tmp,'?'));$tmp=str_replace('admin','',$tmp);?><h3 class="normal"><a href="<?php echo($tmp); ?>" target="_blank" style="color:#bbbbbb;">Hrát</a></h3><?php/*foreach(glob(root.'world/*txt') as $world){	$world=basename($world,".txt");	if($world==w){		echo('<b>'.$world.'</b>');	}else{		echo('<a href="'.str_replace(w,$world,$_SERVER["REQUEST_URI"]).'">'.$world.'</a>');	}	echo('<br>');}<hr/>*/$worlds=array();foreach(sql_array('SHOW TABLES') as $table){	$table=$table[0];	list($world,$tmp)=explode('_',$table,2);	if(strpos($tmp,'config')!==false){			$worlds[$world]=true;	}}foreach($worlds as $world=>$tmp){	$url=$GLOBALS['inc']['url'];	$url=str_replace(array('[world]',w),$world,$url);	$url.='/admin';	if(w==$world){			e('<h3 class="normal3"><b><u>'.$world.'</u></b>');		//------------------------Podsvět		if($GLOBALS['ss']["logged_new"]!='public'){ ?>			<a href="?page=<?=$_GET['page'] ?>&amp;changeworld=<?php e($GLOBALS['ss']["ww"]-1); ?>">--</a>			<?php e($GLOBALS['ss']["ww"]); ?>			<a href="?page=<?=$_GET['page'] ?>&amp;changeworld=<?php e($GLOBALS['ss']["ww"]+1); ?>">++</a>		<?php }		//------------------------		e('</h3>');	}else{		e('<h3 class="normal3"><a href="'.$url.'" style="color:#bbbbbb;">'.$world.'</a></h3>');	}}/*if($GLOBALS['config']!=array()){	//@todo PH Vytvořit skupiny nástrojů a názvy přesunout do towns_lang. Musí se udělat až poté, co bude existovat nástroj na synchronizaci lang menzi localhostem a towns.cz$links=array(	'none'=>'Úvod',	'info'=>'Info',	'adminer'=>'Adminer',	'cronbot'=>'CronBot',	'createnews'=>'Novinky',	'mail'=>'E-mail',	'config'=>'Config',	'object'=>'Object',	'addsurkey'=>'Suroviny+',	'key'=>'Key',	'multibuild'=>'Multibuild',	'html'=>'Html',	'users'=>'Users',	'botx'=>'BotX',	'mainres'=>'MainRES',	'register'=>'Spawn',	'registermap'=>'SpawnMap',	'service'=>'Chaos',	'update'=>'Update FP/FS',	'terrainx'=>'TerrainX',	'unique'=>'Unique',	'unique_text'=>'UniqueText',	'createunique'=>'CreateUnique',	'acceptunique'=>'AcceptUnique',	'showunique'=>'ShowUnique',	'createimg'=>'CreateImg',	'createmap'=>'CreateMap',	'createtmp'=>'CreateTmp',	'createglob'=>'CreateGlob',	'createview'=>'CreateView',	'deletetmp'=>'DeleteTmp',	'git_pull'=>'GIT pull',	'sql_structure'=>'SQL struktura',	'locale'=>'Locale .po 2 .mo',	'backup'=>'Pull DB',	'backup_test'=>'Test Backup',	'import'=>'Import',	'export'=>'Export',	'phpgen_sql'=>'Generate PHP - SQL');}else{$links=array('none'=>'Úvod','sqlx'=>'Import');}//print_r($permissions);foreach($permissions as $permission){foreach($links as $key=>$value){		if($key==$permission or $permission=='*'){	if($key==$GLOBALS['ss']["page"] or ($key=='none' and ''==$GLOBALS['ss']["page"])){		echo('<b><u>'.$value.'</u></b>');	}else{		echo('<a href="?page='.$key.'" style="color:#bbbbbb;">'.$value.'</a>');	}	if($key=='adminer')echo('<a href="../'.$GLOBALS['inc']['app'].'/adminer" target="_blank" style="color:#bbbbbb;">(W)</a>');	echo('<br>');	}}}*/if($permissions=='*'){$permissions=array('*');}else{$permissions=array_merge(array('none'),$permissions);}$pages=glob(adminroot.'*/*.php');sort($pages);$aacpage='';e('<ul>');$pgroup='';foreach($pages as $page){	if($permissions==array('*') or in_array($page,$permissions)){		$page=str_replace(array(adminroot,'.php'),'',$page);		$page=trim($page,'/');		list($group,$page)=explode('/',$page);		//e("$group,$page");		if($group!=$pgroup){			if($pgroup){				e('</ul></li>');			}			$pgroup=$group;			e('<li>');						$groupx=explode('_',$group,2);			if($groupx[1]){				$groupx=$groupx[1];			}else{				$groupx=$groupx[0];			}			e('<h3>'.lr('admin_group_'.$groupx).'</h3>');			e('<ul id="menu_'.$group.'">');		}		$pagekey='admin_page_'.$groupx.'-'.$page;		$pagename=lr($pagekey);		if($pagename!=$pagekey or 1){						if($page==$GLOBALS['ss']["page"]){				$aacgroup=$group;				$aacpage=$group.'/'.$page;				e('<li><a href="#"><b><u>'.$pagename.'</u></b></a></li>');			}else{				e('<li><a href="?page='.$page.'">'.$pagename.'</a></li>');			}		}	}}e('</ul>');?><script type="text/javascript">$(document).ready(function(){	$('#menu_<?=$aacgroup ?>').show();	//$('#menu_building').slideDown();	$("#accordian h3").click(function(){		//alert(123);		//slide up all the link lists		$("#accordian ul ul").slideUp();		//slide down the link list below the h3 clicked - only if its closed		if(!$(this).next().is(":visible"))		{			$(this).next().slideDown();		}	})})</script></div></div></div></td><!--x<td align="left" valign="top" bgcolor="#C0C6D9" width="2"><div style="width:2px;height:100%; background-color: #222222;overflow:hidden;"></div></td>--><td align="left" valign="top" bgcolor="#111111" width="2"><div style="width:2px;height:100%; background-color: #C0C6D9;overflow:hidden;"></div></td>    <td align="left" valign="top" bgcolor="#FFFFFF">	<div style="width:100%;height:100vh;overflow:scroll;margin:5px;">        <?php br();foreach($permissions as $permission){if($GLOBALS['ss']["page"]==$permission or $permission=='*'){		if($GLOBALS['ss']["page"] and $GLOBALS['ss']["page"]!='none' and (defined('w') or $GLOBALS['ss']["page"]=='createworld' or $GLOBALS['ss']["page"]=='res')){$wwhere="ww='".$GLOBALS['ss']["ww"]."'";ob_end_flush();//echo(adminroot.$GLOBALS['ss']["page"].".php");//-----------------------------Spouštění úloh jedna po druhéif($_GET['cron']){	$cron=unserialize($_GET['cron']);	$separator='';	foreach(array_merge($GLOBALS['ss']['cronuz'],$cron) as $tmp){			$tmp.='&';			$tmp=substr2($tmp,'page=','&');			//e('('.$tmp.','.$aacpage.')');			if(trim($tmp)==$aacpage){$a='<b><u>';$b='</u></b>';}else{$a='';$b='';}			if($tmp=='cron')$tmp='finish';			e($separator.$a.$tmp.$b);			$separator=nbsp.'-&gt;'.nbsp;	}	//hr();	//-----------------	$next=$cron[0].'&cron=';	$adtocronuz=array_shift($cron);	//$GLOBALS['ss']['cron']=$cron;	$next=str_replace('&amp;','&',$next);	$next=str_replace(array("\r","\n"),'',$next);	$next.=addslashes(urlencode(serialize($cron)));	$croncron=('<script language="javascript">	setTimeout(function(){window.location.replace(\''.$next.'\');},100);	</script>');	//$croncron=$next;}//-----------------------------require_once(root.core."/model/func_map.php");    include(adminroot.$aacpage.".php");if($_GET['cron'] and $croncron){	$GLOBALS['ss']['cronuz'][]=$adtocronuz;	e($croncron);}else{}}else{	?>	<h3>Towns4Admin</h3>	Administrační prostředí hry Towns4<br/>	<?php if($GLOBALS['ss']["logged_new"]=='public'){ ?>	<br/>	Pokud sem chcete dostat plný přístup, zkontaktujte mě na <a href="mailto:ph@towns.cz">ph@towns.cz</a>	<?php } ?><?php }}} ?>	</div></td>  </tr></table></div></body></html><?php//-------------------------------------------------Vlastní statistikaqlog('admin');//-------------------------------exit2();?>