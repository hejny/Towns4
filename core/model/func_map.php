<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2015
   _____________________________

   core/func_map.php

   php funkce pro vykreslení mapových podkladů
*/
//==============================




ini_set("max_execution_time","1000");
//=============================================================MAPCONFIG
$zoom=$GLOBALS['mapzoom'];
if(!$zoom)$zoom=1;

define("height",212/*$zoom*/ /*1.3*/);//výška html bloku
//---------------------------
define("grid",0);
define("t_brdcc",0.3);//počet kuliček
define("t_brdca",2);//6;//min radius kule
define("t_brdcb",10);//8;//max radius kule
define("t_brdcr",1.62);//8;//poměr šířka/výška kule
define("t_pofb",1);//přesah okrajů
define("t_sofb",100);//velikost bloku z průvodního obrázku

define("height2",height*1.3);
define("png_height",height*1.1);
define("size2",0.75*(height2/375));
//--------level
define('lvl',0);//Zatím je terén vypnutý
define("clvl",35);
define("sun",0.5);
define("sunc",15);

if(lvl){
    define('htmlbgc',60/*-70*/);
    define('htmlunitc',0/*-60-250*/);
}else{
    
    if(logged()){
        define('htmlbgc',60);
        define('htmlunitc',0);
    }else{
        define('htmlbgc',0);
        define('htmlunitc',-60);
    }
}
//---------------------------quality
define('jpg_quality',50);//60
define('png_quality','9');//8
define('png_filters',PNG_ALL_FILTERS);
define('dither',true);
define('png_colors',64);
//---------------------------
$GLOBALS['t2lvl']=array(
   't1' =>0,
   't2' =>40,
   't3' =>15,
   't4' =>16,
   't5' =>50,
   't6' =>70,
   't7' =>15,
   't8' =>30,
   't9' =>25,
   't10'=>50,
   't11'=>10,
   't12'=>27,
   't13'=>30
);
$GLOBALS['t2lvlexp']=3;
$GLOBALS['t2rockkk']=2;
//$GLOBALS['rock2lvl']=100;
//---------------------------NENASTAVITELNÉ
define("size",height/212);
//define("size",0.75*(height/375));
define("zoom",5);

define("nob",true);
define("t_",implode(',',array(height,t_brdcc,t_brdcc,t_brdca,t_brdcb,t_pofb,t_sofb,size,zoom,grid,clvl,sun,sunc,lvl)).'-'.implode(',',$GLOBALS['t2lvl']));
//=============================================================
function imgresizeh($img,$height) {
      $ratio = $height / imagesy($img);
      $width = imagesx($img)* $ratio;
      return(imgresize($img,$width,$height));
   }
 
   function imgresizew($img,$width) {
      $ratio = $width / imagesx($img);
      $height = imagesy($img) * $ratio;
      return(imgresize($img,$width,$height));
   }

    function imgresizewurl($url,$width) {

        $ext = pathinfo($url, PATHINFO_EXTENSION);
        $ext=strtolower($ext);
        $file=tmpfile2(array($url,$width),$ext,'imgresizewurl');
        if(!file_exists($file)/** or 1/**/) {
            if($pos=strpos($url,'userdata')){
                $url=substr($url,$pos);
            }
            $res = imagecreatefromstring(fgc($url));
            //r($res);
            $res=imgresizew($res,$width);
            //r($res);
            switch($ext){
                case 'jpg':
                case 'jpeg':
                case 'bmp':
                    imagejpeg($res,$file);
                    break;
                case 'png':
                    imagepng($res,$file);
                case 'gif':
                    imagegif($res,$file);
                    break;
                default:
                    imagesavealpha($res,true);
                    imagepng($res,$file);
                    break;
            }
            chmod($file,0777);


        }
        $file=rebase(url.'/../'.$file);
        return($file);
    }


    function imgresize($img,$width,$height) {
      $new_image = imagecreatetruecolor($width, $height);
      imagealphablending($new_image,false);
      imagecopyresampled($new_image, $img, 0, 0, 0, 0, $width, $height, imagesx($img), imagesy($img));
      return($new_image);
   } 

   function imgresizecrop($img,$width,$height,$x,$y,$w,$h) {
      $new_image = imagecreatetruecolor($width, $height);
      imagealphablending($new_image,false);
      imagecopyresampled($new_image, $img, 0, 0, $x,$y, $width, $height, $w,$h);
      return($new_image);
   } 
