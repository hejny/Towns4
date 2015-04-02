<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2015
   _____________________________

   core/page/surkey.php

   Surovinová lišta
*/
//==============================


$border4=array(2,'444444');

/*
//e($GLOBALS['ss']['use_object']->hold->vals2str());
//window("{items}","100%");
//if($GET['e']!='aac'){
?>
<div style="background: rgba(30,30,30,0.9);width:1000px;" >
<?php
    $GLOBALS['ss']['use_object']->hold->showimg(true);
?>
</div>
<?php
//}else{
    
//}*/

if(!$GLOBALS['mobile']){
//==========================================================================NORMAL
ob_start();
$GLOBALS['ss']['use_object']->hold->showimg(true,true,false,$border4);
$buffer = ob_get_contents();
ob_end_clean();

ahref($buffer,'e=content;ee=create-create_master;submenu=2');

}else{
//==========================================================================MOBILE

ob_start();
 $GLOBALS['ss']['use_object']->hold->showimg(true,true,false,/*$border4*/NULL,18);
$buffer = ob_get_contents();
ob_end_clean();

if(!android){
	$buffer.=nbsp3;
	$buffer.=imgr('design/mobiledot.png',lr('mobilemenu'),NULL,23);//textbr('...');
}


$buffer="
<table style=\"width:100%height:10px;\"><tr><td>
<span style=\"text-align:left;font-size:16px;\">$buffer</span>
</td></tr></table>
";

ahref($buffer,'e=content;ee=mobilemenu');



//e($buffer);

?>

<script>
menu_open=function(){
<?php e(str_replace('javascript:','',urlr('e=content;ee=mobilemenu'))); ?>
} 
</script>

<?php

//---------------------------------------topinfo
/*if($GLOBALS['topinfo']){

	ob_start();
	eval(subpage("topinfo"));
	$buffer = ob_get_contents();
	ob_end_clean();
	//$buffer=movebyr(0,0,$buffer,'','left:-50%;width:100%;');
	e($buffer);

}*/
//---------------------------------------center
if(!$GLOBALS['ss']['log_object']->set->val('map_xc')){
	
	$url=centerurl($GLOBALS['hl'],$GLOBALS['hl_x'],$GLOBALS['hl_y'],$GLOBALS['hl_ww'],true);
	//js('alert("'.$url.'")');
	//e('click');	
	click($url,-1);
}
//---------------------------------------
}
?>
