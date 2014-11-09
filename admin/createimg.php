<?php
/* Towns4Admin, www.towns.cz 
   © Pavel Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================
?>
<h3>CreateImg</h3>
Vygenerování průvodního mapového podkladu<hr/>
<?php
ini_set("max_execution_time","1000");
ini_set("memory_limit","200M");
//---------------------------------------------------

$size = $_POST["size"];
$firstbg = $_POST["firstbg"];
$secondbg = $_POST["secondbg"];
if($firstbg=='')$firstbg=1;
if($secondbg=='')$secondbg=8;
$voda = $_POST["voda"];
$rr=$_POST["rr"];
$ru=$_POST["ru"];
$r_pocet = $_POST["r_pocet"];
$r_uhel = $_POST["r_uhel"];
$r_uzemi = $_POST["r_uzemi"];
$tr=$_POST["tr"];

$t2=$_POST["t2"];//dlažba
$t3=$_POST["t3"];//sníh/led
$t4=$_POST["t4"];//písek
$t5=$_POST["t5"];//kamení
$t6=$_POST["t6"];//hlína
$t7=$_POST["t7"];//sůl
$t9=$_POST["t9"];//tráva(toxic)
$t10=$_POST["t10"];//les
$t11=$_POST["t11"];//řeka
$t12=$_POST["t12"];//tráva(jaro)
$t13=$_POST["t13"];//tráva(pozim)
//-----------
if(!$size){
$size = 100;
$voda = 20;
$rr=1;
$ru=0;
$r_pocet = 400;
$r_uhel = 25;
$r_uzemi = 0.4;
$tr=1;

$t2=1;//dlažba
$t3=5;//sníh/led
$t4=5;//písek
$t5=20;//kamení
$t6=5;//hlína
$t7=3;//sůl
$t9=1;//tráva(toxic)
$t10=30;//les
$t11=0;//řeka
$t12=50;//tráva(jaro)
$t13=50;//tráva(pozim)
}
if($voda>80)$voda=80;
//-----------
?>

<?php
if($_POST["size"]){
//-------------------main-----------------	
$voda = 100-$voda;
//$piesok = 100-$piesok;
//---------
$x = $size;
while($x > 0){
$y = $size;
while($y > 0){
//------------------------------------	
$mapa[$x][$y]  = $firstbg;
//------------------------------------	
$y = $y - 1;	
}
$x = $x - 1;	
}
//-----------------ostrov------------------
$q = ($size*$size)*($voda/100);
$x = rand(1,$size);
$y = rand(1,$size);
while($q > 0){

if($mapa[round($x)][round($y)]  == $secondbg){$q = $q + 1;	}

$mapa[round($x)][round($y)]  = $secondbg;

$x = $x + rand(0,$rr*200)/100 -$rr;
$y = $y + rand(0,$rr*200)/100 -$rr;

if($x > $size-1 or $x < 1 or $y > $size-1 or $y < 1){
$u=$ru/2;
$x = rand(intval($size*(0.5-$u)),intval($size*(0.5+$u)));
$y = rand(intval($size*(0.5-$u)),intval($size*(0.5+$u)));
}

$q = $q - 1;	
}
//-------------------reky----------------
$q = ($r_pocet*$size)/100;
$u=$r_uzemi/2;
while($q > 0){
$x = rand(intval($size*(0.5-$u)),intval($size*(0.5+$u)));
$y = rand(intval($size*(0.5-$u)),intval($size*(0.5+$u)));
$uhel = rand(1,360);
$px = cos($uhel)/1.41;
$py = sin($uhel)/1.41;
//$prekon = $prekonrek; 
while($mapa[round($x)][round($y)]  != 1 and false == ($x > $size-1 or $x < 1 or $y > $size-1 or $y < 1) ){

$q = $q - 1;

$mapa[round($x)][round($y)]  = 11;


$px = cos($uhel/180*pi())/1.41;
$py = sin($uhel/180*pi())/1.41;
$x = $x+$px;
$y = $y+$py;
$uhel=$uhel+rand(0,$r_uhel*2) -$r_uhel;
}
}
//-----------------funkce------------------
function pozadie($mapa_p,$pocet_p,$jake,$size,$rr){
$mapa = $mapa_p;
$q = /*($size*$size)*($voda/100)*($les/100)*/intval($pocet_p);
//echo($pocet_p);
$x = rand(1,$size);
$y = rand(1,$size);
while($q > 0){

if($mapa[round($x)][round($y)]  == 1 or $mapa[round($x)][round($y)]  == 11){ $x = rand(1,$size); $y = rand(1,$size); }else{

$mapa[round($x)][round($y)]  = $jake;
//echo("$x / $y / $q / $size / $jake  <br/>");

$x = $x + rand(0,$rr*200)/100 -$rr;
$y = $y + rand(0,$rr*200)/100 -$rr;
if($x > $size-1){ $x = rand(1,$size); }
if($x < 1){$x = rand(1,$size);  }
if($y > $size-1){ $y = rand(1,$size); }
if($y < 1){$y = rand(1,$size);  }

}
$q = $q - 1;
}
return($mapa);
}
//-----------------terény------------------
$mapa = pozadie($mapa,($size*$size)*($voda/100)*($t2/100),2,$size,$tr);
$mapa = pozadie($mapa,($size*$size)*($voda/100)*($t3/100),3,$size,$tr);
$mapa = pozadie($mapa,($size*$size)*($voda/100)*($t4/100),4,$size,$tr);
$mapa = pozadie($mapa,($size*$size)*($voda/100)*($t5/100),5,$size,$tr);
$mapa = pozadie($mapa,($size*$size)*($voda/100)*($t6/100),6,$size,$tr);
$mapa = pozadie($mapa,($size*$size)*($voda/100)*($t7/100),7,$size,$tr);
$mapa = pozadie($mapa,($size*$size)*($voda/100)*($t9/100),9,$size,$tr);
$mapa = pozadie($mapa,($size*$size)*($voda/100)*($t10/100),10,$size,$tr);
$mapa = pozadie($mapa,($size*$size)*($voda/100)*($t11/100),11,$size,$tr);
$mapa = pozadie($mapa,($size*$size)*($voda/100)*($t12/100),12,$size,$tr);
$mapa = pozadie($mapa,($size*$size)*($voda/100)*($t13/100),13,$size,$tr);/**/
//------------------ine-----------------
$x = $size;
while($x > 0){
$y = $size;
while($y > 0){
//------------------------------------	
$y = $y - 1;	
}
$x = $x - 1;	
}
$voda = 100-$voda;
//-----------------------------------


