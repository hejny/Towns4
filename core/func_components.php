<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2015
   _____________________________
   core/func_components.php
   HTML komponenty
*/

//======================================================================================

/*function createe($e){
    //$e=substr($r,0,strlen($r)-1);
    $r=$e.'r';
    $php="function $e(){e(call_user_func_array('$r',func_get_args()));}";
    eval($php);
}*/


//======================================================================================


function refresh($url=""){
echo('<script language="javascript">
    window.location.replace("'.urlr($url).'");
    </script>');
}//aaa
function reloc($brutal=false){
    if(!debug or true){
	if($brutal){
            header("location: ".url.'?token='.$_GET['token']);
	}else{
	    echo('<script language="javascript">reloc();</script>');
	}
    }else{
    echo('<a href="'.url.'">location: '.url.'?token='.$_GET['token'].'</a>');
    }
    exit2;
}
function click($url,$timeout=0){
    $tmp=urlr($url);
    if(strpos("x".$tmp,"javascript: ")){$onclick=str_replace("javascript: ","",$tmp);$tmp="";}
    if($tmp){refresh($url);}
    if($onclick){
        if($timeout>0){
            $onclick='setTimeout(function(){'.$onclick.'},'.($timeout*1000).');';
        }
        if($timeout==-1){
            $onclick='$( document ).ready(function() {'.$onclick.'});';
	}
        js($onclick);
    }   
}
//======================================================================================================================
//--------------------------------------------------------------------------window
function window($title=0,$width=0,$height=0,$window='content'){
    if ($title) {
        ?>
        <script type="text/javascript">
            /*aaa*/
            $("#window_title_<?php echo($window); ?>").html('<?php echo(trim($title)); ?>');
        </script>
    <?php
    }
    if ($width) {
        /*
<script>
    $("#scrollbar1").css('width','<?php echo($width); ?>');
        </script>
        */
        ?>
        <div style="width:<?php echo($width); ?>;"></div>
    <?php
    }
    /*if($enableSelection){
                ?>
        <script type="text/javascript"><!--
            $(document).ready(function(){
                $("#copy").enableSelection();
            });
        --></script>
                <?php
        }*/

}
//--------------------------------------------------------------------------permalink

function permalink($name=false,$link=false,$yjs=true){
    if($name or $link){
    $js = "
            /*alert(123);*/
            loadurl='".url.(debug?'debug/':'').$link."';

            var state = {
                'thisIsOnPopState': true
            };
            history.pushState(state, '".lr('apps_title',ucfirst($name))."', loadurl);
            document.title = '".lr('apps_title',ucfirst($name))."';
            ";
    }else{
        $js = "
            /*alert(345);*/
            loadurl='".url.(debug?'debug/':'')."';

            var state = {
                'thisIsOnPopState': true
            };
            history.pushState(state, '".lr('app_title')."', loadurl);
            document.title = '".lr('app_title')."';
            ";
    }
    if($yjs){
        js($js);
    }else{
        e($js);
    }

}


//--------------------------------------------------------------------------w_close


function w_close($w_name,$tt=false){
    r('w_close_'.$w_name);
        echo("<script type=\"text/javascript\">
        \$(document).ready(function(){
        setTimeout(function(){w_close('window_$w_name');},100);
        ".($tt?"setTimeout(function(){w_close('window_$w_name');},110);":'')."
        });
        </script>");
}
//1114415007, 973151688
//----------------
define('contentwidth',449);
function contenu_a($top17='',$scroll=true){ ?>
<?php

if($top17){
    if($top17===true)$top17=nbsp;
    infob($top17);
}
$scroll=true;
$top17=true;
if($scroll){
?>
<style type="text/css">
<!--
#contenu {
	margin:0 auto;
	padding:0px;
}
#contenu ul li{
	margin-bottom:0px;
}
.clear{clear:both;}
-->
</style>
<script type="text/javascript">
    function registerTinyScrollbar(e) {
        $("#contenu").scrollbar({
            taille_bouton: 100,
            marge_scroll_contenu: 15,
            largeur_scrollbar: 5
        });
    }
    $(document).ready(setTimeout(function(){registerTinyScrollbar();},600));
</script>
<?php } ?>
<div style="width:<?php echo(contentwidth); ?>;"></div>
<div style="width:<?php echo(contentwidth-17); ?>px;height:<?=$GLOBALS['ss']['screenheight']?>px;overflow:visible;">
<div id="contenu"><table cellpadding="0" cellspacing="0" border="0" width="100%"><tr><td width="100%" align="left" valign="top"><div id="inner_contenu">
      <?php
    xreport();
 /*
}else{
?>
<script type="text/javascript">
setTimeout(function(){
	<?php if($_GET['width']){e('ww='.$_GET['width'].';');}else{e('ww=$(window).width();');} ?>
	<?php if($_GET['height']){e('hh='.$_GET['height'].';');}else{e('hh=$(window).height();');} ?>
    $('#mobilecontent').css('width',ww-2);
    $('#mobilecontent').css('height',hh);
    $('#mobilecontent').css('display','block');
},10);
</script>
<?php
	e('<div style="width: 100%; height: 100%; overflow: hidden;"><div id="innercontent"  style="width: 100%; height: 100%;">');
} */
}
function contenu_b($forms=false){


    /*
?>
    <br><br><br><br>

    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <!-- Towns content 2 -->
    <ins class="adsbygoogle"
         style="display:inline-block;width:446px;height:60px"
         data-ad-client="ca-pub-5059650173115860"
         data-ad-slot="3907152521"></ins>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script>

<?php
    */


e('</div></td></tr></table></div></div>');
//<td height="'.($GLOBALS['ss']['screenheight']-140).'"></td>

}
//======================================================================================dockbutton
function dockbutton($x,$y,$size,$text,$href,$z=1,$id=false,$width=140,$posuv=false,$background='rgba(10,10,10,0.9)',$border='#222222',$position='absolute'){
    $GLOBALS['screenwidth']=$GLOBALS['ss']['screenwidth']; 
    $GLOBALS['screenheight']=$GLOBALS['ss']['screenheight'];
    if(is_array($size)){
        $sizex=$size[0];
    }else{
        $sizex=$size;
    }
    if(!is_array($text)){$text=array($text);}
    if(!is_array($href)){$href=array($href);}
    if(!is_array($id)){$id=array($id);}
    if(!is_array($width)){$width=array($width);}
    if(!is_array($background)){$background=array($background);}
    if(!is_array($border)){$border=array($border);}
    $count=count($text);
    if(!$posuv){
	    if($count==1){
		$posuv=array(0);
	    }else{
		$posuv=80;
		$posuv=array(-$posuv,$posuv);
	    }
    }
    //if($x<0){$x=$GLOBALS['screenwidth']+$x;}
    if($x==='%'){$x=($GLOBALS['screenwidth']/2)-((array_sum($width)/count($width))/2);}
    //if($y<0){$y=$GLOBALS['screenheight']+$y;}
  
    ?>
        <?php if($position=='relative')e('<div style="position:absolute;z-index:'.$z.';">'); ?>
	<div style="display:block;position:<?php e($position); ?>;left:<?php e(($x<0)?100:0); ?>%;top:<?php e(($y<0)?100:0); ?>%px;z-index:<?php e($z); ?>;">
        
            
            
    <?php 
    $i=0;
    while($text[$i]){
	    ob_start();
    ?>
            <?php /*if($position=='relative')e('<div style="position:absolute;z-index:'.$z.';">');*/ ?>
	    <div <?php e($id[$i]?'id="'.$id[$i].'"':''); ?> style="display:block;position:<?php e($position); ?>;left:<?php e($x+$posuv[$i]); ?>px;top:<?php e($y); ?>px;background: <?php e($background[$i]); ?>;border: 2px solid <?php e($border[$i]); ?>;border-radius: 4px;<?php e($sizex<0?'height:'.$width[$i].'px':'width:'.$width[$i].'px'); ?>;z-index:<?php e($z+$i); ?>;<?=shadow?>">
	    <table border="0" cellpadding="0" cellscpacing="0" width="100%" height="100%">
	    <tr><td valign="midle" align="center" <?php e($count==1?'':'height="'.(abs($y)-9).'px"'); ?>>
	    <?php
	    

	    tee($text[$i],$size);
	    
	    ?>
	    </td></tr>
	    </table>
	    </div>
            <?php /*if($position=='relative')e('</div>');*/ ?>
            
	<?php 
	    $buffer = ob_get_contents();
	    ob_end_clean();   
	    ahref($buffer,$href[$i]);
	 $i++;
    }
?>
    </div>
    <?php if($position=='relative')e('</div>'); ?>
    <?php

    
    
}
//======================================================================================ir
function ir($i,$q=2){
    $i=round($i,$q);
    $i=array_reverse(str_split($i));
    $ii='';$q=1;
    foreach($i as $str){
	if($q==4)$q=1;
	$ii=$str.$ii;
	if($q==3)$ii=nbsp.$ii;
    	$q++;
    }
    return($ii);
}
function ie($i,$q=2){echo(ir($i,$q));}/**/

//======================================================================================tr,te
/**
* @param $i
* @param bool $nonl2br
* @return mixed|string
* @see tr
*/
function tr_text($i,$nonl2br=false){
    $i=xx2x($i);
    $i=htmlspecialchars($i);
    if(!$nonl2br){
        $i=nl2br($i);
        $i=str_replace(nln,'<br>',$i);
        $i=str_replace(' ',nbsp,$i);// Tohle je na p*ču
        //$i=smiles($i);
    }
    //$i=str_replace("<br />","<br>",$i);
    //$i=str_replace(" ","&nbsp;",$i);
    return($i);
}

//----------------
/*
 *  Zobrazení textu
 *
 * */
