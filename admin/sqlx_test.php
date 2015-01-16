<?php
/* Towns4Admin, www.towns.cz 
   © Pavel Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================


error_reporting(E_ALL ^ E_NOTICE);
ini_set("max_execution_time","1000");
ini_set('memory_limit','500M');
//session_start();
 ?>
<h3>Test Záloh</h3>
Tato funkce provede test MySQL Dump...<br/>
<hr/>
<?php

if($_GET['filename']){
	$tmp=adminfile.'files/backup/'.$_GET['filename'];
	if(file_exists($tmp)){

		if(substr($tmp,-4)=='.zip'){
			
			$tmpsql=substr($tmp,0,strlen($tmp)-3).'sql';

			if(!file_exists($tmpsql)){
				extract_zip($tmp,adminfile.'files/backup/');
			}
			
			$tmp=$tmpsql;
			//e($tmp);
			if(file_exists($tmp)){
				chmod($tmp,0777);
				$_SESSION['filename']=$tmp;
			}else{
				echo('Chyba .zip souboru!');
			}
		}else{
			$_SESSION['filename']=$tmp;
		}
	}else{
		$_SESSION['filename']=false;
		echo('Soubor neexistuje!');
	}
}


if(!$_SESSION['filename']){
?>

<form id="form1" name="form1" method="get" action="?page=sqlx">
<table  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><strong>Soubor:</strong></td>
    <td><label>
      <?php e(adminfile.'files/backup/'); ?><input name="filename" type="text" id="filename" value="<?php echo($_GET['filename']?$_GET['filename']:w); ?>" />
    </label></td>
  </tr>
    <tr>
    <td colspan="2"><label>
      <input type="submit" name="Submit" value="OK" />
    </label></td>
    </tr>
</table>
</form>


<?php

}else{


$file1=$_SESSION['filename'];//'world1.sql';

$h=fopen($file1, 'r');

echo('<b>'.$_SESSION['filename'].'</b><br/>');

$tables=array();
$sql='';
$ii=1;
while(!feof($h)){$ii++;
	$line=fgets($h);
	//if(strpos($line,w)!==false){
	//if(strpos($line,'INSERT INTO world1_memory')===false and strpos($line,'INSERT INTO world1_log ')===false){//why not??
		if(strpos($line,';')){
			$sql.=$line;
			//-------------------
				if(strpos('INSERT INTO',$sql)!==0){
					$table=substr2($sql,'INSERT INTO ',' VALUES');
					if(!$tables[$table])$tables[$table]=0;
					$tables[$table]++;
				}
			//-------------------
			$sql='';
			//fputs($h2,$line);
		}else{
			$sql.=$line;
		}
	//}why not??
	//}
}

fclose($h);
//=======================================

print_r($tables);
}

?>
