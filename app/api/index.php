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

	foreach(array_merge(glob('*.*'),glob('examples/*.php')) as $file) {
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
if($_GET['code'] and strpos($_GET['code'],'.')===false){

	?>
	<!DOCTYPE html>
	<html>
		<head>
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<meta charset="UTF-8">
			<title><?=$_GET['code'].'.php'?> - Zdrojový kód | Ukázky Towns API</title
		</head>
		<body>
			<?php highlight_file(($_GET['code']=='index'?'index':'examples/'.$_GET['code']).'.php'); ?>
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
$links=array('map',/*'jsmap',*/'terrain','links','story');

//--------------------------------Stránky pouze s tokenem
if($_SESSION['townsapi_token']){
	$links=array_merge($links,array('buildings','build','attack','wall'));
}
//--------------------------------Aktuální stránka

if($_GET['page'])
	$_SESSION['page']=$_GET['page'];


//----------------------------------------------------------------HTML



	?>

	<!DOCTYPE html>
	<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta charset="UTF-8">
		<title><?=($_SESSION['page']?ucfirst($_SESSION['page']).' | ':'') ?>Ukázky Towns API</title>


		<link rel="stylesheet" href="style.css">

        <!--<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>-->



		<script type="text/javascript">

		function loadURL(id,url){
			var xmlhttp;
			if(window.XMLHttpRequest){
				xmlhttp=new XMLHttpRequest();
				xmlhttp.onreadystatechange=function(){
					if(xmlhttp.readyState==4 && xmlhttp.status==200){
						document.getElementById(id).innerHTML=xmlhttp.responseText;
					}
				}
				xmlhttp.open('GET',url,true);
				xmlhttp.send();
				return false;	
			}
		}
		</script>


	</head>
	<body>

    <?php
    if(!isset($_GET['onlypage'])){
    ?>

    <div class="page">

        <div class="navigation">
            <a href="?zip=1">Stáhnout</a><br>
            <a href="http://forum.towns.cz/api/" target="_blank">Dokumentace API</a><br>
            <a href="?code=index" target="_blank">Zdrojový kód</a>
        </div>

        <h1><a href="?page=map&zoom=5"><img src="logo.png" width="50"> Towns API</a></h1>


        <?php

        if (!$_SESSION['townsapi_token']) {
            //--------------------------------Před zadáním tokenu
            ?>

            <div class="appDescription">
                Jde o ukázku PHP aplikace, která může běžet zcela nezávisle na Towns.cz - tzn. pokud si ji stáhnete na
                svůj localhost nebo kamkoliv jinam, bude fungovat úplně stejně. Se serverem Towns komunikuje pouze
                pomocí http požadavků. Tímto způsobem se dá vyrobit aplikace, která má stejné možnosti jako běžný hráč,
                akce však provádí vámi naprogramovaný skript např. různé herní nástroje, editory, automatičtí hráči
                apod.
                <hr>
                Jednotlivé ukázky slouží k demonstraci různých funkcí API, ne jako finální aplikace pro hráče. Většina
                ukázek by šla výrazně zrychlit použitím JavaScriptu, Cashováním apod. Vše si můžete stáhnout, upravoval
                a libovolně šířit. Pokud si nebudete vědět rady, nebo vytvoříte zajímavou aplikaci, napište nám na <a
                    href="https://www.towns.cz/app/projects/?project=1" target="_blank">stránku projektu</a>, nebo na <a
                    href="mailto:ph@towns.cz">ph@towns.cz</a>.
            </div>


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

            Můžete také vyzkoušet funkce bez nutnosti autentizace:<br><br>

            <?php

            //--------------------------------
        } else {
            //--------------------------------Po zadání tokenu
            ?>

            <p>
                <b>Token:&nbsp;</b><?= $_SESSION['townsapi_token'] ?>&nbsp;<a href="?cancel_token=1"
                                                                              onclick="return confirm('Opravdu?');">zrušit</a>
            </p>

            <?php
            //--------------------------------
        }

        //----------------------------------------------------------------Menu


        $menu = '';
        foreach ($links as $link) {
            if ($link == $_SESSION['page']) {
                $menu .= '<li><a href="#" class="selected">' . ucfirst($link) . '</a></li>';
            } else {
                $menu .= '<li><a href="?page=' . $link . '">' . ucfirst($link) . '</a></li>';
            }
        }

        $menu = "<ul>$menu</ul>";


        echo($menu);
    }

	echo('<div id="page">');


//----------------------------------------------------------------Stránka
if(!$_SESSION['page'])
$_SESSION['page']=$links[0];


if(in_array($_SESSION['page'],$links)){

    if(!isset($_GET['onlypage'])) {
        ?>
        <div class="navigation">
            <a href="?code=<?= $_SESSION['page'] ?>" target="_blank">Zdrojový kód</a><br>
            <a href="?page=<?=$_SESSION['page']?>&onlypage" target="_blank">Bez rámů</a>
        </div>
        <?php
    }

	require("examples/{$_SESSION['page']}.php");
}


//----------------------------------------------------------------Konec

?>

</div></div>

</body>
</html>

<?php


//----------------------------------------------------------------

?>

