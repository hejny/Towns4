<?php
/* Towns4Admin, www.towns.cz 
   © Pavel Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================
?>
<h3>Obnovení spawn míst sv&#283;ta <?php echo(w); ?> </h3>
<b>Upozornění: </b>Tato funkce přemaže spawn pozice světa <?php echo(w); ?>.<br />
<?php
require2("/login/func_core.php");

br();



/*e(htmlspecialchars("
                    SELECT `x`,`y` FROM world1_map where `ww`='".$GLOBALS['ss']["ww"]."' AND 
            RAND()>0.90 AND
                    (`terrain`='t3' OR `terrain`='t4' OR `terrain`='t7' OR `terrain`='t8' OR `terrain`='t9' OR `terrain`='t12' OR `terrain`='t13')  AND 
                    9=(SELECT COUNT(1) FROM world1_map AS Y where Y.`ww`='".$GLOBALS['ss']["ww"]."' AND (Y.`terrain`='t3' OR Y.`terrain`='t4' OR Y.`terrain`='t7' OR Y.`terrain`='t8' OR Y.`terrain`='t9' OR Y.`terrain`='t12' OR Y.`terrain`='t13') AND (Y.`x`+1>=world1_map.`x` AND Y.`y`+1>=world1_map.`y` AND Y.`x`-1<=world1_map.`x` AND Y.`y`-1<=world1_map.`y`))
                    AND
                    0=(SELECT COUNT(1) FROM world1_objects AS X where X.`ww`='".$GLOBALS['ss']["ww"]."' AND  X.`own`!='0' AND (X.`x`+4>world1_map.`x` AND X.`y`+4>world1_map.`y` AND X.`x`-4<world1_map.`x` AND X.`y`-4<world1_map.`y`))
                     ORDER BY RAND()"));*/



if($_GET['wtf']==1){
	foreach(array(/*1,2,3*/'') as $type){
		$file=tmpfile2("registerx".$type."_list","txt","text");
		unlink($file);
	}
}elseif($_GET['wtf']==2){
	foreach(array(/*1,2,3*/'') as $type){
		$position=register_positionx($type);
		br();
		print_r($position);
		br();
	}
}elseif($_GET['wtf']==3 or $_GET['wtf']==4){

	$file=tmpfile2("registerx_list","txt","text");
    if(!file_exists($file) or unserialize(file_get_contents($file))==array()){
		e('No pos to test');br();
    }else{
        $array=unserialize(file_get_contents($file));
		$array2=$array;
		$i=0;$is=0;
		$uz=array();
		while($array[$i]){
		 	list($x,$y)=$array[$i];
				e("($x,$y) - ");

				if(register_test($x,$y) and !$uz["($x,$y)"]){
					textb("OK");
					$uz["($x,$y)"]=true;

				}else{
					if($uz["($x,$y)"]){textb("Duplikát");}
					if($_GET['wtf']==4){array_splice($array2,$i-$is,1);$is++;}
				}
				br();
			$i++;
		}

			/*$i=0;
			while($array2[$i]){
			 	list($x,$y)=$array2[$i];
					e("($x,$y)");
					br();
				$i++;
			}*/
	if($_GET['wtf']==4)fpc($file,serialize($array2));

	}
	hr();
}elseif($_GET['wtf']==5){

		$file=tmpfile2("registerx_list","txt","text");
		$array1=unserialize(file_get_contents($file));
                    /*$array2=sql_array("
                    SELECT `x`,`y` FROM [mpx]map where `type`='terrain' AND `ww`='".$GLOBALS['ss']["ww"]."' AND
            RAND()>0.9999 AND
                    (`terrain`='t3' OR `terrain`='t4' OR `terrain`='t7' OR `terrain`='t8' OR `terrain`='t9' OR `terrain`='t12' OR `terrain`='t13')  AND 
                    9=(SELECT COUNT(1) FROM [mpx]map AS Y where Y.`type`='terrain' AND Y.`ww`='".$GLOBALS['ss']["ww"]."' AND (Y.`terrain`='t3' OR Y.`terrain`='t4' OR Y.`terrain`='t7' OR Y.`terrain`='t8' OR Y.`terrain`='t9' OR Y.`terrain`='t12' OR Y.`terrain`='t13') AND (Y.`x`+1>=[mpx]map.`x` AND Y.`y`+1>=[mpx]map.`y` AND Y.`x`-1<=[mpx]map.`x` AND Y.`y`-1<=[mpx]map.`y`))
                    AND
                    0=(SELECT COUNT(1) FROM `[mpx]pos_obj` AS X where X.`ww`='".$GLOBALS['ss']["ww"]."' AND  X.`own`!='0' AND (X.`x`+4>[mpx]map.`x` AND X.`y`+4>[mpx]map.`y` AND X.`x`-4<[mpx]map.`x` AND X.`y`-4<[mpx]map.`y`))
                    ORDER BY RAND()");*/
        exit2('@todo Převod Map do Objects');//@todo Převod Map do Objects


		fpc($file,serialize(array_merge($array2,$array1)));



	hr();
}


{
	$file=tmpfile2("registerx_list","txt","text");
		if(!file_exists($file) or unserialize(file_get_contents($file))==array()){
			e('No pos!!!!!!!!!!');br();
		}else{
		    $array=unserialize(file_get_contents($file));
			$i=0;
			while($array[$i]){
			 	list($x,$y)=$array[$i];
					e("($x,$y)");
					br();
				$i++;
			}
		}
}



	hr();

	foreach(array(/*1,2,3*/'') as $type){
		$file=tmpfile2("registerx".$type."_list","txt","text");
		$array=unserialize(file_get_contents($file));
		$type='Pozic';
		echo("<h2>$type: ".count($array).'</h2>');
	}
?>
<a href='?page=register'>zobrazit</a><br>
<a href='?page=register&amp;wtf=5'><b>přidávat</b></a><br>
<a href='?page=register&amp;wtf=1'>smazat</a><br>
<a href='?page=register&amp;wtf=2'>vytvořit</a><br>
<a href='?page=register&amp;wtf=3'>testovat</a><br>
<a href='?page=register&amp;wtf=4'><b>filtrovat</b></a>



<?php
if($_GET['wtf']==5){
echo('<script language="javascript">
    window.location.replace("?page=register&wtf=5");
    </script>');
}

?>