//----------------------------------------------------------------------------------------------------------------------MAP1
//=============================================================
/*function mapdata($xc=xc,$yc=yc,$wtf="terrain"){
    //$xc=xc;
    //$yc=yc;
    $w=w;
    $zoom=zoom;
    //die("SELECT x,y,$wtf from `map` WHERE `w`=$w AND `x`>=$xc AND `y`>=$yc AND `x`<$xc+$zoom+2 AND `y`< $yc+$zoom+2 ORDER by `y`,`x`");
    $mapd=sql_array("SELECT x,y,$wtf from `[mpx]map` WHERE ww=".$GLOBALS['ss']["ww"]." AND `x`>=$xc AND `y`>=$yc AND `x`<$xc+$zoom AND `y`< $yc+$zoom ORDER by `y`,`x`");
    $map=array();
    //r($mapd);exit;
    foreach($mapd as $row){list($x,$y,$wtf)=$row;
        $x=$x-$xc;
        $y=$y-$yc;
        if(!$map[$y]){$map[$y]=array();}
        $map[$y][$x]=$wtf;
    }
    return($map);
}*/
//=============================================================
function map1($param,$xc=false,$yc=false,$outfile=false){
    //echo($param);
    if(!$param){$param=sql_1data("SELECT res from `[mpx]pos_obj` WHERE `type`='terrain' AND ww=".$GLOBALS['ss']["ww"]." AND `x`=1 AND `y`=1");}
    if($xc===false or $yc===false){
        $rand=rand(1,7);
    }else{
        $rand=((pow($xc,2)+pow($yc,3))%7)+1;
    }
    //echo($rand);
    
    $t_size=size*424/5;
    $t_sofb=t_sofb;
    $t_pofb=t_pofb;
    $t_brdcc=t_brdcc;//počet kuliček
    $t_brdca=t_brdca;//10;//min radius kule
    $t_brdcb=t_brdcb;//15;//max radius kule
    $file=tmpfile2("$rand,$param,".t_,"png","map");
    //------------------------------
    $kk=($param=='t2'?$GLOBALS['t2rockkk']:1);
    //$kk=2;
    if(file_exists($file) and !notmp /**and false/**/){
        $terrain=imagecreatefrompng($file);
    }else{
//echo(root."data/image/terrain/$param.png");
            //--------------------------2D
            $tmp=imagecreatefrompng(root."ui/image/terrain/$param.png");
            $tmpb=(1+(2*$t_pofb));
            $maxx=imagesx($tmp)-($t_sofb*$tmpb);
            $maxy=imagesy($tmp)-($t_sofb*$tmpb);
            $xt=rand(0,$maxx);
            $yt=rand(0,$maxy);
                $terrain=imagecreatetruecolor($t_size*$tmpb,$t_size*$tmpb/2);
                $terrain2=imagecreatetruecolor($t_size*$tmpb,$t_size*$tmpb);
                imagealphablending($terrain,false);
                //echo("imagecopy($terrain,$tmp,0,0,$xt,$yt,$t_size*$tmpb,$t_size*$tmpb)");
                  $alpha = imagecolorallocatealpha($terrain, 0, 0, 0,127);
                  imagefill($terrain,0,0,$alpha);
                  //imagecopy($terrain,$tmp,0,0,$xt,$yt,$t_size*$tmpb,$t_size*$tmpb);
                  //r($tmp);
                imagecopy($terrain2,$tmp,0,0,$xt,$yt,$t_size*$tmpb,$t_size*$tmpb);
                //r($terrain2);
                //$black = imagecolorallocate($terrain, 0, 0, 0);
                //imagestring ($terrain , 23 ,  1,  1 ,  "hovno" ,  $alpha );
                $tmps=imagesx($terrain2);$tmps2=$tmps/2;
                for ($i=1; $t_brdcc*$tmps*$tmps>$i; $i++){
                    $ytmp=rand(0,$tmps-1);
                    $xtmp=rand(0,$tmps-1);
                    $dist=pow(pow(abs($tmps2-$ytmp),2)+pow(abs($tmps2-$xtmp),2),1/2);
                    $alpha=$dist/($tmps2*1);
                    if($alpha>1){$alpha=1;}
                    $radiusx=rand($t_brdca,$t_brdcb);
                    $radiusy=rand($t_brdca,$t_brdcb);
                    $rgb = imagecolorat($terrain2, round($xtmp),round($ytmp));
                    $r = ($rgb >> 16) & 0xFF;
                    $g = ($rgb >> 8) & 0xFF;
                    $b = $rgb & 0xFF;
                    $a=intval((1-(1-$alpha)*$kk)*127);
                    if($a<1)$a=1;if($a>127)$a=127;
                    $alpha = imagecolorallocatealpha($terrain, $r, $g, $b,$a);
                    //imagesetpixel($terrain,$xtmp,$ytmp,$alpha);
                    imagefilledellipse($terrain,$xtmp,$ytmp/2,$radiusx,$radiusy/t_brdcr,$alpha);
                    //imagefilledellipse($terrain,$xtmp,$ytmp,$radiusx,$radiusy,$alpha); 
    /**/
                }
                if(grid){
                    $black = imagecolorallocate($terrain, 0, 0, 0);
                    
                    $offsetx=imagesx($terrain)/(1+(2*$t_pofb))*$t_pofb;
                    $offsety=imagesy($terrain)/(1+(2*$t_pofb))*$t_pofb;
                    imageline($terrain,imagesx($terrain)/2,$offsety,$offsetx,imagesy($terrain)/2,$black);
                    imageline($terrain,$offsetx,imagesy($terrain)/2,imagesx($terrain)/2,imagesy($terrain)-$offsety,$black);
                    imageline($terrain,imagesx($terrain)/2,$offsety,imagesx($terrain)-$offsetx,imagesy($terrain)/2,$black);
                    imageline($terrain,imagesx($terrain)-$offsetx,imagesy($terrain)/2,imagesx($terrain)/2,imagesy($terrain)-$offsety,$black);
                }
                
        //--------------------------3D               
        //--------------------------Save       
        imagedestroy($terrain2);
        imagedestroy($tmp);
        imagesavealpha($terrain,true);
        imagepng($terrain,$file);
	//die($file);
        chmod($file,0777);
    }
    if($outfile){
	return($file);
    }else{
	return($terrain);
    }
}
/*r(map1('t2'));
exit;

r(map1('t1'));
header("Content-type: image/png");
imagepng(map1('t1'));
exit;*/
//-------------------------------------------------t2rgb
function t2rgb($param=0){
    
    //echo($param);
    if(!$param){$param='t1';}
    
    if(!$GLOBALS['t2rgb'])$GLOBALS['t2rgb']=array();
    
    if(!$GLOBALS['t2rgb'][$param]){

        $tmp=imagecreatefrompng(root."ui/image/terrain/$param.png");

        $r=0;$g=0;$b=0;

        for($y=1;$y<imagesy($tmp);$y++){
            for($x=1;$x<imagesx($tmp);$x++){
                 $rgb = imagecolorat($tmp, $x, $y);
                 $rr = ($rgb >> 16) & 0xFF;
                 $gg = ($rgb >> 8) & 0xFF;
                 $bb = $rgb & 0xFF;

                 $q=imagesx($tmp)*imagesy($tmp);

                 $r+=$rr/$q;
                 $g+=$gg/$q;
                 $b+=$bb/$q;


            }
        }

        $r=intval($r);
        $g=intval($g);
        $b=intval($b);
        
        $GLOBALS['t2rgb'][$param]=array($r,$g,$b);
    }
    
    
    return($GLOBALS['t2rgb'][$param]);
}
//echo('>>');
//r(t2rgb());
//exit2();
//----------------------------------------------------------------------------------------------------------------------MODELS
//==========================================================================================================================MODEL
require_once(root.core."/model/model.php");
//======================================================================================polyrand
function polyrand($seed,$amplitude,$periode,$x,$y){

		$detail=11;
                
                $file=tmpfile2('polyrand-'.$seed,'txt','seed');
                //echo($file);
                if(!$GLOBALS/*['ss']*/['polyseed'][$seed]){
                    if(file_exists($file) /**and false/**/){
                            $contents=file_get_contents($file);
                            $GLOBALS/*['ss']*/['polyseed'][$seed]=unserialize($contents);
                    }else{
                    //if(!$GLOBALS/*['ss']*/['polyseed'][$seed]){

                            //foreach(array('x','y') as $dim){
                                    $i=0;while($i<$detail){$i++;
                                            $GLOBALS/*['ss']*/['polyseed'][$seed]/*[$dim]*/[$i][0]=rand(0,$amplitude*100)/300;//amplituda
                                            $GLOBALS/*['ss']*/['polyseed'][$seed]/*[$dim]*/[$i][1]=rand($periode*100,$periode*1000)/100;//perioda
                                            $GLOBALS/*['ss']*/['polyseed'][$seed]/*[$dim]*/[$i][2]=rand(0,$periode*100)/100;//posuv
                                            $GLOBALS/*['ss']*/['polyseed'][$seed]/*[$dim]*/[$i][3]=rand(0,100)/100;//váha X	
                                            $GLOBALS/*['ss']*/['polyseed'][$seed]/*[$dim]*/[$i][4]=rand(0,100)/100;//váhaY	
                                    }
                            //}
                            $contents=serialize($GLOBALS/*['ss']*/['polyseed'][$seed]);
                            fpc($file, $contents);
                    }
                }
		//--------
		//$zx=0;$zy=0;
                //$zdx=0;$zdy=0;
                $z=0;$zd=0;
                
		//foreach(array('x','y') as $dim){
			$i=0;while($i<$detail){$i++;
				$p1=$GLOBALS/*['ss']*/['polyseed'][$seed]/*[$dim]*/[$i][0];//amplituda
				$p2=$GLOBALS/*['ss']*/['polyseed'][$seed]/*[$dim]*/[$i][1];//perioda
				$p3=$GLOBALS/*['ss']*/['polyseed'][$seed]/*[$dim]*/[$i][2];//posuv	
                                $p4=$GLOBALS/*['ss']*/['polyseed'][$seed]/*[$dim]*/[$i][3];//váha X	
                                $p5=$GLOBALS/*['ss']*/['polyseed'][$seed]/*[$dim]*/[$i][4];//váhaY
                                
                                /*if($dim=='x'){
                                    $zx=$zx+(sin(($x*$p2)+$p3));
                                    $zdx=$zdx+(cos(($x*$p2)+$p3));
                                }else{
                                    $zy=$zy+(sin(($y*$p2)+$p3));
                                    //$zdy=$zdy+(cos(($y*$p2)+$p3));
                                }*/
                                $z=$z+(sin(((($x*$p4)+($y*$p5))*$p2)+$p3)*$p1);
                                $zd=$zd+(cos(((($x*$p4)+($y*$p5))*$p2)+$p3)*$p1);
                                

			}
		//}
                //echo($zy.',');
                //$z=$zx*$zy*$p1;
                //$zd=$zdx*$p1;
                
		//$range=$to-$from;
		
		//$z=intval($z)%$range;
		//$z=$z+$from;
                
                $GLOBALS['dt']=$zd;
		return($z);
	}
