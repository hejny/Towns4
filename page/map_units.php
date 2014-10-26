<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/page/map_units.php

   Mapa budov
*/
//==============================
/*if(!$GLOBALS['mapzoom']){
	if(!$GLOBALS['mobile']){
	    $GLOBALS['mapzoom']=1;
	}else{
	    $GLOBALS['mapzoom']=pow(gr,(1/2));
	}
}*/
//==============================


require_once(root.core."/func_map.php");

    $GLOBALS['units_stream']='';
    $GLOBALS['area_stream']='';
    $GLOBALS['attack_stream']='';



	$say="''";//"(SELECT IF((`".mpx."text`.`timestop`=0 OR ".time()."<=`".mpx."text`.`timestop`),`".mpx."text`.`text`,'')  FROM `".mpx."text` WHERE `".mpx."text`.`from`=`".mpx."objects`.id AND `".mpx."text`.`type`='chat' ORDER BY `".mpx."text`.time DESC LIMIT 1)";
	//$say="'ahoj'";
	$profileown="(SELECT `profile` from [mpx]objects as x WHERE x.`id`=".mpx."objects.`own`) as `profileown`";
	    $xcu=0;
	    $ycu=0;
	    if($GLOBALS['ss']["map_xc"])$xcu=$GLOBALS['ss']["map_xc"];
	    if($GLOBALS['ss']["map_yc"])$ycu=$GLOBALS['ss']["map_yc"];
	    //echo($xcu.','.$ycu);
	    
	    $xu=($ycu+$xcu)*5+1;
	    $yu=($ycu-$xcu)*5+1;
	    
	    //echo(tab(150).$xxu);
	$rxp=424*2.5;//+$xxu;
	$ryp=0;//+$yyu;
	//$p=(200*0.75*((212)/375));
	$px=424/10;$py=$px/2;


if($_GET['id'])$GLOBALS['map_units_ids']=array($_GET['id']);

if(!$GLOBALS['map_units_ids']){

	/*if($_GET['uzidsid']){
		$uzids=explode(',',$_GET['uzidsid']);
		$where=$uzids;
		$where=implode("' OR `id`='",$where);
		$where="(`id`='$where')";
	}else{
		$uzids=array();
		$where="0";
	}*/
	/*$arange=-5;
	$range=45;
	$xpp=5;//5;
	$ypp=-5;//-5;
	$range="x>$xu-$arange+$xpp AND y>$yu-$arange+$ypp AND x<$xu+$range+$xpp AND y<$yu+$range+$ypp";
	$range="(x-y)>($xu-$yu) AND (y-x)>($yu-$xu)-30 AND (x-y)<($xu-$yu)+5 AND (y-x)<($yu-$xu)+20";*/


	if(!mobile){
	/*dafaq*/
	$range="(x-y)>($xu-$yu)-".(logged()?20:26)." AND (x+y)>($xu+$yu)+".(logged()?5:2)." AND (x-y)<($xu-$yu)+".(logged()?35:22)." AND (x+y)<($xu+$yu)+".(logged()?60:55)."";
	}else{
	$range="(x-y)>($xu-$yu)-20 AND (x+y)>($xu+$yu)+5 AND (x-y)<($xu-$yu)+10 AND (x+y)<($xu+$yu)+50";
	}


	//$range=1;
	//echo($range);
	$hlname=id2name($GLOBALS['config']['register_building']);
	// OR (`type`='rock' AND RAND()<0.01)
		//$mapunitstime=intval(file_get_contents(tmpfile2("mapunitstime","txt","text")));
		// AND ((`own`=".useid." AND `expand`!=0) OR `collapse`!=0 OR `t`>$mapunitstime)  AND NOT ($where)
	$array=sql_array("SELECT `x`,`y`,`type`,`res`,`set`,`name`,`id`,`own`,$say,$profileown,expand,collapse,attack,t,`func`,`fp`,`fs` FROM `[mpx]objects` WHERE ww=".$GLOBALS['ss']["ww"]." AND `type`='building' AND "/*." AND (`type`='building') AND "*/.$range/*" AND (`name`!='$hlname' OR (SELECT COUNT(1) FROM [mpx]objects AS X WHERE X. `own`= [mpx]objects.`own` AND X. `type`='building')>1 OR `own`='".logid."' OR `own`='".useid."')"/**/);
}else{
	$where=$GLOBALS['map_units_ids'];
	$where=implode("' OR `id`='",$where);
	$where="(`id`='$where')";
	$array=sql_array("SELECT `x`,`y`,`type`,`res`,`set`,`name`,`id`,`own`,$say,$profileown,expand,collapse,attack,t,`func`,`fp`,`fs` FROM `[mpx]objects` WHERE ww=".$GLOBALS['ss']["ww"]." AND `type`='building' AND $where");
}


