<?php
/* Towns4Admin, www.towns.cz 
   © Pavel Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================


if($_GET['finish']){
	br();
	e('<b>hotovo!</b>');
}

?>
<h3>Cron </h3>
<?php
if($_POST['contents']){
	echo('<b>změněno</b><br/>');
	$contents=file_put_contents2(adminfile.'objects/cron.txt',$_POST['contents']);
}
$contents=file_get_contents(adminfile.'objects/cron.txt');
$contents=htmlspecialchars($contents);
$contentsx=$contents;
?>




<?php
//if($_GET['start']){?page=cron&start=1

$contents=str_replace(nln,' ',$contents);
$contents=str_replace('  ',' ',$contents);
$contents=trim($contents);
$contents=explode(' ',$contents);
$contents[]='?page=cron&finish=1';

$first=$contents[0].'&cron=';
$GLOBALS['ss']['cronuz']=array(array_shift($contents));
//$GLOBALS['ss']['cron']=$contents;
$first.=urlencode(serialize($contents));
$first=str_replace('&','&amp;',$first);
$first=str_replace(array("\r","\n"),'',$first);
$first=addslashes($first);
?>



<h2>Spuštění za <?php timejs(time()+20,str_replace('&amp;','&',url.'admin/'.$first)); ?></h2>
<a href="<?php e($first); ?>">Spustit ručně</a>

<br/>
<br/>
<br/>

<?php
$croncron='';
?>

<form id="form1" name="form1" method="post" action="">
<textarea name="contents" style="width: 90%;height: 40%;"><?php echo($contentsx); ?></textarea><br/>
<input type="submit" name="Submit" value="Změnit" />
</form>

