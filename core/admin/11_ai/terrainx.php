<?php
/* Towns4Admin, www.towns.cz 
   © Pavel Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================
?>

<h3>TerrainX</h3>
Provést pasivní funkci terrainX<br/><br/>


<a href="?page=terrainx&terrainx=1">Provést</a><br/>

<?php
if($_GET['terrainx']){

	//die('nene');

	foreach(sql_array('SELECT id,ww,x,y,func FROM `[mpx]pos_obj` WHERE ww>0 AND `func` LIKE \'%terrainx%\' AND '.objt()) as $row){
		list($id,$ww,$x,$y,$func)=$row;
		if($ww!=0){
		$x=intval($x);
		$y=intval($y);
		$func=func2list($func);
		$terrain=$func['terrainx']['profile']['terrain'];
		$distance=$func['terrainx']['params']['distance'][0]*$func['terrainx']['params']['distance'][1];

		//r($func);


		sql_query("UPDATE [mpx]map SET terrain='$terrain' WHERE `ww`=$ww AND $distance>sqrt(POW(`x`-$x,2)+POW(`y`-$y,2)) ",2);br();
		sql_query("UPDATE `[mpx]pos_obj` SET stoptime='".time()."' WHERE `id`=$id LIMIT 1",2);br();
		//sql_query("DELETE FROM `[mpx]pos_obj` WHERE `id`=$id LIMIT 1",2);br();
		changemap($x,$y,2);
		changemap($x+5,$y,2);
		changemap($x-5,$y,2);
		changemap($x,$y+5,2);
		changemap($x,$y-5,2);
		}

	}

}
?>
