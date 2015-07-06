<?php
/**
 * Spuštění Towns API pro ukázkové Aplikace
 *
 * @copyright 2015 Towns.cz
 * @link http://api.towns.cz/
 * @author     Pavol Hejný
 * @version    1.0
 *
 */

//----------------------------------------------------------------Konfigurace

error_reporting(E_ALL ^ E_NOTICE);
ini_set("register_globals","off");
ini_set("display_errors","on");
//ini_set("display_errors","off");

//----------------------------------------------------------------Stažení všech ukázek v .zip souboru
if($_GET['zip']){

	$zipfile='townsapi.zip';

	//--------------------------------Vytvoření .zip souboru
	$zip = new ZipArchive();
	$zip->open($zipfile,ZIPARCHIVE::OVERWRITE);

	foreach(array_merge(glob('*.*'),glob('external/*.*'),glob('internal/*.*')) as $file) {
		if(in_array(substr($file,-4),array('.php','.css','.png')))
			$zip->addFile($file,$file);
	}

	$zip->close();

	chmod($zipfile,0777);

	//--------------------------------Odeslání .zpi souboru

	header('Content-Description: File Transfer');
	header('Content-Type: application/zip');
	header('Content-Disposition: attachment; filename='.basename($zipfile));
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	header('Content-Length: ' . filesize($zipfile));
	readfile($zipfile);

	unlink($zipfile);
	exit;
}
//----------------------------------------------------------------Zobrazení zdrojového kódu
if($_GET['code'] and strpos($_GET['code'],'.')===false and strpos($_GET['code'],':')===false and strpos($_GET['code'],'//')===false){

	?>
	<!DOCTYPE html>
	<html>
		<head>
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<meta charset="UTF-8">
			<title><?=$_GET['code'].'.php'?> - Zdrojový kód | Ukázky Towns API</title
		</head>
		<body>
			<?php highlight_file(($_GET['code']=='index'?'index':$_GET['code']).'.php'); ?>
		</body>
	</html>
	<?php
	exit;
}

//----------------------------------------------------------------Načtení API


// server should keep session data for AT LEAST 1 month
ini_set('session.gc_maxlifetime', 3600*24*30);
// each client should remember their session id for EXACTLY 1 month
session_set_cookie_params(3600*24*30);
session_start();

require('townsapi.inc.php');

//----------------------------------------------------------------Zobrazování chyb/hlášek
function report($result){
	if($result['error']){
		if(is_string($result['error'])){

			echo '<div class="error">'.$result['error'].'</div>';

		}elseif(is_array($result['error'])){
			foreach($result['error'] as $error){

				echo '<div class="error">'.$error.'</div>';
			
			}
		}

	}
	if($result['ok']){

				echo '<div class="success">OK - Akce byla úspěšně provedena</div>';

	}

}
//----------------------------------------------------------------

//--------------------------------URL světa

if($_POST['townsapi_url'])
	$_SESSION['townsapi_url']=$_POST['townsapi_url'];


if(!$_SESSION['townsapi_url'])
	$_SESSION['townsapi_url']='https://towns.cz';

//--------------------------------Jazyk

$locales=array('cs_CZ','en_US','none');

if($_POST['townsapi_locale'])
if(in_array($_POST['townsapi_locale'],$locales))
	$_SESSION['townsapi_locale']=$_POST['townsapi_locale'];



if(!$_SESSION['townsapi_locale'])
	$_SESSION['townsapi_locale']=$locales[0];


//--------------------------------Token


if($_POST['townsapi_token'])
	$_SESSION['townsapi_token']=$_POST['townsapi_token'];


//-----------------Zrušení tokenu
if($_GET['cancel_token'] and $_SESSION['townsapi_token']){

	TownsApiStart($_SESSION['townsapi_url'],$_SESSION['townsapi_token'],$_SESSION['townsapi_locale']);
	TownsApi('logout');
	$_SESSION['townsapi_token']='';
}

//----------------------------------------------------------------Inicializace

if($_GET['login']){

	//--------------------------------Nové přihlášení
	TownsApiStart($_SESSION['townsapi_url'],NULL,$_SESSION['townsapi_locale']);

	$_SESSION['townsapi_token']=TownsApiGetToken();

	$result=TownsApi('login',$_POST['username'],'towns',$_POST['password']);

	report($result);

	if(!$result['ok']){
		$_SESSION['townsapi_token']=false;
	}

	//--------------------------------
}elseif($_SESSION['townsapi_token']){
	//--------------------------------Token
	TownsApiStart($_SESSION['townsapi_url'],$_SESSION['townsapi_token'],$_SESSION['townsapi_locale']);
	//--------------------------------
}else{
	//--------------------------------Bez Tokenu
	TownsApiStart($_SESSION['townsapi_url'],'none',$_SESSION['townsapi_locale']);
	//--------------------------------
}


//----------------------------------------------------------------Stránky

//--------------------------------Vždy zobrazované stránky

