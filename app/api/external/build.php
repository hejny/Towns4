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
//----------------------------------------------------------------HTML
?>
<!DOCTYPE html>
<html>
<head>
    <title>Stavění | Ukázka Towns API</title>
    <meta charset="UTF-8">
    <meta name="description" content="Správce budov města" />
    <script src="<?=TownsAppPath?>map.lib.js"></script><!--Při importu do Towns potřeba buď zabalit oba soubory do .zip, nebo vše sloučit do jediného .php souboru.-->
    <link rel="stylesheet" href="<?=TownsAppPath?>map.lib.css">
</head>
<body>

<?php
//----------------------------------------------------------------Popis

echo '<div class="pageDescription">Nejdříve vyberte budovu, potom klikáním na mapu stavíte nebo přistavujete.</div>';

//----------------------------------------------------------------Kontrola přihlášení

//Při umístění aplikace na server Towns si můžete nastavit, že bude přístupná pouze po přihlášení. Každá aplikace má své pevné URL, které je dostupné každému a proto je dobré odchytit případ nepřihlášeného hráče.
if(!TownsLogged){
    die('<div class="error">Tato aplikace vyžaduje přihlášení.</div></body></html>');
}

//------------------------------------------------------------------------Postavení nové budovy

if($_GET['selected_x'] and $_GET['selected_y']){
	$wx=intval($_GET['selected_x']);
	$wy=intval($_GET['selected_y']);
	
	$result=TownsApi('create',$_SESSION['selected_object'],$wx,$wy,rand(1,360));
	report($result);
}


//------------------------------------------------------------------------Pozice na mapě a zoom

$nohtml=1;//Proměnná říká, že se map.php nemá obalit do <html>...
require('map.php');//Jako základní adresář je nastaven ten, kde se aplikace nachází.
//Při importu do Towns potřeba buď zabalit oba soubory do .zip, nebo vše sloučit do jediného .php souboru.


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


</body>
</html>


