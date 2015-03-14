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

//------------------------------------------------------------------------------------------------------WHERE PREPARE

	$say="''";//"(SELECT IF((`[mpx]text`.`timestop`=0 OR ".time()."<=`[mpx]text`.`timestop`),`[mpx]text`.`text`,'')  FROM `[mpx]text` WHERE `[mpx]text`.`from`=`[mpx]pos_obj`.id AND `[mpx]text`.`type`='chat' ORDER BY `[mpx]text`.time DESC LIMIT 1)";
	//$say="'ahoj'";
	$profileown="(SELECT `profile` from `[mpx]pos_obj` as x WHERE x.`id`=`[mpx]pos_obj`.`own` LIMIT 1) as `profileown`";
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

        $whereplay=($GLOBALS['get']['play']?'':' AND '.objt());

//------------------------------------------------------------------------------------------------------SELECT - ONLY
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
		// AND ((`own`=".$GLOBALS['ss']['useid']." AND `expand`!=0) OR `collapse`!=0 OR `t`>$mapunitstime)  AND NOT ($where)
	$array=sql_array("SELECT `x`,`y`,`type`,`res`,`set`,`name`,`id`,`own`,$say,$profileown,expand,block,attack,t,`func`,`fp`,`fs`,`starttime`,`readytime`,`stoptime` FROM `[mpx]pos_obj` WHERE ww=".$GLOBALS['ss']["ww"]." AND `type`='building' AND "/*." AND (`type`='building') AND "*/.$range.$whereplay );
}else{                   /*" AND (`name`!='$hlname' OR (SELECT COUNT(1) FROM `[mpx]pos_obj` AS X WHERE X. `own`= `[mpx]pos_obj`.`own` AND X. `type`='building')>1 OR `own`='".$GLOBALS['ss']['logid']."' OR `own`='".$GLOBALS['ss']['useid']."')"*/
    //------------------------------------------------------------------------------------------------------SELECT ALLES
        $where=$GLOBALS['map_units_ids'];
	$where=implode("' OR `id`='",$where);
	$where="(`id`='$where')";
	$array=sql_array("SELECT `x`,`y`,`type`,`res`,`set`,`name`,`id`,`own`,$say,$profileown,expand,block,attack,t,`func`,`fp`,`fs`,`starttime`,`readytime`,`stoptime` FROM `[mpx]pos_obj` WHERE ww=".$GLOBALS['ss']["ww"]." AND `type`='building' AND $where".$whereplay);// AND ".objt()
}


