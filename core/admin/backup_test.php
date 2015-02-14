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
<h3>Analýza backup souboru</h3>
Tato funkce analýzu MySQL Dump...<br/>
<b>Upozornění: </b>Tento proces může trvat i několik mintut.<br/>
<b>Upozornění: </b>Aby byla analýza přesná, je potřeba, aby proběhla celá bez přerušení.<br/>
<hr/>
<?php

if($_GET['filename']){
	$tmp=$_GET['filename'];
	if(file_exists($tmp)){

		if(substr($tmp,-4)=='.zip'){
			
			$tmpsql=substr($tmp,0,strlen($tmp)-3).'sql';

			if(!file_exists($tmpsql)){
				extract_zip($tmp,adminfile.'files/backup/');
			}
			
			$tmp=$tmpsql;
			$tmp='app/admin/files/backup/backup.sql';
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

	for($i=0;$i<=10;$i++){
		$nejm = glob(adminfile . 'files/backup/' . 'backup_*_' . date('j_n_Y',time()-($i*3600*24)) . '*');
		$nejm = $nejm[0];
		if($nejm)break;
	}
	?>

	<form id="form1" name="form1" method="get" action="?page=sqlx">
	<table  border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td><strong>Soubor:</strong></td>
		<td><label>
		  <input name="filename" type="text" id="filename" size="70" value="<?php echo(($_GET['filename'] and $_GET['filename']!='none')?$_GET['filename']:$nejm); ?>" />
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
//=============================================================================================================
$pos=$_GET['pos'];
if(!$pos){
	$pos=0;
	$_SESSION['analyze']=array();
}

$file1=$_SESSION['filename'];//'world1.sql';
$limit=200;//200;

//echo('aaa');
$h=fopen($file1, 'r');

//=======================================Pozice v souboru

echo('<b>'.$_SESSION['filename'].'</b><br/>');
echo("$pos / ".filesize($file1).' <b>('.round(100*$pos/filesize($file1),4).'%)</b><br/><br/>');

//=======================================Analýza
fseek($h,$pos);

$_SESSION['sql']='';

$html='';

$ii=1;
while(!feof($h) and ($ii<=$limit or $_SESSION['sql'])){$ii++;
	$line=fgets($h);
	//if(strpos($line,w)!==false){
	//if(strpos($line,'INSERT INTO world1_memory')===false and strpos($line,'INSERT INTO world1_log ')===false){//why not??
		if(strpos($line,';')){
			$_SESSION['sql'].=$line;
			$html.=(nl2br(htmlspecialchars($_SESSION['sql'])));
			$html.=('<br/>');

			//---------------------------analýza
			if(strpos($_SESSION['sql'],'INSERT')!==false){
				$_SESSION['sql']=str_remove($_SESSION['sql'],array('INSERT','INTO','`','#DATA',nln));
				$_SESSION['sql']=trim($_SESSION['sql']);
				$table=explode(' ',$_SESSION['sql']);
				$table=$table[0];
				//print_r($table);
				if(!isset($_SESSION['analyze'][$table])){
					$_SESSION['analyze'][$table]=0;
				}

				$_SESSION['analyze'][$table]++;
				$html.=(textbr($table.'++').'<br/>');
			}
			//---------------------------

			$_SESSION['sql']='';
			//fputs($h2,$line);
		}else{
			$_SESSION['sql'].=$line;
		}
	//}why not??
	//}
}

$pos=ftell($h);
//=======================================Zobrazení výsledků analýzy


//print_r($_SESSION['analyze']);
foreach($_SESSION['analyze'] as $table=>$rows){
	th($table);
	td($rows);
	tr();
}
table(200,false,1);

//=======================================Pozice v souboru - ovládání
	br();

	e("<a href=\"?page=backup_test&amp;filename=none\">zrušit</a>");
	e(nln.'-'.nln);
if($_GET['pos']){

//die($pos.'>='.filesize($file1));
if($pos>=filesize($file1)){
	/*die('<script language="javascript">
    window.location.replace("?page=backup_test&start=1");
    </script>');*/
	e('<h3>hotovo</h3>');
}else{
e(nln.'-'.nln);
e("<a href=\"?page=backup_test&amp;filename=".$_GET['filename']."&amp;pos=$pos\">next</a>");
e(nln.'-'.nln);
e("<a href=\"?page=backup_test&amp;filename=".$_GET['filename']."&amp;pos=0\">znovu</a>");
br();
e('<script language="javascript">
    window.location.replace("?page=backup_test&filename='.$_GET['filename'].'&pos='.$pos.'");
    </script>');/**/
}
}else{
e("<a href=\"?page=backup_test&amp;filename=".$_GET['filename']."&amp;pos=$pos\"><b>start</b></a><br/>");
}


//=======================================Zobrazení jednotlivých SQL dostazů
br();hr();br();
e($html);
//=======================================

fclose($h);


//$contents=file_get_contents('world1.sql');
//file_get_contents('world1x.sql',$contents);

}

?>