//   //======================================================================================rgb2lvl
function rgb2lvl($r,$g,$b){
    //clvl,sunc
    //t2rgb,t2lvl
    $lvl=0;
    $sum=0;
    
    foreach($GLOBALS['t2lvl'] as $t=>$lvl2){
        list($rr,$gg,$bb)=t2rgb($t);
        
        //$gs2=1/(abs($rr-$gg)+abs($gg-$bb)+abs($bb-$rr)+1);
        
        
        $rate=1/(1+pow(pow(abs($r-$rr),$GLOBALS['t2lvlexp'])+pow(abs($g-$gg),$GLOBALS['t2lvlexp'])+pow(abs($b-$bb),$GLOBALS['t2lvlexp']),1/$GLOBALS['t2lvlexp']));
        
        
        
        
        $sum+=$rate;
        $lvl+=$lvl2*$rate;
        
        
    }
    //$gs=1/(abs($r-$g)+abs($g-$b)+abs($b-$r)+0.001);
    //$sum+=$gs;
    //$lvl+=$GLOBALS['rock2lvl']*$gs;
    
    
    $lvl=$lvl/$sum;
    
    
    
    $lvl=intval($lvl);
    
    return($lvl);
}
//----------------------------------------------------------------------------------------------------------------------STARE PREDEL MORE, PEVNINY A TEMNOTY

/*function tborder($im){//return($im);

	$k=-0.1;
	$q=0.1;
	$temno=gr-1;//0.5;
	$temnox=1;//1.1;//0.5;
	$tlimit=30;
	$limit=(imagesx($im)*imagesy($im))+10000;

	//-------------------

	//for($x = 0; $x!=imagesx($im); $x++){
	$x=0;
	while($x!=imagesx($im) and $limit>0){$limit--;
    
        //for($y = 0; $y!=imagesy($im); $y++){
		$y=0;$i=0;
		while($y!=imagesy($im) and $limit>0){$limit--;
                $rgb=imagecolorsforindex($im,imagecolorat($im, $x,$y));


                $r=$rgb["red"];
                $g=$rgb["green"];
                $b=$rgb["blue"];
                
		/*$r=$tmp;
		$r=$g;
		$g=$tmp;* /
		if($b>$r*gr or $b>$g*gr){
			$b=$b*gr;
		}

		
		//---------------------------more


				$r2=($r*(1-$q-$q))+($g*$q)+($b*$q);
				$g2=($r*$q)+($g*(1-$q-$q))+($b*$q);
				$b2=($r*$k)+($g*$k)+($b*(1-$k-$k));


				if($r2<0)$r2=0;if($r2>255)$r2=255;
				if($g2<0)$g2=0;if($g2>255)$g2=255;
				if($b2<0)$b2=0;if($b2>255)$b2=255;

			$r=$r2;
            $g=$g2;
            $b=$b2;
		//---------------------------temnota

				/*$k=-0.1;
				$q=0.1;
				$r2=($r*(1-$q-$q))+($g*$q)+($b*$q);
				$g2=($r*$q)+($g*(1-$q-$q))+($b*$q);
				$b2=($r*$k)+($g*$k)+($b*(1-$k-$k));


				if($r2<0)$r2=0;if($r2>255)$r2=255;
				if($g2<0)$g2=0;if($g2>255)$g2=255;
				if($b2<0)$b2=0;if($b2>255)$b2=255;* /
				if($r+$g+$b<$tlimit){
					
					//$r2=0;
					//$g2=0;
					//$b2=0;
	
						$vt=true;
						$i=0;
						while($vt and $y!=imagesy($im) and $limit>0){$limit--;
							$i++;
							$x2=$x;
							$y2=$y;
							$r3=pow((($r*2+20)/pow($i,$temno))/255,$temnox)*255;
							$g3=pow((($g*2+10)/pow($i,$temno))/255,$temnox)*255;
							$b3=pow(($b*2/pow($i,$temno))/255,$temnox)*255;
							$tmpcolor=imagecolorallocate($im,$r3,$g3,$b3);
							imagesetpixel($im,$x2,$y2,$tmpcolor);
							imagecolordeallocate($tmpcolor);

							$rgb=imagecolorsforindex($im,imagecolorat($im, $x,$y+5));

							$y++;
							if($rgb["red"]+$rgb["green"]+$rgb["blue"]<$tlimit){
								$vt=true;
							}else{
								$vt=false;
								break;
								$i=0;
							}

						}
				}else{

		//---------------------------

			$tmpcolor=imagecolorallocate($im,$r2,$g2,$b2);
			imagesetpixel($im,$x,$y,$tmpcolor);
			imagecolordeallocate($tmpcolor);
			$y++;

		}



        }
	 $x++;
    }
    return($im);
}*/
//----------------------------------------------------------------------------------------------------------------------NOVE PREDEL MORE, PEVNINY A TEMNOTY

