<h3>MainRES</h3>

<?php

ini_set("max_execution_time","1000");



$array=sql_array("SELECT `id`,`name` FROM `".mpx."objects` WHERE $wwhere AND `type`='town2' ORDER BY name");
if($_POST['res']){
	foreach($array as $row){
   		list($id,$name)=$row;  
		$res=$_POST['res'];
		$res=str_replace(array("\r","\n"),'',$res);
		$res=sql($res);
		sql_query("UPDATE `".mpx."objects` SET res='$res' WHERE $wwhere AND `type`='building' AND `name`='".sql(mainname())."' AND `own`=$id");
	}
}
$res=sql_1data("SELECT A.`res` FROM `[mpx]objects` AS A WHERE A.$wwhere AND A.`type`='building' AND A.`name`='".sql(mainname())."' AND
'town2'=(SELECT B.`type` FROM `[mpx]objects` AS B WHERE A.`own`=B.`id`)");


if($_GET['color']){
    foreach($array as $row){
        list($id,$name)=$row;  
		sql_query("UPDATE `".mpx."objects` SET profile='color=".rand_color()."' WHERE $wwhere AND profile NOT LIKE '%color%' AND `type`='town2' AND `id`='".$id."'",2);br();
    }
}

?>
<form action="?page=mainres" method="POST">
<input type="text" name="res" size="100" value="<?php e($res); ?>">
<input type="submit" value="změnit">
</form>
<h3><a href="?color=1">color</a></h3>
<?php




e('<table width="100%" border="1">');

e('<tr>');
e('<td><b>ID</b></td>');
e('<td><b>Jméno</b></td>');
e('</tr>');   
   

foreach($array as $row){
   list($id,$name)=$row;  
   
   e('<tr>');
   e('<td>'.$id.'</td>');
   e('<td>'.$name.'</td>');
      

   e('</tr>');
}
e('</table>');
?>



