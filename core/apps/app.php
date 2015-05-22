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


if(isset($GLOBALS['get']['id'])) {
    $GLOBALS['ss']['appid']=intval($GLOBALS['get']["id"]);
}
if(isset($_GET['id'])) {
    $GLOBALS['ss']['appid']=intval($_GET['id']);
}

//----------------------------------------------------------------iFrame

if(!$_GET['inner'] and !defined('TownsBackend')) {

    list($name, $permalink) = sql_row('SELECT `name`,`permalink` FROM [mpx]objects WHERE id=' . $GLOBALS['ss']['appid']);
    $name = contentlang($name);


    window($name, contentwidth, $GLOBALS['ss']['screenheight'], 'apps-app');
    permalink($name, $permalink);

    ?>

    <div style="height:<?= $GLOBALS['ss']['screenheight'] ?>px;overflow: hidden;">
        <iframe src="<?= url . '?e=apps-app&inner=1&id=' . $GLOBALS['ss']['appid'] ?>"
                style="width:calc(100% + 22px);height:<?= $GLOBALS['ss']['screenheight'] + 22 ?>px;"
                frameborder="0"></iframe>
    </div>

<?php
}else {


//----------------------------------------------------------------Načtení Aplikace


    list($type, $ww, $profile) = sql_row('SELECT `type`,`ww`,`profile` FROM [mpx]pos_obj WHERE id=' . $GLOBALS['ss']['appid']);

    if ($type !== 'app') {

        error('app_error_no_app');
        exit2();

    } elseif ($ww != 0) {

        error('app_error_not_allowed');
        exit2();

    }else{


//----------------------------------------------------------------Styl pro aplikace

    function TownsStyle()
    {
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
                left: 8px;
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

            hr {
                background-color: #667799;
                height: 2px;
                border: none;
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

    $my_id = $GLOBALS['ss']['logid'];


    $path = root . 'userdata/objects/' . $GLOBALS['ss']['appid'];



        define('TownsAppURL', $GLOBALS['inc']['url'].'?e=apps-app&inner=1&id=' . $GLOBALS['ss']['appid']);
        define('TownsAppPath',$GLOBALS['inc']['url'].'userdata/objects/'.$GLOBALS['ss']['appid'].'/');


    if (!file_exists($path . '/memory')) mkdir($path . '/memory', 0777);


    $app_file = "$path/memory/app.txt";
    if ($my_id) $user_file = "$path/memory/{$my_id}.txt";


    if (file_exists($app_file))
        $TownsApp = unserialize(file_get_contents($app_file));
    else
        $TownsApp = array();

    if ($my_id) {

        if (file_exists($user_file))
            $TownsUser = unserialize(file_get_contents($user_file));
        else
            $TownsUser = array();

        if (!isset($TownsUser['delay'])) $TownsUser['delay'] = false;


        define('TownsLogged', true);
    } else {

        define('TownsLogged', false);

    }

    if(!defined('TownsBackend'))
    define('TownsBackend', false);






//----------------------------------------------------------------Stránka

//error_reporting(E_ALL ^ E_NOTICE);

    $base_dir = getcwd();
    chdir($path);//Jako základní adresář je nastaven ten, kde se aplikace nachází.
//ebr($path);

    ini_set("max_execution_time", "5");
    ini_set("memory_limit", "10M");


    $profile = str2list($profile);

//ebr($profile['index']);

//print_r(glob('*'));
//hr();

    require($profile['index'].'.php');
//hr();

//----------------------------------------------------------------Uložení proměnných

    chdir($base_dir);

    file_put_contents($app_file, serialize($TownsApp));
    if ($my_id)
        file_put_contents($user_file, serialize($TownsUser));


//----------------------------------------------------------------Spouštění aplikace na pozadí

        if($TownsUser['delay']){

            if($TownsUser['delay']<300)
                $TownsUser['delay']=300;


            if(!$TownsAppCount) {
                list($TownsAppCount,$q) = sql_row('SELECT `count`,1 FROM [mpx]ai WHERE `appid`=' . $GLOBALS['ss']['appid'] . ' AND `userid`=' . $GLOBALS['ss']['logid']);
            }else{
                $q=true;
            }

            if(TownsBackend) {
                $TownsAppCount++;
            }

            $TownsAppCount=intval($TownsAppCount);

            if($q)
                sql_1number('UPDATE [mpx]ai SET `count`='.$TownsAppCount.', `time`='.(time()+$TownsUser['delay']).' WHERE `appid`=' . $GLOBALS['ss']['appid'] . ' AND `userid`=' . $GLOBALS['ss']['logid']);
            else
                sql_1number("INSERT INTO `[mpx]ai` (`appid`, `userid`, `time`, `count`)
VALUES ('{$GLOBALS['ss']['appid']}', '{$GLOBALS['ss']['logid']}', '".(time()+$TownsUser['delay'])."', '$TownsAppCount');");



        }

//----------------------------------------------------------------Konec
}}

?>

