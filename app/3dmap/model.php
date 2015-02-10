<?php
//ini_set("max_execution_time","1000");
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_WARNING );
//=============================================================
function paint($im){//return($im);
    //-----------------------------
    $im2 = imagecreate(imagesx($im),imagesy($im));
    $paleta=array();
    $rand=array();
    $bg= imagecolorallocatealpha($im2,0,0,0,127);
    $brd= imagecolorallocate($im2,0,0,0);
    ImageFill($im2,0,0,$bg);
    for($y = 1; $y!=imagesy($im); $y++){
        for($x = 1; $x!=imagesx($im); $x++){
                $rgb=imagecolorsforindex($im,imagecolorat($im, $x,$y));
                //r($rgb);
                //exit;
                $r=$rgb["red"];
                $g=$rgb["green"];
                $b=$rgb["blue"];
                $al=$rgb["alpha"];
                if($al==0){
                    //r($rgb);
                    //r($r, $g, $b);
                    //exit;
                    $i=md5($r."x".$g."x".$b);
                    if(!$rand[$i]){
                        if($r+$g+$b!=0){
                            $rand[$i]["a"]=rand(0,10);
                            $rand[$i]["h"]=rand(2,4);
                            $rand[$i]["w"]=rand(2,4);
                        }else{
                            $rand[$i]["a"]=intval(0.85*127);
                            $rand[$i]["h"]=5;
                            $rand[$i]["w"]=5;
                        }
                    }
                    if(!$paleta[$i])$paleta[$i]=imagecolorallocatealpha($im2,$r, $g, $b,$rand[$i]["a"]);
                    imagefilledellipse($im2,$x,$y,$rand[$i]["h"],$rand[$i]["w"],$paleta[$i]);
                    //imageellipse($im2,$x,$y,$rand[$i]["h"],$rand[$i]["w"],$brd);
                    //return($im2);
                    //imagecolordeallocate($im2,$color);
                }
        }
    }
    //imagefilter($im2,IMG_FILTER_CONTRAST,255);
    return($im2);
}