//Zjištění všech potencionálních aplikací - tzn. souborů .php v adresářích external a internal
$filesExternal=glob('external/*.php');
$filesInternal=glob('internal/*.php');
$files=array_merge($filesExternal,$filesInternal);



$links=array();//Pole všech aplikací

foreach($files as $file){
    //$link=basename($file);//Ostranění cesty k souboru

    $link=$file;
    $link=substr($link,0,strlen($link)-4);//Ostranění koncovky .php

    if(!strpos($link,'.lib')){//Pokud soubor končí na .lib.php není aplikací
        if(!in_array($link,$links)) {//Někdy se stane, že je stejný soubor vypsán 2x.

            $contents=file_get_contents($file);//Načtení obsahu souboru aplikace...
            if($begin=strpos($contents,'<title>')){//aplikace má název (tag title)...
                $begin+=strlen('<title>');
                $end=strpos($contents,'</title>');

                $title=substr($contents,$begin,$end-$begin);
                list($title)=explode('|',$title,2);
                $title=trim($title);
                if(strlen($title)>11)
                    $title=substr($title,0,11).'...';

            }else//Aplikace nemá název

                $title=ucfirst($link);

            }

            $links[$link] = $title;

        }
}

//print_r($links);
//--------------------------------Stránky pouze s tokenem
/*if($_SESSION['townsapi_token']){
	$links=array_merge($links,array('buildings','build','attack','wall'));
}*/
//--------------------------------Aktuální aplikace

if($_GET['appName'])
	$_SESSION['appName']=$_GET['appName'];

if(!$_SESSION['appName'])
    $_SESSION['appName']='internal/storytext';/*$links[0]*/;


//--------------------------------URL Aktuální aplikace


if(substr($_SESSION['appName'],0,strlen('external'))=='external')
    $appURL='app.php?appName='.$_SESSION['appName'];
else
    $appURL=$_SESSION['townsapi_url'] . '?simulate=' . urlencode($_SESSION['townsapi_url'] . '/app/api/app.php?appName=' . $_SESSION['appName']);


