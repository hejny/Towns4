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
<h3>Import</h3>
Tato funkce provede import z MySQL Dump...<br/>
<b>Upozornění: </b>Tato funkce by se měla provádět s prázdnou databází.<br/>
<b>Upozornění: </b>Tento proces může trvat i několik mintut.<br/>
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
//die($_SESSION['filename']);
//=============================================================================================================
try {
$GLOBALS['pdo'] = new PDO('mysql:host='.$GLOBALS['inc']['mysql_host'].';dbname='.$GLOBALS['inc']['mysql_db'], $GLOBALS['inc']['mysql_user'], $GLOBALS['inc']['mysql_password'], array(PDO::ATTR_PERSISTENT => true));
$GLOBALS['pdo']->exec("set names utf8");
} catch (PDOException $e) {
    if(!defined('nodie')){
	//require(root.core."/output.php");
	echo('The server is currently unavailable. Please try again later.');
	die('<!--Could not connect: ' . $e->getMessage().'-->');
}
}
//---------------------
function xsql($text){return(/*mysqli_real_escape_string*/addslashes($text));}
function xsql_mpx($text){return(str_replace('[mpx]',mpx,$text));}
//--------------------------------------------
function xsql_query($q,$w=false){
    $q=xsql_mpx($q);
    if($w==1){r($q);}
    if($w==2){echo($q);}
	$response= $GLOBALS['pdo']->prepare($q);
    $response->execute();
    //$response=$GLOBALS['pdo']->exec($q);
	//print_r($response);
    $err=($response->errorInfo());
	if($err=$err[2]){echo('<b>'.$err.'</b>');echo('<br/>');$_SESSION['error'].=htmlspecialchars($q)."<br/><i>$err</i><br/>";}
    //$error=mysql_error();
    /*if($error and debug){
        echo($q."<br>".$error."<br>");
    }*/
    
    return($response);
}
//=============================================================================================================
$pos=$_GET['pos'];
if(!$pos){
	$pos=0;
	$_SESSION['error']='';
}

$file1=$_SESSION['filename'];//'world1.sql';
$limit=200;//200;

//echo('aaa');
$h=fopen($file1, 'r');

//if(!$_SESSION['error'])

echo('<b>'.$_SESSION['filename'].'</b><br/>');
echo("$pos / ".filesize($file1).' <b>('.round(100*$pos/filesize($file1),4).'%)</b><br/><br/>');
echo($_SESSION['error'].'<br/>');

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
			xsql_query($_SESSION['sql']);
			$_SESSION['sql']='';
			//fputs($h2,$line);
		}else{
			$_SESSION['sql'].=$line;
		}
	//}why not??
	//}
}

$pos=ftell($h);
//=======================================

echo('<br/>');
if($_GET['pos']){
if($pos>=filesize($file1)){
	die('<script language="javascript">
    window.location.replace("?page=createtmp&start=1");
    </script>');
}else{
echo("<a href=\"?page=sqlx&amp;filename=".$_GET['filename']."&amp;pos=$pos\">next</a><br/>");
echo('<script language="javascript">
    window.location.replace("?page=sqlx&filename='.$_GET['filename'].'&pos='.$pos.'");
    </script>');/**/
}
}else{
echo("<a href=\"?page=sqlx&amp;filename=".$_GET['filename']."&amp;pos=$pos\"><h3>start</h3></a><br/>");
}

e($html);
//=======================================

fclose($h);


//$contents=file_get_contents('world1.sql');
//file_get_contents('world1x.sql',$contents);

}

?>