//=============================================================
function model($res,$s=1,$rot=0,$slnko=1,$ciary=1,$zburane=0,$hore=0,$px=0.45,$py=0.1){
    //$s=$s*height/500;
        //$s=0.5;
        //$res=res;
        $res=str_replace("::",":1,1,1:",$res);
        $tmp=split(":",$res);
        /*if($tmp[0]=="tree"){
            $name=$tmp[0];
            $type=$tmp[1];
            //$tmp=imagecreatefrompng("image/nature/$name/$type.png");
            //return($tmp);
            $res=str_replace("::",":1,1,1:",$res);
            $tmp=split(":",$res);
        }*/
        $points=$tmp[0];
        $polygons=$tmp[1];
        $colors=/*split(",",)*/$tmp[2];
        if(!$rot)$rot=$tmp[3];
        //---------------------------colors
        $colors=split(",",$colors);
        //---------------------------rozklad bodu
        $points=substr($points,1,strlen($points)-2); 
        $points=split("]",$points); 
        $i=-1;
        foreach($points as $tmp){
        $i=$i+1; 
        $points[$i]=str_replace("[","",$points[$i]);
        $points[$i]=split(",",$points[$i]);
        }
        //---------------------------zburane
        $i=-1;
        foreach($points as $ii){
        $i=$i+1;
        $x=$points[$i][0];
        $y=$points[$i][1];
        $z=$points[$i][2];
        //-------------------------
        //$x=$x+rand(-10,10);
        //$y=$y+rand(-10,10);
        $x=$x-((($points[$i-1][0]+$points[$i-2][0])*$zburane*0.3)/100);
		$y=$y-((($points[$i-1][1]+$points[$i-2][1])*$zburane*0.3)/100);
		$z=$z-((($points[$i-1][2]+$points[$i-2][2])*$zburane)/100);
		if($x<0){$x=0;}
		if($x>100){$x=100;}
		if($y<0){$y=0;}
		if($y>100){$y=100;}
        if($z<0){$z=0;}
		if($z>250){$z=250;}

        //-------------------------
        $points[$i][0]=$x;
        $points[$i][1]=$y;
        $points[$i][2]=$z;
        //---
        }
        //---------------------------rotace
        $i=-1;
        foreach($points as $ii){
        $i=$i+1;
        $x=$points[$i][0];
        $y=$points[$i][1];
        //echo("(".$x.",".$y.")");
        //-------------------------
        $x=$x+0.1;
        $y=$y+0.1;
        $vzdalenost=sqrt(pow(($x-50),2)+pow(($y-50),2));
        $uhel=acos(($x-50)/$vzdalenost);
        $uhel=($uhel/pi())*180;
        if($y<50){$uhel=$uhel+$rot;}else{$uhel=$uhel-$rot;}
        if((50-$y)<0){$uhel=180+(180-$uhel);}
        $x=50+(cos(($uhel/180)*pi())*$vzdalenost);
        $y=50-(sin(($uhel/180)*pi())*$vzdalenost);
        $x=intval($x);
        $y=intval($y);
        //-------------------------
        //echo("(".$x.",".$y.")<br/>");
        $points[$i][0]=$x;
        $points[$i][1]=$y;
        //---
        }
        //---------------------------polygons
        $polygons=split(";",$polygons);
        $i=-1;
        foreach($polygons as $tmp){
        $i=$i+1;
        $polygons[$i]=split(",",$polygons[$i]);
        if($polygons[$i]==array("")){$polygons[$i][0]=1;$polygons[$i][1]=1;$polygons[$i][2]=1;}
        $polygons[$i][count($polygons[$i])]=$colors[$i];
        
        }
        //---
        /*foreach($polygons as $tmp1){
        echo(join(",",$tmp1));
        echo("<br/>");
        } */
        //---------------------------serazeni bodu
        $x=-1;
        $polygonsord=array();
        $polygonsord2=array();
        foreach($polygons as $tmp){
        $x=$x+1;
        $y=-1;
        foreach($tmp as $ii){
        if($tmp[count($tmp)-1]!=$ii){
        $y=$y+1;
        if($hore!=1){
        $polygonsord2[$x][$y]=($points[$ii-1][0]*0.5)+($points[$ii-1][1]*0.5)+($points[$ii-1][2]*1.11);
        }else{
        $polygonsord2[$x][$y]=$points[$ii-1][2];
        }
        }
        }
        $count=0;$count3=0;foreach($polygonsord2[$x] as $count2){$count=$count+$count2;$count3=$count3+1;}
        $count=intval($count/$count3)."";
        if(strlen($count)==1){$count="00".$count;}
        if(strlen($count)==2){$count="0".$count;}
        $polygons[$x]=$count."_".join(",",$polygons[$x]);
        }
        sort($polygons);
        $x=-1;
        foreach($polygons as $ii){
        $x=$x+1;
        $polygons[$x]=split("_",$polygons[$x]);
        $polygons[$x]=split(",",($polygons[$x][1]));
        }
        //---------------------------vykresleni
        if($hore!=1){
        $_SESSION["im"] = imagecreate($s*200,$s*380);
        }else{
        $_SESSION["im"] = imagecreate($s*150,$s*150);
        }
        //imagealphablending($_SESSION["im"],false);
        $_SESSION["bg"] = imagecolorallocatealpha($_SESSION["im"],0,0,0,127);
        $cierne = imagecolorallocate($_SESSION["im"],10,10,10);
        ImageFill($_SESSION["im"],0,0,$_SESSION["bg"]);
        //$bg = imagecolorallocatealpha($im,0,0,0,127);
        //ImageFill($im,0,0,$bg);
        //----------------------------------------------------------------stin
        //$shadow = imagecolorallocatealpha($_SESSION["im"],0,0,0,50);
		if($ciary==1){
			$shadow = imagecolorallocate($_SESSION["im"],0,0,0);
			$i2=-1;
			foreach($polygons as $tmp){
			$i2=$i2+1;
			$tmppoints=array();
			$i=-1;
		
			foreach($tmp as $ii){
			if($tmp[count($tmp)-1]!=$ii){
			$x=$points[$ii-1][0];
			$y=$points[$ii-1][1];
			$z=$points[$ii-1][2];
			if($hore!=1){
			//$px=0.45;$py=0.1;
			$xx=100+($x*1)-($y*1)+($z*$px);
			$yy=279+($x*0.5)+($y*0.5)+($z*$py);
			//$xx=100+($x*1)-($y*1);
			///$yy=279+($x*0.5)+($y*0.5)-($z*1.11);
			}else{
			$xx=$x+25;
			$yy=$y+25;
			}
			$i=$i+1;
			$tmppoints[$i]=$s*$xx;
			$i=$i+1;
			$tmppoints[$i]=$s*$yy;
			}
			}
			if(!$tmppoints[4]){$tmppoints[0]=0;$tmppoints[1]=0;$tmppoints[2]=0;$tmppoints[3]=0;$tmppoints[4]=0;$tmppoints[5]=0;}
		
			imagefilledpolygon($_SESSION["im"],$tmppoints,count($tmppoints)/2,$shadow);
			}/**/
		}
        //--------------------------------------------------------------polygons
        $i2=-1;
        foreach($polygons as $tmp){
        $i2=$i2+1;
        $tmppoints=array();
        $i=-1;
        
        foreach($tmp as $ii){
        if($tmp[count($tmp)-1]!=$ii){
        $x=$points[$ii-1][0];
        $y=$points[$ii-1][1];
        $z=$points[$ii-1][2];
        if($hore!=1){
        $xx=100+($x*1)-($y*1);
        $yy=279+($x*0.5)+($y*0.5)-($z*1.11);
        $xxx=100+($x*1)-($y*1);
        $yyy=279+($x*0.5)+($y*0.5)-($z*1.11);
        }else{
        $xx=$x+25;
        $yy=$y+25;
        }
        $i=$i+1;
        $tmppoints[$i]=$s*$xx;
        $i=$i+1;
        $tmppoints[$i]=$s*$yy;
        }
        }
        if(!$tmppoints[4]){$tmppoints[0]=0;$tmppoints[1]=0;$tmppoints[2]=0;$tmppoints[3]=0;$tmppoints[4]=0;$tmppoints[5]=0;}
        
        
        $color=/*$colors[$i2]*/$tmp[count($tmp)-1];
        //----------------------
        $x1=$points[$tmp[0]-1][0];
        $y1=$points[$tmp[0]-1][1];
        $x2=$points[$tmp[2]-1][0];
        $y2=$points[$tmp[2]-1][1];
        $x=abs($x1-$x2)+1;
        $y=abs($y1-$y2)+1;
        $rand=pow($x/$y,1/2);
        if($rand>1.5){$rand=1.5;}
        $rand=($rand*30*$slnko)-(25*$slnko);
        //----------------------
        
        $red=hexdec(substr($color,0,2))+$rand;
        $green=hexdec(substr($color,2,2))+$rand;
        $blue=hexdec(substr($color,4,2))+$rand;
        if($red>255){$red=255;}if($red<1){$red=1;}
        if($green>255){$green=255;}if($green<1){$green=1;}
        if($blue>255){$blue=255;}if($blue<1){$blue=1;}
        $nejmcolor="color".rand(1000,9999);
        $$nejmcolor = imagecolorallocate($_SESSION["im"],$red,$green,$blue);
        imagefilledpolygon($_SESSION["im"],$tmppoints,count($tmppoints)/2,$$nejmcolor);
        if($ciary==3){imagepolygon($_SESSION["im"],$tmppoints,count($tmppoints)/2,$cierne);}
        }
        //---------------------------rozvostreni
        if($ciary==1)$_SESSION["im"]=paint($_SESSION["im"]);
        //$file=tmpfile2("model,$res,$s,$rot,$slnko,$ciary,$zburane,$hore","png");
        
        
        //imagesavealpha($_SESSION["im"],true);

        return($_SESSION["im"]);
}

