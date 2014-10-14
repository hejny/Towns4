<?php ob_end_flush();
//error_reporting(E_ALL);
ini_set('memory_limit','10000M');
 ?>
<h3>Export</h3>
Tato funkce provede Mysql Dump...<br/>
<b>Upozornění: </b>Tento proces může trvat i několik minut.<br/>
<a href="?export=1">Vygenerovat</a><br/>
<a href="?export=2">Vygenerovat Vše</a>
<!--<a  href="javascript:void(0);" onclick="document.execCommand('SaveAs',true,'files/expor.xml');">Stáhnout</a>-->
<hr/>
<?php

/*function GetTableDef($table, $crlf)
{
	// MySQL >= 3.23.20
	$schema_create = "";
	$schema_create .= "DROP TABLE IF EXISTS $table;$crlf";
	$result = mysql_query("SHOW CREATE TABLE $table");
	$data=mysql_fetch_array($result);
	$schema_create .= $data[1] . ";$crlf$crlf";
	mysql_free_result($result);
	return $schema_create;
}
echo(GetTableDef('world1_users','-'));*/


set_time_limit(10000);
if($_GET['export']){

mkdir(adminfile.'files/backup');
chmod(adminfile.'files/backup',0777);

$nejm=w.'_'.time().'_'.date('j_n_Y');

$file_name=adminfile."files/backup/".$nejm.".sql";
$file_zip=adminfile."files/backup/".$nejm.".zip";

require(adminroot."dumpf.php");
echo('<b>uloženo do <a href="https://www.towns.cz/app/admin/files/backup/'.$nejm.'.zip">files/backup/'.$nejm.'.zip</a></b>');
}



?>