function tborder($im){//return($im);

	$k=-0.1;
	$q=0.1;
	$py=50;
	$tlimit=40;
	$fall=1;
	$fally=1;
	$rmax=135;$gmax=188;$bmax=255;
	$limit=(imagesx($im)*imagesy($im))+10000;

	$im2=imagecreatetruecolor(imagesx($im),imagesy($im));
	imagecopy($im2,$im,0,0,0,0,imagesx($im),imagesy($im));

	//-------------------

	//for($x = 0; $x!=imagesx($im); $x++){
	$x=0;
	while($x!=imagesx($im) and $limit>0){$limit--;
    
        //for($y = 0; $y!=imagesy($im); $y++){
		$y=0;$i=0;
		while($y!=imagesy($im) and $limit>0){$limit--;
                $rgb=imagecolorsforindex($im,imagecolorat($im, $x,$y));


                $r=$rgb["red"];
                $g=$rgb["green"];
                $b=$rgb["blue"];
                
		/*$r=$tmp;
		$r=$g;
		$g=$tmp;*/
		if($b>$r*gr or $b>$g*gr){
			$b=$b*gr;
		}

		
		//---------------------------more


		$r2=($r*(1-$q-$q))+($g*$q)+($b*$q);
		$g2=($r*$q)+($g*(1-$q-$q))+($b*$q);
		$b2=($r*$k)+($g*$k)+($b*(1-$k-$k));


		if($r2<0)$r2=0;if($r2>255)$r2=255;
		if($g2<0)$g2=0;if($g2>255)$g2=255;
		if($b2<0)$b2=0;if($b2>255)$b2=255;

		$r=$r2;
        $g=$g2;
        $b=$b2;

		/*if($b>$r and $b>$g){
		if($r+$g+$b>$rmax+$gmax+$bmax){ $rmax=$r;$gmax=$g;$bmax=$b; }
		}*/
		//---------------------------temnota

		$x2=$x;
		$y2=$y;

		if($r+$g+$b<$tlimit){
			
			$down=$tlimit-($r+$g+$b);

			$downy=round(pow($down,$fally));

			$x2=$x;
			$y2=$y+$downy;

			//$r=($r+50)/$down;
		    //$g=($g)/$down;
		    //$b=($b)/$down;

			//$r=100;
			if($b>$r+2 and $b>$g+2){
				$vocas=15;
				$g=pow($g*gr,1)*gr*1-pow($down,$fall);
				$b=pow($b*gr,1)*gr*1-pow($down,$fall);
				$r=pow($r*gr,1)*gr*1-pow($down,$fall);

			}else{
				$vocas=0;
				$g=pow($g/2,1)/2-pow($down,$fall);
				$b=pow($b,gr)/2-pow($down,$fall);
				$r=pow($r/2,1)*gr-pow($down,$fall)+10;
			}
			
		}else{
			$vocas=0;
			/*if($b>$r and $b>$g){

				if($r+$g+$b+100<$rmax+$gmax+$bmax){

					$sum=($r+$g+$b);
					$sumax=($rmax+$gmax+$bmax);

					$r=$r/$sum*$sumax/gr;
					$g=$g/$sum*$sumax/gr;
					$b=$b/$sum*$sumax/gr;
					//$r=($r+$rmax)/2;//-pow($down,$fall);
					//$g=($g+$gmax)/2;//-pow($down,$fall);
					//$b=($b+$bmax)/2;//-pow($down,$fall);
				}
			}*/
		}

		//---------------------------

		if($r<0)$r=0;
		if($g<0)$g=0;
		if($b<0)$b=0;
		$tmpcolor=imagecolorallocate($im2,round($r),round($g),round($b));
		//if($x==$x2 and $y==$y2){	
		//	imagesetpixel($im2,$x2,$y2,$tmpcolor);
		//}else{
			imageline($im2,$x2,$y2-$vocas,$x2,$y2+$py-$vocas,$tmpcolor);
		//}
		imagecolordeallocate($tmpcolor);
		$y++;



        }
	 $x++;
    }
    return($im2);
}


