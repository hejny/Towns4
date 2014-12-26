
<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2015
   _____________________________

   core/page/vasasojak.php

   skrytá blbost
*/
//==============================
//<a style="position:absolute;left:1px;top:1px;" onclick="alert('to je ale kravina!');">X</a>


//modrý vasasoják běžel uvařit divnou kadidudku

$gs=array('m','z','s','i','y');
$poj_m=array('vasasoják','koberec','nosorožec','katapult','chobot','robot','chrochta','banán','kokos','pomeranč','slon','','','','','','','','','');
$poj_z=array('expanzní věž','hra','kukačka','ambasáda','tužka','','','','','','','','','','','','','','','');
$poj_s=array('moře','jméno','heslo','dělo','lvíče','kaně','avokádo','','','','','','','','','','','','','');
$poj_i=array('vasasojáci','nosorožci','boti','hráči','uživatelé','stavitelé','choboti roboti','spameři','trolové','','','','','','','','','','');
$poj_y=array('expanzní věže','kraviny','kukačky','katapulty','děla','hradby','palisády','mosty','akvadukty','terény','káňata','','','','','','','','','');

$prj=array('modr~','červen~','bíl~','nov','star','rozebran','opraven','vylepšen','','','','','','','','','','','');

$sl=array('běžel','stavěl','kulil','byl~ -','útočil','kopal','rozebíral','sestavoval','obsazoval','bořil','ničil','boural','opravoval','vylepšoval','navrhoval','','','','','','');


$pum=array('do prčic','do vody','pod vodu','na ostrov','na sever','na jih','na východ','na západ','do pryč','na temnotě',' na poušti','od počítače','','','','','','','','');
$puc=array('včera','dneska','zítra','na konci světa','předevčirem','když skončil svět','na počátku','','','','','','','','','','','','','');

function one($array){
	//print_r($array);
	$return=$array[rand(0, count($array) - 1)];
	if($return){
		return($return);
	}else{
		return(one($array));
	}
}

function poj($g='m'){
	global $poj_m,$poj_z,$poj_s,$poj_i,$poj_y,$prj;
	//echo(one($prj));	

	if(rand(1,3)!=1){
		$text=one($prj);
		if(!strpos($text,'~')){$text.='~';}
	}else{
		$text='';
	}

	if($g=='m'){
		$text=str_replace('~','ý',$text);
		$text.=' '.one($poj_m);
	}elseif($g=='z'){
		$text=str_replace('~','á',$text);
		$text.=' '.one($poj_z);
	}elseif($g=='s'){
		$text=str_replace('~','é',$text);
		$text.=' '.one($poj_s);
	}elseif($g=='i'){
		$text=one($poj_i);
	}elseif($g=='y'){
		$text=str_replace('~','é',$text);
		$text.=' '.one($poj_y);
	}
	
	$text=trim($text);
	return(/*'('.$g.')'.*/$text);
}

function sentence($g='m'){
	global $gs,$sl,$pum,$puc;
	
	$g=one($gs);
	$text=poj($g).' ';


	if(rand(1,2)!=1){
		$text.=one($puc).' ';
	}

	$tmp=one($sl);
	if(!strpos($tmp,'~')){$tmp.='~';}
	$text.=$tmp.' ';
	if($g=='m'){
		$text=str_replace('~','',$text);
	}elseif($g=='z'){
		$text=str_replace('~','a',$text);
	}elseif($g=='s'){
		$text=str_replace('~','o',$text);
	}elseif($g=='i'){
		$text=str_replace('~','i',$text);
	}elseif($g=='y'){
		$text=str_replace('~','y',$text);
	}
	
	$text=str_replace('-',/*'POJ'.*/poj(/*one($gs)*/$g),$text);




	if(rand(1,2)!=1){
		$text.=one($pum).' ';
	}

	$text=str_replace('  ',' ',$text);
	$text=trim($text);
	$text.='.';
	$first=substr($text,0,1);
	$first=strtoupper($first);
	$text=$first.substr($text,1);

	return($text);
}

?>
<script>

sentences=[<?php

for($i=1;$i<19;$i++){
	e("'".sentence()."',");
}

?>];
i=0;
</script>
<a style="cursor:not-allowed" onclick="alert(sentences[i]);i++;if(typeof(sentences[i])=='undefined'){i=0;}">.</a>

