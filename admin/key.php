<?php
/* Towns4Admin, www.towns.cz 
   © Pavel Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================

ob_end_flush();

require2("/text/func_core.php");
?>
<h3>Key</h3>
Vygenerování bonusových kódů.<br/>
<b>Upozornění: </b>Soubory budou uloženy do <?php echo(adminfile); ?>files/key/.<br/>

<form id="form1" name="form1" method="post" action="">
<table  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><strong>Zlato:</strong></td>
    <td><label>
      <input name="gold" type="text" id="gold" value="1001" />
    </label></td>
  </tr>
  <tr>
    <td><strong>Počet:</strong></td>
    <td><label>
      <input name="count" type="text" id="count" value="1" />
    </label></td>
  </tr>
  <tr>
    <td><strong>Typ:</strong></td>
    <td><label>
      <input name="type" type="text" id="count" value="1" /> (1=normal 2=list)
    </label></td>
  </tr>
    <tr>
    <td colspan="2"><label>
      <input type="submit" name="Submit" value="OK" />
    </label></td>
    </tr>
</table>
</form>
(Hodnota 1001 zlata je testovací.)

<?php
//error_reporting(E_ALL);
ini_set("max_execution_time","1000");
ini_set('memory_limit','5000M');
set_time_limit(60);

//---------------------

if($_POST['gold']){

	mkdir(adminfile.'files/key');
	chmod(adminfile.'files/key',0777);

$files=array();
$keys=array();

$gold=intval($_POST['gold']);
$count=intval($_POST['count']);





$fontfile='lib/font/Trebuchet MS.ttf';


//$size=1000;
//-------------------------A
if($_POST['type']==1){
	//$img=imagecreatetruecolor($size,$size);
	$img=imagecreatefrompng(adminfile.'files/A.png');
	$size=imagesx($img);
	$s1=$size*(17/500);
	$s2=$size*(30/500);
	$black=imagecolorallocate($img, 0, 0, 0);

	imagettftext($img, $s1 ,0, 0.7*$size, 0.72*$size, $black,$fontfile, $gold);


	$file=adminfile.'files/key/a'.$gold.'.png';
	$files[]=$file;

	imagepng($img,$file);
	chmod($file,0777);
	imagedestroy($img);
}


//-------------------------
br(2);
$i=0;
while($i<$count){$i++;

$key=rand(10000000,99999999);
//-------------------------B
if($_POST['type']==1){
	//$img=imagecreatetruecolor($size,$size);
	$img=imagecreatefrompng(adminfile.'files/B.png');
	$black=imagecolorallocate($img, 0, 0, 0);

	imagettftext($img, $s2 ,0, 0.595*$size, 0.435*$size, $black,$fontfile, $key);
	imagettftext($img, $s1 ,0, 0.2*$size, 0.45*$size, $black,$fontfile, $gold);

	$file=adminfile.'files/key/b'.$gold.'_'.$key.'.png';
	$files[]=$file;

	imagepng($img,$file);
	chmod($file,0777);
	imagedestroy($img);
}else{
	$keys[]=$key;
}
//-------------------------

sql_query("INSERT INTO `[mpx]key` (`key`, `reward`, `id`, `time_create`, `time_used`)
VALUES ('$key', 'gold=$gold;', '', '".time()."', '');");

}


//mail('ph@towns.cz','Towns key create','user: '.id2name(logid).nln.'townid: '.useid.nln.'gold: '.$_POST['gold'].nln.'count: '.$_POST['count'].nln.'type: '.$_POST['type']);
send_message(logid,$GLOBALS['inc']['write_id'],'Towns key create','user: '.id2name(logid).nln.'townid: '.useid.nln.'gold: '.$_POST['gold'].nln.'count: '.$_POST['count'].nln.'type: '.$_POST['type']);
		

if($_POST['type']==1){
	$file=adminfile.'files/key/'.$gold.'_'.time().'.zip';
	//print_r($files);
	create_zip($files,$file);
	echo('<br/><b>vygenerováno - <a href="'.$file.'">stáhnout</a></b>');

}else{

	textb($gold.nbsp.lr('res_gold2'));
	br();
	e(implode('<br/>',$keys));
}






}
?>
