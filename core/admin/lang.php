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
if(!$GLOBALS['ss']["editlang"]){
   $GLOBALS['ss']["editlang"]='cz';
}
if($_GET["editlang"]){
   $GLOBALS['ss']["editlang"]=$_GET["editlang"];
}

$q=false;foreach(array('cz','en') as $lang){
   if($q){
      echo('&nbsp;-&nbsp;');
   }
   $q=true;
   if($GLOBALS['ss']["editlang"]==$lang){
      echo('<b>'.$lang.'</b>');
   }else{
      echo('<a href="?editlang='.$lang.'">'.$lang.'</a>');
   }
}
br();
echo('<a href="?onlynew=1">Pouze nové</a>');

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
<table border="1">
<tr>
<td align='center'><b>Klíč</b></td>
<td align='center'><b>Hodnota</b></td>
<td align='center'><b>Poznámka</b></td>
<td align='center'><b>Akce</b></td>
</tr>
<?php

$array=sql_array('SELECT `key`,`value`,`description` FROM [mpx]lang WHERE lang=\''.$GLOBALS['ss']["editlang"].'\' AND `key`!=\'\' '.($_GET['onlynew']?' AND `description`=\'new\' AND `key` NOT LIKE \'%building_%_count%\'':'').' ORDER BY `key`');

foreach($array as $row){
	list($key,$_value,$_description)=$row;
	if(strpos($key,' ')===false and strpos($key,'languageprotection')===false and strpos($key,'$')===false and strpos($key,'<')===false and !is_numeric($key)){

	$key=htmlspecialchars($key);
	$value=htmlspecialchars($_value);
	$description=htmlspecialchars($_description);
	if(strlen($value)<100){
		$form1='<textarea name="a_'.$key.'" cols="50" rows="1">'.($_POST['a_'.$key]?$_POST['a_'.$key]:$value).'</textarea>';		
		//$form1='<input type="input" name="a_'.$key.'" value="'.$value.'" size="'.$size.'"/>';
		$form2='<input type="input" name="b_'.$key.'" value="'.($_POST['b_'.$key]?$_POST['b_'.$key]:$description).'" size="'.$size.'"/>';
	}else{
		$form1='<textarea name="a_'.$key.'" cols="50" rows="5">'.($_POST['a_'.$key]?$_POST['a_'.$key]:$value).'</textarea>';
		$form2='<input type="input" name="b_'.$key.'" value="'.($_POST['b_'.$key]?$_POST['b_'.$key]:$description).'" size="'.$size.'"/>';		
		//$form2='<textarea name="b_'.$key.'" cols="50" rows="5">'.($_POST['b_'.$key]?$_POST['b_'.$key]:$description).'</textarea>';
	}

	e('<tr><td>');
	e($key);
	e('</td><td>');

	if($_POST['a_'.$key] and $_POST['a_'.$key]!=$_value){
		/*hr();
		echo($_POST['a_'.$key]);
		hr();
		echo($value);
		hr();*/
		sql_query('UPDATE [mpx]lang SET `value`=\''.addslashes($_POST['a_'.$key]).'\' WHERE `lang`=\''.$GLOBALS['ss']["editlang"].'\' AND `key`=\''.$key.'\'');
		echo('<b>změněno</b><br/>');
	}
	e($form1);
	e('</td><td>');
	if($_POST['b_'.$key] and $_POST['b_'.$key]!=$_description){
		sql_query('UPDATE [mpx]lang SET `description`=\''.addslashes($_POST['b_'.$key]).'\' WHERE `lang`=\''.$GLOBALS['ss']["editlang"].'\' AND `key`=\''.$key.'\'');
		echo('<b>změněno</b><br/>');
	}
	e($form2);
	e('</td><td>');



	e('<a href="?page=lang&amp;delete='.$key.'" onclick="return confirm_click();">smazat</a>');
	e('</td></tr>');
}}

?>
</table>
<input type="submit" name="Submit" value="Změnit" />
</form>
