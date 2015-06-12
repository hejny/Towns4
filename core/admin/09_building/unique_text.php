<?php
/* Towns4Admin, www.towns.cz 
   © Pavol Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================


ob_end_flush();

?>
<h3>Unique </h3>
<?php
if($_POST['contents']){
	echo('<b>změněno</b><br/>');
	$contents=fpc(adminfile.'objects/models.txt',$_POST['contents']);
}
$contents=file_get_contents(adminfile.'objects/models.txt');
$contents=htmlspecialchars($contents);
?>

<form id="form1" name="form1" method="post" action="">
<textarea name="contents" style="width: 90%;height: 70%;">
<?php echo($contents); ?> 
</textarea><br/>
<input type="submit" name="Submit" value="Změnit" />
</form>