//----------------------------------------------------------------HTML



	?>

	<!DOCTYPE html>
	<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta charset="UTF-8">
		<title><?=($_SESSION['appName']?$links[$_SESSION['appName']].' | ':'') ?>Ukázky Towns API</title>


		<link rel="stylesheet" href="style.css">

        <!--
        <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        -->



	</head>
	<body>

    <div class="appName">

    <div class="navigation">
        <a href="?zip=1">Stáhnout</a><br>
        <a href="http://forum.towns.cz/api/" target="_blank">Dokumentace API</a><br>
        <a href="?code=index" target="_blank">Zdrojový kód</a><br>
        <a href="?code=<?= $_SESSION['appName'] ?>" target="_blank">Z. kód aplikace</a><br>
        <a href="<?=$appURL?>" target="_blank">Bez rámů</a>
    </div>

    <h1><a href="?appName=map&zoom=5"><img src="logo.png" width="50"> Towns API</a></h1>


    <?php

    if (!$_SESSION['townsapi_token']) {
        //--------------------------------Před zadáním tokenu
        ?>

        <div class="appDescription">
            Do hry Towns můžete naprogramovat <b>vlastní aplikace v PHP</b>. Tato stránka obsahuje skripty, které Vám nasimulují prostředí, ve kterém bude aplikace po importu do Towns běžet + několik ukázek aplikací. Vše zde si můžete stáhnout na
            svůj localhost nebo na jiný server a stránka Vám bude fungovat úplně stejně. Se serverem Towns aplikace komunikují pomocí http požadavků. Tímto způsobem se dá vyrobit aplikace, která má stejné možnosti jako běžný hráč,
            akce však provádí vámi naprogramovaný skript.
            <!--<hr>
            Jednotlivé ukázky aplikací slouží k demonstraci různých funkcí API, ne jako finální aplikace pro hráče. Většina
            ukázek by šla výrazně zrychlit použitím JavaScriptu, cashováním apod. Vše si můžete stáhnout, upravoval
            a libovolně šířit.-->
            <hr>
            Po stažení a rozbalení můžete okamžitě začít vyrábět <b>svou vlastní aplikaci</b>. V základním adresáři se nachází skripty, které TownsAPI inicializují a nasimulují prostředí běhu jako na serveru Towns (těmi se vůbec nemusíte zabývat). Jednotlivé aplikace se nachází v adresářích <a href="http://forum.towns.cz/wiki/439-2/api/externi-vs-interni-aplikace/" target="_blank">external nebo internal</a> (co soubor to aplikace). Ty si samozřejmě můžete libovolně upravovat, kombinavat a šířit.
            <hr>
            <b>Pokud vyrobíte funkční aplikaci, odešlete ji přímo na server Towns.</b><br>
                Návod na to je na: <a href="http://forum.towns.cz/wiki/439-2/api/import-aplikace-do-towns/" target="_blank">http://forum.towns.cz/wiki/439-2/api/import-aplikace-do-towns/</a>
            <br><br>
            Nebo ji můžete umístit na svůj server a poslat nám odkaz, my ji rádi zveřejníme.
            <br><br>
            Pokud si nebudete vědět rady, napište nám na <a
                href="https://www.towns.cz/app/projects/?project=1" target="_blank">stránku projektu</a>, nebo na <a
                href="mailto:apps@towns.cz">apps@towns.cz</a>.

        </div>
        <hr>

        <form method="post" action="?">
            <table width="500" border="0">
                <tr>
                    <td width="150"><h3>URL Světa:</h3></td>
                    <td colspan="2"><input type="text" name="townsapi_url" style="width:350px;"
                                           value="<?= $_SESSION['townsapi_url'] ?>" ;/></td>
                </tr>
                <tr>
                    <td><h3>Lokalizace:</h3></td>
                    <td>
                        <select name="townsapi_locale">
                            <?php
                            foreach ($locales as $locale) {
                                echo '<option value="' . $locale . '" ' . ($locale == $_SESSION['townsapi_locale'] ? 'selected="selected"' : '') . '>' . $locale . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                    <td align="right">
                        <input type="submit" name="Submit" value="OK"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <h3>Token:</h3>
                        Token je tajný klíč, který slouží k identifikaci konkrétního hráče.<br>
                        Existují 2 Způsoby, jak ho zadat:

                    </td>
                </tr>

            </table>
        </form>




        <table width="800" border="0">
            <tr>
                <td width="400" bgcolor="#eeeeee" align="center"><h4>Zadat existující token</h4></td>
                <td width="400" bgcolor="#eeeeee" align="center"><h4>Vytvořit nový token</h4></td>
            </tr>
            <tr>
                <td align="left" valign="top">

                    V Towns.cz zjistíte token zadáním zkratky [P][T] v záložce token.
                    Stejný token využívá přímo aplikace Towns a bude platný, dokud se neodhlásíte.

                    <form method="post" action="?">
                        <b>Sem zadejte token:</b><br>
                        <input name="townsapi_token" type="text" id="token" style="width:350px;"
                               value="<?= $_SESSION['townsapi_token'] ?>"/>

                        <div align="right"><input type="submit" name="Submit2" value="OK"/></div>
                        <br>
                    </form>
                </td>
                <td align="left" valign="top">

                    Zcela nový token můžete vytvořit zadáním svých přihlašovacích údajů.

                    <form method="post" action="?login=1">
                        <table border="0">
                            <tr>
                                <td width="71"><b>Jméno:</b></td>
                                <td><input name="username" type="text" id="username" style="width:150px;"
                                           value="<?= $_POST['username'] ?>"/></td>
                            </tr>
                            <tr>
                                <td><b>Heslo:</b></td>
                                <td><input name="password" type="password" id="password" style="width:150px;"
                                           value="<?= $_POST['password'] ?>"/></td>
                            </tr>
                            <tr>
                                <td colspan="2" align="right">
                                    <input type="submit" name="Submit" value="Přihlásit se"/>
                                </td>
                            </tr>
                        </table>
                    </form>


                </td>
            </tr>
        </table>

        <!--Můžete také vyzkoušet funkce bez nutnosti autentizace:<br><br>-->

        <?php

        //--------------------------------
    } else {
        //--------------------------------Po zadání tokenu
        ?>

        <p>
            <b>Token:&nbsp;</b><?= $_SESSION['townsapi_token'] ?>&nbsp;<a href="?cancel_token=1" onclick="return confirm('Opravdu?');">zrušit</a>
        </p>

        <?php
        //--------------------------------
    }

    //----------------------------------------------------------------Menu


    $menuExternal = '';
    $menuInternal = '';
    foreach ($links as $link=>$title) {

        if(substr($link,0,strlen('external'))=='external')
                $type='menuExternal';
            else
                $type='menuInternal';


        if ($link == $_SESSION['appName']) {
            $$type .= '<li><a href="#" class="selected'.substr($type,4).'">' . $title . '</a></li>';
        } else {
            $$type .= '<li><a href="?appName=' . $link . '">' . $title . '</a></li>';
        }
    }


    ?>

    <span style="float:left;margin-right: 5px;">
        <h3 class="titleInternal">Interní aplikace:</h3><br>
        <ul class="menuInternal"><?=$menuInternal?></ul>
    </span>

    <span style="float:right;">
        <h3 class="titleExternal">Externí aplikace:</h3><br>
        <ul class="menuExternal"><?=$menuExternal?></ul>
    </span>



    <?php

	echo('<div id="appName">');


//----------------------------------------------------------------Stránka

if(isset($links[$_SESSION['appName']])){

    ?>
    <div style="height: 4px;"></div><!--Mezera mezi mezu a aplikací-->

    <iframe
    src="<?= $appURL ?>"
    style="width:100%;height:100vh;border: 0px solid #888888;" scrolling="no"></iframe>


    <?php

	//require("apps/{$_SESSION['page']}.php");
}


//----------------------------------------------------------------Konec

?>

</div></div>

</body>
</html>

<?php


//----------------------------------------------------------------

?>

