<?php
/* Towns4Admin, www.towns.cz 
   © Pavel Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================
?>
<h3>SetDefault</h3>
Nastavení aktuálního používaného světa.
<br />
<br />
<?php
if($_POST['default']){
	$stream=file_get_contents(root.'index.php');//echo($stream);
	$stream=substr2($stream,'define("w","','"',0,$_POST['default']);
	//echo($stream);
	file_put_contents2(root.'index.php',$stream);
}
$stream=file_get_contents(root.'index.php');
$default=substr2($stream,'define("w","','"');
?>
<form id="form1" name="form1" method="post" action="?">
  <label>
  <input name="default" type="text" id="default" value="<?php echo($default); ?>" />
  </label>
<label><input name="Submit" type="submit" value="OK" />
</label>
</form>
