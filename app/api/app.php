<?php
/**
 * Spuštění konkrétní aplikace
 *
 * @copyright 2015 Towns.cz
 * @link http://api.towns.cz/
 * @author     Pavol Hejný
 * @version    1.0
 *
 */
//----------------------------------------------------------------Načtení API

// server should keep session data for AT LEAST 1 month
ini_set('session.gc_maxlifetime', 3600*24*30);
// each client should remember their session id for EXACTLY 1 month
session_set_cookie_params(3600*24*30);
session_start();

require('townsapi.inc.php');

session_start();

/* $_SESSION je definované v index.php
 *
 */
//----------------------------------------------------------------Případná změna page


if($_GET['appName'])
    $_SESSION['appName']=$_GET['appName'];

//----------------------------------------------------------------Inicializace API


TownsApiStart($_SESSION['townsapi_url'],$_SESSION['townsapi_token'],$_SESSION['townsapi_locale']);


//----------------------------------------------------------------Styl pro aplikace

function TownsStyle(){
?>
<style>
    body {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        text-align: left;
        color: #CCCCCC;
        margin: 5px;
        /*background-color: #111111;*/
    }


    a {
        color: #cccccc;
    }


    h1 {
        position: relative;
        left:8px;
    }
    h1 a {
        text-decoration: none;
    }


    h3 {
        display: block;
        font-size: 1.17em;
        margin-top: 0;
        margin-bottom: 0;
        margin-left: 0;
        margin-right: 0;
        font-weight: bold;
        color: #000055;
    }


    hr{
        background-color:#667799;
        height:2px;
        border:none;
    }

    .description {
        display: block;
        background: #111111;
        padding: 20px;
    }

    .error {
        color: #FF0000;
        font-weight: bold;
    }


    .success {
        color: #0055cc;
        font-weight: bold;

    }
</style>
<?php
}
//----------------------------------------------------------------Inicializace proměnných a konstant

    $result=TownsApi('list','id','me');
    //print_r($result);
    $result=$result['objects'];
    $my_id=$result[0]['id'];
    //echo($my_id);

    $path='memory';
    if(!file_exists($path))mkdir($path,0777);

    $path.='/'.substr($_SESSION['appName'],strlen('internal/'));
    if(!file_exists($path))mkdir($path,0777);

    $app_file="$path/app.txt";
    if($my_id)$user_file="$path/{$my_id}.txt";


    if(file_exists($app_file))
        $TownsApp=unserialize(file_get_contents($app_file));
    else
        $TownsApp=array();

    if($my_id){

        if (file_exists($user_file))
            $TownsUser = unserialize(file_get_contents($user_file));
        else
            $TownsUser = array();

        if(!isset($TownsUser['delay']))$TownsUser['delay']=false;


        define('TownsLogged',true);
    }else{

        define('TownsLogged',false);

    }

    define('TownsBackend',isset($_GET['backend']));



if(substr($_SESSION['appName'],0,strlen('internal'))=='internal') {
    $app_dir='internal';
    define('TownsAppURL','internal/');
}else{
    $app_dir='external';
    define('TownsAppURL','external/');

}
//----------------------------------------------------------------Stránka


$base_dir=getcwd();
chdir($app_dir);//Jako základní adresář je nastaven ten, kde se aplikace nachází.
$page=str_replace($app_dir.'/','',$_SESSION['appName']);


ini_set("max_execution_time","5");
ini_set("memory_limit","10M");

if(str_replace(array('.','/',':'),'',$page)==$page)//Kontrola Injection
require($page.'.php');


//----------------------------------------------------------------Uložení proměnných

chdir($base_dir);

file_put_contents($app_file, serialize($TownsApp));
if ($my_id)
    file_put_contents($user_file, serialize($TownsUser));

//----------------------------------------------------------------Spouštění aplikace na pozadí

if($TownsUser['delay'])
    if(!TownsBackend){

        //----------------------------------

        ?>
        <br>
        <hr>
        Tato aplikace nastavila proměnnou $TownsUser['delay'] na <?=$TownsUser['delay']?>. V testovacím prostředí se automatické spouštění pouze nasimuluje pomocí skrytého iframe, který se v pravidelném intervalu obnovuje pomocí JavaScriptu. Po umístění aplikace na server Towns bude spouštění prováděno automaticky bez ohledu na to, zda má hráč spuštěný prohlížeč.


        <iframe src="app.php?appName=<?=$_SESSION['appName']?>&backend" width="1" height="1" frameborder="0" scrolling="no"></iframe>


        <?php
        //----------------------------------
    }else{
        //----------------------------------Znovuspouštění na pozadí
        ?>
        <script>
            setTimeout(function(){
                document.location.reload();
            },<?=($TownsUser['delay']*1000)?>);
        </script>


        <?php
        //----------------------------------
    }

//----------------------------------------------------------------Konec


?>