function te($i,$nonl2br=false){
    echo(tr_text($i,$nonl2br));
}
//======================================================================================markdown
function markdown($text){
    
    //echo($text);
    //require_once('lib/markdown/MarkdownInterface.php');
    //require_once('lib/markdown/Markdown.inc.php');
    //use \Michelf\Markdown;
    //$text = file_get_contents('Readme.md');
    
    //echo(123);
    //$markdown=new Markdown;
    //$html=$markdown->transform($text);
    //use lib\markdown\Markdown;
    //require_once 'lib/markdown/Markdown.inc.php';
    //$html = Markdown::defaultTransform($text);
    require_once 'lib/parsedown/parsedown.php';
    $Parsedown = new Parsedown();
    $html=$Parsedown->text($text);
    return($html);
}
//======================================================================================inteligentparse
function inteligentparse($text){
    $textx=trim($text);
    if(substr($textx,0,1)=='{'){
        //return($text);
        return(contentlang($text));
    }else{
        return(markdown(xx2x($text)));
    }
}
//======================================================================================trr,tee
function trr($text,$size=0,$style=false,$html='',$tracetext='',$tag='img',$splitspace=true){
    
    t('trr - start');
    $fileX=tmpfile2(array($text,$size,$style,$html,$tracetext,$tag,$img,$splitspace),'txt','word');
    //die($fileX);


    if(!file_exists($fileX)/** or 1 /**/){
        
        $stream='';
        //$tracetext=$
        $text=str_replace('[15]',' ',$text);
        if(!$tracetext)$tracetext='X1PavelHejný';
        if(is_array($size)){
            list($size,$color)=$size;
            //echo('ahoj');
            //$stream=($orient);
            //-------
            $red=hexdec(substr($color,0,2));
            $green=hexdec(substr($color,2,2));
            $blue=hexdec(substr($color,4,2));
            if($red>255){$red=255;}if($red<1){$red=1;}
            if($green>255){$green=255;}if($green<1){$green=1;}
            if($blue>255){$blue=255;}if($blue<1){$blue=1;}
        }else{$color='none';}


        if($size<0){$size=-$size;$orient=1;}else{$orient=0;}
        if(!$size){
            $size=11;
        }
        $ga=230;
        if($style){
            $k=6;
            $gb=70;
            $ab=100;
        }else{
            $k=0;
            $gb=70;
            $ab=70;
        }
        $supersize=gr;
        $fontfile = root.'lib/font/Trebuchet MS.ttf';
	//$fontfile = root.'lib/font/ShragScript.otf';
        $fontsize=1;
        //$fontsize=1;
        $fn="-$size-$style-$ga-$k-$gb-$ab-$supersize-$fontfile-$fontsize-$color-$html-$tracetext-$orient";
        $size=intval($size*$supersize*$fontsize);
        $ttfbox=imagettfbbox($size ,0, $fontfile, $tracetext);
        $hh=$ttfbox[1]-$ttfbox[5];
        $space='';
        $text=contentlang($text);
        //$text=str_replace(nbsp,' ',$text);
        if($splitspace){$text=explode(' ',$text);}else{$text=array($text);}
        //error_reporting(E_ALL);
        foreach($text as $word){
			//e(substr($word,0,6).',');
			if(substr($word,0,6)!='rvscgo'){
				//-------------------------------------------------------------------------------------------NORMAL
		        $ttfbox=imagettfbbox($size ,0, $fontfile, 'X'.$word);
		        $ttfboxX=imagettfbbox($size ,0, $fontfile, 'X');
		        $width=($ttfbox[2]-$ttfbox[0])-($ttfboxX[2]-$ttfboxX[0]);	

		        $height=$hh;//$ttfbox[1]-$ttfbox[5];
		        //$word=contentlang($word);
		        //r($word);r();
				//-------------------------------------------------WORD
		        $file=tmpfile2($word.$fn,'png','word');
                //die($file);
		        if(!file_exists($file)/** or 1 /**/){
		           //r($ttfbox);	

		           $img=imagecreate($width,$height);
		           $img2=imagecreate($width,$height);
		           imagealphablending($img, false);
		           //imagesavealpha($img,true);
		           $fill=imagecolorallocatealpha($img, 255, 255, 255,127);
		           $fill2=imagecolorallocatealpha($img2, 255, 255, 255,127);
		           $color2=imagecolorallocate($img, 0, 0, 0);
		           //$color=imagecolorallocate($img, 0, 0, 0);
		           if($red)$color=imagecolorallocate($img, $red, $green, $blue);
		           if(!$red)$color=imagecolorallocate($img, $ga, $ga, $ga);
		           //$glow=imagecolorallocatealpha($img, 255, 255, 255,0);
		           if($red)$glow=imagecolorallocatealpha($img, $red, $green, $blue,$ab);
		           if(!$red)$glow=imagecolorallocatealpha($img, $gb, $gb, $gb,$ab);



                    if(is_array($style)){
                        list($style,$color_)=$style;
                        $red_=hexdec(substr($color_,0,2));
                        $green_=hexdec(substr($color_,2,2));
                        $blue_=hexdec(substr($color_,4,2));
                        if($red_>255){$red_=255;}if($red_<1){$red_=1;}
                        if($green_>255){$green_=255;}if($green_<1){$green_=1;}
                        if($blue_>255){$blue_=255;}if($blue_<1){$blue_=1;}
                        $ellipse=imagecolorallocate($img, $red_, $green_, $blue_);
                    }else{
                        $ellipse=imagecolorallocate($img, 20, 20, 20);
                    }



		           imagefill($img,0,0,$fill);
		           imagefill($img2,0,0,$fill2);
		           imagettftext($img2, $size ,0, 0, -$ttfbox[5], $color2,$fontfile, $word);
		                 for($y = 1; $y<imagesy($img2); $y++){
		                        for($x = 1; $x<imagesx($img2); $x++){
		                                $rgb=imagecolorsforindex($img2,imagecolorat($img2, $x,$y));
		                                $al=$rgb["alpha"];
		                                if($al<127){
		                                    imagefilledellipse($img,$x,$y,$k,$k,$glow);
		                                }
		                 }}
		            if($style==2){
		                imagefilledellipse($img,$width/2,$height/2,$width/1,$height/1,$ellipse);
		                //imageellipse($img,$width/2,$height/2,$width/2,$width/2,$glow);
		            }
		            if($style==3){
		                imagefilledellipse($img,$width/2,$height/2,200,200,$ellipse);
		                //imageellipse($img,$width/2,$height/2,$width/2,$width/2,$glow);
		            }

		           imagealphablending($img, true);
		           imagettftext($img, $size ,0, 0, -$ttfbox[5], $color,$fontfile, $word);

		           imagesavealpha($img,true);

		           if($orient){
		                $img=imagerotate($img,270,0,true);
		                imagesavealpha($img,true);
		           }
		              imagepng($img,$file,9,PNG_ALL_FILTERS); 


		           chmod($file,0777);
		        }
				//-------------------------------------------------

		        if(!$html){
		                $html='border="'.($style==3?2:'0').'"';
		        }

		        $stream.=$space.'<'.$tag.' '.($tag=='input'?'type="image"':'').' src="'.rebase(url.str_replace('../','',$file))/*$file*/.'" width="'.intval(($orient?$height:$width)/$supersize).'" height="'.intval(($orient?$width:$height)/$supersize).'" alt="'.$word.'" '.$html.'/>';
		        $space=$orient?'<br/>'.imgr('design/blank.png','',7,7).'<br/>':' ';
			//-------------------------------------------------------------------------------------------
			}else{
			//-------------------------------------------------------------------------------------------RVSCGO
				$word=substr($word,6);

				//$width
				if(!$red){
					$red=255;$green=255;$blue=255;
				}
				$stream.=$space.'<'.$tag.' '.($tag=='input'?'type="image"':'').' src="'.rebase(url.str_replace('../','',rvscgo($word,70,5,$red, $green, $blue))).'" '.($orient?'height':/*'width'*/'height').'="'.intval($hh/*$supersize*/).'" alt="'.$word.'" '.$html.'/>';
				$space=' ';
		        //$space=$orient?'<br/>'.imgr('design/blank.png','',7,7).'<br/>':' ';

			//-------------------------------------------------------------------------------------------
			}
        }
        
        fpc($fileX, $stream);
    }else{
        $stream=file_get_contents($fileX);
    }
    t('trr - stop');
    
    return($stream);
}

function tee($text,$size=0,$style=false,$html='',$tracetext='',$tag='img',$splitspace=true){
    echo(trr($text,$size,$style,$html,$tracetext,$tag,$splitspace));
}


    //e(base64_decode('U3Rhdml0ZWwgIGTFmWV2YQ=='));
    //exit;
    //tee(base64_decode('U3Rhdml0ZWwgIGTFmWV2YQ=='),13);
    //exit;

function buttonr($text,$size=19){
$text=str_replace(' ',nbsp,$text);
return(trr($text.nbsp,$size,3,'style="background: rgba(30,30,30,0.9);border: 2px solid #222222;border-radius: 2px;"'));
}
function button($text,$size=19){
e(buttonr($text,$size));
}