foreach($array as $row){//WHERE res=''//modelnamape//   

    //if(connection_aborted()){die();}
    
    t($name.' - start');    


	//t($name);
    $type=$row[2];    
    $res=$row[3];
    
    $set=$row[4];
    //$func=$row[5];
    //$func=func2list($func);
    $name=trim($row[5]);
    $name=contentlang($name);
    //echo($name);br();
    $id=$row[6];
    $own=$row[7];
    //$text=xx2x($row[8]);
    $profileown=$row[9];
    
    $expand=floatval($row[10]);
    $collapse=floatval($row[11]);
    $attack=floatval($row[12]);
    
    /*$func=new func($row[16]);
    $attack=$func->param('attack','distance');
    //e($row[16]);br();
    unset($func);*/
    
    $time=intval($row[13]);
    $func=$row[14];
    $fp=$row[15];
    $fs=$row[16];
    //$idid=$row[17];

    $uzids[]=$id;

    //$profileown=str2list($profileown);
    //$profileown=$profileown['color'];

    
  

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

    
    if(contentlang(id2name($GLOBALS['config']['register_building']))==$name and logged()){ 
	$say=id2name($own);
	if(!is_numeric($say)){
        	$say=str_replace(' ','&nbsp;',$say);
	}else{
		//$say=($say.','.logid);
		if($own==useid){
			$say=lr('xtype_own');
		}else{
			$say=lr('xtype_noreg');
		}
	}
    }else{
        $say='';
    }
    
    if($id==useid){
        $_xc=$GLOBALS['ss']["use_object"]->x;
        $_yc=$GLOBALS['ss']["use_object"]->y;
        //$text=($_xc.','.$_yc);
        
    }else{
        $_xc=$row[0];
        $_yc=$row[1];
    }
    $xx=$_xc-$xu;
    $yy=$_yc-$yu;
    //if($id!=useid)$xx=$xx+(rand(0,100)/100);
    /*$ix2rx=0.5*$p;$ix2ry=0.25*$p;
    $iy2rx=-0.5*$p;$iy2ry=0.25*$p;
    $rx=($ix2rx*$xx)+($iy2rx*$yy)+$rxp;
    $ry=($ix2ry*$xx)+($iy2ry*$yy)+$ryp;*/
    $rx=round((($px*$xx)-($px*$yy)+$rxp)/$GLOBALS['mapzoom']);
    $ry=round((($py*$xx)+($py*$yy)+$ryp)/$GLOBALS['mapzoom']);
    if($id==useid){
        $built_rx=$rx;
        $built_ry=$ry;
        //r($built_rx);
    }
    //if($rx>156 and $ry>0 and $rx<424*2.33-10 and $ry<212*3-20/* and $id!=useid*/){ }
        //GRM313
        if(/*($res or debug) and ($set=='x' or $set=='0=x') or $set=='x=x'*/true){
        
        /*if($own==useid){
            //echo('aaa');
            $area=$func['expand']['params']['distance'][0]*$func['expand']['params']['distance'][1];
            if($area){
                //echo($area);
            }       
        }*/
        
	t($name.' - beforeexpandcollapse');

       //--------------------------------------------------------------EXPAND,COLLAPSE
           
        if(($expand and $own==useid) or $collapse){
	    if($own!=useid){$expand=0;}
        $file=tmpfile2('expand'.$expand.'collapse'.$collapse,'png',"image");
        //e($file);
	       $y=1;//gr;
	       $brd=3*$y;
	       $se=82*$expand*$y;
	       $sc=82*$collapse*$y;
	       $s=max(array($se,$sc));   
     
        if(!file_exists($file)  or notmpimg/** or true/**/){


		
		//$sesc="$expand=$se,$collapse=$sc";

                $img=imagecreate/*truecolor*/($s,$s/2);
                imagealphablending($img,false);
                $outer =  imagecolorallocatealpha($img, 0, 0, 0, 127);
		imagealphablending($img,true);
                imagefill($img,0,0,$outer);
                
        $sxs=array('se'=>$se,'sc'=>$sc);
        arsort($sxs);
        foreach($sxs as $key=>$sx){
            if($sx){
        		//-----EXPAND
        		if($key=='se'){
                        $inner =  imagecolorallocatealpha($img, 50, 50, 70, 60);//, 0, 0, 0, 70
        		}
        		//-----COLLAPSE
        		if($key=='sc'){
                     	$inner =  imagecolorallocatealpha($img, 255, 0, 40, 70);
        		}
        		//-----ATTACK
        		/*if($key=='sa'){
                     	$inner =  imagecolorallocatealpha($img, 200, 255, 10, 60);
        		}*/
        		//-----DRAW
        		imagefilledellipse($img, $s/2, $s/4, $sx-$brd, ($sx/2)-$brd, $inner);
            }
        }
		//-----
                imagesavealpha($img,true);
                imagepng($img,$file,9,PNG_ALL_FILTERS);
		        imagedestroy($img);
                chmod($file,0777);
        }
        
        $src=rebase(url.base.$file);        
        $GLOBALS['area_stream'].='<div style="position:absolute;z-index:150;" id="expand'.$id.'">
        <div style="position:relative; top:'.($ry-((($s/$y/4)+htmlbgc)/$GLOBALS['mapzoom'])).'; left:'.($rx-($s/$y/2/$GLOBALS['mapzoom'])).';" >
        <img src="'.$src.'" widht="'.($s/$y/$GLOBALS['mapzoom']).'" height="'.($s/$y/2/$GLOBALS['mapzoom']).'"  class="clickmap" border="0" />
        </div></div>';
        }   
       //--------------------------------------------------------------ATTACK
	//$attackx=$attack;
	//$attack=1;
       if($attack or $own!=useid){
	    if($own!=useid){$attack=1;$aa=gr;}else{
		$aa=gr;
		//$attack_mafu=$GLOBALS['ss']["use_object"]->set->val("attack_mafu");
		//list($attack_ma)=explode('-',$attack_mafu);
		$set=$GLOBALS['ss']["use_object"]->set->val("set");
		$set=str2list(xx2x($set));
		$attack_mafu=$set['attack_mafu'];
		list($attack_ma)=explode('-',$attack_mafu);
		//print_r($set);br();
		//e("$attack_ma==$id");br();
		if($attack_ma==$id){
			$selected='selected';
		}else{
			$selected='';
		}
		}


        $file=tmpfile2('attack'.($own==useid?$attack:'x').$selected,'png',"image");
        //e($file);
	       $y=1;//gr;
	       $brd=3*$y;
	       $s=82*$attack*$y;
     
        if(!file_exists($file) or notmpimg/** or true/**/){


		
		//$sesc="$expand=$se,$collapse=$sc";

                $img=imagecreate($aa*$s,$aa*$s/2);
                imagealphablending($img,false);
                $outer =  imagecolorallocatealpha($img, 0, 0, 0, 127);
		imagealphablending($img,true);
		//imageantialias($img,true);
                imagefill($img,0,0,$outer);
                
		if($own==useid){
			if($selected){
        			$inner =  imagecolorallocatealpha($img, 150, 255, 255, 70);
				$outer =  imagecolorallocatealpha($img, 0, 0, 0, 50);
			}else{
        			$inner =  imagecolorallocatealpha($img, 150, 255, 255, 70);
				$outer =  imagecolorallocatealpha($img, 0, 0, 0, 120);
			}
			
			$plus=2;
			imagefilledellipse($img, $aa*$s/2, $aa*$s/4, $aa*$s, $aa*($s/2), $outer);
			imagefilledellipse($img, $aa*$s/2, $aa*$s/4, $aa*($s-$brd), $aa*(($s/2)-$brd), $inner);
		}else{
        		$inner =  imagecolorallocatealpha($img, 255, 100, 100, 80);
			$outer =  imagecolorallocatealpha($img, 0, 0, 0, 50);
			$plus=2;
			imagefilledellipse($img, $aa*$s/2, $aa*$s/4, $aa*$s, $aa*($s/2), $outer);
			imagefilledellipse($img, $aa*$s/2, $aa*$s/4, $aa*($s-$brd), $aa*(($s/2)-$brd), $inner);
		}
		//-----
                imagesavealpha($img,true);
                imagepng($img,$file,9,PNG_ALL_FILTERS);
		imagedestroy($img);
                chmod($file,0777);
        }
        
        $src=rebase(url.base.$file);


	//die($src);    
        $GLOBALS['attack_stream'].='<div style="position:absolute;z-index:150;" id="attack'.$id.'">
        <div style="position:relative; top:'.($ry-((($s/$y/4)+htmlbgc)/$GLOBALS['mapzoom'])).'; left:'.($rx-($s/$y/2/$GLOBALS['mapzoom'])).';" >'./*$attackx.*/'       	
	<img src="'.$src.'" widht="'.($s/$y/$GLOBALS['mapzoom']).'" height="'.($s/$y/2/$GLOBALS['mapzoom']).'"  class="clickmap" border="0" />
        </div></div>';
        } 

	
	//--------------------------------------------------------------
        	t($name.' - beforeunit');
	if(1/* and $time>$mapunitstime*/){

        $modelurl=modelx($res,$fp/$fs/*$_GLOBALS['map_night']*/,$usercolor);
	t($name.' - afrer modelx');
        //TOTÁLNÍ MEGASRAČKA - //list($width, $height) = getimagesize($modelurl);
	//TOTÁLNÍ MEGASRAČKA - echo("$width, $height");
	if(substr($res,0,1)=='['){
		$width=133;
		$height=254;
	}else{
		$width=82;
		$height=123;
	}
	t($name.' - afrer getimagesize');
        if(!$GLOBALS['model_resize'])  $GLOBALS['model_resize']=1;      
        $width=$width*$GLOBALS['model_resize'];
        $height=$height*$GLOBALS['model_resize'];
        // width="83"
        
        ?>
        <?php
        //ob_start();        
        /**/
	
	$GLOBALS['units_stream'].='
        <div style="position:absolute;z-index:'.($ry+1000).';" '.($id==useid?'id="jouu"':'').'>
        <div id="object'.$id.'" style="position:relative; top:'.($ry+round((-132-$height+157+4)/$GLOBALS['mapzoom'])).'; left:'.($rx+round((-43+2)/$GLOBALS['mapzoom'])).';">
	';

        if($res and (/*$own==useid or */$time>$mapunitstime)){

	$GLOBALS['units_stream'].='
        <img src="'.($modelurl).'" width="'.(round(82/$GLOBALS['mapzoom'])).'" class="clickmap" border="0" alt="'.aacute(ENT_QUOTES).'" title="'.(aacute($name)).'"/>
	';
        
	}else{r('!res');}
	t($name.' - afrer show res');
          
        $GLOBALS['units_stream'].='</div>';
        $GLOBALS['units_stream'].='</div>';
	}

        $GLOBALS['units_stream'].='
        <div style="position:absolute;z-index:'.($ry+2000).';" >
        <div title="'.aacute(($name)).'" style="position:relative; top:'.($ry+round((-132-40+157)/$GLOBALS['mapzoom'])).'; left:'.($rx+round((-43+7)/$GLOBALS['mapzoom'])).';">
        <img src="'.imageurl('design/blank.png').'" class="unit" id="'.($id).'" border="0" alt="'.aacute(aacute($name)).'" title="'.(aacute($name)).'" width="'.(round(70/$GLOBALS['mapzoom'])).'" height="'.(round(35/$GLOBALS['mapzoom'])).'"/>
        </div>    
        </div>';
        t($name.' - afrer show shit');
        
        if($say){
        
	$GLOBALS['units_stream'].='
        <div class="saybox" style="position:absolute;display:'.(onlymap?'none':'block').';z-index:'.($ry+2000).';" >
        <div title="'.(aacute($name)).'" style="position:relative; top:'.($ry-round(100/$GLOBALS['mapzoom'])).'; left:'.($rx+((-43+7)/$GLOBALS['mapzoom'])).';background: rgba(0,0,0,0.75); border-radius: 2px;'.($usercolor?'border: 2px solid #'.$usercolor.';':'').' padding: 4px;">'.trr($say,13,NULL,'class="clickmap"').'</div>    
        </div>';

	t($name.' - afrer show say');        

        }
        

	t($name.' - konec');

        //$GLOBALS['units_stream'].=ob_get_contents();
        //ob_end_clean();
	//$GLOBALS['units_uzids']=$uzids;
        /**/ ?>

	

        <?php } ?>
        
        
        <?php
        
        }
        
        $GLOBALS['units_stream'].=jsr('map_units_time='.time());
        ?>
        
  

<?php
/*
<script>
<?php
if(!logged()){
    subjs('units_stream',$GLOBALS['units_stream']);
}
?>
//$('#zaloha_u').html('');
//$('#units_stream').css('left',0);
//$('#units_stream').css('top',0);
</script>
<!--usemap="#clickmap"<map name="clickmap" id="clickmap">
<area shape="poly" coords="0,137,41,116,83,137,41,157" href="#" class="unit" />
</map>-->
*/
t('konec');
?>

    
