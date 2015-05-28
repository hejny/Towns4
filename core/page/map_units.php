<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2015
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

if(!$GLOBALS['all_images'])
$GLOBALS['all_images']=array();


$all_images_spec=array();

$GLOBALS['units_stream']='';

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


$whereplay=($GLOBALS['get']['play']?'':' AND '.objt());

//------------------------------------------------------------------------------------------------------SELECT - ALLES
//if(!$GLOBALS['map_units_ids']){ todo zrušit uplně



    $range='1';
    $range="x>$xu-5 AND y>$yu-10 AND x<$xu+40 AND y<$yu+40";
    $range.=" AND (x-y)>($xu-$yu)-".(logged()?20:26)." AND (x-y)<($xu-$yu)+".(logged()?35:22)." AND (x+y)>($xu+$yu)+".(logged()?5:2)." AND (x+y)<($xu+$yu)+".(logged()?60:55)."";


    $hlname=id2name($GLOBALS['config']['register_building']);

    /*$objects=array_merge(
        sql_assoc("SELECT `x`,`y`,`type`,`res`,`set`,`name`,`id`,`own`,$profileown,expand,block,attack,t,`func`,`fp`,`fs`,`starttime`,`readytime`,`stoptime` FROM `[mpx]pos_obj` WHERE ww=".$GLOBALS['ss']["ww"]." AND `type`='building' AND ".$range.$whereplay )
        ,sql_assoc("SELECT `x`,`y`,`type`,'t' as `res`,`id`,t,`fp`,`fs`,`starttime`,`stoptime` FROM `[mpx]pos_obj` WHERE ww=".$GLOBALS['ss']["ww"]." AND `type`='tree' AND ".$range.$whereplay )
        ,sql_assoc("SELECT `x`,`y`,`type`,`res`,`id`,t,`fp`,`fs`,`starttime`,`stoptime` FROM `[mpx]pos_obj` WHERE ww=".$GLOBALS['ss']["ww"]." AND `type`='rock' AND ".$range.$whereplay )
        ,sql_assoc("SELECT `x`,`y`,`type`,`res`,`name`,`id`,t,`func`,`fp`,`fs`,`starttime`,`stoptime` FROM `[mpx]pos_obj` WHERE ww=".$GLOBALS['ss']["ww"]." AND `type`='story' AND ".$range.$whereplay )
    );*/


    $sql=sql_mpx("SELECT `x`,`y`,`type`,`res`,`set`,`name`,`id`,`own`,$profileown,expand,block,attack,t,`func`,`fp`,`fs`,`starttime`,`readytime`,`stoptime` FROM `[mpx]pos_obj` WHERE ww=".$GLOBALS['ss']["ww"]." AND (`type`='building' OR `type`='tree' OR `type`='rock' OR `type`='story') AND ".$range.$whereplay.' ORDER BY `x`+`y`' );

    $GLOBALS['tmp']=($sql);

    $objects= $GLOBALS['pdo']->query($sql);

/*}else{

    //------------------------------------------------------------------------------------------------------SELECT ONLY
    $where=$GLOBALS['map_units_ids'];
    $where=implode("' OR `id`='",$where);
    $where="(`id`='$where')";
    //$objects=sql_array("SELECT `x`,`y`,`type`,`res`,`set`,`name`,`id`,`own`,$profileown,expand,block,attack,t,`func`,`fp`,`fs`,`starttime`,`readytime`,`stoptime` FROM `[mpx]pos_obj` WHERE ww=".$GLOBALS['ss']["ww"]." AND `type`='building' AND $where".$whereplay);

    $sql=sql_mpx("SELECT `x`,`y`,`type`,`res`,`set`,`name`,`id`,`own`,$profileown,expand,block,attack,t,`func`,`fp`,`fs`,`starttime`,`readytime`,`stoptime` FROM `[mpx]pos_obj` WHERE ww=".$GLOBALS['ss']["ww"]." AND `type`='building' AND $where".$whereplay);
    $objects= $GLOBALS['pdo']->query($sql);

}*/


//print_r($array);