//----------------------------------------------------------------------------------------------------------------------PROPOJENI
function mapbg($xc,$yc){
    //echo("$xc,$yc");
    define("xx",0);
    define("yy",0);
    //define("top",200*(height/375));
    $t_pofb=t_pofb;
    //---------------------------------
        $size=1;
        $width=height*2;//150*5*(height/375);
        $height=height;//75*5*(height/375);
        $img=imagecreatetruecolor($width,$height*(lvl?(7/5):1));
        //$black=imagecolorallocate($img, 0, 0, 0);
        $white=imagecolorallocate($img, 255, 255, 255);
        imagefill($img,0,0,$white);
        //imageantialias($img, true);
        
        
        //$xc=5*($y+$x)+1;//-(($gy-1)/10)+(($gx-1)/10);
        //$yc=5*($y-$x)+1;//(($gy-1)/10)+(($gx-1)/10);
        $zoom=5;
        $exp=4;
        $pos=4.5;
        $lvlexp=0;
        
        //--------------------
        $data=array();
        for($y=0;$y<($yc+$zoom+$exp+$pos)-($yc-$exp-$pos);$y++){
            $data[$y]=array();
            for($x=0;$x<($xc+$zoom+$exp+$pos)-($xc-$exp-$pos);$x++){
                $data[$y][$x]='';  
            }    
        }        
        //--------------------
           
        $array=sql_array("SELECT x,y,res from `[mpx]pos_obj` WHERE `type`='terrain' AND ww=".$GLOBALS['ss']["ww"]." AND `x`>=".round($xc-$exp-$pos)." AND `y`>=".round($yc-$exp-$pos)." AND `x`<".round($xc+$zoom+$exp+$pos+$lvlexp)." AND `y`<".round($yc+$zoom+$exp+$pos+$lvlexp)." ORDER by `y`,`x`");
        
        
        foreach($array as $row){
            list($x,$y,$terrain)=$row;
            //$terrain='t'.($terrain-1000);

            $data[$y-($yc-$exp-$pos)][$x-($xc-$exp-$pos)]=$terrain; 
        } 
        //--------------------

        $y=-$exp-$pos-$pos-1;
        foreach($data as $row){$y++;
        $x=-$exp-$pos-$pos-1;
        foreach($row as $terrain){$x++;
            //echo("($x,$y)");
            //echo("$terrain,");    
            //$x=$x-$xc-$pos;
            //$y=$y-$yc-$pos;
            $cast=map1($terrain,$x+$xc+$pos,$y+$yc+$pos);
            
            $rx=(($x-$y)*$width/10)+($width/2)-($width/10)-(imagesx($cast)/(1+(2*$t_pofb)));
            $ry=(($x+$y)*$height/10)+($width/10)-(imagesx($cast)/(1+(2*$t_pofb)));       
            
            $rxx=($rx+imagesx($cast));
            $ryy=($ry+imagesy($cast));
            $q=true;     
            if($rxx<0)$q=false;
            if($ryy<0)$q=false;
            if($rx>$width)$q=false;     
            if($ry>$height)$q=false;
            //imagecopy($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h);
            if($q)imagecopy($img,$cast,$rx,$ry,0,0,imagesx($cast),imagesy($cast));   
            imagedestroy($cast);
        }}
        //------------------------------------------ELEVATION
        if(lvl){
            $clvl=-clvl;//-35;
            $stickarea=10;
            $sun=sun;//2;
            $sunc=sunc;//15;
            
            $img2=imagecreatetruecolor($width,$height*(7/5));
            $fill=imagecolorallocatealpha($img2, 0, 0, 0, 127);
            imagefill($img2, 0, 0, $fill);


            for($yy=0;$yy<$height*(6/5);$yy++){
                for($xx=0;$xx<$width;$xx++){

                    //$lvl=rand(-10,10);
                    //$lvl=(sin($xx/17)+sin($yy/17))*10+10;
                    
                    $x=$xc+($xx*5/$width)-2.5+($yy*5/$heigth);
                    $y=$yc-($xx*5/$width)+($yy*5/$heigth);
                    
                    $rgb = imagecolorat($img, $xx, $yy);
                    $r = ($rgb >> 16) & 0xFF;
                    $g = ($rgb >> 8) & 0xFF;
                    $b = $rgb & 0xFF;
                    
                    $lvl=rgb2lvl($r,$g,$b);
                    //$lvl=polyrand('lvl',20,1,$x,$y);
                    if($lvl<0)$lvl=0;
                    $dt=$lvl;/*$GLOBALS['dt'];*/
                    

                    $r=$r+(($dt+$clvl+$sunc)*$sun);
                    $g=$g+(($dt+$clvl+$sunc)*$sun);
                    $b=$b+(($dt+$clvl+$sunc)*$sun);
                    if($r<0)$r=0;if($r>255)$r=255;
                    if($g<0)$g=0;if($g>255)$g=255;
                    if($b<0)$b=0;if($b>255)$b=255;
                    
                    if($yy-$lvl-$clvl>$stickarea){
                        $tmpcolor=imagecolorallocate($img2, $r, $g, $b);
                    }else{
                        $a=intval(127*(1-($yy/$stickarea)));
                        if($a<0)$a=0;if($a>127)$a=127;
                        $tmpcolor=imagecolorallocatealpha($img2, $r, $g, $b,$a);
                    }

                    
                    imagefilledellipse($img2, $xx,  $yy-$lvl-$clvl+50, 5, 100, $tmpcolor);
                    
                    //imageline($img2, $xx, $yy-$lvl-$clvl, $xx, $yy-$lvl+100+$clvl, $tmpcolor);
                    imagecolordeallocate($img2, $tmpcolor);
                }
            }

            imagesavealpha($img2,true);
            return($img2);
        }else{
	    $img=tborder($img);
            return($img);
        }
}


//r(mapbg(20,5));
//r(mapbg(25,0));
//r(mapbg(-5,-5));
//br();
//r(mapbg(25,-5));
//die();
/*br();
?>

<div style="position: absolute;">
<div style="position: relative;top:<?php e(-clvl-(height/5)); ?>px;">

<?php r(mapbg(25,10)); ?>
</div>
</div>


<?php

die();
 * */