$im=imagecreate($size,$size);
$black = imagecolorallocate($im, 0, 0, 0);
imagefill($im,0,0,$black);
$colors=array();
$y=0;
foreach($mapa as $row){
$x=0;
foreach($row as $row1){	
$p=$row1 ;
//echo($p."<br>");
if($p == 1){ $color = "5299F9"; }//moře
elseif($p == 2){ $color = "545454"; }//dlažba
elseif($p == 3){ $color = "EFF7FB"; }//sníh/led
elseif($p == 4){ $color = "F9F98D"; }//písek
elseif($p == 5){ $color = "878787"; }//kamení
elseif($p == 6){ $color = "5A2F00"; }//hlína
elseif($p == 7){ $color = "DCDCAC"; }//sůl
elseif($p == 8){ $color = "2A7302"; }//tráva(normal)
elseif($p == 9){ $color = "51F311"; }//tráva(toxic)
elseif($p == 10){ $color = "535805"; }//les
elseif($p == 11){ $color = "337EFA"; }//řeka
elseif($p == 12){ $color = "8ABC02"; }//tráva(jaro)
elseif($p == 12){ $color = "8A9002"; }//tráva(pozim)



if(!isset($colors[$p])){
$red=hexdec(substr($color,0,2));
$green=hexdec(substr($color,2,2));
$blue=hexdec(substr($color,4,2));
if($red>255){$red=255;}if($red<1){$red=1;}
if($green>255){$green=255;}if($green<1){$green=1;}
if($blue>255){$blue=255;}if($blue<1){$blue=1;}
$colors[$p] = imagecolorallocate($im,$red,$green,$blue);
}
imagesetpixel($im,$x,$y,$colors[$p]);

$x++;
}

$y++;
}


imagepng($im,adminfile."files/tmpmap.png");
chmod(adminfile."files/tmpmap.png",0777);
imagedestroy($im);





}


$datastream=file_get_contents(adminfile."files/tmpmap.png");
$datastream='data:image/png;base64,'.base64_encode($datastream);


?>
<form id="form1" name="form1" method="post" action="?">
<img src="<?php echo($datastream); ?>"/>
<table>
<tr><td bgcolor="#000000" width="25"></td>
<td>velikost:</td>
<td><input name="size" type="text" id="size" size="5" value="<?php echo($size); ?>" /></td></tr>

<tr><td bgcolor="#000000" width="25"></td>
<td>PodkladBG:</td>
<td><input name="firstbg" type="text" id="firstbg" size="5" value="<?php echo($firstbg); ?>" /></td></tr>

<tr><td bgcolor="#000000" width="25"></td>
<td>OstrovBG:</td>
<td><input name="secondbg" type="text" id="secondbg" size="5" value="<?php echo($secondbg); ?>" /></td></tr>

<tr><td bgcolor="#000000" height="25"></td>
<td>voda%:</td>
<td><input name="voda" type="text" id="voda" size="5" value="<?php echo($voda); ?>" /></td></tr>

<tr><td bgcolor="#000000" height="25"></td>
<td>zrnitost:</td>
<td><input name="rr" type="text" id="rr" size="5" value="<?php echo($rr); ?>" /></td></tr>

