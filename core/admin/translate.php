<?php
/* Towns4Admin, www.towns.cz 
   © Pavel Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================

ob_end_flush();

?>
<h3>Lang </h3>
<script type="text/javascript">
function confirm_click()
{
return confirm("Fakt?");
}

</script>
<?php

if($_GET['delete']){
	sql_query('DELETE FROM [mpx]lang WHERE `lang`=\''.$GLOBALS['ss']["editlang"].'\' AND `key`=\''.$_GET['delete'].'\'');
}
//$contents=file_get_contents(root.'data/lang/'.$GLOBALS['ss']["editlang"].'.txt');
//$contents=htmlspecialchars($contents);
/*lang	varchar(10)	 
key	varchar(200)	 
value	text	 
font	varchar(100)	 
author	int(11)	 
description	text	 
time*/
?>

<form id="form1" name="form1" method="post" action="<?php e($_GET['onlynew']?'?onlynew=1':''); ?>">
<input type="submit" name="Submit" value="Změnit" />
<table border="1" width="100%">
<tr>
<td align='center'><b>#</b></td>
<td align='center'><b><?php e($GLOBALS['tlang'][0]) ?></b></td>
<td align='center'><b><?php e($GLOBALS['tlang'][1]) ?></b></td>
</tr>
<?php

$array=sql_array('SELECT `key`,`value`,(SELECT `value` as `valuex` FROM [mpx]lang as `x` WHERE `x`.`key`=[mpx]lang.`key` AND lang=\''.$GLOBALS['tlang'][1].'\'  LIMIT 1) FROM [mpx]lang WHERE lang=\''.$GLOBALS['tlang'][0].'\'  ORDER BY `key`');

//print_r($_POST);
$i=0;
foreach($array as $row){
	list($key,$_value,$_value2)=$row;
	if(strpos($key,' ')===false and strpos($key,'languageprotection')===false and strpos($key,'$')===false and strpos($key,'<')===false and !is_numeric($key)){
	$i++;
	$key=htmlspecialchars($key);
	$value=htmlspecialchars($_value);
	$value2=htmlspecialchars($_value2);
	if(strlen($value)<100){
		$form1='<textarea name="a_'.$key.'" cols="50" rows="1">'.($_POST['a_'.$key]?$_POST['a_'.$key]:$value2).'</textarea>';		
	}else{
		$form1='<textarea name="a_'.$key.'" cols="50" rows="5">'.($_POST['a_'.$key]?$_POST['a_'.$key]:$value2).'</textarea>';		
	}

	e('<tr><td>');
	e($i);
	e('</td><td>');
	e('<a onclick="alert(\''.$key.'\');"><div style="overflow:auto;width:200;">'.nl2br($value).'</div></a>');
	e('</td><td>');


	if($_POST['a_'.$key] and $_POST['a_'.$key]!=$_value2){
		/*hr();
		echo($_POST['a_'.$key]);
		hr();
		echo($value);
		hr();*/
		sql_query('INSERT INTO [mpx]lang (`lang`,`key`) VALUES (\''.$GLOBALS['tlang'][1].'\',\''.$key.'\')',1);
		sql_query('UPDATE [mpx]lang SET `value`=\''.addslashes($_POST['a_'.$key]).'\' WHERE `lang`=\''.$GLOBALS['tlang'][1].'\' AND `key`=\''.$key.'\'',1);
		echo('<b>změněno</b><br/>');
	}
	;
	e($form1);	
	e('</td></tr>');
}}

?>
</table>
<input type="submit" name="Submit" value="Změnit" />
</form>
