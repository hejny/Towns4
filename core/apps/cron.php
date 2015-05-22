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

define('TownsBackend', true);
$GLOBALS['ss']['query_output']= new vals();

$crons=sql_array('SELECT * FROM [mpx]ai WHERE `time`<='.time().' ORDER BY `time`');


foreach($crons as $cron){

    $TownsAppCount=$cron['count'];

    //print_r($cron);
    //hr();
    force_login($cron['userid']);

    //hr();


    $GLOBALS['ss']['appid']=$cron['appid'];

    require(root.core.'/apps/app.php');



    /*$GLOBALS['ss']['log_object']->update();
    $GLOBALS['ss']['use_object']->update();
    unset($GLOBALS['ss']['log_object']);
    unset($GLOBALS['ss']['use_object']);*/

    hr();

}

?>

