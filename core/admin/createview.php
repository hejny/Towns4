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
<a href="?page=createview&amp;create=1">Vygenerovat Velké (zoom=1)</a><br>
<a href="?page=createview&amp;create=0.7">Vygenerovat Normální (zoom=0.7)</a>
<hr>
<a href="?page=createview&amp;create=0.7&amp;createpost=test">Vygenerovat příspěvek (Test)</a><br>
<a href="?page=createview&amp;create=0.7&amp;createpost=1">Vygenerovat příspěvek =&gt;WP</a>
<hr>
<!--<a href="?page=createview&create=adjust&height=720">Vygenerovat HD</a><br>
<a href="?page=createview&create=adjust&height=1080">Vygenerovat FullHD</a><br>-->

<?php
//error_reporting(E_ALL);
ini_set("max_execution_time","1000");
ini_set('memory_limit','5000M');
set_time_limit(60);
require2("/func_map.php");
//---------------------



if($_GET['create']){
    
    if($_GET['createpost']){
        //---------------------------------------------------Zjištění, co se posílalo naposledy a co se bude posílat teď?
        $file=adminfile.'objects/lastposttype.txt';
        $lastposttype=file_get_contents($file);
        $posttype=$lastposttype;
        while($posttype==$lastposttype){
            $posttype=rand(1,7);   
        }
        fpc($file,$posttype);
        //---------------------------------------------------Zjištění, kdy se posílalo naposledy?
        $file=adminfile.'objects/lastview'.$posttype.'.txt';
        $time=time()-strtotime(file_get_contents($file));
        if($time==time())$time=3600*24;
        $time=$time-1+1;
        fpc($file,date(DATE_ATOM,time()));
        //---------------------------------------------------Zjištění posledního zobrazovaného města?
        $file=adminfile.'objects/lasttown.txt';
        $lasttown=file_get_contents($file)-1+1;
        //---------------------------------------------------Speciální zdroj
        if($posttype==1){
            //--------------------------------------Nejaktivnější město
                $id=sql_1data("SELECT own FROM `[mpx]pos_obj` WHERE type='building' AND starttime>".(time()-$time)." AND own!=$lasttown GROUP BY own ORDER BY COUNT(own) DESC LIMIT 1",3);

                if(!$id)return('žádné 1');
                
                if($id)$lasttown=$id;
                list($x,$y)=townid2xy($id);
                $x+=rand(-2,2);$y+=rand(-2,2);
                $name=town2name($id);

                $title=lr('view1_title',array('name'=>$name));

            //--------------------------------------
        }elseif($posttype==2){
            //--------------------------------------Nejvíce útočné
                $id=sql_1data("SELECT useid FROM [mpx]log WHERE function='attack' AND time>".(time()-$time)." AND useid!=$lasttown GROUP BY useid ORDER BY COUNT(useid) DESC LIMIT 1",3);

                if(!$id)return('žádné 2');
                
                if($id)$lasttown=$id;
                list($x,$y)=townid2xy($id);
                $x+=rand(-2,2);$y+=rand(-2,2);
                $name=town2name($id);

                $title=lr('view2_title',array('name'=>$name));

            //--------------------------------------
        }elseif($posttype>=3 and $posttype<=7){
            //--------------------------------------Náhodné místo na mapě              

                //$x=rand(0,mapsize);
                //$y=rand(0,mapsize);
                // AND x='$x' AND y='$y'
                
                $terrains=array(2,3,4,5,6,7,10,11);
                shuffle($terrains);
                $terrain='t'.$terrains[0];
                list(list($x,$y,$terrain))=sql_array("SELECT x,y,terrain FROM [mpx]map WHERE ww='".$GLOBALS['ss']['ww']."' AND terrain='$terrain' ORDER BY rand() LIMIT 1",3);
                $title=lr('terrain_'.$terrain);
                
                //$title=lr('view3_title',array('terrain'=>$terrain));

            //--------------------------------------
        }
        //---------------------------------------------------Uložení právě zobrazovaného města
        $file=adminfile.'objects/lasttown.txt';
        file_put_contents($file,$lasttown);
        //---------------------------------------------------
    }else{
    //------Testovací místo - náhoda
        $x=rand(0,mapsize);
        $y=rand(0,mapsize);
    //------
    }

    //$id=13889641;
    $xsize=3;
    $ysize=4;
    
    
    $s=$_GET['create']-1+1;
    $sf='s'.str_replace('.','o',$s).'xs'.$xsize.'ys'.$ysize;
    
    
    //---------------------------------------Zobrazovaná pozice
    
    if(!$x and !$y){//pokud není pozice v $x a $y doplní se z $id
        $destinationobject=new object($id);
        if(!$destinationobject->loaded)exit('chyba!');
        $x=$destinationobject->x;
        $y=$destinationobject->y;
        //$ww=$destinationobject->ww;
        unset($destinationobject);  
    }
    //r($x,$y);
    $x=round($x);
    $y=round($y);
    $xonmap=$x;
    $yonmap=$y;
    //---------------------------------------Přepočítání do jiného SS 
    $tmp=2;
    $xc=(-(($y-1)/10)+(($x-1)/10));
    $yc=((($y-1)/10)+(($x-1)/10));
    $xc=intval($xc)-1;
    $yc=intval($yc)-$tmp;
    //---------------------------------------
    

    $size=424;//424

    
    $img=imagecreatetruecolor($xsize*$size*$s*1.3,$ysize*$size*$s*0.5*1.185);
    //$img=imagecreate(($xm+$xm+1)*$size,($ym+1)*$size*0.5);
    //$img=imagecreatetruecolor(500,500);

    $yy=0;
    for($y=$yc; $y<=$yc+$ysize; $y++){$xx=0;
        for ($x=$xc; $x<=$xc+$xsize; $x++) {
            
    //for($y=0; $y<=$ym; $y++){$xx=0;
    //    for ($x=-$xm; $x<=$xm; $x++) {

            
            $file1=htmlmap($x,$y,1,true);
            $file2=htmlmap($x,$y,2,true,NULL,true);
            unlink($file2);
            
            ob_start();
            htmlmap($x,$y,NULL,NULL,NULL,true);
            ob_end_clean();
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
    $yy+($posuv*$s),

    0 , 0 ,  ceil($size*$s) ,  ceil(($size*0.5+1)*$s) , imagesx($part),imagesy($part) /*$size ,  $size*0.5 */);
                    imagedestroy($part);

            }
            /*width="<? echo(ceil($size)); ?>" border="0" style="position: absolute;top:<? echo($y*$size*0.5); ?>px;left:<? echo(($x*$size)+($screen/2)-($size/2)); ?>px;"/>*/
                    $xx+=ceil($size*$s);
        }
        $yy+=ceil($size*$s*0.5);
    }
    /*header("Content-type: image/png");
    imagepng($img);*/
    //r($img);

    mkdir(adminfile.'files/view');
    chmod(adminfile.'files/view',0777);
    $file=adminfile.'files/view/'.w.'ww'.$GLOBALS['ss']["ww"].$sf.'x'.$x.'y'.$y.'t'.time().'d'.date('j').'m'.date('n').'y'.date('Y').'.jpg';

    //r($img);

    imagejpeg($img,$file,90);
    chmod($file,0777);
    imagedestroy($img);


    echo('<br/><b>uloženo do <a href="../../'.$file.'" target="_blank">'.$file.'</a></b>');

    if($title){
        
        $file=str_replace(array('[world]/',w.'/'),'',$GLOBALS['inc']['url']).$file;
        
        $mapurl=$GLOBALS['inc']['url'].$xonmap.','.$yonmap;
        $text.=textbr(lr('view_onmap')).'<a href="'.$mapurl.'" target="_blank">'.$mapurl.'</a>';
        $text.='<br/>';
        $text.='<img src="'.$file.'">';
        

        if($_GET['createpost']==1){
            if($GLOBALS['inc']['wp_xmlrpc']){
                $result=wppost($title,$text,$GLOBALS['inc']['wp_categories_view']);
                br();
                e($result);
                //print_r($result);
            }
        }
        
        //----------------------------------------
        
        br(2);
        textb('TITLE: ');
        e($title);
        br();
        textb('TEXT: ');br();
        e($text);
        
    }

   

}


