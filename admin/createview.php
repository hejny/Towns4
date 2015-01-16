<?php
/* Towns4Admin, www.towns.cz 
   © Pavel Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================


ob_end_flush();

?>
<h3>CreateView</h3>
Vykreslení náhledu mapy do jednoho obrázku<br/>
<b>Upozornění: </b>Tento proces může trvat i několik minut.<br/>
<br>
<a href="?page=createglob&start=1">Smazat tmp/mapunitsb, spustit CreateTmp</a><br>
<a href="?page=createglob&create=1">Vygenerovat Giga (zoom=1)</a><br>
<a href="?page=createglob&create=0.5">Vygenerovat Velké (zoom=0.5)</a><br>
<a href="?page=createglob&create=0.1">Vygenerovat Malé (zoom=0.1)</a><br>
<a href="?page=createglob&create=adjust&height=720">Vygenerovat HD</a><br>
<a href="?page=createglob&create=adjust&height=1080">Vygenerovat FullHD</a><br>

<?php
//error_reporting(E_ALL);
ini_set("max_execution_time","1000");
ini_set('memory_limit','5000M');
set_time_limit(60);
require2("/func_map.php");
//---------------------


if($_GET['create']){


if($_GET['create']=='adjust'){
	/*if($_GET['height']){
	$hh=$_GET['height']-1+1;
	$mapsize=mapsize;
	$size=424;
	$ym=$mapsize/5-1;
	$h=((($ym+1)*$size*0.5))*1;

	$s=($hh/$h);
	$sf='h'.$hh;

	}else{
		die('!height');
	}*/
}else{
    $s=$_GET['create']-1+1;
    $sf='s'.$s;
}

//----------------------------------------------------------

$screen=1270;

$ym=11;
$xm=8;

$xmp=1;
//echo($xm);
$ym=$ym-1;$xm=$xm-1;$xm=$xm/2;
$size=$screen/($xm+$xm+1);//750;


for($y=$yc; $y<=$ym+$yc; $y++){
    for ($x=-$xm+$xc; $x<=$xm+$xc+$xmp; $x++) {


            $width=round(424/$zoom);
            $height=round(211/$zoom);

            if($type=1){
                $file.=htmlmap($x,$y,1,true/*,$y-$yc,$_GLOBALS['map_night']*/);
            
            }else{
                
            
                $file.=htmlmap($x,$y,2,true/*,$y-$yc,$_GLOBALS['map_night']*/);
            }
            
            echo($file);
            br();

        $ad=("</td>");$stream1.=$ad;$stream2.=$ad;$stream3.=$ad;
    }
    $ad=("</tr>");$stream1.=$ad;$stream2.=$ad;$stream3.=$ad;
}
$ad=("</table>");$stream1.=$ad;$stream2.=$ad;$stream3.=$ad;/**/

//----------------------------------------------------------


/*mkdir(adminfile.'files/glob');
chmod(adminfile.'files/glob',0777);
$file=adminfile.'files/glob/'.w.'_ww'.$GLOBALS['ss']["ww"].'_'.$sf.'_'.time().'_'.date('j_n_Y').'.png';

imagepng($img,$file,png_quality,png_filters);
chmod($file,0777);
imagedestroy($img);
echo('<br/><b>uloženo do <a href="../../'.$file.'" target="_blank">'.$file.'</a></b>');*/
}
?>
