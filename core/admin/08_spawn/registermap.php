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

$scale=2;


/*if($_GET['refresh']){
	$outimg=tmpfile2("worldmap,$width,$w,$minsize".(serialize($worldmap_red)).t_,"png","map");
}*/


$file=tmpfile2("registerx_list","txt","text");
$array=unserialize(fgc($file));
//print_r($array);
//-----------
$change=false;
//--------------------------
if($_GET['add']){
	$add=$_GET['add'];
	$add=str_replace('?','',$add);
	$array2=array();
	$array2=explode(',',$add);
	$array2[0]=round($array2[0]/$scale);
	$array2[1]=round($array2[1]/$scale);
	$q=true;
	foreach($array as $row){
		if($row[0]==$array2[0] and $row[1]==$array2[1]){
			error('Pozice už existuje!');
			$q=false;
		}
	}
	if($q){
		$array2['x']=$array2[0];
		$array2['y']=$array2[1];
		$array2=array($array2);
		if($array and $array!=array()){
			$array=array_merge($array2,$array);
		}else{
			$array=$array2;
		}
		$change=true;
	}}
//--------------------------
if($_GET['delete']){
	array_splice($array,$_GET['delete']-1,1);
	$change=true;
}
//--------------------------
if($change){
		fpc($file,serialize($array));
}
//-------
$i=0;
while($array[$i]){

list($x,$y)=$array[$i];
		
//e("($x,$y)");
		

for($yy=0;$yy<3;$yy++){
			for($xx=0;$xx<3;$xx++){

				e('<div style="position:absolute;z-index:'.(($xx==1 and $yy==1)?20:1).';"><div style="position:relative;top:'.(($y*$scale)+$yy-8).';left:'.(($x*$scale)+$xx-11).';width:20px;text-align:center;font-size:11px;">');
				e('<a style="color:#'.(($xx==1 and $yy==1)?'ffffff':'000000').';e(" href="?page=registermap&delete='.($i+1).'">');
				e('<b>'.($i+1).'</b>');
				e('</a></div></div>');
			}
		}
		
//if($i<5){
			e('<div style="position:absolute;"><div style="position:relative;top:'.(($y*$scale)-9).';left:'.(($x*$scale)-9).';">');
			e('<a href="?page=registermap&delete='.($i+1).'">'.imgr('design/register'.(($i<5)?'2':'').'.png','',18,18).'</a>');
			e('</div></div>');
		
//}
		
//br();
	$i++;
}
$file=tmpfile2('mapdata','txt','worldmap');
if(!file_exists($file)){

	$mapdata=sql_array('SELECT x,y FROM `[mpx]pos_obj` WHERE type=\'building\' AND ww='.$GLOBALS['ss']["ww"].' AND '.objt()/*objt(false,floor(time()/(3600*24))*(3600*24))*/);
	fpc($file,serialize($mapdata));

}else{
	$mapdata=unserialize(fgc($file));
}
	e('<a href="?page=registermap&add="><img id="minimap" src="../../'.worldmap(mapsize*$scale,50,false,true,$mapdata)/**/.'" ismap /></a>');
?>
<br>
<i>


Pro onovení je potřeba <a href="?total=<?php e(w); ?>&dir=tmp/small/<?php e(w); ?>" target="_blank">smazat tmp/worldmap</a></i><br><br>



