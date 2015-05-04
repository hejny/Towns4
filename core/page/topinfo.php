<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2015
   _____________________________

   core/page/topinfo.php

   Informační lišta nad záložkama - například výzva k vytvoření hesla
*/
//==============================
/*if($_GET['j']!='23'){
echo('<script language="javascript">
    window.location.replace("?j=23");
    </script>');
exit2();
}*/

if($GLOBALS['topinfo']){

?>
<table height="<?php e($iconsize); ?>" width="100%" border="0" cellpadding="0" cellspacing="0">
<tr><td align="center">
<?php

    if($GLOBALS['topinfo_url']){$url=$GLOBALS['topinfo_url'].js2('$(\'#topinfo\').css(\'display\',\'none\');');}else{$url='';}
    if($GLOBALS['topinfo_color']){$color=$GLOBALS['topinfo_color'];}else{$color=/*'770077'*/'292929';}
    if($GLOBALS['topinfo_textcolor']){$textcolor=$GLOBALS['topinfo_textcolor'];}else{$textcolor='ffcccc';}


    //e('<div style="background:#'.$color.';width:100%;border:0px;" >'.ahrefr($GLOBALS['topinfo'],$url,'none;font-size:16px;color: #'.$textcolor.';').'</div>');

e('<table style="background: rgba(30,30,30,0.9);border: 2px solid #222222;border-radius: 7px;" cellpadding="0" cellspacing="0" width="650" height="25"><tr><td align="center">');

ahref($GLOBALS['topinfo'],$url,'none;color: #'.$textcolor.';');

e('</td></tr></table>');



//-------------------------- MAIN BUILDING FROM INDEX
/*
if(!$GLOBALS['hl']){
if($GLOBALS['config']['register_building']){
//if(1){

	if($hl=sql_array('SELECT id,ww,x,y FROM `[mpx]pos_obj` WHERE ww='.$GLOBALS['ss']['ww'].' AND own='.$GLOBALS['ss']['useid'].' AND type=\'building\' and TRIM(name)=\''.id2name($GLOBALS['config']['register_building']).'\' LIMIT 1')){
	 //br();
     //print_r($hl);
    list($GLOBALS['hl'],$GLOBALS['hl_ww'],$GLOBALS['hl_x'],$GLOBALS['hl_y'])=$hl[0];
}else{//e(1);
    $GLOBALS['hl']=0; 
}
}else{//e(2);
    $GLOBALS['hl']=0; 
}
}*/
//--------------------------

$_GLOBALS['noxmap']=true;
//e($GLOBALS['hl'].','.$GLOBALS['hl_x'].','.$GLOBALS['hl_y'].','.$GLOBALS['hl_ww']);

//$url=centerurl($GLOBALS['hl'],$GLOBALS['hl_x'],$GLOBALS['hl_y'],$GLOBALS['hl_ww']);
//click($url,1);


?>
</td>
</tr>
</table>

<?php



}


/*
?>


<table height="<?php e($iconsize); ?>" width="100%" border="0" cellpadding="0" cellspacing="0">
<tr><td align="center">
<?php
e('<table style="background: rgba(30,30,30,0.9);border: 2px solid #222222;border-radius: 7px;" cellpadding="0" cellspacing="0" width="'.(!$GLOBALS['mobile']?650:200).'" height="25"><tr><td>');
e('<div name="mine">sss</div>');
e('</td></tr></table>');
?>
</td>
</tr>
</table>
<?php */ ?>
