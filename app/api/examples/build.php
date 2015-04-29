<?php
/**
 * Ukázková Towns API Aplikace - Stavění
 *
 * @copyright 2015 Towns.cz
 * @link http://api.towns.cz/
 * @author     Pavol Hejný
 * @version    1.0
 *
 */

echo '<div class="pageDescription"><b>Správce budov města:</b> Nejdříve vyberte budovu, potom klikáním na mapu stavíte nebo přistavujete.</div>';

//------------------------------------------------------------------------Postavení nové budovy

if($_GET['selected_x'] and $_GET['selected_y']){
	$wx=intval($_GET['selected_x']);
	$wy=intval($_GET['selected_y']);
	
	$result=TownsApi('create',$_SESSION['selected_object'],$wx,$wy,rand(1,360));
	report($result);
}


//------------------------------------------------------------------------Pozice na mapě a zoom

require('examples/map.php');

//------------------------------------------------------------------------Seznam všech unikátů

if($_GET['selected_object'])
$_SESSION['selected_object']=$_GET['selected_object'];


//Zjištění všech unikátních budov seřazených podle skupiny
$buildings = TownsApi('list', 'id,_name,resurl,x,y,ww,func,group',array('unique',"group!=''"), 'group',100);
$buildings = $buildings['objects'];


$lastgroup=$buildings[0]['group'];


//--------------------------------CSS
?>
    <style type="text/css">

        .skupina {
            background: #ffeeee;
			border-width: 1px;
			border-style: solid;
			border-color: #cccccc;
			padding: 4px;
			margin: 0px;
			position:relative;
			top:0;
			left:0;
			vertical-align:top;
			max-width:800px;
			display:inline-block;
        }

    </style>
<?php

//--------------------------------Vykreslení budov

echo('<div class="skupina">');
if($buildings)
foreach($buildings as $building){

if($lastgroup!=$building['group']){
	echo '</div><div class="skupina">';
	//echo $object['group'];
	$lastgroup=$building['group'];
}

?>
<a href="?selected_object=<?=$building['id']?>">
<img height="100" src="<?=$building['resurl']?>" border="<?=($building['id']==$_SESSION['selected_object']?2:0)?>" alt="<?=$building['_name']?>" title="<?=$building['_name']?>">
</a>
<?php



}
echo('</div>');


//------------------------------------------------------------------------

?>



