<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/page/test.php

   testování
*/
//==============================

?>


<?php
/*
//$GLOBALS['get']["contextid"]=$GLOBALS['hl'];
$array=sql_array('SELECT `id` FROM `[mpx]objects` WHERE `ww`=\''.$GLOBALS['ss']['ww'].'\' AND `type`=\'building\' AND `own`='.useid);
foreach($array as $row){
list($id)=$row;
t('cache minimenu '.$id);
$GLOBALS['get']["contextid"]=$id;
?>
<div id="cache_minimenu_<?php e($id); ?>" style="display:none;">
<?php eval(subpage("minimenu")); ?>
</div>
<?php
}*/



//$name1='A, F {and} C+';
//$name2='C {and} A++ {and} E';
//$base='C';
//$name1='{'.$base.'}+';
//$name2="{".$base."_count4;alt=$base+++}";
$name='';$stream='';

$bases=str_split('ABCDEFGHIJKLMN');
$i=100;
while($i>0){$i--;
	$base=$bases[rand(0,count($bases)-1)];
	$count=rand(1,4);
	$plus='';$ii=1;while($ii<=$count-1){$plus.='+';$ii++;}
	if(rand(1,10)!=1){
		$namep="{".$base."_count".$count.";alt=$base$plus}";
	}else{
		$namep='{'.$base.'}'.$plus;
	}

	if(!$name){
		$stream.=$namep;
		$name=$namep;
	}

	$name=name2name($name,$namep);
	$stream.=("<br><b>+</b> $namep <b>=</b><br> $name <br>");
}



$name=name2name($name1,$name2);

e($stream);hr();
e(contentlang($stream));
//e("<hr><br>$name1 <br>+<br> $name2 <br>=<br> $name");
//e(contentlang("<hr><br>$name1 <br>+<br> $name2 <br>=<br> $name"));
die();
?>
