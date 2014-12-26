<?php
/* Towns4Admin, www.towns.cz 
   © Pavel Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================
?>
<h3>CronBot </h3>
<?php
//require2("/login/func_core.php");
//require2("/create/func_core.php");
//require2("/attack/func_core.php");

$speed=$_GET['speed'];
if(!$speed)$speed=1;
$ul=$_GET['ul'];
if(!isset($ul))$ul=1;

$uc=$_GET['uc'];
if(!isset($uc))$uc=0;

$refresh=$_GET['refresh'];
if(!isset($refresh))$refresh=60;

/*$url=url.'admin?page=cronbot&ul='.$ul.'&uc='.$uc.'&refresh='.$refresh;
<h2>Obnovení za <?php timejs(time()+10,$url); ?></h2>
<a href="<?php e($url); ?>">Obnovit</a><br/>*/

$botfile=url.'?e=login-bot&ul='.$ul.'&uc='.$uc.'&refresh='.$refresh;//core.'/login/
?>

<h2><a href="<?php e($botfile); ?>" target="_blank">Spustit</a></h2>

<?php


$refreshs=array(10,60,300,0);
$separator='';
foreach($refreshs as $tmp){
	e($separator);
	if($refresh==$tmp){
		e('<b><u>'.$tmp.'</u></b>');
	}else{
		e('<a href="?page=cronbot&amp;ul='.$ul.'&amp;uc='.$uc.'&amp;refresh='.$tmp.'">'.$tmp.'</a>');
	}
	$separator=nbsp.'-'.nbsp;
}
br();

/*$speeds=array(10000,100,5,1,0.1);
$separator='';
foreach($speeds as $tmp){
	e($separator);
	if($speed==$tmp){
		e('<b><u>Speed '.$tmp.'x</u></b>');
	}else{
		e('<a href="?page=cronbot&amp;speed='.$tmp.'&amp;ul='.$ul.'&amp;uc='.$uc.'&amp;refresh='.$refresh.'">Speed '.$tmp.'x</a>');
	}
	$separator=nbsp.'-'.nbsp;
}
br();*/


if($ul){
		e('<a href="?page=cronbot&amp;ul=0&amp;uc='.$uc.'&amp;refresh='.$refresh.'">Normal</a> - ');
		e('<b><u>Unlimited Resource</u></b>');
	}else{
		e('<b><u>Normal</u></b> - ');
		e('<a href="?page=cronbot&amp;ul=1&amp;uc='.$uc.'&amp;refresh='.$refresh.'">Unlimited Resource</a>');
	}
br();
if($uc){
		e('<a href="?page=cronbot&amp;ul='.$ul.'&amp;uc=0&amp;refresh='.$refresh.'">Normal</a> - ');
		e('<b><u>Unlimited Cooldown</u></b>');
	}else{
		e('<b><u>Normal</u></b> - ');
		e('<a href="?page=cronbot&amp;ul='.$ul.'&amp;uc=1&amp;refresh='.$refresh.'">Unlimited Cooldown</a>');
	}



//$reporting=true;
//$botfile=url.'?e=login-bot&ul='.$ul.'&uc='.$uc.'&resource='.$resource;//core.'/login/bot.php';

//e('URL: '.$botfile);br();
//e('<iframe frameborer="2" src="'.$botfile.'" width="90%" height="400"></iframe>')
//include($botfile);

?>