<tr><td bgcolor="#000000" height="25"></td>
<td>zrnitostX:</td>
<td><input name="tr" type="text" id="tr" size="5" value="<?php echo($tr); ?>" /></td></tr>

<tr><td bgcolor="#000000" height="25"></td>
<td>čtverec*:</td>
<td><input name="ru" type="text" id="ru" size="5" value="<?php echo($ru); ?>" /></td></tr>

<tr><td bgcolor="#000000" height="25"></td>
<td>délkaŘ:</td>
<td><input name="r_pocet" type="text" id="r_pocet" size="5" value="<?php echo($r_pocet); ?>" /></td></tr>

<tr><td bgcolor="#000000" height="25"></td>
<td>točivostŘ°:</td>
<td><input name="r_uhel" type="text" id="r_uhel" size="5" value="<?php echo($r_uhel); ?>" /></td></tr>

<tr><td bgcolor="#000000" height="25"></td>
<td>centralitaŘ*:</td>
<td><input name="r_uzemi" type="text" id="r_uzemi" size="5" value="<?php echo($r_uzemi); ?>" /></td></tr>


<tr><td bgcolor="#5299F9" height="25"></td>
<td>moře</td>
<td></td></tr>

<tr><td bgcolor="#545454" height="25"></td>
<td>dlažba</td>
<td><input name="t2" type="text" id="t2" size="5" value="<?php echo($t2); ?>" /></td></tr>

	
<tr><td bgcolor="#EFF7FB" height="25"></td>
<td>sníh/led</td>
<td><input name="t3" type="text" id="t3" size="5" value="<?php echo($t3); ?>" /></td></tr>
	
<tr><td bgcolor="#F9F98D" height="25"></td>
<td>písek</td>
<td><input name="t4" type="text" id="t4" size="5" value="<?php echo($t4); ?>" /></td></tr>
	
<tr><td bgcolor="#878787" height="25"></td>
<td>kamení</td>
<td><input name="t5" type="text" id="t5" size="5" value="<?php echo($t5); ?>" /></td></tr>

<tr><td bgcolor="#5A2F00" height="25"></td>
<td>hlína</td>
<td><input name="t6" type="text" id="t6" size="5" value="<?php echo($t6); ?>" /></td></tr>
	
<tr><td bgcolor="#DCDCAC" height="25"></td>
<td>sůl</td>
<td><input name="t7" type="text" id="t7" size="5" value="<?php echo($t7); ?>" /></td></tr>
	
<tr><td bgcolor="#2A7302" height="25"></td>
<td>tráva(normal)</td>
<td></td></tr>
	
<tr><td bgcolor="#51F311" height="25"></td>
<td>tráva(toxic)</td>
<td><input name="t9" type="text" id="t9" size="5" value="<?php echo($t9); ?>" /></td></tr>
	
<tr><td bgcolor="#535805" height="25"></td>
<td>les</td>
<td><input name="t10" type="text" id="t10" size="5" value="<?php echo($t10); ?>" /></td></tr>
	
<tr><td bgcolor="#337EFA" width="25"></td>
<td>řeka</td>
<td><input name="t11" type="text" id="t11" size="5" value="<?php echo($t11); ?>" /></td></tr>
	
<tr><td bgcolor="#8ABC02" height="25"></td>
<td>tráva(jaro)</td>
<td><input name="t12" type="text" id="t12" size="5" value="<?php echo($t12); ?>" /></td></tr>
	
<tr><td bgcolor="#8A9002" height="25"></td>
<td>tráva(pozim)</td>
<td><input name="t13" type="text" id="t13" size="5" value="<?php echo($t13); ?>" /></td></tr>



</table>
*&lt;0;1&gt;<br>
<input type="submit" name="submit" value="create" />
<!--
</form>
<br>
<form id="form1" name="form1" method="post" action="createmap.php?image=tmpmap.png&amp;action=start">
<input name="world" type="text" id="world" size="11" value="" /><br>
<input type="submit" name="submit" value="create" />
</form>
-->
<?php
/*echo("INSERT INTO towns2 (`xc` , `yc` , `obrazok` , `meno` , `rand` , `pozadie` , `vlastnik` , `cas` , `casovac` , `napis` , `uroven` , `budovanas` , `zivot` , `utokna` , `tmp`) VALUES<br/>");
$x = $size;
while($x > 0){
$y = $size;
while($y > 0){
//------------------------------------
$nod = ",";
if(1 == $x and 1 == $y){ $nod = ""; }
echo("('$x' , '$y' , '".$mapa[$x][$y]["obrazok"]."' , '' , '".rand(1,5)."' , '".$mapa[$x][$y] ."' , '1' , '1' , '0' , '' , '1' , '' , '100' , '0' , '')$nod<br/>");
//------------------------------------	
$y = $y - 1;	
}
$x = $x - 1;	
}*/
?>

