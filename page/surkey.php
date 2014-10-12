<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
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

ob_start();
$GLOBALS['ss']['use_object']->hold->showimg(true,true,false,$border4);
$buffer = ob_get_contents();
ob_end_clean();

ahref($buffer,'e=content;ee=create-create_master;submenu=2');

}else{

ob_start();
 $GLOBALS['ss']['use_object']->hold->showimg(true,true,false,/*$border4*/NULL,18);
$buffer = ob_get_contents();
ob_end_clean();

if(!android){
	$buffer.=nbsp3;
	$buffer.=imgr('design/mobiledot.png',lr('mobilemenu'),NULL,23);//textbr('...');
}


ahref($buffer,'e=content;ee=mobilemenu');


?>

<script>
menu_open=function(){
<?php e(str_replace('javascript:','',urlr('e=content;ee=mobilemenu'))); ?>
} 
</script>

<?php
}
?>
