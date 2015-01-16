<?php
//error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_WARNING );
//error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_WARNING );

if($_POST['action'])$action=$_POST['action'];
if($_POST['id'])$id=$_POST['id'];
if($_POST['res'])$res=$_POST['res'];
if($_POST['pass'])$pass=$_POST['pass'];
if($_POST['lang'])$lang=$_POST['lang'];
if($_GET['action'])$action=$_GET['action'];
if($_GET['id'])$id=$_GET['id'];
if($_GET['res'])$res=$_GET['res'];
if($_GET['pass'])$pass=$_GET['pass'];
if($_GET['lang'])$lang=$_GET['lang'];
//---------------------------------------
if($action=='lang'){
//--------------------------------
	//$lang=$_GET["lang"];
	//if(!$lang){$lang="cz";}
	if($_POST['lang_prefix'])$lang_prefix=$_POST['lang_prefix'];
	if($_GET['lang_prefix'])$lang_prefix=$_GET['lang_prefix'];
	/*$file=("data/lang/".$lang.".txt");
	$lang=file_get_contents($file);
	$lang=astream($lang);*/
		$langx=array();
		foreach(sql_array('SELECT `key`,`value` FROM [mpx]lang WHERE lang=\''.$lang.'\'') as $row){
		    list($key,$value)=$row;
		    $langx[$key]=$value;
		}
		$lang=$langx;
	//----------------
	$lang2=array();
	foreach($lang as $key=>$value){//list($key,$value)=explode('=',$tmp);
			if(strpos($key,$lang_prefix)===0){
				$key=str_replace($lang_prefix,'',$key);
				$lang2[]=$key.'='.$value;
			}	
	}
	$lang=implode('&',$lang2);
	$lang=str_replace('& ','&',$lang);
	//----------------
	header( 'Content-Type: text/html; charset=UTF-8' );
	echo($lang);
//--------------------------------
}elseif($action=='load'){
	$p_pass=sql_1data('SELECT `key` FROM `[mpx]login` WHERE `id`='.$id);
	if($pass==$p_pass){
		$res=sql_1data('SELECT `res` FROM `[mpx]objects` WHERE `id`='.$id);
		echo('success=1&res='.$res);
	}else{
		echo('error=1');
	}
//--------------------------------
}elseif($action=='save'){
	$p_pass=sql_1data('SELECT `key` FROM `[mpx]login` WHERE `id`='.$id);
	if($pass==$p_pass){
		sql_query('UPDATE `[mpx]objects` SET `res`=\''.$res.'\', `t`=\''.time().'\'  WHERE `id`='.$id);
		$xy=sql_array('SELECT `x`,`y` FROM `[mpx]objects` WHERE `id`='.$id);
		changemap($xy[0][0],$xy[0][1],true);
		echo('success=1');
	}else{
		echo('error=1');
	}
}
?>
