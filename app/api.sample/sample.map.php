<?php
/**
 * Ukázková Towns API Aplikace
 * Copyright 2015 Towns.cz
 *
 * @link http://forum.towns.cz/
 *
 * @author     Pavol Hejný
*/

error_reporting(E_ALL ^ E_NOTICE);
ini_set("register_globals","off");
ini_set("display_errors","on");
//ini_set("display_errors","off");

//Inicializace
require('townsapi.inc.php');

//Pomocí objektu
/*
$TownsApi = new TownsApi('...','...');
$result = $TownsApi->q('12345.create', 10000023, '+4', '+5', 60)
/**/




//Pomocí funkcí
TownsApiStart('http://localhost/towns/small/','','cs_CZ');

if($_GET['x']){
    $x=intval($_GET['x']);
    $y=intval($_GET['y']);
}else{
    $x=10;
    $y=10;
}

$zoom=20;

$result = TownsApi('list', 'x,y,res', array('terrain',"box($x,$y,".($x+$zoom).",".($y+$zoom).")"), 'y,x');
$result = $result['objects'];

$map=array();


foreach($result as $row){
    list($x_,$y_,$res)=$row;
    $x_=$x_-$x;
    $y_=$y_-$y;


    if(!isset($map[$y_]))$map[$y_]=array();

    $map[$y_][$x_]=$res;

}

//print_r($map);

$terrains=array();
$terrains['t0'] = '#000000';//temnota
$terrains['t1'] = '#5299F9';//moře
$terrains['t2'] = '#545454';//dlažba
$terrains['t3'] = '#EFF7FB';//sníh/led
$terrains['t4'] = '#F9F98D';//písek
$terrains['t5'] = '#878787';//kamení
$terrains['t6'] = '#5A2F00';//hlína
$terrains['t7'] = '#DCDCAC';//sůl
$terrains['t8'] = '#2A7302';//tráva(normal)
$terrains['t9'] = '#51F311';//tráva(toxic)
$terrains['t10'] = '#535805';//les
$terrains['t11'] = '#337EFA';//řeka
$terrains['t12'] = '#8ABC02';//tráva(jaro)
$terrains['t13'] = '#8A9002';//tráva(pozim)


$cell=8;
?>


<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title>Ukázka Towns API - Zobrazení terénu</title>
    <style type="text/css">
        <!--
        a {
            font-size: 24px;
            color: #000000;
        }
        a:link {
            text-decoration: none;
        }
        a:visited {
            text-decoration: none;
            color: #000000;
        }
        a:hover {
            text-decoration: none;
            color: #000000;
        }
        a:active {
            text-decoration: none;
            color: #000000;
        }
        -->
    </style>

</head>
<body>

<div align="center">
<table width="500" height="500" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center" valign="middle"><a href="?x=<?=$x-1?>&amp;y=<?=$y-1?>">+</a></td>
        <td align="center" valign="middle"><a href="?x=<?=$x?>&amp;y=<?=$y-1?>">+</a></td>
        <td align="center" valign="middle"><a href="?x=<?=$x+1?>&amp;y=<?=$y-1?>">+</a></td>
    </tr>
    <tr>
        <td align="center" valign="middle"><a href="?x=<?=$x-1?>&amp;y=<?=$y?>">+</a></td>
        <td align="center" valign="middle">

            <table border="0" cellpadding="0" cellspacing="0">
                <?php
                foreach($map as $tr){
                    echo('<tr>');
                    foreach($tr as $td) {
                        echo('<td width="'.$cell.'" height="'.$cell.'" bgcolor="' . ($terrains[$td]) . '"></td>');
                    }
                    echo('</tr>');
                }
                ?>


            </table>

        </td>
        <td align="center" valign="middle"><a href="?x=<?=$x+1?>&amp;y=<?=$y?>">+</a></td>
    </tr>
    <tr>
        <td align="center" valign="middle"><a href="?x=<?=$x-1?>&amp;y=<?=$y+1?>">+</a></td>
        <td align="center" valign="middle"><a href="?x=<?=$x?>&amp;y=<?=$y+1?>">+</a></td>
        <td align="center" valign="middle"><a href="?x=<?=$x+1?>&amp;y=<?=$y+1?>">+</a></td>
    </tr>
</table><!---->
</div>


</body>
</html>