//===========================================================================================================================rvscgo
function rvscgo($text,$sizesize=70,$bold=5,$red=0, $green=0, $blue=0){

//$=$_GET["text"];
$sizex=44*($sizesize/70);
$size=60*($sizesize/70);
$bold=$bold*($sizesize/70);
$file=tmpfile2("rvscgo,$text,$sizex,$size,$bold,$red,$green,$blue",'png','rvscgo');
if(!file_exists($file)){
	//------
	$text=strtolower($text);
	$text=str_replace("ě","e",$text);
	$text=str_replace("š","s",$text);
	$text=str_replace("č","c",$text);
	$text=str_replace("ř","r",$text);
	$text=str_replace("ž","z",$text);
	$text=str_replace("ý","y",$text);
	$text=str_replace("á","a",$text);
	$text=str_replace("í","i",$text);
	$text=str_replace("é","e",$text);
	$text=str_replace("ú","u",$text);
	$text=str_replace("ů","u",$text);
	$text=str_replace("ť","t",$text);
	$text=str_replace(array("b","d","f","p","q","z"),"",$text);
	$text=str_replace(array("0","1","2","3","4","5","6","7","8","9"),"",$text);
	if($text){
	$text=str_replace("scg","ss",$text);
	//if(strlen($text)/2!=intval(strlen($text)/2))$text=$text."x";
	$text=str_replace(array("k","l"),"i",$text);
	$text=str_replace(array("r","j","e"),"y",$text);
	$text=str_replace(array("n","m"),"a",$text);
	$text=str_replace(array("h","c","g","x","t"),"s",$text);
	$text=str_replace(array("v","u","w"),"o",$text);
	//------
	$text2=$text;$text="";$q=0;
	foreach(str_split($text2) as $ch){
	if($ch=="a"){
	if($q){
	$ch="s";
	}else{
	$ch="o";
	}
	if($q==0){$q=1;}else{$q=0;}
	}
	$text=$text.$ch;
	}
	//------
	$width=$sizex*((strlen($text)/4)+0.25);
	$height=$sizex;
	$img = imagecreatetruecolor($width,$height);
	$bg = imagecolorallocate($img, 255, 255, 255);
	imagefill($img, 0, 0, $bg);
	$l=array();
	$l["i"][0] = imagecreatefromjpeg("ui/image/rvscgo/l0001.jpg");
	$l["y"][0] = imagecreatefromjpeg("ui/image/rvscgo/l0002.jpg");
	$l["s"][0] = imagecreatefromjpeg("ui/image/rvscgo/l0003.jpg");
	$l["o"][0] = imagecreatefromjpeg("ui/image/rvscgo/l0004.jpg");
	$l["i"][1] = imagecreatefromjpeg("ui/image/rvscgo/l0005.jpg");
	$l["y"][1] = imagecreatefromjpeg("ui/image/rvscgo/l0006.jpg");
	$l["s"][1] = imagecreatefromjpeg("ui/image/rvscgo/l0007.jpg");
	$l["o"][1] = imagecreatefromjpeg("ui/image/rvscgo/l0008.jpg");
	//bool imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
	$q=1;$i=0;
	foreach(str_split($text) as $ch){
	$x=$sizex/2*$i/2;
	$y=$sizex/2*$q;
	imagecopyresampled($img,$l[$ch][$q],$x,$y,0,0,$sizex/2,$sizex/2,imagesx($l[$ch][$q]),imagesy($l["y"][$q]));
	if($q==0){$q=1;}else{$q=0;}$i++;
	}
	//-------------------BOLD
	$width=$size*((strlen($text)/4)+0.25)+(2*$bold);
	$height=$size+(2*$bold);
	$img2 = imagecreatetruecolor($width,$height);
		    imagealphablending($img2,true);
		    imagesavealpha($img2,true);
	$bg = imagecolorallocatealpha($img2, 255, 255, 255,127);
	$pen = imagecolorallocate($img2,$red, $green, $blue);
	imagefill($img2, 0, 0, $bg);
	for($y=0;$y<imagesy($img);$y++){
	for($x=0;$x<imagesx($img);$x++){
		$rgb = imagecolorat($img, $x, $y);
		$colors = imagecolorsforindex($img, $rgb);
		if($colors["red"]<100){
			$xx=$x*($size/$sizex)+$bold;
			$yy=$y*($size/$sizex)+$bold;
			imagefilledellipse ($img2,$xx,$yy,$bold,$bold,$pen);
		}
	}
	}
	//-------------------
	}else{
	$img2 = imagecreatetruecolor(1,1);
		    imagealphablending($img2,true);
		    imagesavealpha($img2,true);
	$bg = imagecolorallocatealpha($img2, 255, 255, 255,127);
	imagefill($img2, 0, 0, $bg);
	}
	//-------------------
	//header("Content-type: image/png");
	imagepng($img2,$file);
	chmod($file,0777);
}
return($file);
}
//echo(rvscgo('wewretrytuyiu'));
//exit;
//===========================================================================================================================tfontr
function tfontr($text,$size=14,$color=""){
    if($color){
        //r($color);
        return("<span style=\"font-size:$size"."px;color: #$color;\">".($text)."</span>");
    }else{
        return("<span style=\"Font-size:$size"."px;\">".($text)."</span>");
    }
}
function tfont($text,$size=14,$color=""){
    echo(tfontr($text,$size,$color));
}
function tfont_a($size=14){
    echo("<span style=\"Font-size:$size"."px;\">");
}
function tfont_b(){
    echo("</span>");
}
function tcolorr($text,$color=""){
        return("<span style=\"color: #$color;\">".($text)."</span>");
}
function tcolor($text,$color=""){
    echo(tcolorr($text,$color));
}
function textabr($a,$b,$width=300,$width2=200){
    return(tableabr("<b>".tr($a)."</b>",tr($b),$width,$width2));
}
function textab($a,$b,$width=300,$width2=200){echo( textabr($a,$b,$width,$width2));}
//--------------------------------------------
function textabr_($array,$width=300,$width2=200,$font=false){
    $al=" align=\"left\"  valign=\"top\"";
    $stream='<table width=\"$width\" $al  border="0" cellpadding="0" cellspacing="0">';
    foreach($array as $tmp){list($a,$b)=$tmp;
        if($b!=''){
            $stream.=("<tr><td width=\"$width2\" $al><b>".($font?tfontr($a,$font):tr($a))."</b></td><td $al>".($font?tfontr($b,$font):tr($b))."</td></tr>");
        }else{
            $stream.=("<tr><td width=\"$width2\" $al colspan=\"2\"><b>".($font?tfontr($a,$font):tr($a))."</b></tr>");
        }
    }
        
    $stream.='</table>';
    //if($font)$stream=tfontr($stream,$font);
    return($stream);
}
function textab_($array,$width=300,$width2=200,$font=false){echo( textabr_($array,$width,$width2,$font));}
//--------------------------------------------
/*function blockr($w){
    return(imgr("design/none.png",$w,$w));
}*/
function movebyr($text,$x=0,$y=0,$id="",$style=""){
    return("<span ".($id?"id=\"$id\"":'')." style=\"position:absolute;$style\"><span style=\"position:relative;left:$x;top:$y;\">".$text."</span></span>");
}
function moveby($text,$x=0,$y=0,$id="",$style=""){
    echo(movebyr($text,$x,$y,$id,$style));
}
function borderr($html,$brd=1,$w=10,$id="",$category="",$countdown=0){
	 if(is_array($brd)){
		list($brd,$brdcolor)=$brd;
	 }else{
		$brdcolor='cccccc';
	 }
    if($id)$id="border_".$category."_".$id;
    $countdownx='';
    if($countdown){
	if(is_numeric($countdown)){
        $md5=md5(rand(0,999999));
        $md5js='<script type="text/javascript">
        setInterval(function(){ 
            /*alert(($("#'.$md5.'").html()));*/
            $("#'.$md5.'").html(parseFloat($("#'.$md5.'").html())-1);
            if(parseFloat($("#'.$md5.'").html())<=0){'.urlxr('e=map_context;ee=minimenu',false).'}
		html="";
		    t=parseFloat($("#'.$md5.'").html());
		    if(t>0){
		    dd=Math.floor(t/(3600*24));
		    t=t-(dd*(3600*24));
		    hh=Math.floor(t/3600);
		    t=t-(hh*3600);
		    mm=Math.floor(t/60);
		    ss=Math.floor(t-(mm*60));
		    
	  	    if(ss){html=ss/*+"{tsecondx}"*/;};
	  	    if(mm){html=mm+"'.lr('tminutex').'"/*+ss+"{tsecondx}"*/;};
	  	    if(hh){html=hh+"'.lr('thourx').'"/*+mm+"{tminutex}"*/;};
	  	    if(dd){html=dd+"'.lr('tdayx').'"/*+hh+"{thourx}"*/;};		
		    }
		    $("#'.$md5.'x").html(html);

        },1000);
        </script>';
	$countdownx=(movebyr(textcolorr('<span id="'.$md5.'" style="display:none;">'.$countdown.'</span><span id="'.$md5.'x">'.timecr(time()+$countdown,true,true).'</span>','dddddd').$md5js,-34+$brd,18+$brd,NULL,'z-index:2001'));
	}else{
	list($countdown)=explode(' ',$countdown,2);
	//$countdown=capital($countdown);
	$countdownx=(movebyr(textcolorr('<span style="display:block;">'.buttonr($countdown,11).'</span>','dddddd'),-$w+$brd,$w,NULL,'z-index:2001'));
	}
    }


    $puvodni=(movebyr($html,0,0,$id,"position:absolute;width:".($w)."px;height:".($w)."px;border: ".$brd."px solid #".$brdcolor.";z-index:1000").imgr("design/iconbg.png",'',$w+2,$w+2).$countdownx);

    return('<div style="display:inline-block;">'.$puvodni.'</div>');
}
//<table id=\"\" width=\"$w\" height=\"$w\" style=\"position:absolute;border: ".$brd."px solid #ffffff\" cellpadding=\"0\" cellspacing=\"0\"><tr><td>$html</td></tr></table>
function border($html,$brd=1,$w=10,$id="",$category="",$countdown=0){echo(borderr($html,$brd,$w,$id,$category,$countdown));}
function borderjs($id,$sendid="",$category="",$brd=1,$q=true){
    //return("border_".$category."='#border_".$category."_".$id."';alert(border_".$category.")");
    $style_a="'".$brd."px solid #cccccc'";
    $style_b="'0px solid #cccccc'";
    return("\$('#border_".$category."_".$id."').css('border',$style_a);$('#border_".$category."_".$id."').css('z-index',z_index);if(typeof border_".$category."!='undefined')if('#border_".$category."_".$id."'!=border_".$category.")$(border_".$category.").css('border',$style_b);border_".$category."='#border_".$category."_".$id."';z_index++;".($q?"setset='$category,$sendid';map_units_time=1;":''));
/*"$(function(){\$.get('?token=".$_GET['token']."&e=aac&set=".$category.",".$sendid."');});"*/
}
function borderr2($html,$brd=1){
    return('<span style="border: '.$brd.'px solid #cccccc;z_index:1000">'.$html.'</span>');
}
function border2($html,$brd=1){echo( borderr2($html,$brd));}
//======================================================================================================================IDčka


function spanidr($html,$id){
    return('<span id="'.$id.'">'.$html.'</span>');
}

function spanid($html,$id){
    echo(spanidr($html,$id));
}

//======================================================================================================================HTML TABLES VERZE 2011 @deprecated
/*
* @deprecated
 *
* */
function tableabr($a,$b,$width="100%",$width2="50%"){$al=" align=\"left\"  valign=\"top\"";
    return("<table width=\"$width\" $al><tr><td width=\"$width2\" $al>".$a."</td><td $al>".$b."</td></tr></table>");
}
/*
* @deprecated
 *
* */
function tableab($a,$b,$width="100%",$width2="50%"){echo( tableabr($a,$b,$width,$width2));}
/*
* @deprecated
*
* */
function tableab_a($al='left',$width="100%",$width2="50%"){
    $al=" align=\"$al\"  valign=\"top\"";
    if($width)$width="width=\"$width\"";
    if($width2)$width2="width=\"$width2\"";
    echo("<table $width $al border=\"0\" cellpadding=\"0\" cellspacing=\"0\" valign=\"middle\"><tr><td $width2 $al>");
}
/*
* @deprecated
*
* */
function tableab_b($al="left",$val="top"){$al=" align=\"$al\"  valign=\"$val\"";echo("</td><td $al>");}
/*
* @deprecated
*
* */
function tableab_c(){echo("</td></tr></table>");}

//======================================================================================================================HTML TABLES VERZE 2014
/*
 * Převod pole na HTML tabulku
 * @author PH
 *
 * @param array Data tabulky
 * @param integer šířka
 * @param array align,valign
 * @return array cikcak barvy array(barva1,barva2) nebo 1=automaticky
 *
 * */
function array2tabler($table,$width=false,$alignvalign=false,$cikcak=false/*,$header=false*/){
	//if(debug)print_r($table);

    $buffer='';
	if(!$width)$width='100%';
	if(!$alignvalign)$alignvalign=array('left','top');	
	list($align,$valign)=$alignvalign;
	if(!$cikcak)$cikcak=array('','');
	if($cikcak==1)$cikcak=array('rgba(30,30,30,0.2)','rgba(10,10,60,0.2)');
	$i=0;


    $buffer.=('<table border="0" cellpadding="0" cellspacing="0" '.((is_string($width) or is_numeric($width))?'width="'.$width.'"':'width="100%"').'>');
	foreach($table as $row){
	
		if(($i/2)==floor($i/2)){
			$color=$cikcak[0];
		}else{
			$color=$cikcak[1];
		}
        $buffer.=('<tr '.($color?'style="Background-color:'.$color.';"':'').'>');
		$ii=0;
		foreach($row as $cell){
			if(is_array($width)){
				if($width[$ii]){
					$widthx=$width[$ii];
				}else{
					$widthx=0;
				}
			}else{
				$widthx=false;
			}

            if(is_array($cell)){
                list($cell,$celltag)=$cell;
            }else{
                $celltag='td';
            }

            $buffer.=('<'.$celltag.' align="'.$align.'" valign="'.$valign.'" '.($widthx?'width="'.$widthx.'"':'').'>');
            $buffer.=($cell);
            $buffer.=('</'.$celltag.'>');
			$ii++;
		}
        $buffer.=('</tr>');
		$i++;
	}
    $buffer.=('</table>');
    return($buffer);
}
//--------------------------------
/* @uses array2tabler
* */
function array2table($table,$width=false,$alignvalign=false,$cikcak=false/*,$header=false*/){
    e(array2tabler($table,$width,$alignvalign,$cikcak));
}

//======================================================================================================================HTML TABLES VERZE 2015 - inteligentní tabulky
/*
* Objekt pro inteligentní vykreslování HTML tabulky
* @author PH
 *
* */
class table
{
    var $tabledata = array();
    var $rowdata = array();
    //--------------------------------__construct
    /*
    * */
    function __construct(){
    }
    //--------------------------------td
    /* Odeslání buňky tabulky
    *
    * @param string
    *  */
    public function td($data = ''){
        $this->rowdata[] = $data;
    }
    //--------------------------------th
    /* Odeslání headru tabulky
    *
    * @param string
    * */
    public function th($data = ''){
        $this->rowdata[] = array($data, 'th');
    }
    //--------------------------------tr
    /* Commitnutí řádku
    *
    * @param string
    * */
    public function tr(){
        if (count($this->rowdata)) {
            $this->tabledata[] = $this->rowdata;
            $this->rowdata = array();
        }
    }
    //--------------------------------tabler
    /* Vrácení tabulky
    *
    * @see array2tabler
    * @return string
    * */
    public function tabler($width = false, $alignvalign = false, $cikcak = false){
        return (array2tabler($this->tabledata, $width, $alignvalign, $cikcak));
    }
}
//----------------------------------------------------------------Funkcionální obalení
/*
*  Vytvoření globální objektu $GLOBALS['table'] - pomocná funkce k td,th,tr
* */
function createtable(){
    if(!isset($GLOBALS['protected']['table']) or !is_object($GLOBALS['protected']['table'])){
        unset($GLOBALS['protected']['table']);
        $GLOBALS['protected']['table'] = new table();
    }
}