//--------------------------------------------------------------UNITS
function mapunits($gx,$gy,$xy,$buildings=false){
    define("xx",0);
    define("yy",0);
    //define("height2",height*1.3);
    define("top",200*(height2/375));
    //---------------------------------
        $size=1;
        $width=150*5*(height2/375);
        $height=75*5*(height2/375);
        $img=imagecreatetruecolor/**/($width,$height);//r();
        imagealphablending($img,true);
        //imagesavealpha($img,true);
        //imagesavealpha($img,true);
        $fill=imagecolorallocatealpha($img, 0, 0, 0, 127);
        imagefill($img, 0, 0, $fill);
        //imageantialias($img, true);
        $z=3;
        $zoom=$z*5;
        $zzoom=1+(($z-1)*5);
        $top=250*(height2/375);//230;
        //----------------------------------AREA------------
        $x=$gx-5;
        $y=$gy-5;
        $top=408*(height2/375);//458;
        $q=false;
	//`type`='building' OR 
	//echo('hurá');
        
        $profileown="(SELECT `profile` from `[mpx]pos_obj` as x WHERE x.`id`=`[mpx]pos_obj`.`own` LIMIT 1) as `profileown`";
        foreach(array_merge(

/*sql_array("SELECT x,y,res,name,id,fp,fs FROM `[mpx]pos_obj` WHERE res!='' AND ww=".$GLOBALS['ss']["ww"]." "."AND (`type`='tree')"."  AND x>=$x AND y>=$y AND x<=$x+$zoom AND y<=$y+$zoom ORDER BY RAND() LIMIT 1"),
sql_array("SELECT x,y,res,name,id,fp,fs FROM `[mpx]pos_obj` WHERE res!='' AND ww=".$GLOBALS['ss']["ww"]." "."AND (`type`='rock')"."  AND x>=$x AND y>=$y AND x<=$x+$zoom AND y<=$y+$zoom ORDER BY RAND() LIMIT 1"),*/
sql_array("SELECT x,y,res,name,id,fp,fs,$profileown FROM `[mpx]pos_obj` WHERE  ww=".$GLOBALS['ss']["ww"]." "."AND ".objt()." AND res!='' AND (`type`='tree' OR `type`='rock'".($buildings?' OR `type`=\'building\'':'').")"."  AND x>=$x AND y>=$y AND x<=$x+$zoom AND y<=$y+$zoom ORDER BY x,y")

) as $row){
                    //if($row[2]){
                            //------------------------------------------------Barva uživatele
                            $profileown=$row['profileown'];
                            $a=strpos($profileown,'color=');
                            if($a!==false){

                                    $profileown=substr($profileown,$a+6);
                                    $usercolor=$profileown;
                                    $b=strpos($profileown,';');
                                    if($b)$profileown=substr($profileown,0,$b);
                                    //e($profileown);		

                                    if(strpos($res,'000000')){
                                            $res=str_replace('000000',$profileown,$res);
                                    }else{
                                            /*$res=explode(':',$res);
                                            $pos=strrpos($res[2],',');
                                            $res[2]=substr($res[2],0,$pos+1).$profileown;
                                            $res=implode(':',$res);
                                            //$res=str_replace('000000',$profileown['color'],$res);	
                                            */
                                    }
                            }else{
                                    $usercolor=false;
                            }
                            //------------------------------------------------
                        $q=true;                        
                        $model=model($row[2],1,20,1.5,0,$row[5]/$row[6],0,false,$usercolor);                        
						//r($row[3]);                        
                        //r($model);
                        //imagealphablending($model,true);
                        //imagesavealpha($model,true);
                        //$model=place($model);
                        $xx=$row[0]-$x;
                        $yy=$row[1]-$y;
                        $rxp=(imagesx($img)-imagesx($model))*0.5;//($width/2)-(imagesx($cast)*0.5*$size);
                        $ryp=-top*$size-$top;
                        $p=(200*size2);
                        $ix2rx=0.5*$p;$ix2ry=0.25*$p;
                        $iy2rx=-0.5*$p;$iy2ry=0.25*$p;
                        $rx=($ix2rx*$xx)+($iy2rx*$yy)+$rxp;
                        $ry=($ix2ry*$xx)+($iy2ry*$yy)+$ryp;
                        // ( $dst_im , $src_im , $dst_x , $dst_y , $src_x , $src_y , $src_w , $src_h )
                        $s=height2/500;

			$hovnonad=0;$hovnonadx=0;
			$www=$s*200*(imagesx($model)/110);
			$hhh=$s*380*(imagesy($model)/209);
			//r($www);
			if(substr($row[2],0,1)=='('){
				$www=110.24;
				$hhh=(imagesy($model)/imagesx($model)*110.24);
				$hovnonad=intval((110*0.75)/133*(254))-imagesy($model)+11;
				$hovnonadx=-14;
			}

                        //r("imagecopyresized(img,model,$rx,$ry,0,0,$s*200*(".imagesx($model)."/110),$s*380*(".imagesy($model)."/209),".imagesx($model).",".imagesy($model)."));");
                        imagecopyresized($img,$model,$rx+$hovnonadx,$ry+$hovnonad,0,0,$www,$hhh,imagesx($model),imagesy($model));
                    //}
                }
          
        /**/ 
    //exit;
    if($q){	
	   imagesavealpha($img,true);
        return($img);
    }else{
        return(false);   
    }
}
//r(mapunits(20,128));
//exit;
//------------------------------------------------------------------------------------------------------------PROPOJENI2 HTMLMAP
/*$gx=2;$gy=15;
$x=($gy+$gx)*5+1;
$y=($gy-$gx)*5+1;
$treerock=mapunits($x,$y);
r($treerock);*/
//=============================================================
function htmlmap($gx=false,$gy=false,$w=0,$only=false,$row=1,$buildings=false/*$width=424*/){

	if(!$GLOBALS['mapzoom'])$GLOBALS['mapzoom']=1;
	$zoom=$GLOBALS['mapzoom'];


            //$gx=-10;            
            //$gy=0;
            $width=424;
    //NOCACHE//ile$file=tmpfile2("output6,".root.",$gx,$gy,".$GLOBALS['ss']["ww"],"txt","map");
    //NOCACHE//if(!file_exists($file) and !notmp){
        //if($_GET["x"]){$gx=$_GET["x"];}else{$gx=0;}
        //if($_GET["y"]){$gy=$_GET["y"];}else{$gy=0;}
            
            //echo(mapsize);
            $ym=ceil(mapsize/5);//-1;
            $xm=ceil((mapsize/5-1)/2);
            $x=($gy+$gx)*5+1;
            $y=($gy-$gx)*5+1;            
            
            //echo("($gx>$xm) or ($gx<-$xm) or ($gy>$ym) or ($gy<0)");
            // or ($gx>$xm) or ($gx<-$xm) or ($gy>$ym) or ($gy<0)
            $t=11;
            if(is_bool($gx) or is_bool($gy) or ($x<-$t) or ($y<-$t) or ($x>mapsize+$t) or ($x>mapsize+$t)){$gx=-$xm-1;$gy=-1;}//$gx=-$xm;$gy=0;
            if($w!=2)$outimg=tmpfile2("outimgbg,".$gx.",".$gy.",".$GLOBALS['ss']["ww"].',quality'.jpg_quality.','.t_,/*lvl?'png':*/"jpg","mapbg");
			if($w!=1)$outimgunits=tmpfile2("outimgunits".$gx.",".$gy.",".$GLOBALS['ss']["ww"].',quality'.png_quality.','.t_,(png_quality=='gif'?'gif':'png'),"mapunits".($buildings?'b':''));

            if($w==1 and $only)return($outimg);
            if($w==2 and $only)return($outimgunits);
            
            $border=0;
            $html='';
            //======================================================BACKGROUND
	    //$border=3;
            if($w!=2){
            if(!file_exists($outimg) or notmp/** or 1/**/){if(debug)$border=3;
                //r('generate new bg');
                
                $x=($gy+$gx)*5+1-5;
                $y=($gy-$gx)*5+1-5;
                
                
                
                $img1=mapbg($x,$y/*,"x".$gx."y".$gy*/);
                $img2=mapbg($x+5,$y+5/*,"x".$gx."y".$gy*/);

                
                $img=imagecreatetruecolor(imagesx($img1), round($width/424*212));
                
                $posuvy=0;
                imagecopy($img, $img1, 0, imagesy($img)*(-1/5)+$posuvy, 0, 0, imagesx($img1), imagesy($img1));
                imagecopy($img, $img2, 0, imagesy($img)*(4/5)+$posuvy, 0, 0, imagesx($img1), imagesy($img2));
//                imagefilter($img, IMG_FILTER_COLORIZE,9,0,5);
//                imagefilter($img, IMG_FILTER_CONTRAST,-10);
//                $emboss = array(array(0, 0.05, 0), array(0.05, 0.8,0.05), array(0, 0.05, 0));
//                imageconvolution($img, $emboss, 1, 0);
            
                /*if(lvl){
                    imagesavealpha($img,true);
                    imagepng($img,$outimg);
                }else{*/
			imagejpeg($img,$outimg,jpg_quality);
                //}
                chmod($outimg,0777);
                ImageDestroy($img);
            }
            //-----------------------
            if(lvl){
                //$row=1;
                //$clvla='<span style="position: relative;top:'.(htmlbgc+(-clvl-(height/5))*$row).'px;">';
                //$clvlb='</span>';
                //$clvlh=6/5;
                $clvla='';$clvlb='';
                $clvlh=1;
            }else{
                $clvla='';$clvlb='';
                $clvlh=1;
            }
            //-----------------------
            
            $datastream=rebase(url.str_replace('../','',$outimg).'?'.filemtime($outimg));
            //$datastream='data:image/png;base64,'.base64_encode(file_get_contents($outimg));
            if($w==0)$html.=$clvla.'<img src="'.$datastream.'" border="'.$border.'" width="'.(ceil($width/$zoom)).'" height="'.(ceil($width/424*212*$clvlh/$zoom)).'" style="z-index:1;" "/>'.$clvlb;//class="clickmap"   usemap="#x'.$gx.'y'.$gy.'"
            else     $html.=$clvla.'<img src="'.$datastream.'" border="'.$border.'" width="'.(1+ceil($width/$zoom)).'" height="'.(1+ceil($width/424*212*$clvlh/$zoom)).'" />'.$clvlb;            
            }
            //======================================================UNITS
            if($w!=1){
            if(!file_exists($outimgunits) or notmp/** or 1/**/){if(debug)$border=3;
                //r('generate new units');
            
                $x=($gy+$gx)*5+1;
                $y=($gy-$gx)*5+1;
                if($img=mapunits($x,$y/*,"x".$gx."y".$gy*/,NULL,$buildings)){
                    //$img=imgresizew($img,424);
                    //r($GLOBALS['ss']["area"]);exit;
                    imagefilter($img, IMG_FILTER_COLORIZE,9,0,5);
                    imagefilter($img, IMG_FILTER_CONTRAST,-5);
                    //$emboss = array(array(0, 0.05, 0), array(0.05, 0.8,0.05), array(0, 0.05, 0));
                    //imageconvolution($img, $emboss, 1, 0);
                
                    //header('Content-Type: image/jpeg');
		    if(png_quality=='gif'){
			$black = imagecolorallocatealpha($img, 0, 0, 0,127);
			imagecolortransparent($img,$black);
			imagegif($img,$outimgunits);
                    }else{
			
			//imagecolortransparent($img, imagecolorat($im,100,100));

			$widthx=png_height*2;
			$heightx=png_height;
			if(!$buildings){
				$img2=imagecreate/*truecolor*/($widthx,$heightx);
			}else{
				$img2=imagecreatetruecolor($widthx,$heightx);
			}
			imagesavealpha($img2,true);


			/*$model_rock=model('rock4',1,20,1.5,0,1);
			$model_tree=model(sql_1data("SELECT res FROM `[mpx]pos_obj` WHERE res!='' AND ww=".$GLOBALS['ss']["ww"]." "."AND (`type`='tree')"." AND id='1172015'  LIMIT 1"),1,20,1.5,0,1);//model('tree4',1,20,1.5,0,1);
			imagealphablending($model_tree,true); 
			                        
                        imagecopyresized($model_rock,$model_tree,0,0,0,0,imagesx($model_rock),imagesy($model_rock),imagesx($model_rock),imagesy($model_rock));*/
			$treerock=imagecreatefrompng(root.'ui/image/design/treerock.png');
                        
                        
                        
			/*$gx=2;$gy=15;
			$x=($gy+$gx)*5+1;
                	$y=($gy-$gx)*5+1;
			$treerock=mapunits($x,$y);*/
                        
                        
                        
			//r($treerock);
			//$model_tree=model(sql_1data("SELECT res FROM `[mpx]pos_obj` WHERE res!='' AND ww=".$GLOBALS['ss']["ww"]." "."AND (`type`='tree')"." LIMIT 1"),1,20,1.5,0,1);//model('tree4',1,20,1.5,0,1);
			//imagealphablending($model_tree,true); 
			//imagecopyresized($treerock,$model_tree,0,0,0,0,imagesx($model_tree),imagesy($model_tree),imagesx($model_tree),imagesy($model_tree));
			
			//r($treerock);
			//r($model_tree);
			//echo('ahoj');
			//r($treerock);                    
                 	//imagealphablending($img2,true);
                        imagecopyresized($img2,$treerock,0,0,0,0,imagesx($treerock),imagesy($treerock),imagesx($treerock),imagesy($treerock));
                     //imagealphablending($img2,true);

			//imageantialias($img2, true);
			imagealphablending($img2,false);
			//imagesavealpha($img2,true);
			//imagesavealpha($img,true);
			//$fill=imagecolorallocatealpha($img2, 0, 0, 0, 127);
			//imagefill($img2, 0, 0, $fill);
			imagecopyresized($img2,$img,0,0,0,0,$widthx,$heightx,imagesx($img),imagesy($img));
			imagepng($img2,$outimgunits,png_quality,png_filters);
			
                    }
                    
                    chmod($outimgunits,0777);
                    ImageDestroy($img);
                }else{
                    fpc($img,'');    
                }
            }
            //-----------------------
            if(filesize($outimgunits)>1){
                $datastream=rebase(url.str_replace('../','',$outimgunits).'?'.filemtime($outimgunits));
                //$datastream='data:image/png;base64,'.base64_encode(file_get_contents($outimg));
                if($w==0)$html.='<span style="position:absolute;width:0px;z-index:2;"><img src="'.$datastream.'" style="position:relative;left:-'.round(($width+htmlunitc)/$zoom).'px;z-index:2;" class="clickmap" width="'.ceil($width/$zoom).'" height="'.(ceil($width/424*212/$zoom)).'" border="'.$border.'"/></span>';//class="clickmap"   usemap="#x'.$gx.'y'.$gy.'"
                else     $html.='<img src="'.$datastream.'" width="'.(1+ceil($width/$zoom)).'" height="'.(1+ceil($width/424*212/$zoom)).'" class="clickmap" border="'.$border.'"/>';
            }elseif($w!=0){
                $html.='<table width="'.ceil($width/$zoom).'" height="'.ceil($width/(2*$zoom)).'" border="0" cellpadding="0" cellspacing="0" class="clickmap" ><tr><td></td></tr></table>';

            }
            }
            //======================================================

                    //NOCACHE//    file_put_contents2($file,$html);
        //NOCACHE// }else{
        //NOCACHE//     //r($file);
        //NOCACHE//    $html=file_get_contents($file);
        //NOCACHE//}
    //if(root)$html=str_replace("src=\"","src=\"".root,$html);
    if(!$w)echo($html);
    else   return($html);
}
//htmlmap(-3,2);
//br();
/*htmlmap(-3,3);
//htmlmap(-2,3);
br();*/
//htmlmap(-3,4);
//htmlmap(-2,4);
//die();

