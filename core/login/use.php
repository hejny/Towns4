<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2015
   _____________________________

   core/login/use.php

   vyber mest
*/
//==============================



//e($_GET['q']);
//e($GLOBALS['ss']['use_object']->name);

if(!$usenotclose){
	  ?> 
	<script type="text/javascript">
	    w_close('content');
	</script>
	<?php

	//click('e=towns');
}

if($_GET['e']=='-html_fullscreen'/**/ or 1/**/){
    $centerurl=centerurl($GLOBALS['hl'],$GLOBALS['hl_x'],$GLOBALS['hl_y'],$GLOBALS['hl_ww'],false);
    
    //click($centerurl,-1);
    //js('alert("'.$GLOBALS['ss']['log_object']->set->val('map_xc').'")');
	if(!$GLOBALS['ss']['log_object']->set->val('map_xc')){
		
		$url=centerurl($GLOBALS['hl'],$GLOBALS['hl_x'],$GLOBALS['hl_y'],$GLOBALS['hl_ww'],true);
		//js('alert("'.$url.'")');
		//e('click');	
		click($url,-1);
	}


    $delay=4000;
}else{
    
    $delay=100;
}

    $js='
    
    cache=function(xkey){return(false);};
    ifcache=function(xkey){return(false);};
    
    
    $( "#cache_loading" ).hide();
    setTimeout(function() {
        $( "#cache_loading" ).show();
        $.get(\'?token=&e=cache\', function(vystup){
            //$( "#cache_loading" ).slideUp();
            $( "#cache_loading" ).hide();
            $(\'#cache\').html(vystup);
        });
    }, '.$delay.');
    
    '.subjsr('towns').subjsr('dockbuttons');
    
    
    js($js);
    
    
$array=sql_array('SELECT `id`,`name`,`profile` FROM `[mpx]pos_obj` WHERE `own`=\''.($GLOBALS['ss']['logid']).'\' AND (`type`=\'town\' OR `type`=\'town2\') ORDER BY `type`,ABS('.$GLOBALS['ss']['logid'].'-`id`)');

/*(SELECT id FROM `[mpx]pos_obj` AS X WHERE X.`own`=`[mpx]pos_obj`.id AND name=\''.sql(mainname()).'\' LIMIT 1),
(SELECT x FROM `[mpx]pos_obj` AS X WHERE X.`own`=`[mpx]pos_obj`.id AND name=\''.sql(mainname()).'\' LIMIT 1),
(SELECT y FROM `[mpx]pos_obj` AS X WHERE X.`own`=`[mpx]pos_obj`.id AND name=\''.sql(mainname()).'\' LIMIT 1),*/

if(count($array)>1 and !onlymap){
?>



<table border="0" cellpadding="0" cellspacing="0" <?php e($style); ?>>

<?php
//$border0=array(2,'222222');

$iconsize=35;


foreach($array as $row){
	list($id,$name,$profile,$mainid,$x,$y,$ww)=$row;
?>
<tr><td valign="middle" align="center" width="<?php e($iconsize); ?>" height="<?php e($iconsize); ?>">
<?php

	$profile=str2list($profile);
	$color=$profile['color'];
	if(!$color)$color='699CFE';
	$border1=array(2,$color);

	if($id==$GLOBALS['ss']['useid']){
		$url=$centerurl;
	}else{
		//$url=js2('reuse('.$id.');');
		//e("$mainid,$x,$y,$ww");
		//$url=centerurl($mainid,$x,$y,$ww,true);
		$url='e=login-use;q=use,'.$id;//.js2("qbuffer='use,$id';");
		//e($url);
	}

	border(iconr($url,"profile_town",contentlang($name),$iconsize,NULL,($id==$GLOBALS['ss']['useid'])?0:1),/*($id==$GLOBALS['ss']['useid'])?*/$border1/*:$border0*/,$iconsize);
	e('</td></tr>');
}

//e($space);

e('</table>');
}
?>

