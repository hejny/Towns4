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
<h3>CreateGlob</h3>
Vykreslení celé mapy (<?php e(mapsize.'x'.mapsize); ?>) do jednoho obrázku<br/>
<b>Upozornění: </b>Tuto funkci spouštějte až po vygenerování všech pomocných podkladů!<br/>
<b>Upozornění: </b>Tuto funkci spouštějte až po "opravě mapy", nevypálené budovy se nebudou zpracovávat!<br/>
<b>Upozornění: </b>Tento proces může trvat i několik minut.<br/>
<?php
if($_GET['start']==1){

		emptydir(root.cache.'/mapunitsb');
		
		if(!$croncron){
			echo('<script language="javascript">
    window.location.replace("?world='.w.'&page=createtmp&start=1");
    </script>');
		}

		echo('smazáno<br>');
}
?>
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
//---------------------


if($_GET['create']){


/*if($_GET['create']==1){
 $s=0.5;
}else{
 $s=0.1;
}*/
if($_GET['create']=='adjust'){
	if($_GET['height']){
	$hh=$_GET['height']-1+1;
	$mapsize=mapsize;
	$size=424;
	$ym=$mapsize/5-1;
	$h=((($ym+1)*$size*0.5))*1;

	$s=($hh/$h);
	$sf='h'.$hh;

	}else{
		die('!height');
	}
}else{
 $s=$_GET['create']-1+1;
 $sf='s'.$s;
}

$mapsize=mapsize;
$size=424;//424
$ym=$mapsize/5-1;
$xm=ceil(($mapsize/5-1)/2);
//echo("imagecreate(($xm+$xm+1)*$size,$xm*$size*0.5);");

$w=((($xm+$xm+1)*$size))*$s;
$h=((($ym+1)*$size*0.5))*$s;
//die($w.' x '.$h);
$img=imagecreatetruecolor($w,$h);
//$img=imagecreate(($xm+$xm+1)*$size,($ym+1)*$size*0.5);
//$img=imagecreatetruecolor(500,500);


for($y=0; $y<=$ym; $y++){$xx=0;
    for ($x=-$xm; $x<=$xm; $x++) {
        
	
	$file1=htmlmap($x,$y,1,true);
	$file2=htmlmap($x,$y,2,true,NULL,true);
	//r($file2);
	//$file=tmpfile2("outimg,".size.",".zoom.",".$x.",".$y.",".w.",".gird.",".t_sofb.",".t_pofb.",".t_brdcc.",".t_brdca.",".t_brdcb.','.$GLOBALS['ss']["ww"],'jpg','map');
	
        //die($file."aaa<br>");
        //$file=file_get_contents($file);

	$posuv=htmlunitc-htmlbgc+$top;
	foreach(array(array($file1,0),array($file2,$posuv)) as $tmp){list($file,$posuv)=$tmp;
        	$part=imagecreatefromstring(file_get_contents($file));
        	imagecopyresampled ($img,$part,

//((($x*$size)+(imagesx($img)/2)+($size)))*$s+(imagesx($img)*$s*0.5),
$xx,
(($y*$size*0.5)+$posuv)*$s,

0 , 0 ,  ceil($size*$s) ,  ceil(($size*0.5+1)*$s) , imagesx($part),imagesy($part) /*$size ,  $size*0.5 */);
        	imagedestroy($part);
			
	}
        /*width="<? echo(ceil($size)); ?>" border="0" style="position: absolute;top:<? echo($y*$size*0.5); ?>px;left:<? echo(($x*$size)+($screen/2)-($size/2)); ?>px;"/>*/
		$xx+=ceil($size*$s);
    }
}
/*header("Content-type: image/png");
imagepng($img);*/
//r($img);

mkdir(adminfile.'files/glob');
chmod(adminfile.'files/glob',0777);
$file=adminfile.'files/glob/'.w.'_ww'.$GLOBALS['ss']["ww"].'_'.$sf/*($_GET['create']==2?'_small':'_big')*/.'_'.time().'_'.date('j_n_Y').'.png';

imagepng($img,$file,png_quality,png_filters);
chmod($file,0777);
imagedestroy($img);
echo('<br/><b>uloženo do <a href="../../'.$file.'" target="_blank">'.$file.'</a></b>');
}
?>