/*
* Odeslání buňky tabulky
* @uses table::td
*
* */
function td($data){
      createtable();
      $GLOBALS['protected']['table']->td($data);
}
//--------------------------------
/*
* Odeslání headru tabulky
* @uses table::th
*
* */
function th($data){
      createtable();
      $GLOBALS['protected']['table']->th($data);
}
//--------------------------------
/*
* Commitnutí řádku tabulky
* @uses table::tr
* @see tr
*
* */
function tr_table(){//@todo Může fungovat i bez toho
    createtable();
    $GLOBALS['protected']['table']->tr();
}
//--------------------------------
/*
* Vrácení tabulky
* @uses table::tabler
*
* */
//--------------------------------
function tabler($width=1,$alignvalign=false,$cikcak=false){
    createtable();
    $return=$GLOBALS['protected']['table']->tabler($width,$alignvalign,$cikcak);
    unset($GLOBALS['protected']['table']);
    return($return);
}
//--------------------------------
/*
* Vykreslení tabulky
* @uses tabler
*
* */
function table($width=1,$alignvalign=false,$cikcak=false){
    e(tabler($width,$alignvalign,$cikcak));
}
//======================================================================================================================LOADBAR

function loadbar($fp,$fs,$plus=0,$show=0,$width='0',$height='0',$color1='33cc66',$color2='aa4422'){
	echo(loadbarr($fp,$fs,$plus,$show,$width,$height,$color1,$color2));
}
function loadbarr($fp,$fs,$plus=0,$show=0,$width='0',$height='0',$color1='33cc66',$color2='aa4422'){
//e("($fp/$fs+$plus)");
if(!$width)$width='100%';
if(!$height)$height='10';
if($show and $height<19)$height=19;
$md5='loadbar'.rand(111111,999999);
$html='';
$script='';

if(substr($width,-1)=='%'){
	$width=substr($width,0,strlen($width)-1);
	$p='%';
}else{
	$p='';
}
$width=$width-1+1;
//$width1=round($width*$fp/$fs);
$width1=round($width*$fp/$fs);
if($width1>$width)$width1=$width;
$wplus=(($plus/$fs)*$width);
$width2=$width-$width1;
//$width.=$p;
//$width1.=$p;
//$width2.=$p;
if($show){
	$htmlx='';
	$htmlx.=('<table border="0" cellpadding="0" cellspacing="0" width="'.$width.$p.'" height="'.$height.'" >');
	$htmlx.=('<tr><td align="center" valign="middle">');
	$htmlx.=('<span id="'.$md5.'text">'.ir($fp).'</span> / '.ir($fs));
	$htmlx.=('</td></tr>');
	$htmlx.=('</table>');
	$html.=movebyr($htmlx,0,0,0,"width:$width$p;height:$height;");
	if($plus and $width1<$width){
		$script.="countupto('$md5"."text',".time().",$fp,$plus,$fs);";
	}
}

$html.=('<table border="0" cellpadding="0" cellspacing="0" width="'.$width.$p.'" height="'.$height.'" bgcolor="#'.$color2.'">');
$html.=('<tr><td bgcolor="#'.$color1.'" id="'.$md5.'" width="'.$width1.$p.'">');
$html.=('</td><td bgcolor="#'.$color2.'" id="'.$md5.'b" width="'.$width2.$p.'">');//
$html.=('</td></tr>');
$html.=('</table>');
if($plus and $width1<$width){
	$script.="loadupto('$md5',".time().",$width1,$wplus,$width,'$p');";
}
//$html=tfontr($html,5);
return($html.js($script));
}

//======================================================================================================================Image
//--------------------------------------------
function imageurl($file,$rot=1,$grey=false){
	$ext=explode('.',$file);
	$ext=$ext[count($ext)-1];
	
    $file2=tmpfile2($file.','.$rot.','.$grey,$ext,"image");
    $file1="ui/image/".$file;
    if(!file_exists($file2) or filemtime($file1)>filemtime($file2) or notmpimg /** or true/**/){
        t('imageurl - startcreating');
        if(str_replace("id_","",$file)==$file){
            //r($rot);
            if($rot>1 or $grey){
                $img=imagecreatefromstring(file_get_contents($file1));
                if($rot==2)$img = imagerotate($img, 90, 0);
                if($rot==3)$img = imagerotate($img, 180, 0);
                if($rot==4)$img = imagerotate($img, 270, 0);
                
                if($grey){                
                    imagefilter($img,IMG_FILTER_GRAYSCALE);
                    imagefilter($img,IMG_FILTER_CONTRAST,40);
                    imagefilter($img,IMG_FILTER_BRIGHTNESS,-70);
                }                
                
                imagesavealpha($img,true);
                imagepng( $img,$file2);
                chmod($file2,0777);
            }else{
            	if(!file_exists($file1)){
            		$file1="ui/image/design/blank.png";
            	}
                copy($file1,$file2);
                chmod($file2,0777);
            }
        }else{
            //r($file2);
            $file=str_replace("id_","",$file);
            if(str_replace("_icon","",$file)!=$file){$q=true;}else{$q=false;}
            $file=str_replace("_icon","",$file);
            $contents=root."userdata/image/".$file.".jpg";//sql_1data("SELECT res FROM objects WHERE id='".$file."' OR name='".$file."'");
            //------
            if(/*!$contents*/!file_exists($contents)){
                
                $profile=sql_1data("SELECT profile FROM `[mpx]pos_obj` WHERE id='".$file."' OR name='".$file."'");
                $profile=new profile($profile);
                $profile=$profile->vals2list();
                $icon=$profile["image"];
                if($icon and false){//BEZ UžIATELSKýCH PROFILOVEK
                    $contents=root."ui/image/".$icon.".jpg";//sql_1data("SELECT res FROM objects WHERE id='".$icon."' OR name='".$icon."'");
                }else{
                //'hybrid','nature','message','hero','unit','building','item','terrain','image'
                    $res=sql_1data("SELECT res FROM `[mpx]pos_obj` WHERE id='".$file."' OR name='".$file."'");
                    if($res/* and $q==true*/){
                        //r($res);
                        $uz=1;
                        //$GLOBALS['model_bigimg']=true;
                        $img1=model($res,2,20,1.5,0);
                        //$GLOBALS['model_bigimg']=false;
                        imagesavealpha($img1,true);
                        $contents=$GLOBALS['model_file'];
                        $contents=file_get_contents($contents);
                    }else{
                        $type=sql_1data("SELECT type FROM `[mpx]pos_obj` WHERE id='".$file."'");
                        $contents=("ui/image/types/$type.png");
                        //r("image/types/$type.png");
                    }
                }
            }
            //------
            if(!$uz)$contents=file_get_contents($contents);
            //r();
            if($q){
                if(!$uz)$img1=imagecreatefromstring($contents);
                $img2=imagecreatetruecolor(50,50);
                $fill = imagecolorallocate($img2, 40, 40, 40);
                imagefill($img2,0,0,$fill);
                
                if(!$uz){
                    imagecopyresampled($img2,$img1,1,1,1,1,50,50,imagesx($img1),imagesy($img1));
                }else{
                    //$bound=80;
                    imagecopyresampled($img2,$img1,1,1,1,imagesy($img1)-imagesx($img1),50,50,imagesx($img1),imagesx($img1));
                }
                imagejpeg( $img2,$file2);
                chmod($file2,0777);
                //$contents="";
            }else{
            //header("Content-Type: image/png");
            //die($contents);
                fpc($file2,$contents);
            }
        }
        t('imageurl - stopcreating');
        }

        //echo(url.$file2.'<br>');
        //die(rebase(url.$file2).'<br>');

        $stream=rebase(url.$file2);//=$GLOBALS['ss']["url"].$file2;
        return($stream);
    
}
//--------------------------------------------
function imageurle($file){echo(imageurl($file));}
//--------------------------------------------
function imgr($file,$alt="",$width="",$height="",$rot=1,$border=0,$grey=0){
    $alt=tr($alt,true);
    if($width){$width="width=\"$width\"";}
    if($height){$height="height=\"$height\"";}
    $stream=imageurl($file,$rot,$grey);
    t('imgr - after imageurl');
    if($border)
        $border='style="border: '.$border.'px solid #cccccc;"';
    else
        $border='border="0"';
    $stream="<img src=\"$stream\" $border alt=\"$alt\" $width $height />";
    $stream=labelr($stream,$alt);
    return($stream);
}
//--------------------------------------------
function imge($file,$alt="",$width="",$height="",$rot=1,$border=0,$grey=0){
    echo(imgr($file,$alt,$width,$height,$rot,$border,$grey));
}
//imge("id_69");
//--------------------------------------------
function iconr($url,$icon,$name="",$s=22,$rot=1,$grey=0){
    $target='';
    if(is_array($s)){
        list($w,$h)=$s;
    }else{
        list($w,$h)=array($s,$s);   
    }
    
    //$s=22;
	//e($icon);
	//e(substr($icon,-4,1));
    if(substr($icon,-4,1)=='.'){
	$file=$icon;
    }else{
    	$file="icons/".$icon.".png";
    }
    //r($file); 
    if(strpos("x".$url,"menu:")){$class='href="#menu" class="menu" id="menu_'.str_replace('menu:','',$url).'"';$url='';}
    $tmp=urlr($url);
    if(strpos("x".$tmp,"javascript: ")){$onclick=str_replace("javascript: ","",$tmp);$tmp="#";}
	
    if(strpos($url,'http://')===0 or strpos($url,'https://')===0){//e($url);
	$target='target="_blank"';
     }    
    if($url){$url="href=\"".$tmp."\"";}
    if($onclick){$onclick="onclick=\"$onclick\"";}   

    if(1 or !$class){
    	$a="<a $url $onclick $class $target >";
    	$b="</a>";
    }else{
    	$a="<span $class >";
    	$b="</span>";    }
    return($a.imgr($file,$name,$w,$h,$rot,NULL,$grey).$b);
}
function icon($url,$icon,$name="",$s=22,$rot=1,$grey=0){echo(iconr($url,$icon,$name,$s,$rot,$grey));}
//--------------------------------------------
function iconpr($prompt,$url,$icon,$name="",$s=22){
    //$s=22;
    $file="icons/".$icon.".png";
    //ahrefpr($prompt,$text,$url,$textd="underline",$nol=false,$id="page",$data=false)
    //$a="<a href=\"".urlr($url)."\">";
    //$b="</a>";
    return(ahrefpr($prompt,imgr($file,$name,$s,$s),$url,"none","x"));
}
function iconp($prompt,$url,$icon,$name="",$s=22){echo(iconpr($prompt,$url,$icon,$name,$s));}
//--------------------------------------------
function objecticonr($id,$name="",$type="",$fs=0,$fp=0,$url=false,$x=0,$y=0,$br=0){$px=$x;
    //'hybrid','nature','message','hero','unit','building','item','terrain','image'
    //$name=htmlspecialchars($name);
    if($fp>$fs)$fp=$fs;
    if($br){
        $y=$y+intval($x/$br);
        $x=mod($x,$br);
    }
    if($id){
    $stream="<div style=\"position: absolute;\" ><div style=\"position: relative;top: ".($y*60)."px;left: ".($x*55)."px;width:60px; z-index:2;\" id=\"$id\" class=\"itemdrag\">";
    $stream.=imgr("id_$id"."_icon",$name,50,50);
    if($type!='image' and $type!='message'){
    $stream.="<div style=\"position: relative;top: -50px;left: 0px;Font-size:12px;Color:#000000;\">".fs2lvl($fs)."</div>";   
    $stream.="<div style=\"position: relative;top: -15px;left: 0px;height:4px;width:50px;Background-color:#ff0000;\">";
    $stream.="<div style=\"height:4px;width:".($fp/$fs*50)."px;Background-color:#00ff00;\"></div></div>";}
    //$stream.="<table width=\"50\"  border=mprofile\"0\" cellpadding=\"2\" cellspacing=\"0\"><tr><td bgcolor=\"#00ff00\" width=\"50%\"></td><td bgcolor=\"#ff0000\"></td></tr><table/>";
    //$stream.="<table>xxx<table/>";
    //<div style="position: absolute;"><div style="position: relative;top: 60px;left: 0px;width:50px; height:50px; border: 1px solid #FFFFFF; background-color:#222222; z-index:1;">
    $stream.="</div></div>";
    $stream=labelr($stream,$name);
    }else{
        //r("$px,$br($x,$y)");
    $stream="<div style=\"position: absolute;\" ><div style=\"position: relative;top: ".($y*60)."px;left: ".($x*55)."px;width:50px; height:50px; border: 1px solid #333333; background-color:#222222;  z-index:1;\" id=\"$name\"  class=\"itemdrop\">";
    $stream.="</div></div>";
    }
    if($url){
        $stream=ahrefr($stream,"e=content;ee=profile;id=$id","none",'x');
        //$stream="<a href=\"".urlr($url)."\">$stream</a>";
    }
    $stream=nln.nln.$stream.nln.nln;
    return($stream);
}
function objecticone($id,$name="",$type="",$fs=0,$fp=0,$url="",$x=0,$y=0,$br=0){echo(objecticonr($id,$name,$type,$fs,$fp,$url,$x,$y,$br));}
//--------------------------------------------
function functionholder($name,$inner,$x=0,$y=0,$br=0){$px=$x;
    $s=40;
    if($br){
        $y=$y+intval($x/$br);
        $x=mod($x,$br);
    }
    $stream="<div style=\"position: absolute;\" ><div style=\"position: relative;top: ".($y*($s+5))."px;left: ".($x*($s+5))."px;width:".$s."px; height:".$s."px; border: 1px solid #333333; background-color:#222222;  z-index:1;\" id=\"$name\"  class=\"functionholder\">";
    $stream.=$inner."</div></div>";
    echo($stream);
}
//--------------------------------------------
function iprofiler($id,$width=50,$border=0){//r($id);
    return(ahrefr(imgr("id_$id"."_icon","",$width,$width,1,$border),"e=content;ee=profile;id=$id","none",'x'));
}
function iprofile($id,$width=50,$border=0){//r($id);
    e(iprofiler($id,$width,$border));
}
//--------------------------------------------
function vprofile($id,$values=array()){//r($id);
    tableab_a(200,50);
    iprofile($id);
    tableab_b();
    echo("<span height=\"60\">");//style=\"background:#333333\"
    tee(id2name($id),16);br();
    foreach($values as $a=>$b)
        if($b){textab($a,$b,150,80/*,200,65*/);br();}
    //br(count($values));
    echo("</span>");
    tableab_c();
}
//--------------------------------------------
function mprofile($id){//r($id);
    list($name,$type,$fs,$fp,$x,$y)=id2info($id,"name,type,fs,fp,x,y");
    //$hint=$name.' '.'['.$x.','.$y.']';
    objecticone($id,$name,$type,$fs,$fp,"a",0,0);
}
//--------------------------------------------
function tprofile($id){
    $name=id2name($id);
    ahref($name,"page=profile;id=".$id,"none",true);
}
//=====================================================================================
function form_a($url="",$id=''){
    echo("<form method=\"POST\" action=\"$url\" ".($id?'id="form_'.$id.'" name="form_'.$id.'" onsubmit="return false"':'').">");
    $GLOBALS['formid']='form_'.$id;
}
//----------
function form_b(){
    echo("</form>");
}
//----------
function form_send($text="ok",$style=''){
    echo("<input type=\"submit\" value=\"$text\" style=\"$style\" />");
}
//----------
function form_sb($text="ok"){form_send($text);form_b();}
//----------
function form_js($sub,$url,$rows,$script=true,$rand=''){
//$url=urlr($url);
//$url=str_replace('&amp;','&',$url);
if($script)e('<script type="text/javascript">');
?>
//alert('1send');
//$( document ).ready(function() {
//alert("#<?php e($GLOBALS['formid']); ?>");
$("#<?php e($GLOBALS['formid']); ?>").submit(function() {
    //alert('send'+'<?php e($url.'&token='.$_GET['token']); ?>');

    $.post('<?php e($url.'&token='.$_GET['token']); ?>',
        {
        <?php
            $q=false;
            foreach($rows as $val){
                if($q)echo(',');
                e("$val: $('#$val$rand').val()");
                $q=true;
            }
        ?>
         },
        function(vystup){$('#<?php e($sub); ?>').html(vystup);}
    );

    <?php
    foreach($rows as $val){
        e("$('#$val').css('enabled','0');");
    }
    ?>


    return(false);
});
//});
<?php
if($script)e('</script>');
}
//--------------------------------------------
function input_textr($name,$value=false,$max=100,$cols="",$style='border: 2px solid #000000; background-color: #eeeeee',$placeholder=''){
    //echo(xsuccess());
    if($value===false and !xsuccess())$value=$_POST[$name];
    $value=addslashes($value);
    $stream="<input type=\"text\" name=\"$name\" id=\"$name\" value=\"$value\" size=\"$cols\"  maxlength=\"$max\" style=\"$style\" placeholder=\"$placeholder\" />";
    return($stream);
}
function input_text($name,$value=false,$max=100,$cols="",$style='border: 2px solid #000000; background-color: #eeeeee',$placeholder=''){echo(input_textr($name,$value,$max,$cols,$style,$placeholder));}


