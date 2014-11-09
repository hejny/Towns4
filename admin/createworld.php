<?php
/* Towns4Admin, www.towns.cz 
   © Pavel Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================
?>
<h3>Vytvo&#345;en&iacute; nov&eacute;ho sv&#283;ta </h3>
Tento nátroj vytvoří kopii světa.<br/>
<b>Upozornění: </b>Podkud cílový svět extistuje bude přemazán!<br />
<b>Upozornění: </b>Podkud cílový svět extistuje zůstanou mu pomocné souory, ty je dobré přemazat!<br />
<b>Upozornění: </b>Název by měl obsahovat pouze malá písmena bez háčků a čárek!<br />
<?php
br();
if($_POST['from'] and $_POST['to']){
$from=$_POST['from'];
$to=$_POST['to'];

$tables=array();
foreach(sql_array('SHOW TABLES') as $table){
	$table=$table[0];
	list($world,$table)=explode('_',$table,2);
	if($world==$from){
		$tables[]=$table;

	}
}
foreach($tables as $table){
	textb($table);br();
	sql_query('DROP TABLE IF EXISTS `'.$to.'_'.$table.'`;',2);br();
	sql_query('CREATE TABLE `'.$to.'_'.$table.'` LIKE `'.$from.'_'.$table.'`',2);br();
	sql_query('INSERT INTO `'.$to.'_'.$table.'` SELECT * FROM `'.$from.'_'.$table.'`',2);br();
}


textb('Byly překopírovány tabulky '.join(',',$tables).'.');br();

}

?>

<form id="form1" name="form1" method="post" action="?page=createworld">
<input name="from" type="text" id="from" value="<?php echo(w); ?>" /> <b>-&gt;</b>
<input name="to" type="text" id="to" value="<?php echo($_POST['to']); ?>" />

<input type="submit" name="Submit" value="Vytvo&#345;it" />

</form>