//----------------------------------------------------------NEWOLD

/*$screen=1270;
$ym=11;
$xm=8;

$xmp=1;
//echo($xm);
$ym=$ym-1;$xm=$xm-1;$xm=$xm/2;
$size=$screen/($xm+$xm+1);//750;

$width=$xm*424;
$height=$xm*212;
$img=  imagecreatetruecolor($width, $height);
foreach(array(1,2) as $type){
    $y=0;
    for($y=$yc; $y<=$ym+$yc; $y++){
        $x=0;
        for ($x=-$xm+$xc; $x<=$xm+$xc+$xmp; $x++) {


                $width=round(424/$zoom);
                $height=round(211/$zoom);

                if($type=1){
                    $file=htmlmap($x,$y,1,true);

                }else{
                    $file=htmlmap($x,$y,2,true);
                }
                if(strpos($file,'.png')){
                    $part=imagecreatefrompng($file);
                }else{
                    $part=imagecreatefromjpeg($file);
                }
                e($file);
                r($part);
                imagecopy($img, $part, $x*424, $y*212, 0, 0, 424, 212);
                imagedestroy($part);
                //echo($file);
                //br();
                
            $x++;
        }
        $y++;
    }
}*/

//----------------------------------------------------------


/*mkdir(adminfile.'files/glob');
chmod(adminfile.'files/glob',0777);
$file=adminfile.'files/glob/'.w.'_ww'.$GLOBALS['ss']["ww"].'_'.$sf.'_'.time().'_'.date('j_n_Y').'.png';

imagepng($img,$file,png_quality,png_filters);
chmod($file,0777);
imagedestroy($img);
echo('<br/><b>uloženo do <a href="../../'.$file.'" target="_blank">'.$file.'</a></b>');*/
?>
