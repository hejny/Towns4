<?php
/**
 * Ukázková Towns API Aplikace - Útočení
 *
 * @copyright 2015 Towns.cz
 * @link http://api.towns.cz/
 * @author     Pavol Hejný
 * @version    1.0
 *
 */

echo '<div class="pageDescription">Správce útoků. Nejdřive označte útočící budovu, potom klikněte na cíl.<br>
<div style="display:inline-block;width:19px;height:19px;background: #8888ff;border: 2px solid #000000;border-radius:19px;"></div> Vaše útočné budovy<br>
<div style="display:inline-block;width:19px;height:19px;background: #88ff88;border: 2px solid #000000;border-radius:19px;"></div> Označená útočná budova<br>
<div style="display:inline-block;width:19px;height:19px;background: #ff0000;border: 2px solid #000000;border-radius:19px;"></div> Nepřátelské budovy - cíle
</div>';






//------------------------------------------------------------------------Změna útočné budovy

if($_GET['attacker']){
	$_SESSION['attacker']=$_GET['attacker'];
}

//------------------------------------------------------------------------Útok!

if($_GET['target']){
	$result = TownsApi($_SESSION['attacker'].'.attack',$_GET['target']);
	report($result);
}

//-------------------------------------------------------------------------Mapa

require('example.map.php');

//-------------------------------------------------------------------------Mapa - ovládací prvky

//--------------------------------Zjištění všech budov v daném úseku

if($objects)
foreach($objects as $object){

    $relative_x=$object['x']-$_SESSION['x'];
    $relative_y=$object['y']-$_SESSION['y'];


	$real_x=($relative_x-$relative_y)*$cell;
	$real_y=($relative_x+$relative_y)*0.5*$cell;

	$real_x+=550;//Srovnání mapové a ovládací vrstvy
	$real_y-=800;


	//--------------------------------Útočná budova
	if($object['func']['attack']){
	?>

	<div style="position:absolute;z-index:10000">
		<a href="?attacker=<?=$object['id']?>">
		<div style="position:relative;left:<?=intval($real_x)?>px;top:<?=intval($real_y+100)?>px;width:30px;height:30px;background: #<?=($_SESSION['attacker']==$object['id']?'88ff88':'8888ff')?>;border: 2px solid #000000;border-radius:30px;">
		</div>
		</a>
	</div>

	<?php
	}


	//--------------------------------Potenciální cíl

	if($object['own']!=$result['useid'] and $object['type']=='building'){
	?>

	<div style="position:absolute;z-index:10000">
		<a href="?target=<?=$object['id']?>">
		<div style="position:relative;left:<?=intval($real_x)?>px;top:<?=intval($real_y+100)?>px;width:30px;height:30px;background: #ff0000;border: 2px solid #000000;border-radius:30px;">
		</div>
		</a>
	</div>

	<?php
	}
	//--------------------------------

}

//-------------------------------------------------------------------------

?>