//--------------------------------------------


function input_hiddenr($name,$value){
  $stream='<input type="hidden" name="'.$name.'" id="'.$name.'" value="'.addslashes($value).'" />';
  return($stream);
}
function input_hidden(){e(call_user_func_array('input_hiddenr',func_get_args()));}
//--------------------------------------------
/*function input_colorr($name,$value='000000'){
    //echo(xsuccess());
    if(!$value/* and !xsuccess()* /)$value=$_POST[$name];
    //$value=tr($value,true);
    $stream="<input type=\"input\" name=\"$name\" id=\"$name\" value=\"$value\" size=\"$cols\"  maxlength=\"$max\" style=\"$style\"/>";
    $stream.=jsr('
$(\'#'.$name.'\').ColorPicker({
	onSubmit: function(hsb, hex, rgb, el) {
		$(el).val(hex);
		$(el).ColorPickerHide();
	},
	onBeforeShow: function () {
		$(this).ColorPickerSetColor(this.value);
	}
});
$(\'#'.$name.'\').bind(\'keyup\', function(){
	$(this).ColorPickerSetColor(this.value);
});
');
    return($stream);
}
function input_color($name,$value='000000'){echo(input_colorr($name,$value));}
*/
//--------------------------------------------
function input_passr($name,$value=''){
    $stream="<input type=\"password\" name=\"$name\" id=\"$name\" value=\"".addslashes($value)."\" />";
    return($stream);
}
function input_pass($name,$value=''){echo(input_passr($name,$value));}
//--------------------------------------------
function input_textarear($name,$value='',$cols="",$rows="",$style='',$placeholder='',$class=''){
    if(!$value and !xsuccess())$value=$_POST[$name];
    if($value=='none')$value='';
    if($cols){$cols="cols=\"$cols\"";}
    if($rows){$rows="rows=\"$rows\"";}
    $stream="<textarea name=\"$name\" id=\"$name\"  $cols $rows style=\"$style\" placeholder=\"$placeholder\" class=\"$class\" >".htmlspecialchars($value)."</textarea>";
    return($stream);
}
function input_textarea($name,$value='',$cols="",$rows="",$style='',$placeholder='',$class=''){echo(input_textarear($name,$value,$cols,$rows,$style,$placeholder,$class));}



//--------------------------------------------
function input_tinymcer($name,$value='',$width='',$height='',$type=1){


      if(!$value and !xsuccess())$value=$_POST[$name];
      if($value=='none')$value='';

        //$rand=rand(11111,99999);


   ?>
    <script type="text/javascript">

        <?php
        if(!isset($GLOBALS['tinyMCEremove'])){
            $GLOBALS['tinyMCEremove']=true;
            ?>
            try{
                tinyMCE.remove();
                //tinymce.destroy();
            }catch(e){
            }



        tinyMCE.init(
            {
                mode: 'textareas',
                selector: 'textarea.tinymcetextarea',
                skin_url: '<?=(url.'lib/tinymce/skins/custom')?>',
                content_css : '<?=(url.'lib/tinymce/skins/custom/content.css')?>',
                language: 'cs',
                theme: 'modern',
                width : '<?=$width?>',
                height : '<?=$height?>',
                statusbar:false,
                //pagebreak_separator: '<!-- my page break -->',
                <?php if($type==1){ ?>
                    menubar: 'edit view format table tools',
                    plugins: 'advlist autolink lists link image charmap print preview hr anchor searchreplace wordcount visualblocks visualchars code fullscreen media nonbreaking table contextmenu emoticons template paste textcolor colorpicker textpattern jbimages',
                    toolbar: " bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link ",
                <?php }elseif($type==2){ ?>
                    menubar: 'edit insert view format table tools jbimages',
                    plugins: 'advlist autolink lists link image charmap print preview hr anchor searchreplace wordcount visualblocks visualchars code fullscreen media nonbreaking table contextmenu emoticons template paste textcolor colorpicker textpattern jbimages',
                    toolbar: " bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link |  jbimages  ",//pagebreak
                <?php } ?>
                relative_urls: false
            }
        );

        <?php } ?>


    </script>

    <textarea name="<?=$name?>" id="<?=$name?>" class="tinymcetextarea" style="width:<?=$width?>;height:<?=$height?>;" ><?=htmlspecialchars($value)?></textarea>
    <?php
  $stream="";
  return($stream);
}
function input_tinymce(){e(call_user_func_array('input_tinymcer',func_get_args()));}