//print_r($array);
//------------------------------------------------------------------------------------------------------FOREACH
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
    $block=floatval($row[11]);
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
    $starttime=$row['starttime'];
    $readytime=$row['readytime'];
    $stoptime=$row['stoptime'];
    if(!$stoptime or $stoptime>time()){
         $onmap=true;
        if($readytime>time()){
             
            $fpfs=1+($readytime-time())/($readytime-$starttime);
            
        }else{
            $fpfs=$fp/$fs;
        }
         
    }else{
        $onmap=false;
    }

    $uzids[]=$id;

    //$profileown=str2list($profileown);
    //$profileown=$profileown['color'];

    
  
        //------------------------------------------------Barva uživatele
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


    //------------------------------------------------------------------------------------------------------pozice
    
    /*if($id==$GLOBALS['ss']['useid']){
        $_xc=$GLOBALS['ss']["use_object"]->x;
        $_yc=$GLOBALS['ss']["use_object"]->y;
        //$text=($_xc.','.$_yc);
        
    }else{*/
        $_xc=$row[0];
        $_yc=$row[1];
    /*}*/
    $xx=$_xc-$xu;
    $yy=$_yc-$yu;

    //------------------------------------------------------------------------------------------------------
    
    $rx=round((($px*$xx)-($px*$yy)+$rxp)/$GLOBALS['mapzoom']);
    $ry=round((($py*$xx)+($py*$yy)+$ryp)/$GLOBALS['mapzoom']);
    if($id==$GLOBALS['ss']['useid']){
        $built_rx=$rx;
        $built_ry=$ry;
    }
    
    //------------------------------------------------------------------------------------------------------
    
        if(/*($res or debug) and ($set=='x' or $set=='0=x') or $set=='x=x'*/true){
        
	t($name.' - beforeexpandcollapse');

           
       //------------------------------------------------------------------------------------------------------EXPAND,COLLAPSE
        define('size_radius',100);


        if($onmap and (($expand and $own==$GLOBALS['ss']['useid']) or $block)){
	    if($own!=$GLOBALS['ss']['useid']){$expand=0;$aa=gr;$ad='q';}else{$aa=gr;$ad='w';}
		if($block){$block=distance_wall;}else{$block=0;}
		//$expand=0.3;
		//$expand=0.1;

        $file=tmpfile2(size_radius.'expand'.$expand.'block'.$block.$ad/**.rand(1,9999)/**/,'png',"image");
        //e($file);
	       $y=1;//gr;
	       $brd=3*$y;
	       $se=size_radius*$expand*$y;
	       $sc=size_radius*$block*$y;
	       $s=max(array($se,$sc));   
     
        if(!file_exists($file)  or notmpimg/** or true/**/){


		
		//$sesc="$expand=$se,$collapse=$sc";

                $img=imagecreate($aa*$s,$aa*$s/2);
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
						$outer =  imagecolorallocatealpha($img, 0, 0, 0, 50);
						imagefilledellipse($img, $aa*$s/2, $aa*$s/4, $aa*$sx, $aa*($sx/2), $outer);
						imagefilledellipse($img, $aa*$s/2, $aa*$s/4, $aa*($sx-$brd), $aa*(($sx/2)-$brd), $inner);
        		}
        		//-----BLOCK
        		if($key=='sc'){
						if($own!=$GLOBALS['ss']['useid']){
                     		$inner =  imagecolorallocatealpha($img, 255, 0, 40, 70);
							$outer =  imagecolorallocatealpha($img, 150, 0, 20,  50);
						}else{
                     		$inner =  imagecolorallocatealpha($img, 0, 255, 40, 70);
							$outer =  imagecolorallocatealpha($img, 0, 150, 20,  50);
						}
						
						imagefilledellipse($img, $aa*$s/2, $aa*$s/4, $aa*$sx, $aa*($sx/2), $outer);
						imagefilledellipse($img, $aa*$s/2, $aa*$s/4, $aa*($sx-$brd), $aa*(($sx/2)-$brd), $inner);
        		}
        		//-----ATTACK
        		/*if($key=='sa'){
                     	$inner =  imagecolorallocatealpha($img, 200, 255, 10, 60);
        		}*/
        		//-----DRAW

            }
        }
		//-----
                imagesavealpha($img,true);
                imagepng($img,$file,9,PNG_ALL_FILTERS);
		        imagedestroy($img);
                chmod($file,0777);
        }
        
        $src=rebase(url.$file);
        $GLOBALS['area_stream'].='<div style="position:absolute;z-index:150;" id="expand'.$id.'">
        <div style="position:relative; top:'.($ry-((($s/$y/4)+htmlbgc)/$GLOBALS['mapzoom'])).'; left:'.($rx-($s/$y/2/$GLOBALS['mapzoom'])).';" >
        <img src="'.$src.'" widht="'.($s/$y/$GLOBALS['mapzoom']).'" height="'.($s/$y/2/$GLOBALS['mapzoom']).'"  class="clickmap" border="0" />
        </div></div>';
        }   
       //------------------------------------------------------------------------------------------------------ATTACK
	//$attackx=$attack;
	//$attack=1;
       if($onmap and ($attack or $own!=$GLOBALS['ss']['useid'])){
	    if($own!=$GLOBALS['ss']['useid']){$attack=1;$aa=gr;}else{
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


        $file=tmpfile2(size_radius.'attack'.($own==$GLOBALS['ss']['useid']?$attack:'x').$selected,'png',"image");
        //e($file);
	       $y=1;//gr;
	       $brd=3*$y;
	       $s=size_radius*$attack*$y;
     
        if(!file_exists($file) or notmpimg/** or true/**/){


		
		//$sesc="$expand=$se,$collapse=$sc";

                $img=imagecreate($aa*$s,$aa*$s/2);
                imagealphablending($img,false);
                $outer =  imagecolorallocatealpha($img, 0, 0, 0, 127);
		imagealphablending($img,true);
		//imageantialias($img,true);
                imagefill($img,0,0,$outer);
                
		if($own==$GLOBALS['ss']['useid']){
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
        
        $src=rebase(url.$file);






	//die($src);    
        $GLOBALS['attack_stream'].='<div style="position:absolute;z-index:150;" id="attack'.$id.'">
        <div style="position:relative; top:'.($ry-((($s/$y/4)+htmlbgc)/$GLOBALS['mapzoom'])).'; left:'.($rx-($s/$y/2/$GLOBALS['mapzoom'])).';" >'./*$attackx.*/'       	
	<img src="'.$src.'"width="'.($s/$y/$GLOBALS['mapzoom']).'" height="'.($s/$y/2/$GLOBALS['mapzoom']).'"  class="clickmap" border="0" />
        </div></div>';
        } 

	
	//------------------------------------------------------------------------------------------------------MODEL
        	t($name.' - beforeunit');

        $modelurl=modelx($res,$fpfs/*$_GLOBALS['map_night']*/,$usercolor);
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
        <div style="position:absolute;z-index:'.($ry+1000).';display:'.($onmap?'block':'none').';"  class="timeplay" starttime="'.($starttime?$starttime:0).'" stoptime="'.($stoptime?$stoptime:0).'" >
        <div id="object'.$id.'" style="position:relative; top:'.($ry+round((-132-$height+157+4)/$GLOBALS['mapzoom'])).'; left:'.($rx+round((-43+2)/$GLOBALS['mapzoom'])).';">
	';

        if($res and (/*$own==$GLOBALS['ss']['useid'] or */$time>$mapunitstime)){

	//---------------------------------First Buildings for help
	//$yourid=id2name($own)."-$own=sagfdr=".$GLOBALS['ss']['useid'];
	$yourid='';
	if($own==$GLOBALS['ss']['useid']){
		$specialname=$row[5];
		$specialname=str_replace(array('{','}'),'',$specialname);
		$yourid='id="first_'.$specialname.'"';
		//if(in_array($specialname,array('building_name'))){
			if(!isset($GLOBALS['first'])){$GLOBALS['first']=array();}
			if(!isset($GLOBALS['first'][$specialname])){
				$GLOBALS['first'][$specialname]=true;
				$yourid='id="first_'.$specialname.'"';
			}
		//}

		
	}
	//---------------------------------



	$GLOBALS['units_stream'].='
        <img src="'.($modelurl).'"   width="'.(round(82/$GLOBALS['mapzoom'])).'" class="clickmap" border="0" alt="'.aacute(aacute($name)).'" title="'.(aacute($name)).'"/>
	';
        
	}else{r('!res');}
	t($name.' - afrer show res');
          
        $GLOBALS['units_stream'].='</div>';
        $GLOBALS['units_stream'].='</div>';

        //------------------------------------------------------------------------------------------------------UNIT CLICKMAP
        if($onmap){
            $GLOBALS['units_stream'].='
            <div style="position:absolute;z-index:'.($ry+2000).';" >
            <div title="'.aacute(($name)).'" style="position:relative; top:'.($ry+round((-132-40+157)/$GLOBALS['mapzoom'])).'; left:'.($rx+round((-43+7)/$GLOBALS['mapzoom'])).';" '.$yourid.'>
            <img src="'.imageurl('design/blank.png').'" class="unit" id="'.($id).'" border="0" alt="'.aacute(aacute($name)).'" title="'.(aacute($name)).'" width="'.(round(70/$GLOBALS['mapzoom'])).'" height="'.(round(35/$GLOBALS['mapzoom'])).'"/>
            </div>    
            </div>';
            t($name.' - afrer show shit');
        }
        //------------------------------------------------------------------------------------------------------Nápis nad městem / budovou
        
        
        if($onmap){

            if(contentlang(id2name($GLOBALS['config']['register_building']))==$name and logged()){ 
                $say=id2name($own);
                if(!is_numeric($say)){
                        $say=str_replace(' ','&nbsp;',$say);
                }else{
                        //$say=($say.','.$GLOBALS['ss']['logid']);
                        if($own==$GLOBALS['ss']['useid']){
                                $say=lr('xtype_own');
                        }else{
                                $say=lr('xtype_noreg');
                        }
                }
            }else{
                $say='';
            }
            //------------------------------------SHOW
            if($say){
                $GLOBALS['units_stream'].='
                <div class="saybox" style="position:absolute;display:'.(onlymap?'none':'block').';z-index:'.($ry+2000).';" >
                <div title="'.(aacute($name)).'" style="position:relative; top:'.($ry-round(100/$GLOBALS['mapzoom'])).'; left:'.($rx+((-43+7)/$GLOBALS['mapzoom'])).';background: rgba(0,0,0,0.75); border-radius: 2px;'.($usercolor?'border: 2px solid #'.$usercolor.';':'').' padding: 4px;">'.trr($say,13,NULL,'class="clickmap"').'</div>    
                </div>';

                t($name.' - afrer show say');   
            }

        }
        
        //------------------------------------------------------------------------------------------------------
        
	t($name.' - konec');

        }
        
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

    
