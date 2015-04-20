<?php
/**
 * Ukázková Towns API Aplikace - Pozice na mapě, centrování a zoom
 *
 * @copyright 2015 Towns.cz
 * @link http://api.towns.cz/
 * @author     Pavol Hejný
 * @version    1.0
 *
 */

if($_GET['x']){//Posouvání mapy
    $_SESSION['x']=intval($_GET['x']);
    $_SESSION['y']=intval($_GET['y']);
}

if($_GET['xm'])//Relativní posouvání mapy
    $_SESSION['x']+=intval($_GET['xm']);

if($_GET['ym'])//Relativní posouvání mapy
    $_SESSION['y']+=intval($_GET['ym']);

if($_GET['zoom'])//Změna zoomu mapy
    $_SESSION['zoom']=intval($_GET['zoom']);


//------------------------------------------------Vycentrovat

if(!isset($_SESSION['x']) or $_GET['center']){

	$result=TownsApi('list','x,y','mytown');//Zjištění pozice mého aktuálního města
	if($result['objects'][0]){
		$_SESSION['x']=intval($result['objects'][0]['x']-($_SESSION['zoom']/2));//Vycentrování na střed
		$_SESSION['y']=intval($result['objects'][0]['y']-($_SESSION['zoom']/2));
	}else{
		$_SESSION['x']=15;
		$_SESSION['y']=43;
	}

}

if(!isset($_SESSION['zoom']))
	$_SESSION['zoom']=5;

//------------------------------------------------Vycentrovat na určité x,y

if($_SESSION['page']=='map')
if($_GET['selected_x'] and $_GET['selected_y']){
	$_SESSION['x']=intval($_GET['selected_x']-($_SESSION['zoom']/2));//Vycentrování na střed
	$_SESSION['y']=intval($_GET['selected_y']-($_SESSION['zoom']/2));
}


//--------------------------------Tlačítka


echo '<span id="mapCenter">';
echo '&nbsp;<a href="?center=1">Vycentrovat</a><br>';
echo '&nbsp;<a href="?zoom=5">Zoom 5</a><br>';
echo '&nbsp;<a href="?zoom=10">Zoom 10</a><br>';
echo '&nbsp;<a href="?zoom=15">Zoom 15</a><br>';
echo '&nbsp;<a href="?zoom=20">Zoom 20</a>';

echo '</span>';

//----------------------------------------------------------------





?>

