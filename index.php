<?php
/*if(strpos($_SERVER["REQUEST_URI"],'corex')){
	$x='x';
	die('corex neni k dispozici!');
}else{
	$x='';
}*/


$inc=array(
    'base' => '',
    'core' => 'core',
    'app' => 'app',
    'worlds' => array('world2','world1','small'),
    'world' => 'small',
    //'url' => 'http://192.168.0.102/towns/[world]/',
    'url' => 'http://localhost/towns/[world]/',//192.168.56.1
    'cache' => 'tmp/[world]',
    'mysql_host' => 'localhost',
    'mysql_user' => 'root',
    'mysql_password' => '',
    'mysql_db' => 'towns_small',
	'mysql_global' => array('lang','key','users','emails'),
	'mysql_global_prefix' => 'towns_',
    //Uz neni potreba//'mysql_prefix' =>'[world]_',
    'lang' => 'cz',
    'debug' => true,
    'timeplan' => array('sql_query','sql_1data','sql_array','sql_csv'),//*=všechno~array('abc','cde')

	'wp_posts'=>'wp_posts',
    'fb_appid' =>'408791555870621',
    'fb_secret' =>'155326bed6c70ad2d4b21ef27d69c94e',
    'paypal_username' => 'info_1343221840_biz_api1.injectioncomp.com',
    'paypal_password' => '1343221872',
    'paypal_signature' => 'A2PyKp89S2eNM15amICDOkeE7uDNAF5TUyb1VKBs6nZo7noj3kX644Fa',
    'paypal_enviroment' => 'sandbox.',
    'ad_url' => 'http://test.towns.cz/towns_cz/www/app/ad/',
    //'paypal_username' => 'info_api1.injectioncomp.com',
    //'paypal_password' => '3E3DVY5G6H9PBHY5',
    //'paypal_signature' => 'AiPC9BjkCyDFQXbSkoZcgqH3hpacAU8F0VteQyr3LMgMhs0kv9FFNRnf',
    //'paypal_enviroment' => '',
    //'analytics' => 'UA-16346522-15',
    'fb_page' => 'pages/townsgame/296592843863589',
	'google_page' => '101630931721695253050',
	'twitter_page' => 'townsgame',
    'forum' =>'http://forum.towns.cz/',
    'admin' => array(
			'x' => array('password' => 'x', 'permissions' => '*', 'tlang' => array('cz','czx')),
			'y' => array('password' => 'y', 'permissions' => 'translate', 'tlang' => array('cz','czx')),
			'public' => array( 'permissions' => array('unique'), 'tlang' => array('cz','czx'))
			//'backup' => array('password' => 'x')
		    ),
    //'push_password'=>'att31415hoo',    
    'log_mail'=>'ph@towns.cz',
    'write_id'=>'1000077',
    'log_func'=>array('register'),
	'restart_url'=>'',

    'ftp_host' => 'towns.cz',
    'ftp_user' => 'ftpuser',
    'ftp_password' => '2Ztflvhb',
    'ftp_path' => '/var/www/towns_cz/www/corex',


	'backup_url'=>'https://www.towns.cz/world1/admin/?page=dump&username=admin&password=2Ztalvhb&export=2&notime=1',
	'backup_file'=>'https://www.towns.cz/app/admin/files/backup/backup.zip',

	'bot_password' => 'e5gq0w0h032hn5rg52opu35yl9z6elyt',
	'bot_email' => '[name]@towns.cz'
	//'crontab_file'=>'/var/spool/cron/crontabs/hejny'
    
);

//print_r(glob('*'));
//basename();
date_default_timezone_set('Europe/Prague');
if(!defined('noinc'))
require($inc['core'].'/index.php');
/*}else{
$inc=array(
    'base' => '',
    'core' => 'bin/towns.php',
    'app' => 'app',
    'world' => 'world2',
    'url' => 'http://localhost/towns/[world]/',
    'cache' => 'tmp/[world]',
    'mysql_host' => 'localhost',
    'mysql_user' => 'root',
    'mysql_password' => '',
    'mysql_db' => 'towns',
    'mysql_prefix' =>'[world]_',
    'debug' => true,
    'lang' => 'cz'
);
require($inc['core']);
}*/
?>
