<?php
/* Towns4Admin, www.towns.cz 
   © Pavel Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================
?>
<h3>Registace míst na mapě</h3>
<?php
require2("/func_map.php");

/*if($_GET['refresh']){
	$outimg=tmpfile2("worldmap,$width,$w,$minsize".(serialize($worldmap_red)).t_,"png","map");

}*/

if($_GET['type']){$type=$_GET['type'];}else{$type=1;}
$file=tmpfile2("registerx".$type."_list","txt","text");
$array=unserialize(file_get_contents($file));    

//r($array);

e('<img id="minimap" src="'.worldmap(700,50,NULL,NULL,$array).'" width="700"/>');
?>
<!--<a href="?page=registermap&amp;type=1">náhodně</a><br>
<a href="?page=registermap&amp;type=2">opuštěná místa</a><br>
<a href="?page=registermap&amp;type=3">vedle hráčů</a><br>-->
