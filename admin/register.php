<h3>Obnovení spawn míst sv&#283;ta <?php echo(w); ?> </h3>
<b>Upozornění: </b>Tato funkce přemaže spawn pozice světa <?php echo(w); ?>.<br />
<?php
require2("/login/func_core.php");

if($_GET['wtf']==1){
	foreach(array(/*1,2,3*/'') as $type){
		$file=tmpfile2("registerx".$type."_list","txt","text");
		unlink($file);
	}
}elseif($_GET['wtf']==2){
	foreach(array(/*1,2,3*/'') as $type){
		register_positionx($type);
	}
}


	foreach(array(/*1,2,3*/'') as $type){
		$file=tmpfile2("registerx".$type."_list","txt","text");
		$array=unserialize(file_get_contents($file)); 
		echo("<b>$type:</b> ".count($array));
		br();
	}
?>

<a href='?page=register&amp;wtf=1'>smazat</a><br>
<a href='?page=register&amp;wtf=2'>vytvořit</a>
