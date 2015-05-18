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

require_once(core.'/login/func_core.php');

$scale=2;


//-------
$limit=10;
while($limit>0){$limit--;

$ms=microtime();
list($x,$y)=register_position();
$ms=microtime()-$ms;
$ms=$ms*1000;
$ms=round($ms);
//e(round($ms,2).' ms<br/>');
		

for($yy=0;$yy<3;$yy++){
			for($xx=0;$xx<3;$xx++){

				e('<div style="position:absolute;z-index:'.(($xx==1 and $yy==1)?20:1).';"><div style="position:relative;top:'.(($y*$scale)+$yy-8).';left:'.(($x*$scale)+$xx-11).';width:20px;text-align:center;font-size:11px;">');
				e('<a style="color:#'.(($xx==1 and $yy==1)?'ffffff':'000000').';e(" href="#">');
				e('<b>'.$ms.'</b>');
				e('</a></div></div>');
			}
		}
		
//if($i<5){
			e('<div style="position:absolute;"><div style="position:relative;top:'.(($y*$scale)-9).';left:'.(($x*$scale)-9).';">');
			imge('design/register2.png','',18,18);
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
	e('<img id="minimap" src="../'.worldmap(mapsize*$scale,50,false,true,$mapdata)/**/.'" />');
?>
<br>
<i>


Pro onovení je potřeba <a href="?total=<?php e(w); ?>&dir=tmp/small/<?php e(w); ?>" target="_blank">smazat tmp/worldmap</a></i><br><br>