//=============================================================GRID
function mapgrid(){
	/*$outimg=tmpfile2("margrid,".',quality'.png_quality.','.t_,/*lvl?'png':/"png","mapbg");
	if(file_exists($outimg) and // false //){
	}else{
        	$width=150*5*(height*1.3/375);
        	$height=75*5*(height*1.3/375);
        	$img=imagecreatetruecolor($width,$height);
		
        	$fill=imagecolorallocatealpha($img, 0, 0, 0, 127);
        	imagefill($img, 0, 0, $fill);
		
		$black=imagecolorallocate($img, 0, 0, 0, 127);
		foreach(){


		}


		imagepng($img,$outimg,png_quality);

	}*/
	$outimg=imageurl('design/grid.png');
	return($outimg);
}

//echo("<img src=\"".mapgrid()."\">");
//die();
//======================================================
/*function terraincolor($terrain){
    $tmp=imagecreatefrompng(root."data/image/terrain/$terrain.png");
    $r=0;$g=0;$b=0;
    $d=imagesx($tmp)*imagesy($tmp)/100000;
    for($yyy=1;$yyy<=imagesy($tmp);$yyy+=100){
        for($xxx=1;$xxx<=imagesx($tmp);$xxx+=100){
            $rgb = imagecolorat($tmp, $xxx,$yyy);
            $r += (($rgb >> 16) & 0xFF)/$d;
            $g += (($rgb >> 8) & 0xFF)/$d;
            $b += ($rgb & 0xFF)/$d;
        }
    }
    imagedestroy($tmp);
    return(array($r,$g,$b));
}*/
function terraincolor($terrain){
    $tmp=imagecreatefrompng(root."ui/image/terrain/$terrain.png");
    $rgb = imagecolorat($tmp,round(imagesx($tmp)/2),round(imagesy($tmp)/2));
    $r = (($rgb >> 16) & 0xFF);
    $g = (($rgb >> 8) & 0xFF);
    $b = ($rgb & 0xFF);
    imagedestroy($tmp);
    return(array($r,$g,$b));
}
//-----------------------
function worldmap($width=0,$minsize=0,$w=false,$top=false,$worldmap_red=false){
    if(!$w){
        $w=$GLOBALS['ss']["ww"];
        $mapsize=mapsize;
    }else{
        $mapsize1=sql_1data('SELECT max(x) FROM [mpx]pos_obj WHERE `type`=\'terrain\' AND ww=\''.$w.'\'');
        $mapsize2=sql_1data('SELECT max(y) FROM [mpx]pos_obj WHERE `type`=\'terrain\' AND ww=\''.$w.'\'');
        $mapsize1=intval($mapsize1)+1;
        $mapsize2=intval($mapsize2)+1;
        if($mapsize1>$mapsize2){
            $mapsize=$mapsize1;
        }else{
            $mapsize=$mapsize2;
        } 
    }
    if(!$width){
		$width=$mapsize;
	}
    
	//print_r($worldmap_red);
    $outimg=tmpfile2("worldmap,$width,$w,$minsize".($top?'top':'iso').($worldmap_red?serialize($worldmap_red):'').t_,"png","worldmap");
    if(!file_exists($outimg)/** or true/**/){
        
        if($mapsize<$minsize){   
            $kk=$minsize/$mapsize; 
        }else{
            $kk=1;   
        }      
        
        $colors=array();
        
		if(!$top){
		    $s=$width/(sqrt(2*pow($mapsize,2))*$kk);      
		    //$width=sqrt(2*pow($mapsize,2))*$s*$kk;
		    $height=$width/2;
		}else{
		    //$s=2*$width/(sqrt(2*pow($mapsize,2))*$kk);      
		    $height=$width;
		}
        
        $img=imagecreatetruecolor($width, $height);
        imagealphablending($img, false);
        list($r,$g,$b)=terraincolor('t0');
        $y=gr;$yy=5;//5;
        //$colors[0]=imagecolorallocatealpha($img, ((($y*$r)+$g+$b)/(2+$y))/$yy,(($r+($y*$g)+$b)/(2+$y))/$yy,(($r+$g+($y*$b))/(2+$y))/$yy,90);
		$colors['t1']=imagecolorallocate($img,$r,$g,$b);
        //$colors[0]=imagecolorallocatealpha(0,0,0,50);  
        imagefill($img,0,0,$colors['t1']);
        
        $limit=0;$q=true;
        while($q){$q=false;
            foreach(sql_array('SELECT x,y,res FROM [mpx]pos_obj WHERE `type`=\'terrain\' AND ww=\''.$w.'\' LIMIT '.$limit.',500') as $row){//WHERE terrain!=\'t1\' AND
                $q=true;
                list($x,$y,$terrain)=$row;
                //$terrain='t'.($terrain-1000);

				if(!$top){
		            $xx=($x-$y)/($mapsize*2)*($width/$kk)+($width/2);
		            $yy=($x+$y)/($mapsize*2)*($height/$kk)+(($height-($height/$kk))/2);
		            $radius=ceil($s*sqrt(2));
				}else{
		            $xx=$x/($mapsize)*($width/$kk);
		            $yy=$y/($mapsize)*($height/$kk);
		            $xx2=($x+1)/($mapsize)*($width/$kk);
		            $yy2=($y+1)/($mapsize)*($height/$kk);
				}                

				if(!$terrain)$terrain='t0';
                if($terrain/* and $terrain!='t1'*/){
                    if(!$colors[$terrain]){
                        list($r,$g,$b)=terraincolor($terrain);
                        $colors[$terrain]=imagecolorallocate($img, $r, $g, $b);   
                    }

					if(!$top){
                    	imagefilledellipse($img, round($xx), round($yy), $radius, ceil($radius/gr), $colors[$terrain]);
					}else{
                    	imagefilledrectangle($img, round($xx), round($yy), round($xx2), round($yy2), $colors[$terrain]);
					}
                    //imagesetpixel($img, round($xx), round($yy), $colors[$terrain]);
                }
            
            }
            $limit+=500;
        }
        //-------------------------------------------------------
        if($worldmap_red){
            $bbg=imagecolorallocate($img, 10,10,10);
			$bbr=imagecolorallocate($img, 0,0,0);
            foreach($worldmap_red as $row){
                list($x,$y)=$row;
                /*$xx=($x-$y)/($mapsize*2)*($width/$kk)+($width/2);
                $yy=($x+$y)/($mapsize*2)*($height/$kk)+(($height-($height/$kk))/2);
                imagefilledellipse($img, round($xx), round($yy), $radius, ceil($radius/gr), $red);*/
				//if(!$top){
		        //    $xx=($x-$y)/($mapsize*2)*($width/$kk)+($width/2);
		        //    $yy=($x+$y)/($mapsize*2)*($height/$kk)+(($height-($height/$kk))/2);
		        //    $radius=ceil($s*sqrt(2));
				//}else{
		            $xx=$x/($mapsize)*($width/$kk);
		            $yy=$y/($mapsize)*($height/$kk);
		            $xx2=($x+1)/($mapsize)*($width/$kk);
		            $yy2=($y+1)/($mapsize)*($height/$kk);
					imagefilledrectangle($img, round($xx), round($yy), round($xx2), round($yy2), $bbg);
					imagerectangle($img, round($xx), round($yy), round($xx2), round($yy2), $bbr);
				//}
            }
        }
        //-------------------------------------------------------
        //imagefilter($img, IMG_FILTER_COLORIZE,9,0,5);
        imagefilter($img, IMG_FILTER_CONTRAST,-5);
        imagesavealpha($img, true);
        imagepng($img,$outimg,png_quality,png_filters);
		chmod($outimg,0777);
    }
    return($outimg);
}
//echo('<img src="'.worldmap(200,50,1).'"/>');
//die();
?>
