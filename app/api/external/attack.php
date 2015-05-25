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
//----------------------------------------------------------------HTML
?>
<!DOCTYPE html>
<html>
<head>
    <title>Útoky | Ukázka Towns API</title>
    <meta charset="UTF-8">
    <meta name="description" content="Správce útoků" />
    <script src="<?=TownsAppPath?>map.lib.js"></script><!--Při importu do Towns potřeba buď zabalit oba soubory do .zip, nebo vše sloučit do jediného .php souboru.-->
    <link rel="stylesheet" href="<?=TownsAppPath?>map.lib.css">
</head>
<body>

<?php
//----------------------------------------------------------------Popis

echo '<div class="pageDescription">Nejdříve označte útočící budovu, potom klikněte na cíl.<br>
<div style="display:inline-block;width:19px;height:19px;background: #8888ff;border: 2px solid #000000;border-radius:19px;"></div> Vaše útočné budovy<br>
<div style="display:inline-block;width:19px;height:19px;background: #88ff88;border: 2px solid #000000;border-radius:19px;"></div> Označená útočná budova<br>
<div style="display:inline-block;width:19px;height:19px;background: #ff0000;border: 2px solid #000000;border-radius:19px;"></div> Nepřátelské budovy - cíle
</div>';

//----------------------------------------------------------------Kontrola přihlášení

//Při umístění aplikace na server Towns si můžete nastavit, že bude přístupná pouze po přihlášení. Každá aplikace má své pevné URL, které je dostupné každému a proto je dobré odchytit případ nepřihlášeného hráče.
if(!TownsLogged){
    die('<div class="error">Tato aplikace vyžaduje přihlášení.</div></body></html>');
}

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

$nohtml=1;//Proměnná říká, že se map.php nemá obalit do <html>...
require('map.php');//Jako základní adresář je nastaven ten, kde se aplikace nachází.
//Při importu do Towns potřeba buď zabalit oba soubory do .zip, nebo vše sloučit do jediného .php souboru.

//-------------------------------------------------------------------------Mapa - ovládací prvky

//--------------------------------Zjištění všech budov v daném úseku

if($buildings)
foreach($buildings as $building){

    $relative_x=$building['x']-$_SESSION['x'];
    $relative_y=$building['y']-$_SESSION['y'];


	$real_x=($relative_x-$relative_y)*$cell;
	$real_y=($relative_x+$relative_y)*0.5*$cell;

	$real_x+=550;//Srovnání mapové a ovládací vrstvy
	$real_y-=800;


	//--------------------------------Útočná budova
	if($building['func']['attack']){
	?>

	<div style="position:absolute;z-index:10000">
		<a href="?attacker=<?=$building['id']?>">
		<div style="position:relative;left:<?=intval($real_x)?>px;top:<?=intval($real_y+100)?>px;width:30px;height:30px;background: #<?=($_SESSION['attacker']==$building['id']?'88ff88':'8888ff')?>;border: 2px solid #000000;border-radius:30px;">
		</div>
		</a>
	</div>

	<?php
	}


	//--------------------------------Potenciální cíl

	if($building['own']!=$result['useid'] and $building['type']=='building'){
	?>

	<div style="position:absolute;z-index:10000">
		<a href="?target=<?=$building['id']?>">
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


</body>
</html>