//--------------------------------------------
function input_checkboxr($name,$value){
    if(!$value and !xsuccess())$value=$_POST[$name];
    if($value){$ch="checked=\"checked\"";}else{$ch="";}
    $stream="<input type=\"checkbox\" name=\"$name\" id=\"$name\" $ch />";
    return($stream);
}
function input_checkbox($name,$value){echo(input_checkboxr($name,$value));}
//--------------------------------------------
function input_selectr($name,$value,$values){
    if(!$value/* and !xsuccess()*/)$value=$_POST[$name];
    $stream="<select name=\"$name\" id=\"$name\">";
    //print_r($values);
    foreach($values as $a=>$b){
        //echo($a.'=='.$value);
        if($a==$value){$ch="selected=\"selected\"";}else{$ch="";}
        $stream.="<option value=\"$a\" $ch >$b</option>";
    }
    $stream.="</select>";
    return($stream);
}
function input_select($name,$value,$values){echo(input_selectr($name,$value,$values));}
//----------------------------------------------------------------------------------------
function s_input($name,$value){
    $input="s_input_".$name;
    if($_POST[$input])$GLOBALS['ss'][$name]=$_POST[$input];
    if($GLOBALS['ss'][$name])$value=$GLOBALS['ss'][$name];
    form_a('?');
    input_text($input,$value);
    form_sb();
    return($GLOBALS['ss'][$name]);
}
//----------------------------------------------------------------------------------------
function limit($page,$w,$step,$to,$d=0){$to=$to-$step;//d-deafult
	//br();echo("limit($page,$w,$step,$to,$d=0)");br();
	//echo('('.$d.'/'.$to.')');
	//echo('('.(ceil($d/$step)+1).'/'.(ceil($to/$step)+1).')');

    if(is_array($page)){$e=$page[0];$ee=$page[1];}else{$e=$page;$ee=$page;}
    $w=md5("limit_".$e."_".$w);
    if(get('limit')){$GLOBALS['ss'][$w]=get($w);}else{$GLOBALS['ss'][$w]=$d;}
    //if(!$GLOBALS['ss'][$w])$GLOBALS['ss'][$w]=$d;
    $d=$GLOBALS['ss'][$w];
    
    //echo("$step,$to");
    if($to+$step>$step){
    $a=$d-$step;if($a<0){$a=0;}
    $b=$d+$step;if($b>$to){$b=$to;}
    //-----
    $col="222222";
    $font=17;
    e('<div style="background:#'.$col.';" ><table width="100%"><tr align="center"><td>');
    $l=lr("stat_first");tfont($d==0?textcolorr($l,'777777'):ahrefr($l,"e=$e;ee=$ee;noi=1;limit=1;$w=".(0),"none",true),$font);
    e('</td><td>');
    $l=lr("stat_previous");tfont($d==0?textcolorr($l,'777777'):ahrefr($l,"e=$e;ee=$ee;noi=1;limit=1;$w=".($a),"none",true),$font);
    e('</td><td>');
    tfont(lr("stat_page",(ceil($d/$step)+1).";".(ceil($to/$step)+1)),$font);
    e('</td><td>');
    $l=lr("stat_next");tfont($d==$to?textcolorr($l,'777777'):ahrefr($l,"e=$e;ee=$ee;noi=1;limit=1;$w=".($b),"none",true),$font);
    e('</td><td>');
    $l=lr("stat_last");tfont($d==$to?textcolorr($l,'777777'):ahrefr($l,"e=$e;ee=$ee;noi=1;limit=1;$w=".($to),"none",true),$font);
    e('</td></tr></table></div>');
    }
    
    $GLOBALS['ss']['ord']=$GLOBALS['ss'][$w];
    return($d.",".$step);
}
//======================================================================================
function showhide($id1){
    /*if($firstshow==2){
        $tmp=$id1;
        $id1=$id2;
        $id2=$tmp;
    }*/
    
 $js="
    if(\$('#".$id1."').css('display')=='block'){
        \$('#".$id1."').css('display','none');
    }else{
        \$('#".$id1."').css('display','block');
    }
 ";
 
 $js=js2($js);
 return($js);
}
//======================================================================================
function bhpr($text,$id=''){
    return("<a onclick=\"$('#hydepark_".$id."').css('display','block');\" href=\"#".rand(111111,999999)."\">$text</a>");
}
function bhp($text,$id=''){
    echo(bhpr($text,$id));
}
function hydepark($id=''){
    echo("<div style=\"display: none;z-index:10000;\" id=\"hydepark_".$id."\">");
}
function ihydepark(){
    echo("</div>");
}

