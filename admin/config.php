<?php
/* Towns4Admin, www.towns.cz 
   © Pavel Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================
?>
<h3>Config </h3>
<?php

?>

<form id="form1" name="form1" method="post" action="<?php e($_GET['onlynew']?'?onlynew=1':''); ?>">
<input type="submit" name="Submit" value="Změnit" />
<table border="1">
<tr>
<td align='center'><b>Klíč</b></td>
<td align='center'><b>Hodnota</b></td>
<td align='center'><b>Poznámka</b></td>
</tr>
<?php

$array=sql_array('SELECT `key`,`value`,`description` FROM [mpx]config ORDER BY `key`');

foreach($array as $row){
	list($key,$_value,$_description)=$row;
	if(strpos($key,' ')===false and strpos($key,'languageprotection')===false and strpos($key,'$')===false and strpos($key,'<')===false and !is_numeric($key)){

	$key=htmlspecialchars($key);
	$value=htmlspecialchars($_value);
	$description=htmlspecialchars($_description);
	if(strlen($value)<100){
		$form1='<textarea name="a_'.$key.'" cols="50" rows="1">'.(isset($_POST['a_'.$key])?$_POST['a_'.$key]:$value).'</textarea>';		
		//$form1='<input type="input" name="a_'.$key.'" value="'.$value.'" size="'.$size.'"/>';
		$form2='<input type="input" name="b_'.$key.'" value="'.(isset($_POST['b_'.$key])?$_POST['b_'.$key]:$description).'" size="'.$size.'"/>';
	}else{
		$form1='<textarea name="a_'.$key.'" cols="50" rows="5">'.(isset($_POST['a_'.$key])?$_POST['a_'.$key]:$value).'</textarea>';
		$form2='<input type="input" name="b_'.$key.'" value="'.(isset($_POST['b_'.$key])?$_POST['b_'.$key]:$description).'" size="'.$size.'"/>';		
		//$form2='<textarea name="b_'.$key.'" cols="50" rows="5">'.($_POST['b_'.$key]?$_POST['b_'.$key]:$description).'</textarea>';
	}

	e('<tr><td>');
	e($key);
	e('</td><td>');

	if(isset($_POST['a_'.$key]) and $_POST['a_'.$key]!=$_value){
		/*hr();
		echo($_POST['a_'.$key]);
		hr();
		echo($value);
		hr();*/
		sql_query('UPDATE [mpx]config SET `value`=\''.addslashes($_POST['a_'.$key]).'\' WHERE `key`=\''.$key.'\'');
		echo('<b>změněno</b><br/>');
	}
	e($form1);
	e('</td><td>');
	if(isset($_POST['b_'.$key]) and $_POST['b_'.$key]!=$_description){
		sql_query('UPDATE [mpx]config SET `description`=\''.addslashes($_POST['b_'.$key]).'\' WHERE `key`=\''.$key.'\'');
		echo('<b>změněno</b><br/>');
	}
	e($form2);
	e('</td><td>');

	e('</tr>');
}}

?>
</table>
<input type="submit" name="Submit" value="Změnit" />
</form>