//============================================================
define("res","[-1,-1,0][90,50,0][10,90,0][10,10,0][50,90,0][90,10,0][60,60,0][50,60,0][60,50,0][50,84,30][50,66,30][50,84,0][50,66,0][95,63,0][91,54,0][81,52,0][73,58,0][73,69,0][81,75,0][91,73,0][95,63,30][91,54,30][81,52,30][73,58,30][73,69,30][81,75,30][91,73,30][50,90,51][50,60,51][60,50,51][90,50,51][90,10,51][10,10,51][10,90,51][60,60,51][40,90,51][20,90,51][90,20,51][90,40,51][98,20,0][40,98,0][20,98,0][90,40,0][90,20,0][98,40,0][40,90,0][20,90,0][20,80,51][80,27,51][80,20,51][20,20,51][44,48,51][40,40,105][47,43,51][29,80,51][22,66,30][22,66,0]:24,23,22,21,27,26,25;34,28,29,35,30,31,32,33;57,56,11,13;14,14,21,27,20;19,20,27,26;18,19,26,25;24,25,18,17;12,5,28,29,8,13,11,10;28,5,3,34;4,3,34,33;4,33,32,6;32,31,2,6;30,31,2,9;29,35,7,8;35,30,9,7;44,40,38;43,39,45;44,43,45,40;38,39,45,40;47,42,37;36,41,46;37,36,41,42;48,53,51;51,53,50;53,50,49;53,55,48;53,52,54;14,21,22,15;15,22,23,16;24,23,16,17:0000cc,888888,111111,006600,006600,006600,006600,444444,444444,444444,444444,444444,444444,444444,444444,111111,111111,111111,111111,111111,111111,111111,ff5500,ff5500,551100,551100,551100,006600,006600,006600");
//($res,$s=1,$rot=0,$slnko=1,$ciary=1,$zburane=0,$hore=0)
if($_GET["res"])$res=$_GET["res"];//res
if($_POST["res"])$res=$_POST["res"];//res
if($res=="res")$res=res;
if($_GET["s"]){$s=$_GET["s"];}else{$s=1;}//s
if($s>5)$s=5;
if($_GET["rot"]){$rot=$_GET["rot"];}else{$rot=0;}//rot
if($_GET["sun"]){$sun=$_GET["sun"];}else{$sun=1;}//sun
if($_GET["mode"]){$mode=$_GET["mode"];}else{$mode=1;}//mode[paint,nlbr,br]
if($_GET["destruct"]){$destruct=$_GET["destruct"];}else{$destruct=0;}//destruct
if($_GET["up"]){$up=$_GET["up"];}else{$up=0;}//up
if($_GET["sx"]){$sx=$_GET["sx"];}else{$sx=0.45;}//sx
if($_GET["sy"]){$sy=$_GET["sy"];}else{$sy=0.1;}//sy
//======================
if($res){
	$img=model($res,$s,$rot,$sun,$mode,$destruct,$up,$sx,$sy);
	//header("Cache-Control: no-cache");
	header("Content-type: image/png");
    	ImagePng($img);
    	imageDestroy($img);
	exit;
}else{
?>
<b>res</b>=[resource]&amp;<br/>
<b>s</b>=[size,0-5]&amp;<br/>
<b>rot</b>=[rotation,0-360]&amp;<br/>
<b>sun</b>=[sun]&amp;<br/>
<b>mode</b>=[1=paint,2=nlbr,3=br]&amp;<br/>
<b>destruct</b>=[0-100]&amp;<br/>
<b>up</b>=[0=normal,1=up]&amp;<br/>
<?php
}
?>
