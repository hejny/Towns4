<?php
/**
 * Ukázková Towns API Aplikace - Odkazy na budovy, terény
 *
 * @copyright 2015 Towns.cz
 * @link http://api.towns.cz/
 * @author     Pavol Hejný
 * @version    1.0
 *
 */

echo '<div class="pageDescription">Zobrazení náhodných objektů na mapě a odkazů na ně</div>';

//----------------------------------------------------------------Náhodné budovy
echo '<p><b>Budovy:</b><br>';

$objects = TownsApi('list', 'id,name,resurl,x,y,ww','building', 'rand',100);
$objects = $objects['objects'];

if($objects)
foreach($objects as $object){
?>
<a href="<?=($_SESSION['townsapi_url'].'/'.$object['x'].','.$object['y'].','.$object['ww'])?>" target="_blank">
<img width="50" src="<?=$object['resurl']?>" ></a>
	
<?php
}
echo '</p>';



//----------------------------------------------------------------Náhodné terény
echo '<p><b>Terény:</b><br>';

$objects = TownsApi('list', 'id,name,resurl,x,y,ww','terrain', 'rand',100);
$objects = $objects['objects'];

if($objects)
foreach($objects as $object){
?>
<a href="<?=$_SESSION['townsapi_url'].'/'.$object['x'].','.$object['y'].','.$object['ww']?>" target="_blank">
<img width="50" src="<?=$object['resurl']?>" ></a>
	
<?php
}
echo '</p>';

//----------------------------------------------------------------


