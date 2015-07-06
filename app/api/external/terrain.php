<?php
/**
 * Ukázková Towns API Aplikace - Mapa, Zobrazení terénů
 *
 * @copyright 2015 Towns.cz
 * @link http://api.towns.cz/
 * @author     Pavol Hejný
 * @version    1.0
 *
 */

//----------------------------------------------------------------HTML
?>
<!DOCTYPE html>
<html>
<head>
    <title>Terény | Ukázka Towns API</title>
    <meta charset="UTF-8">
    <meta name="description" content="Zobrazení terénů v html tabulce" />
    <script src="<?=TownsAppPath?>map.lib.js"></script><!--Při importu do Towns potřeba buď zabalit oba soubory do .zip, nebo vše sloučit do jediného .php souboru.-->
    <link rel="stylesheet" href="<?=TownsAppPath?>map.lib.css">
</head>
<body>

<?php

//----------------------------------------------------------------Pozice na mapě a zoom


require('positions.lib.php');//Jako základní adresář je nastaven ten, kde se aplikace nachází.
//Při importu do Towns potřeba buď zabalit oba soubory do .zip, nebo vše sloučit do jediného .php souboru.


//----------------------------------------------------------------Data mapy

//-------------------------Načtení všech terénů v daném úseku

$result = TownsApi('list', 'x,y,resurl', array('terrain',"box({$_SESSION['x']},{$_SESSION['y']},".($_SESSION['x']+$_SESSION['zoom']).",".($_SESSION['y']+$_SESSION['zoom']).")"), 'y,x');

$result = $result['objects'];



//-------------------------Vytvoření prázdné mapy

$map=array();
for($y=0;$y<$_SESSION['zoom'];$y++){
	$map[$y]=array();
	for($x=0;$x<$_SESSION['zoom'];$x++){
		$map[$y][$x]='';
	}
}

//-------------------------Naplnění mapy terény
if($result)
foreach($result as $row){

    $row['x']=$row['x']-$_SESSION['x'];
    $row['y']=$row['y']-$_SESSION['y'];


    if(!isset($row['y']))$map[$row['y']]=array();

    $map[$row['y']][$row['x']]=$row['resurl'];

}


//----------------------------------------------------------------Vykreslení mapy

$cell=22;//Velikost jednoho políčka mapy
$moveby=2;//Posouvat o xx políček
?>


<table width="500" height="500" border="0" cellpadding="0" cellspacing="0" style="position:relative;left:120px;">
    <tr>
        <td align="center" valign="middle"><a class="move" href="?xm=<?=-$moveby?>&ym=<?=-$moveby?>"	onclick="return loadMap(<?=-$moveby?>,<?=-$moveby?>);"></a></td>
        <td align="center" valign="middle"><a class="move" href="?ym=<?=-$moveby?>"						onclick="return loadMap(0,<?=-$moveby?>);"></a></td>
        <td align="center" valign="middle"><a class="move" href="?xm=<?=$moveby?>&ym=<?=-$moveby?>"		onclick="return loadMap(<?=$moveby?>,<?=-$moveby?>);"></a></td>
    </tr>
    <tr>
        <td align="center" valign="middle"><a class="move" href="?xm=<?=-$moveby?>"						onclick="return loadMap(<?=-$moveby?>,0);"></a></td>
        <td align="center" valign="middle">

            <table border="0" cellpadding="0" cellspacing="0">
                <?php
                foreach($map as $tr){
                    echo('<tr>');
                    foreach($tr as $td) {
                        echo('<td width="'.$cell.'" height="'.$cell.'" style="background-image: url('.($td).');background-position: center center;"></td>');
                    }
                    echo('</tr>');
                }
                ?>


            </table>

        </td>
        <td align="center" valign="middle"><a class="move" href="?xm=<?=$moveby?>"						onclick="return loadMap(<?=$moveby?>,0);"></a></td>
    </tr>
    <tr>
        <td align="center" valign="middle"><a class="move" href="?xm=<?=-$moveby?>&ym=<?=$moveby?>"		onclick="return loadMap(<?=-$moveby?>,<?=$moveby?>);"></a></td>
        <td align="center" valign="middle"><a class="move" href="?ym=<?=$moveby?>"						onclick="return loadMap(0,<?=$moveby?>);"></a></td>
        <td align="center" valign="middle"><a class="move" href="?xm=<?=+$moveby?>&ym=<?=$moveby?>"		onclick="return loadMap(<?=$moveby?>,<?=$moveby?>);""></a></td>
    </tr>
</table>


</body>
</html>

