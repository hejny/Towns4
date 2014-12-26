<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/page/stat2.php

   Seznam objektů pro stavění budov
*/
//==============================


$contentwidth=450;


require_once(root.core."/func_map.php");
backup($GLOBALS['where'],"1");
//r($GLOBALS['where']);
$order="fs";
$max=sql_1data("SELECT COUNT(1) FROM `".mpx."objects` WHERE ".$GLOBALS['where'].' AND '.objt());
//echo($max);
$limit=limit("stat2",$GLOBALS['where'].' AND '.objt(),102,$max);


$array=sql_array("SELECT `id`,`name`,`type`,`fs`,`fp`,`fr`,`fx`,`fc`,`res`,`profile`,`own`,(SELECT `own`  FROM `".mpx."objects` as `Y` WHERE `Y`.`id`=(SELECT `own` FROM `".mpx."objects` as `X` WHERE `X`.`name`=`".mpx."objects`.`name` ORDER BY ww,t LIMIT 1) LIMIT 1) AS `rown`,`in`,`x`,`y`,`ww` FROM `".mpx."objects` WHERE ".$GLOBALS['where']." AND ".objt()." ".$GLOBALS['groupby']." ORDER BY $order LIMIT $limit",1);

contenu_a();


if($GLOBALS['description'])info($GLOBALS['description']);
?>
<table width="<?php e((!$GLOBALS['mobile']?contentwidth:'100%')); ?>"><tr>

<?php

//$array=array_merge($array,$array);
/**/
$i=$GLOBALS['ss']['ord'];
$onrow=1;
$ii=$onrow;
foreach($array as $row){$i++;$ii--;
    list($id,$name,$type,$fs,$fp,$fr,$fx,$fc,$res,$profile,$own,$rown,$in,$x,$y,$ww)=$row;
    $profile=new profile($profile);
    $description=trim($profile->val('description'));
    $res=resc($res,$GLOBALS['ss']['useid']);
    /*$hline=ahrefr(textcolorr(lr($type),$dev)." ".tr($name,true),"e=content;ee=profile;id=$id","none","x");
    $in=xyr($x,$y);
    $lvl=fs2lvl($fs);
    if($fp==$fs){
        $fpfs=round($fs);
    }else{
        $fpfs=round($fp).'/'.round($fs);
    }*/
    e('<td  width="'.(70*0.75).'">');
    //e($i);
    
    $fc=new hold($fc);
    if($GLOBALS['ss']['use_object']->hold->testhold($fc)){
        $js="w_close('content');build('".$GLOBALS['ss']['master']."','$id','".$GLOBALS['get']['func']."');";
    }else{
        $rhold=new hold($GLOBALS['ss']['use_object']->hold->vals2str());
        $rhold->rhold($fc);
        //$js="alert('{create_remain} ".($rhold->textr(2))."');";
	$js="w_close('content');build('".$GLOBALS['ss']['master']."$master','$id','".$GLOBALS['get']['func']."');";
    }
    
    ahref('<img src="'.modelx($res).'" width="'.(70*0.75).'">',js2($js),'none',true);
    e('</td>');
    e('<td>');
    //e($i);
    ahref(trr($name,13),/*'e=content;ee=profile;id='.$id*/js2($js),'none',true);
    br();
    ahref(lr('unique_profile'),'e=content;ee=profile;id='.$id,'none',true);
    br();
    $fc->showimg(true,true);
    //showhold($fc,true);
    /*if($GLOBALS['author']){
	br();
	le('author');line($own);
    }*///ZATIM NEAUTOR
    if($description){
        br();
        te(contentlang($description));   
    }
    if(!$GLOBALS['ss']['use_object']->hold->testhold($fc)){

	if($GLOBALS['ss']['use_object']->hold->testchange($fc)){
		blue(lr('create_change'));
	}else{
		error(lr('create_remain').' '.($rhold->textr(2)),false);
	}

    }
    e('</td>');
    if($ii==0){e('</tr><tr '.($iii?$iii=0:$iii='style="background: rgba(50,50,50,0.25);"').'>');$ii=$onrow;}
    /*if($ww==0){
	;
        icon(js2($js),"f_create_building_submit","{build_submit}",15);
    }*/
    
}/**/
while($ii>0){$ii--;
    e('<td>&nbsp;</td>');
    e('<td>&nbsp;</td>');
}
?>

</tr>
<tr><td><?php e('<img src="'.modelx('[-1,-1,0]::').'" width="'.(70*0.75).'">'); ?></td><td>&nbsp;</td></tr>
<tr><td><?php e('<img src="'.modelx('[-1,-1,0]::').'" width="'.(70*0.75).'">'); ?></td><td>&nbsp;</td></tr>
<tr><td><?php e('<img src="'.modelx('[-1,-1,0]::').'" width="'.(70*0.75).'">'); ?></td><td>&nbsp;</td></tr>
<tr><td><?php e('<img src="'.modelx('[-1,-1,0]::').'" width="'.(70*0.75).'">'); ?></td><td>&nbsp;</td></tr>
<tr><td><?php e('<img src="'.modelx('[-1,-1,0]::').'" width="'.(70*0.75).'">'); ?></td><td>&nbsp;</td></tr>
<tr><td><?php e('<img src="'.modelx('[-1,-1,0]::').'" width="'.(70*0.75).'">'); ?></td><td>&nbsp;</td></tr>
<tr><td><?php e('<img src="'.modelx('[-1,-1,0]::').'" width="'.(70*0.75).'">'); ?></td><td>&nbsp;</td></tr>
<tr><td><?php e('<img src="'.modelx('[-1,-1,0]::').'" width="'.(70*0.75).'">'); ?></td><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
</table>
<?php
contenu_b();
?>