//===============================================================================================================
function alert($text,$type,$tr=true,$nbsp=true){
    //message,error
    if($tr)$text=tr($text);
    $col=$type;
    if($type==1){$col="367329";}
    if($type==2){$col="992E2E";}
    if($type==3){$col="322E99";}
    if($type==4){$col="333333";}
    if(strlen($col)==6)$col='#'.$col;
    if($nbsp)$text=str_replace('&nbsp;',' ',$text);
    echo("<div style=\"background:$col;width:100%;\" >".($nbsp?'&nbsp;&nbsp;&nbsp;':'')."$text</div>");
}
function error($text,$tr=true){
    alert($text,2,$tr);
}
function success($text,$tr=true){
    alert($text,1,$tr);
}
function info($text,$tr=true){
    alert($text,4,$tr);
}
function blue($text,$tr=true){
    alert($text,3,$tr);
}
function infob($text,$color=4,$tr=false,$nbsp=false){
    alert('<table width="100%"><tr align="center"><td>'.$text.'</td></tr></table>',$color,$tr,$nbsp);
}
function infobb($text,$tr=false,$nbsp=false){
    alert('<table width="100%"><tr align="center"><td>'.$text.'</td></tr></table>','rgba(20,0,20,0.5)',$tr,$nbsp);
}
//======================================================================================
//report
function xr($text="",$tr=true,$q=3){
//alert(gettype($text),$q,$tr);
switch (gettype($text)) {
    case "NULL":
            alert("NULL",$q,$tr);
        break;
    case "string":
            //string, float, int
            if($text){
                if($text!="t"){
                    alert($text,$q,$tr);
                }else{
                    alert(ir(time()+microtime()-timestart,2),$q,$tr);
                }
            }else{
               alert("-----------------------------------------------------------------",$q,$tr);
            }
        break;
    case "double":
    case "integer":
            alert($text,$q,$tr);
        break;
    case "resource":
            //image
            imagesavealpha($text,true);
            //define("nob",true);
            //header ("Content-type: image/png");
            ob_start();    
            imagepng($text);            
            $datastream=ob_get_contents();
            ob_end_clean();
            $datastream='data:image/png;base64,'.base64_encode($datastream);
            
            echo('<img src="'.$datastream.'"/>');
            //exit;
        break;
    case "boolean":
            //bool
            if($text){
                alert("true",$q,$tr);
            }else{
                alert("false",$q,$tr);
            }
        break;
    case "array":
                        //array
                        if($text!=array()){
                            //print_r($text);
                            /*foreach($text as $a=>$b){
                                 if(is_array($b)){$b=join(",",$b);}
                                alert("$a' => $b",$q);
                            }*/
                            $sub=array(0);
                            $i=0;
                            while(!$sub[-1] and $i<1000){$i++;
                                $value=$text;
                                foreach($sub as $ii){
                                    $keys=array_keys($value);
                                    $key=$keys[$ii];
                                    $value=$value[$key];
                                }
                                if(isset($value)){
                                    if(!is_array($value)){
                                        $iii=1;$sp="";
                                        while($iii<sizeof($sub)){$iii++;
                                            $sp=$sp."__";
                                        }
                                        //echo($sp.$value.br);
                                        alert($sp.'['.$key."] = ".$value,$q,$tr);
                                        $sub[sizeof($sub)-1]++;
                                    }else{
                                        $iii=1;$sp="";
                                        while($iii<sizeof($sub)){$iii++;
                                            $sp=$sp.nbsp2;
                                        }
                                        alert($sp.'['.$key."] => ",$q,$tr);
                
                                        $sub[sizeof($sub)]=0;
                                    }
                                }else{
                                    array_pop($sub);
                                    $sub[sizeof($sub)-1]++;
                                }
                                //print_r($sub);
                                //echo(br);
                
                            }
                        }else{
                            alert("empty array",$q,$tr);
                        }
        break;
    default:
        alert("neznámý typ",$q,$tr);
}
}
//-----------------------------------------
function r($text="",$a,$b,$c,$d,$e,$f){
    if((defined('debug') and debug) or is_resource($text)){
    xr($text);
    if(isset($a)){xr($a);}
    if(isset($b)){xr($b);}
    if(isset($c)){xr($c);}
    if(isset($d)){xr($d);}
    if(isset($e)){xr($e);}
    if(isset($f)){xr($f);}
    }
}
//-----------------------------------------
function rx($text=""){
    r($text);
    exit;
}
//-----------------------------------------
function rn($text=""){
    xr($text,false);
}
//-----------------------------------------
//$array=(array(abc=>0,"hovno",array(array(array(1,2,3,4,5)),8),array(array(2,4)),array(7,abc=>"aaa")));
//r($array);
//echo($array[0][0][0][0]);
//echo($array[array(0,0,0,0)]);
//exit;
//===============================================================================================================
function textcolorr($text,$color){
    if($color=="M"){$color="ff7766";}//ff7766
    if($color=="T"){$color="7799ff";}//7799ff
    if($color=="N"){$color="99CC66";}//99CC66
    if($color=="X"){$color="cccccc";}//cccccc
    if($color){
        return("<span style=\"color: #$color;\">$text</span>");
    }else{
        return($text);
    }
}
//===============================================================================================================
function textqqr($text){
    return(nbsp2.textcolorr("(".$text.")","999999"));
}
//===============================================================================================================
function textbr($text){
    return("<b>$text</b>");
}
function textb($text){
    echo(textbr($text));
}
function textur($text){
    return("<u>$text</u>");
}
function textu($text){
    echo(textur($text));
}
//======================================================================================
function lvlr($a,$b,$a2=false,$b2=false){
    //r("$a,$b,$a2,$b2");
    if($a2!==false){
        $q=round(($a+$a2)*($b*$b2),2);
        $a=round($a,2);$b=round($b,2);
        $a2=round($a2,2);$b2=round($b2,2);
        $q=textbr($q);
        $q=$q.textqqr("$a,$b%)($a2,$b2%");
    }else{
        $q=round($a*$b,2);$a=round($a,2);$b=round($b,2);
        $q=textbr($q);
        if($b!=1){$b=$b*100;$q=$q.textqqr("$a,$b%");}
    }
    return($q);
}
//======================================================================================
function jsr($js){
    $js='<script type="text/javascript">'.$js.'</script>';
    return($js);
}
function js($js){
    echo(jsr($js));
}
//======================================================================================
//ODKAZY
//===============================================================
function ahrefr($text,$url,$textd="none",$nol=true,$id=false,$data=false,$onclick=""){
    $target='';
    if(!$data){$data=$GLOBALS['ss'];}
    if($nol!="x"){ if(!$nol){$text=lr($text);}else{$text=tr($text);}}
    //if(str_replace($data[$id],"",$url)==$url){r();}


    if($id){
	//e('-idtag-'.$id);
	$idtag='id="'.$id.'"';
    }else{
	$idtag='';
    }


	if(!$textd){$textd="none";}
	$add1="<span style=\"text-decoration:$textd;\">";
	$add2="</span>";

    //if($textd=="none"){$add1="";$add2="";}
	 if(strpos("x".$url,"menu:")){$class='href="#menu" class="menu" id="menu_'.str_replace('menu:','',$url).'"';$url='';}
    $tmp=urlr($url);
	//echo($tmp);
    if(strpos("x".$tmp,"javascript: ")){$onclick=str_replace("javascript: ","",$tmp);$tmp="#".rand(100000,999999);}else{}   //24.12.2014
    
    if(strpos($url,'http://')===0 or strpos($url,'https://')===0){
        if(strpos($url,'#noblank')===false){
            $target='target="_blank"';
        }else{
            $tmp=str_replace('#noblank','',$tmp);
        }
     }
     
    if($url){$url="href=\"".$tmp."\"";}
    if($onclick){$onclick="onclick=\"$onclick\"";}
	//e($url);
    return("<a $url $idtag $onclick $class $target >$add1$text$add2</a>");
}
function ahref($text,$url,$textd="none",$nol=true,$id=false,$data=false,$onclick=""){echo(ahrefr($text,$url,$textd,$nol,$id,$data,$onclick));}
//==========================================================================================
function ahrefpr($prompt,$text,$url,$textd="none",$nol=false,$id="page",$data=false){
    $tmp=urlr($url);
    if(strpos("x".$tmp,"javascript: ")){$onclick=str_replace("javascript: ","",$tmp);$tmp="#";}    
    
    if($onclick){
        $onclick="pokracovat = confirm('$prompt');if(pokracovat) {".$onclick.'}';
    }else{
        $onclick="pokracovat = confirm('$prompt');if(pokracovat) window.location.replace('".urlr($url)."');";
    }
    $html=ahrefr($text,"",$textd,$nol,$id,$data,$onclick);
    return($html);
}
function ahrefp($prompt,$text,$url,$textd="none",$nol=false,$id="page",$data=false){echo(ahrefpr($prompt,$text,$url,$textd,$nol,$id,$data));}
//==========================================================================================
function submenu($page,$array,$deafult=1,$session="",$v=false){$session='submenu_'.$session;
    
    //r($GLOBALS['ss']["get"]);
    if(is_array($page)){$e=$page[0];$ee=$page[1];}else{$e=$page;$ee=$page;}
    if($GLOBALS['get']['submenu']){$GLOBALS['ss'][$session]=$GLOBALS['get']['submenu'];}
    if($_GET['submenu']){$GLOBALS['ss'][$session]=$_GET['submenu'];}
    if(!$GLOBALS['ss'][$session]){$GLOBALS['ss'][$session]=$deafult;}
    //r($GLOBALS['ss']["get"][$session]);
    if($GLOBALS['ss']["get"][$session]){$GLOBALS['ss'][$session]=$GLOBALS['ss']["get"][$session];}
    $col="111111";
    $percent=round(100/count($array));
    if(!$v)echo("<table width=\"100%\" bgcolor=\"$col\"><tr>");
    $i=0;
    //r($array);
    //print_r($array);
    while($array[$i] or $array[$i]===false){//e($array[$i]);br();
        if($array[$i]){
            if(!$v)echo("<td align=\"center\" width=\"$percent%\">");
    		if($GLOBALS['ss'][$session]==$i+1){
            	textb(textur(lr($array[$i])));
    		}else{
            	ahref($array[$i],"e=$e;ee=$ee;".$session."=".($i+1),"none",false,"submenu");
    		}
            if(!$v){echo("</td>");}else{br();}
        }
        $i++;
    }
    if(!$v)echo("</tr></table>");
    $GLOBALS['ss']['submenu']=$GLOBALS['ss'][$session];
    return($GLOBALS['ss'][$session]);
}
//======================================================================================
function submenu_img($page,$label,$images,$names,$session="submenu_img"){
    if(is_array($page)){$e=$page[0];$ee=$page[1];}else{$e=$page;$ee=$page;}
    echo("<table>");
    echo("<tr><td align=\"left\"  valign=\"center\" width=\"100\">");
    tfont($label,17);
    //r($GLOBALS['ss']["get"]);
    if(!$GLOBALS['ss'][$session]){$GLOBALS['ss'][$session]=1;}
    if($GLOBALS['ss']["get"][$session]){$GLOBALS['ss'][$session]=$GLOBALS['ss']["get"][$session];}
    $col="111111";
    $percent=100/count($array);
    $i=0;
    //r($names);
    while($images[$i]){
         echo("</td><td valign=\"top\" align=\"center\" width=\"40\">");
        //icon($url,$icon,$name="",$s=22,$rot=1
        $icon=iconr("e=$e;ee=$ee;".$session."=".($i+1),$images[$i],$names[$i],40);
        //e($icon);
        if($GLOBALS['ss'][$session]==($i+1)){
            border($icon,2,40);
        }else{
            e($icon);
        }
        $i++;
    }
    echo("</td></tr>");
    echo("</table>");
    return($GLOBALS['ss'][$session]);
}
//======================================================================================
/*function num2text($n){
    $stream="";
    if(!$n){$stream="0";
    }elseif($n>=1 and $n<=20){$stream="$n";
        list($e1,$e2,$e3,$e4)=divarray($t,array(1,10,100,1000));
        list($e1,$e2,$e3,$e4)=array("$e1 e1",$e2,$e3,$e4);
    }
    return($stream);
}
die(num2text(33));*/
//===============================================================================================================
function timer($t){$timestamp=$t;
$t=date("Y:m:d:H:i:s",$t);
list($year, $month, $day, $hour, $minute, $second) = explode(':', $t);
$params="timestamp=$timestamp;day=$day;month=$month;year=$year;hour=$hour;minute=$minute;second=$second";
if(date("d:m:Y",time()-86400*2) == "$day:$month:$year"){
return(lr("timex2",$params));
}
if(date("d:m:Y",time()-86400) == "$day:$month:$year"){
return(lr("timex1",$params));
}
if(date("d:m:Y") == "$day:$month:$year"){
return(lr("timex",$params));
}
if(date("d:m:Y",time()+86400) == "$day:$month:$year"){
return(lr("timeq1",$params));
}
if(date("d:m:Y",time()+86400*2) == "$day:$month:$year"){
return(lr("timeq2",$params));
}
return(lr("time",$params));
}
//--------------------------------------------
function timee($t){echo(timer($t));}
//--------------------------------------------------------------------------
function timecr($t,$sec=true,$x=false){
    $t=abs($t-time());
    list($second,$minute,$hour,$day,$month,$year)=divarray($t,array(1,60,3600,3600*24,3600*24*30,3600*24*356));
    if(!$sec)$second=0;
    if($second){if($x and $second){$second=$second;}elseif($second==1){$second=lr("tsecond1");}elseif($second<5){$second="$second ".lr(tsecond2);}else{$second="$second ".lr(tsecond5);}}
    if($minute){if($x and $minute){$minute=$minute.lr('tminutex');}elseif($minute==1){$minute=lr('tminute1');}elseif($minute<5){$minute=$minute.lr('tminute2');}else{$minute=$minute.lr('tminute5');}}
    if($hour){if($x and $hour){$hour=$hour.lr('thourx');}elseif($hour==1){$hour=lr('thour1');}elseif($hour<5){$hour=$hour.lr('thour2');}else{$hour=$hour.lr('thour5');}}
    if($day){if($x and $day){$day=$day.lr('tdayx');}elseif($day==1){$day=lr('tday1');}elseif($day<5){$day=$day.lr('tday2');}else{$day=$day.lr('tday5');}}
    if($month){if($x and $month){$month=$month.lr('tmonthx');}elseif($month==1){$month=lr('tmonth1');}elseif($month<5){$month=$month.lr('tmonth2');}else{$month=$month.lr('tmonth5');}}
    if($year){if($x and $year){$year=$year.lr('tyearx');}elseif($year==1){$year=lr('tyear1');}elseif($year<5){$year=$year.lr('tyear2');}else{$year=$year.lr('tyear5');}}
    $stream=$x?'':lr('tleft');
    foreach(array($year,$month,$day,$hour,$minute,$second) as $row){
        if($q){if($row and !$x){$stream.=' '.lr('tjoin').$row;}break;}
        if($row){$stream.=$row;$q=true;}
    }
    return($stream);
}
//--------------------------------------------
function timece($t,$sec=true){echo(timecr($t,$sec));}
//======================================================================================
function timejsr($t,$urlx=''){
        $md5='js'.substr(md5($t),0,6).rand(1,1000);
        $js='<script type="text/javascript">
		'.$md5.'t='.($t-time()).';
		'.$md5.'x=true;
        setInterval(function(){ 
            /*alert(($("#'.$md5.'").html()));
            $("#'.$md5.'").html(parseFloat($("#'.$md5.'").html())-1);*/
			'.$md5.'t='.$md5.'t-1;
            if('.$md5.'t<=0){
				if('.$md5.'x){
					'.$md5.'x=false;
					//alert(\''.$urlx.'\');
					'.(substr($urlx,0,7)=='http://'?'window.location.replace(\''.$urlx.'\')':urlxr($urlx,false)).';
				}
				$("#'.$md5.'").html("0");
			}
			html="";
		    t='.$md5.'t;
			
		    if(t>0){
		    dd=Math.floor(t/(3600*24));
		    t=t-(dd*(3600*24));
		    hh=Math.floor(t/3600);
		    t=t-(hh*3600);
		    mm=Math.floor(t/60);
		    ss=Math.floor(t-(mm*60));
		    
	  	    if(ss){html=ss;};
	  	    if(mm){html=mm+"'.lr('tminutex').'"+ss+"'.lr('tsecondx').'";};
	  	    if(hh){html=hh+"'.lr('thourx').'"+mm+"'.lr('tminutex').'";};
	  	    if(dd){html=dd+"'.lr('tdayx').'"+hh+"'.lr('thourx').'";};		
		    }
		    $("#'.$md5.'").html(html);

        },1000);
        </script>';
        
        $timer='<span id="'.$md5.'">'.timecr($t,true,true).'</span>'.$js;
        
		//$timer='';
        return($timer);
}
//--------------------------------------------
function timejs($t,$urlx=''){echo(timejsr($t,$urlx));}
//======================================================================================
function timesr($t,$sec=true){
    $t=abs($t);
    list($second,$minute,$hour,$day,$month,$year)=divarray($t,array(1,60,3600,3600*24,3600*24*30,3600*24*356));
    if(!$sec)$second=0;
    if($second){if($second==1){$second=lr('tsecond1');}elseif($second<5){$second=$second.' '.lr('tsecond2');}else{$second=$second.' '.lr('tsecond5');}}
    if($minute){if($minute==1){$minute=lr('tminute1');}elseif($minute<5){$minute=$minute.' '.lr('tminute2');}else{$minute=$minute.' '.lr('tminute5');}}
    if($hour){if($hour==1){$hour=lr('thour1');}elseif($hour<5){$hour=$hour.' '.lr('thour2');}else{$hour=$hour.' '.lr('thour5');}}
    if($day){if($day==1){$day=lr('tday1');}elseif($day<5){$day=$day.' '.lr('tday2');}else{$day=$day.' '.lr('tday5');}}
    if($month){if($month==1){$month=lr('tmonth1');}elseif($month<5){$month=$month.' '.lr('tmonth2');}else{$month=$month.' '.lr('tmonth5');}}
    if($year){if($year==1){$year=lr('tyear1');}elseif($year<5){$year=$year.' '.lr('tyear2');}else{$year=$year.' '.lr('tyear5');}}
    //return($minute);
    foreach(array($year,$month,$day,$hour,$minute,$second) as $row){
        if($q){if($row){$stream.=' '.lr('tjoin').' '.$row;}break;}
        if($row){$stream.=$row;$q=true;}
    }
    return($stream);
}
//--------------------------------------------
function timese($t,$sec=true){echo(timesr($t,$sec));}
//======================================================================================
function xyr($x,$y,$ww=''){
    if($ww and $ww!=$GLOBALS['ss']['ww']){
        return(tcolorr("[".intval($x).",".intval($y)."]",'777777'));
    }else{
        return("[".intval($x).",".intval($y)."]");
    }
}
function xy($x,$y){echo(xyr($x,$y));}
//======================================================================================
function labelr($html,$label){
//$label=lr($label);
//return("<span label=\"$label\">$html</span>");
//$label="";//htmlspecialchars($label);
$html="<span title=\"$label\">$html</span>";
return($html);
}
function labele($html,$label){echo(labelr($html,$label));}
//======================================================================================
//tables
//======================================================================================
function liner_($id="use",$p=1){
    $response=xquery("info",$id);
    $id=$response["id"];
    //-----------
    if($p==1)$p='';
    $hline=lr($response["type"].$p)." ".tr($response["name"],true);
    if($response["in"]){
        $hline=$hline.'('.$response["inname"].')';
    }
    return($hline);
}
//--------------------------------------------------------
function liner($id="use",$p=1){
    $response=xquery("info",$id);
    $id=$response["id"];
    //-----------
    if($p==1)$p='';
    $hline=lr($response["type"].$p)." ".tr($response["name"],true);
    if($response["in"]){
        $hline=$hline.textqqr(ahrefr($response["inname"],"page=profile;id=".$response["in"],"none",true));
    }

    //e("e=content;ee=profile;page=profile;ref=left;".show.";id=".$id);
    $hline=ahrefr($hline,"e=content;ee=profile;page=profile;".show.";id=".$id,"none",true);
    return($hline);
}
function line($id="use",$p=1){echo(liner($id,$p));}
//======================================================================================================================
function profiler($id="use"){//@todo Aktualizovat

    $stream="";
    $response=xquery("info",$id);
    //r($response);
    $response["func"]=new func($response["func"]);
    $funcs=$response["func"]->vals2list();
    $response["profile"]=new profile($response["profile"]);
    $array=$response["profile"]->vals2list();
    $response["set"]=new set($response["set"]);
    $array2=$response["set"]->vals2list();
    $id=$response["id"];
    $origin=explode(',',$response["origin"]);
    $level=count($origin);
    if($array["showmail"]){$array["mail"]=$array2["mail"];}
    $array["showmail"]="";

    //----------------------------------------------------------------Základní info ID, LVL, počet budov, vlastník
    $stream.=("<table width=\"".(contentwidth-3)."\"><tr><td valign=\"top\"><table>");
    //-----------
    /*$hline=lrr(contentlang(tfontr(textcolorr(lr($response["type"]),$response["dev"])." ".tr($response["name"],true),18));
    if($response["in"]){
        $hline=lrr(contentlang($hline.textqqr(ahrefr($response["inname"],"page=profile;id=".$response["in"],"none",true))),16);
    }*/
	$stream.=brr();
	$hline=trr(contentlang(lr($response["type"])).' '.tr($response["name"],true),16);
    $stream.=("<tr><td colspan=\"2\"  width=\"300\">$hline<hr/></td></tr>");
    $stream.=("<tr><td><b>".lr("id").": </td><td></b>".($response["id"])."</td></tr>");
    
    if($response["type"]=='building'){
        
        $stream.=("<tr><td><b>".lr("level").": </td><td></b>".$level."</td></tr>");
        $stream.=("<tr><td><b>".lr("life").": </td><td></b>".round($response["fp"])." / ".round($response["fs"])."</td></tr>");
    
    }elseif($response["type"]=='town' or $response["type"]=='town2'){
    
        $building_count=sql_1data('SELECT count(1) FROM `[mpx]pos_obj` as x WHERE x.own='.$id.' AND type=\'building\'');
        $lvl=sql_1data('SELECT sum(fs) FROM `[mpx]pos_obj` WHERE name!=\''.mainname().'\' AND own='.$id.' AND type=\'building\' AND '.objt());
        
        $stream.=("<tr><td><b>".lr("level").": </td><td></b>".$level."</td></tr>");
        $stream.=("<tr><td><b>".lr("building_count").": </td><td></b>".$building_count."</td></tr>");
		
    }elseif($response["type"]=='user'){
        $town_count=sql_1data('SELECT count(1) FROM `[mpx]pos_obj` as x WHERE x.own='.$id.' AND (type=\'town\' OR type=\'town2\')');
        $building_count=sql_1data('SELECT count(1) FROM `[mpx]pos_obj` as x WHERE x.own=(SELECT y.id FROM `[mpx]pos_obj` as y WHERE y.own='.$id.' AND (y.type=\'town\' OR y.type=\'town2\') LIMIT 1) AND type=\'building\'');

        $lvl=sql_1data('SELECT sum(fs) FROM `[mpx]pos_obj` WHERE name!=\''.mainname().'\' AND superown='.$id.' AND type=\'building\' AND '.objt());
		
        $stream.=("<tr><td><b>".lr("level").": </td><td></b>".$level."</td></tr>");

        //$stream.=("<tr><td><b>".lr("town_count").": </td><td></b>".$town_count."</td></tr>");
        $stream.=("<tr><td><b>".lr("building_count").": </td><td></b>".$building_count."</td></tr>");
    }
	if($response["own"]){
	$stream.=("<tr><td><b>".lr("owner").": </td><td></b>"./*ahrefr(id2name,'e=content;ee=profile;id='.$response["own"])*/liner($response["own"])."</td></tr>");
	}
	if($response["superown"] and $response["superown"]!=$response["own"] and $response["superown"]!=$response["id"]){
	$stream.=("<tr><td><b>".lr("superowner").": </td><td></b>"./*ahrefr(id2name,'e=content;ee=profile;id='.$response["own"])*/liner($response["superown"])."</td></tr>");
	}
    //----------------------------------------------------------------Profilové info
    foreach($array as $a=>$b){
        if($a!=''.($a-1+1) and trim($b) and $b!="@" and $a!="text"  and $a!="description"  and $a!="signature"  and $a!="text" and $a!="image" and $a!="public" and $a!="author" and strpos($a,"mail")===false and strpos($a,"fb")===false){
            $pa=$a;
            $a=lr($a);
            $b=tr($b);
            //gender,age,mail,showmail,web
            if($pa=="gender"){$b=lr($b);/*if($b=="m"){$b=lr("Muž");}if($b=="f"){$b=lr("Žena");}*/}
            if($pa=="age"){$b=intval((time()-$b)/(3600*24*365.25),0.1);}
            if($pa=="mail"){$b="<a href=\"mailto: $b\">$b</a>";}
            if($pa=="web"){$b="<a href=\"http://$b/\">$b</a>";}
			if($pa=="color"){$b=tfontr('#'.$b,NULL,$b);}
            //if(!($pa=="description")){
            $stream.=("<tr><td ><b>$a: </b></td><td>$b</td></tr>");
            //}else{
            //    $stream.=("<tr><td colspan=\"2\"><code>$b</code></td></tr>");
            //}
        }
    }
    //-----------
    $stream.=("<tr><td colspan=\"2\"><hr/></td></tr>");
    //------------------------------------------------------------------------Info o funkcích
    //$stream.=("<b>".lr("f_life").": </b>".$response["fp"]."/".$response["fs"]."");
	
	$q=false;
    $classes=array("move","create","attack","defence");
    foreach($classes as $aclass){
        foreach($funcs as $name=>$func){
            $class=$func["class"];
            if($class==$aclass){
                $params=$func["params"];
                $profile=$func["profile"];
                if($profile["name"]){$tmp=textbr($profile["name"]).textqqr(lr("f_$class"));}else{$tmp=textbr(lr("f_$class"));}
                if(!($class=="image" or $class=="message" or $class=="hold" or $class=="info" or $class=="item" or $class=="items" or $class=="login" or $class=="profile_edit" or $class=="set_edit" or $class=="use")){
                    $stream.=("<tr><td colspan=\"2\">$tmp</td></tr>");
                    //$stream.=lr("f_".$name).": </b>$class".br; 
                    foreach($params as $fname=>$param){
                        $e1=$param[0];$e2=$param[1];//$e3=$param[2];$q=$e1*$e2;
                        //$e1=round($e1,2);$e2=round($e2,2);/*$e3=round($e3,2);*/$q=round($q,2);
                        $stream.=("<tr><td>");
                        $stream.=nbsp3.lr("f_".$class."_".$fname).":";
                        $stream.=("</td><td>");
						$q=true;
                        //$q.textqqr("$e1,$e2");
                        //r("$class - $fname");
                        $support1=$support[$class][$fname];
                        if($support1){
                            list($se1,$se2)=$support1;
                            /*$se1=$e1+$se1;
                            $se2=$e2*$se2;*/
                            $stream.=lvlr($e1,$e2,$se1,$se2);
                        }else{
                            $stream.=lvlr($e1,$e2);
                        }
                        $stream.=("</td></tr>");
                    }
                }
            }
        }
    }
    //if($funcs["message"]){$stream.=("<tr><td colspan=\"2\">Tento uživatel může odesílat zprávy.</td></tr>");}
    //if($funcs["image"]){$stream.=("<tr><td colspan=\"2\">Tento uživatel může nahrávat obrázky.</td></tr>");}
    //$stream.=("<tr><td colspan=\"2\"><hr/></td></tr>");
    $stream.=("</table>");
    //
	if($q)$stream.=hrr();
	
    //-----------{""
    $stream.=$stream3;
    //------------------------------------------------------------------------vlastněná města
	if($response["type"]=='user'){
		$iconsize=25;
		$towns=sql_array('SELECT `id`,`name`,`profile` FROM `[mpx]pos_obj` WHERE `own`=\''.($id).'\' AND (`type`=\'town\' OR `type`=\'town2\') ORDER BY `type`,ABS('.$id.'-`id`)');
		foreach($towns as $town){
			list($id_,$name_,$profile_,$mainid_,$x_,$y_,$ww_)=$town;
			$profile_=str2list($profile_);
			$color_=$profile_['color'];
			if(!$color_)$color_='699CFE';
			$border1=array(2,$color_);
			$url='e=content;ee=profile;id='.$id_;
			$stream.=borderr(iconr($url,"profile_town",contentlang($name_),$iconsize,NULL),$border1,$iconsize);
			$stream.=nbsp3;
			$stream.=ahrefr(trr(contentlang($name_),15),$url);
			$stream.=brr();
		}
		$stream.=hrr();
	}
    //------------------------------------------------------------------------Další akce
        //--------------Vlastní
        if($response['ww']==$GLOBALS['ss']['ww']){
        //$stream.=hrr();
        if($GLOBALS['ss']['useid']==$id or $GLOBALS['ss']['logid']==$id){
            
            $stream.=ahrefr(lr("profile_edit"),"e=content;ee=settings;submenu=1;id=$id",false);
            $stream.=brr();
            
            if($GLOBALS['ss']['logid']==$id){
                $stream.=ahrefr(lr("password_edit"),"e=content;ee=settings;submenu=2",false);
                $stream.=brr();
            }
        }
		//--------------Nejste to vy
		{
			//--------------útok
             if($response["type"]=='building' or $response["type"]=='tree' or $response["type"]=='rock'){
                    $stream.=ahrefr(lr("attack_".$response["type"]),"e=content;ee=attack-attack;page=attack;set=attack_id,$id",false);
					$stream.=brr();
             }elseif($response["type"]=='user'){
					//--------------Poslat zprávu
                    $stream.=ahrefr(lr('send_message'),"e=content;ee=text-messages;submenu=5;to=$id",false);
					$stream.=brr();
             }elseif($response["type"]=='town' or $response["type"]=='town2' or $response["type"]=='building'){
					//--------------Centrovat mapu
					$url=centerurl($id,$response["x"],$response["y"],$response["ww"]);
    				$stream.=ahrefr(lr('stat_center'),$url);
					$stream.=brr();
                    //$stream.=ahrefr(lr('send_message'),"e=content;ee=text-messages;submenu=5;to=$id",false);

             }
        }}

    //----------------------------------------------------------------OBRázek, POPIS
    if($response["type"]!="message") {
        $stream.=("</td><td align=\"justify\" valign=\"top\" width=\"147\">");

        if($response["type"]=='user'){
            $email=sql_1data("SELECT email FROM `[mpx]users` WHERE id=(SELECT `userid` FROM `[mpx]pos_obj` WHERE id=".$id." AND ".objt()." LIMIT 1) AND aac=1 LIMIT 1");
            $stream.=('<img src="'.gravatar($email,147).'" border="2">');
        }else{
            $stream.=imgr("id_$id","",147,NULL,1,2);
        }

        $stream.=br;
        $stream.=($array['description']);
    }/*else{
        $stream.=("</td><td width=\"200\" align=\"left\" valign=\"top\">");
        $stream.="<b>".tr($array["subject"])."</b>".br;
        $stream.=tr($array["text"]);
    }*/
    $stream.=("</td></tr></table>");
    return($stream);
	//----------------------------------------------------------------
}
function profile($id="use"){echo(profiler($id));}


//======================================================================================================================ROZDĚLOVAČE
/* Rozdělovač dvou nezávislých funkcí se stejným názvem
 * zobrazení textu vs. commitnutí řádku HTML tabulky
 *
 * @uses tr_text
 * @uses tr_text
 *
 * */
function tr($param1=false,$param2=false){
    if($param1) {
        return(tr_text($param1, $param2));
    }else{
        return(tr_table());
    }
}

?>