//======================================================================================================================FOREACH
while($object = $objects -> fetch(PDO::FETCH_ASSOC)){

    t($object['name'] . ' - start');
    //------------------------------------------------------------------------------------------------------------------Příprava proměnných

    $uzids[] = $object['id'];

    $object['pname'] = $object['name'];


    if($object['type']=='building')
        $object['name'] = contentlang(trim($object['name']));
    elseif($object['type']=='tree' or $object['type']=='rock')
        $object['name'] = '';



    $object['expand'] = floatval($object['expand']);
    $object['block'] = floatval($object['block']);
    $object['attack'] = floatval($object['attack']);

    $object['t'] = intval($object['t']);

    //ebr($object['name']);

    //------------------------------------------------------------------------------------------------------------------Rozestavěnost


    if (!$object['stoptime'] or $object['stoptime'] > time()) {
        $onmap = true;

        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~Only Building

        //Způsob in_array($object['type'],array('building','tree','rock')) je pomalejší
        if($object['type']=='building' or $object['type']=='tree' or $object['type']=='rock') {

            if ($object['readytime'] > time()) {

                $fpfs = 1 + ($object['readytime'] - time()) / ($object['readytime'] - $object['starttime']);

            } else {
                $fpfs = $object['fp'] / $object['fs'];
            }

        }
        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    } else {
        $onmap = false;
    }


    //------------------------------------------------------------------------------------------------------------------Barva uživatele

    if($object['type']=='building'){

        $a = strpos($object['profileown'], 'color=');
        if ($a !== false) {

            $object['profileown'] = substr($object['profileown'], $a + 6);
            $usercolor = $object['profileown'];
            $b = strpos($object['profileown'], ';');
            if ($b) $object['profileown'] = substr($object['profileown'], 0, $b);
            //e($object['profileown']);

            if (strpos($object['res'], '000000')) {
                $object['res'] = str_replace('000000', $object['profileown'], $object['res']);
            } else {
                /*$object['res']=explode(':',$object['res']);
                $pos=strrpos($object['res'][2],',');
                $object['res'][2]=substr($object['res'][2],0,$pos+1).$object['profileown'];
                $object['res']=implode(':',$object['res']);
                //$object['res']=str_replace('000000',$object['profileown']['color'],$object['res']);
                */
            }
        } else {
            $usercolor = false;
        }

    }

    //------------------------------------------------------------------------------------------------------------------pozice

    $_xc=$object['x'];
    $_yc=$object['y'];
    $xx=$_xc-$xu;
    $yy=$_yc-$yu;

    //------------------------------------------------

    $rx=round((($px*$xx)-($px*$yy)+$rxp)/$GLOBALS['mapzoom']);
    $ry=round((($py*$xx)+($py*$yy)+$ryp)/$GLOBALS['mapzoom']);
    if($object['id']==$GLOBALS['ss']['useid']){
        $built_rx=$rx;
        $built_ry=$ry;
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~Only Building
    if($object['type']=='building') {
        //--------------------------------------------------------------------------------------------------------------EXPAND,COLLAPSE

        t($object['name'] . ' - beforeexpandcollapse');

        define('size_radius', 100);


        if ($onmap and (($object['expand'] and $object['own'] == $GLOBALS['ss']['useid']) or $object['block'])) {
            if ($object['own'] != $GLOBALS['ss']['useid']) {
                $object['expand'] = 0;
                $aa = gr;
                $ad = 'q';
            } else {
                $aa = gr;
                $ad = 'w';
            }
            if ($object['block']) {
                $object['block'] = distance_wall;
            } else {
                $object['block'] = 0;
            }
            //$object['expand']=0.3;
            //$object['expand']=0.1;

            $file = tmpfile2(size_radius . 'expand' . $object['expand'] . 'block' . $object['block'] . $ad/**.rand(1,9999)/**/, 'png', "image");
            //e($file);
            $y = 1;//gr;
            $brd = 3 * $y;
            $se = size_radius * $object['expand'] * $y;
            $sc = size_radius * $object['block'] * $y;
            $s = max(array($se, $sc));

            if (!file_exists($file) or notmpimg/** or true/**/) {


                //$sesc="$object['expand']=$se,$collapse=$sc";

                $img = imagecreate($aa * $s, $aa * $s / 2);
                imagealphablending($img, false);
                $outer = imagecolorallocatealpha($img, 0, 0, 0, 127);
                imagealphablending($img, true);
                imagefill($img, 0, 0, $outer);

                $sxs = array('se' => $se, 'sc' => $sc);
                arsort($sxs);

                foreach ($sxs as $key => $sx) {
                    if ($sx) {
                        //-----EXPAND
                        if ($key == 'se') {
                            $inner = imagecolorallocatealpha($img, 50, 50, 70, 60);//, 0, 0, 0, 70
                            $outer = imagecolorallocatealpha($img, 0, 0, 0, 50);
                            imagefilledellipse($img, $aa * $s / 2, $aa * $s / 4, $aa * $sx, $aa * ($sx / 2), $outer);
                            imagefilledellipse($img, $aa * $s / 2, $aa * $s / 4, $aa * ($sx - $brd), $aa * (($sx / 2) - $brd), $inner);
                        }
                        //-----BLOCK
                        if ($key == 'sc') {
                            if ($object['own'] != $GLOBALS['ss']['useid']) {
                                $inner = imagecolorallocatealpha($img, 255, 0, 40, 70);
                                $outer = imagecolorallocatealpha($img, 150, 0, 20, 50);
                            } else {
                                $inner = imagecolorallocatealpha($img, 0, 255, 40, 70);
                                $outer = imagecolorallocatealpha($img, 0, 150, 20, 50);
                            }

                            imagefilledellipse($img, $aa * $s / 2, $aa * $s / 4, $aa * $sx, $aa * ($sx / 2), $outer);
                            imagefilledellipse($img, $aa * $s / 2, $aa * $s / 4, $aa * ($sx - $brd), $aa * (($sx / 2) - $brd), $inner);
                        }
                        //-----ATTACK
                        /*if($key=='sa'){
                                $inner =  imagecolorallocatealpha($img, 200, 255, 10, 60);
                        }*/
                        //-----DRAW

                    }
                }
                //-----
                imagesavealpha($img, true);
                imagepng($img, $file, 9, PNG_ALL_FILTERS);
                imagedestroy($img);
                chmod($file, 0777);
            }

            $src = rebase(url . $file);
            /*$GLOBALS['area_stream'] .= '<div style="position:absolute;z-index:150;" id="expand' . $object['id'] . '">
        <div style="position:relative; top:' . ($ry - ((($s / $y / 4) + htmlbgc) / $GLOBALS['mapzoom'])) . 'px; left:' . ($rx - ($s / $y / 2 / $GLOBALS['mapzoom'])) . 'px;" >
        <img src="' . $src . '" widht="' . ($s / $y / $GLOBALS['mapzoom']) . '" height="' . ($s / $y / 2 / $GLOBALS['mapzoom']) . '"  class="clickmap" border="0" />
        </div></div>';*/
            $all_images_spec[]=array($src,($rx - ($s / $y / 2 / $GLOBALS['mapzoom'])),($ry - ((($s / $y / 4) + htmlbgc) / $GLOBALS['mapzoom'])),($s / $y / $GLOBALS['mapzoom']),($s / $y / 2 / $GLOBALS['mapzoom']),'expand');
        }

        //--------------------------------------------------------------------------------------------------------------ATTACK

        //$object['attack']x=$object['attack'];
        //$object['attack']=1;
        if ($onmap and ($object['attack'] or $object['own'] != $GLOBALS['ss']['useid'])) {
            if ($object['own'] != $GLOBALS['ss']['useid']) {
                $object['attack'] = 1;
                $aa = gr;
            } else {
                $aa = gr;
                //$attack_mafu=$GLOBALS['ss']['use_object']->set->val("attack_mafu");
                //list($attack_ma)=explode('-',$attack_mafu);
                $object['set'] = $GLOBALS['ss']['use_object']->set->val("set");
                $object['set'] = str2list(xx2x($object['set']));
                $attack_mafu = $object['set']['attack_mafu'];
                list($attack_ma) = explode('-', $attack_mafu);
                //print_r($object['set']);br();
                //e("$attack_ma==$object['id']");br();
                if ($attack_ma == $object['id']) {
                    $selected = 'selected';
                } else {
                    $selected = '';
                }
            }


            $file = tmpfile2(size_radius . 'attack' . ($object['own'] == $GLOBALS['ss']['useid'] ? $object['attack'] : 'x') . $selected, 'png', "image");
            //e($file);
            $y = 1;//gr;
            $brd = 3 * $y;
            $s = size_radius * $object['attack'] * $y;

            if (!file_exists($file) or notmpimg/** or true/**/) {


                //$sesc="$object['expand']=$se,$collapse=$sc";

                $img = imagecreate($aa * $s, $aa * $s / 2);
                imagealphablending($img, false);
                $outer = imagecolorallocatealpha($img, 0, 0, 0, 127);
                imagealphablending($img, true);
                //imageantialias($img,true);
                imagefill($img, 0, 0, $outer);

                if ($object['own'] == $GLOBALS['ss']['useid']) {
                    if ($selected) {
                        $inner = imagecolorallocatealpha($img, 150, 255, 255, 70);
                        $outer = imagecolorallocatealpha($img, 0, 0, 0, 50);
                    } else {
                        $inner = imagecolorallocatealpha($img, 150, 255, 255, 70);
                        $outer = imagecolorallocatealpha($img, 0, 0, 0, 120);
                    }

                    $plus = 2;
                    imagefilledellipse($img, $aa * $s / 2, $aa * $s / 4, $aa * $s, $aa * ($s / 2), $outer);
                    imagefilledellipse($img, $aa * $s / 2, $aa * $s / 4, $aa * ($s - $brd), $aa * (($s / 2) - $brd), $inner);
                } else {
                    $inner = imagecolorallocatealpha($img, 255, 100, 100, 80);
                    $outer = imagecolorallocatealpha($img, 0, 0, 0, 50);
                    $plus = 2;
                    imagefilledellipse($img, $aa * $s / 2, $aa * $s / 4, $aa * $s, $aa * ($s / 2), $outer);
                    imagefilledellipse($img, $aa * $s / 2, $aa * $s / 4, $aa * ($s - $brd), $aa * (($s / 2) - $brd), $inner);
                }
                //-----
                imagesavealpha($img, true);
                imagepng($img, $file, 9, PNG_ALL_FILTERS);
                imagedestroy($img);
                chmod($file, 0777);
            }

            $src = rebase(url . $file);


            //die($src);


            /*$GLOBALS['attack_stream'] .= '<div style="position:absolute;z-index:150;" id="attack' . $object['id'] . '">
            <div style="position:relative; top:' . ($ry - ((($s / $y / 4) + htmlbgc) / $GLOBALS['mapzoom'])) . 'px; left:' . ($rx - ($s / $y / 2 / $GLOBALS['mapzoom'])) . 'px;" >' .
                '
        <img src="' . $src . '" width="' . ($s / $y / $GLOBALS['mapzoom']) . '" height="' . ($s / $y / 2 / $GLOBALS['mapzoom']) . '"  class="clickmap" border="0" />
            </div></div>';*/


            $all_images_spec[]=array($src,($rx - ($s / $y / 2 / $GLOBALS['mapzoom'])),($ry - ((($s / $y / 4) + htmlbgc) / $GLOBALS['mapzoom'])),($s / $y / $GLOBALS['mapzoom']),($s / $y / 2 / $GLOBALS['mapzoom']),'attack');

        }
    }

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~Only Building, Tree and rock
    if($object['type']=='building' or $object['type']=='tree' or $object['type']=='rock') {
        //--------------------------------------------------------------------------------------------------------------MODEL
        t($object['name'] . ' - beforeunit');



        $GLOBALS['model_resize'] = 1;

        //------------------------------------------------------
        if($object['type']=='tree' and $fpfs==1){

            if (!$alltree) {
                if (!$alltree = cache('alltree')) {
                    $alltree = array();
                    $objects_ = sql_array('SELECT DISTINCT `res` FROM [mpx]pos_obj WHERE `type`=\'tree\' AND ' . objt() . ' AND `res`!=\'\' ORDER BY id LIMIT 200');
                    foreach ($objects_ as $object_)
                        $alltree[] = modelx($object_['res']);
                    cache('alltree', $alltree);
                    t('after alltree');
                } else {
                    //$GLOBALS['model_resize']=0.75/(0.75*gr);//todo Nepěkné řešení
                    //$GLOBALS['model_resize']=1;
                }
            }

            $rand = ((pow($object['x'], 3) + pow($object['y'], 2)) % count($alltree));
            //rand(0,1);

            $modelurl = $alltree[$rand];
            $GLOBALS['model_resize'] = 0.75 / (0.75 * gr);

            //------------------------------------------------------
        }elseif($object['type']=='rock' and $fpfs==1){
            //------------------------------------------------------

            if (!$allrock) {
                if (!$allrock = cache('allrock')) {
                    $allrock = array();
                    $objects_ = sql_array('SELECT DISTINCT `res` FROM [mpx]pos_obj WHERE `type`=\'rock\' AND ' . objt() . ' AND `res`!=\'\' ORDER BY id LIMIT 400');
                    foreach ($objects_ as $object_)
                        $allrock[$object_['res']] = modelx($object_['res']);
                    cache('allrock', $allrock);
                    t('after allrock');
                } else {
                    //$GLOBALS['model_resize']=0.75/(0.75*gr);//todo Nepěkné řešení
                    //$GLOBALS['model_resize']=1;
                }
            }

            $modelurl = $allrock[$object['res']];
            $GLOBALS['model_resize'] = 1;
            $ry-=35/$GLOBALS['mapzoom'];

            //------------------------------------------------------
        }else/**/{
            //------------------------------------------------------

            $modelurl = modelx($object['res'], $fpfs/*$_GLOBALS['map_night']*/, $usercolor);

            //------------------------------------------------------
        }
        //------------------------------------------------------










        t($object['name'] . ' - afrer modelx');
        //TOTÁLNÍ MEGASRAČKA - //list($width, $height) = getimagesize($modelurl);
        //TOTÁLNÍ MEGASRAČKA - echo("$width, $height");

        $first=substr($object['res'], 0, 1);
        if ($first=='[' or $first=='{') {
            $width = 133;
            $height = 254;
        } else {
            $width = 82;
            $height = 156;//123;
        }
        t($object['name'] . ' - afrer getimagesize');
        //if (!$GLOBALS['model_resize'])
        $width = $width * $GLOBALS['model_resize'];
        $height = $height * $GLOBALS['model_resize'];
        // width="83"


        /*$GLOBALS['units_stream'] .= '
            <div style="position:absolute;z-index:' . ($ry + 1000) . ';display:' . ($onmap ? 'block' : 'none') . ';"  class="timeplay" starttime="' . ($object['starttime'] ? $object['starttime'] : 0) . '" stoptime="' . ($object['stoptime'] ? $object['stoptime'] : 0) . '" >
            <div id="object' . $object['id'] . '" style="position:relative; top:' . round($ry + round((-132 - $height + 157 + 4) / $GLOBALS['mapzoom'])) . 'px; left:' . round($rx + round((-43 + 2) / $GLOBALS['mapzoom'])) . 'px;">
        ';*/

        if ($object['res'] and $object['t'] > $mapunitstime) {


            //------------------------------------------------First Buildings for help

            /*$yourid = '';
            if ($object['own'] == $GLOBALS['ss']['useid']) {
                $specialname = $object['pname'];
                $specialname = str_replace(array('{', '}'), '', $specialname);
                $yourid = 'id="first_' . $specialname . '"';
                //if(in_array($specialname,array('building_name'))){
                if (!isset($GLOBALS['first'])) {
                    $GLOBALS['first'] = array();
                }
                if (!isset($GLOBALS['first'][$specialname])) {
                    $GLOBALS['first'][$specialname] = true;
                    $yourid = 'id="first_' . $specialname . '"';
                }
                //}
            }*/
            //------------------------------------------------

            /*$GLOBALS['units_stream'] .= '
                <img src="' . ($modelurl) . '"   width="' . (round(82 / $GLOBALS['mapzoom'])) . '" class="clickmap" border="0" alt="' . addslashes($object['name']) . '" title="' . addslashes($object['name']) . '"/>
            ';*/

            /*$GLOBALS['units_stream'].=jsr('


                  var allImages['.md5($modelurl).'] = new Image();

                  allImages['.md5($modelurl).'].onload = function() {
                    ctx.drawImage(allImages['.md5($modelurl).'], '.round($rx + round((-43 + 2) / $GLOBALS['mapzoom'])).', '.round($ry - htmlbgc + round((-132 - $height + 157 + 4) / $GLOBALS['mapzoom'])).',' . (round(82 / $GLOBALS['mapzoom'])) . ',' . (round(156 / $GLOBALS['mapzoom'])) . ');

                  };
                  allImages['.md5($modelurl).'].src = "'.$modelurl.'";

            ');*/

            $GLOBALS['all_images'][]=array($modelurl,($rx + ((-43 + 2) / $GLOBALS['mapzoom'])),($ry -htmlbgc/*+ htmlunitc*/ - ((132 - $height + 157 + 4) / $GLOBALS['mapzoom'])),(82 / $GLOBALS['mapzoom']) , (156 / $GLOBALS['mapzoom']),$object['type']);


        } else {
            r('!res');
        }


        t($object['name'] . ' - afrer show res');

        /*$GLOBALS['units_stream'] .= '</div>';
        $GLOBALS['units_stream'] .= '</div>';*/

        //--------------------------------------------------------------------------------------------------------------UNIT CLICKMAP

        if ($object['type'] == 'building')
            if ($onmap) {

                $GLOBALS['units_stream'] .= '
                <div style="position:absolute;z-index:' . ($ry + 2000) . ';" >
            <div title="' . addslashes($object['name']) . '" style="position:relative; top:' . ($ry + round((-132 - 40 + 157) / $GLOBALS['mapzoom'])) . 'px; left:' . ($rx + round((-43 + 7) / $GLOBALS['mapzoom'])) . 'px;cursor:hand;" ' . $yourid . '>
            <img src="' . imageurl('design/blank.png') . '" class="unit" id="' . ($object['id']) . '" border="0" alt="' . addslashes($object['name']) . '" title="' . addslashes($object['name']) . '" width="' . (round(70 / $GLOBALS['mapzoom'])) . '" height="' . (round(35 / $GLOBALS['mapzoom'])) . '"/>
            </div>
            </div>';
                t($object['name'] . ' - afrer show shit');

            }
    }
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~END OF Only Building
    //------------------------------------------------------------------------------------------------------------------Nápis nad městem / budovou


    if($onmap){


        if($object['type']=='building') {
            //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~Only Building
            if (contentlang(id2name($GLOBALS['config']['register_building'])) == $object['name'] and !$_GET['first']) {
                $say = id2name($object['own']);
                if (!is_numeric($say)) {
                    $say = str_replace(' ', '&nbsp;', $say);
                } else {
                    //$say=($say.','.$GLOBALS['ss']['logid']);
                    if ($object['own'] == $GLOBALS['ss']['useid']) {
                        $say = lr('xtype_own');
                    } else {
                        $say = lr('xtype_noreg');
                    }
                }
            } else {
                $say = '';
            }
            //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        }elseif($object['type']=='story'){
            //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~Only Story

            //$object['res'] = explode(':',$object['res'],2);
            //$html=$object['res'][1];

            //$say = $object['name'].'<hr>'.$html;
            //$say = $object['name'].'<hr>'.$html;


            $say = short( $object['name'] , 30);

            if($say and $say!='-')
                $showsay=true;
            else
                $showsay=false;



            if(strpos($object['res'],'<img')!==false) {

                $img = substr2($object['res'],'<img','>');
                $img = substr2($img, 'src="', '"');

                if(strpos($img,$object['name'])!==false){
                    $showsay=false;
                }


                $img=html_entity_decode($img);

            }else{

                $img='';

            }






            //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        }
        //-------------------------------------------------------------------------Zobrazení Nápisu
        if($say){
            if($object['type']=='building'){
                //------------------------------------------------Zobrazení nápisu pro budovy

                $GLOBALS['units_stream'].='
                <div class="saybox" style="position:absolute;display:'.(onlymap?'none':'block').';z-index:'.($ry+2000).';" >
                <div title="'.addslashes($object['name']).'" style="position:relative; top:'.($ry-round(100/$GLOBALS['mapzoom'])).'px; left:'.($rx+((-43+7)/$GLOBALS['mapzoom'])).'px;background: rgba(0,0,0,0.75); border-radius: 2px;'.($usercolor?'border: 2px solid #'.$usercolor.';':'').' padding: 4px;">'.trr($say,13,NULL,'class="clickmap"').'</div>
                </div>';

                //------------------------------------------------
            }elseif($object['type']=='story') {
                //------------------------------------------------Zobrazení nápisu pro příběhy
                $color='906090';//'3355cc';
                $GLOBALS['units_stream'].='
                <div class="saybox" style="position:absolute;display:'.(onlymap?'none':'block').';z-index:'.($ry+2000).';" >

                '.ahrefr('
                <div title="'.addslashes($object['name']).'" style="position:relative; top:'.($ry).'px; left:'.($rx+((-43+7)/$GLOBALS['mapzoom'])).'px;background: rgba(0,0,0,0.75);border:2px solid #'.$color.';border-radius: 2px; padding: 4px;max-width:70px;max-height:70px;overflow:hidden;">'.($showsay?trr($say,13):'').(($showsay and $img)?'<br>':'').($img?'<img src="'.imgresizewurl($img,60).'" width="60">':'').'</div>
                ','e=content;ee=text-story;id='.$object['id']).'
                </div>';
                //width:150px;height:120px;overflow:hidden;

                //------------------------------------------------
            }
        }

        t($object['name'].' - afrer show say');

    }

    //------------------------------------------------------------------------------------------------------

    t($object['name'].' - end');

}
//======================================================================================================================

$GLOBALS['all_images']=array_merge($all_images_spec, $GLOBALS['all_images']);

$GLOBALS['units_stream'].=jsr('map_units_time='.time());


t('konec');
?>

    