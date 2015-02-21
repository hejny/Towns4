<?php
//Towns.cz

$inc=array(

    'base' => '',                     
    'core' => 'core',                     
    'app' => 'app',                     
    'world' => 'small',                         //Deafultní svět
    'url' => 'http://localhost/www/[world]/',   //URL adresa [world] se nahradí za jméno světa
    'cache' => 'tmp/[world]',             	    //Pomocné soubory

    'mysql_host' => 'localhost',            
    'mysql_user' => 'root',                
    'mysql_password' => '',
    'mysql_db' => 'towns',
    //Tabulky s globálním prefixem
    'mysql_global' => array('lang','key','users','emails'),
    'mysql_global_prefix' => 'towns_',
    'mysql_prefix' =>'[world]_',

    'debug' => true,                		    //Zda je povolené zapnout debug mód; Tabulky pro time debug
    //'timeplan' => array('sql_query','sql_1data','sql_array','sql_csv'),

    'lang' => 'cz',                             //deafultní jazyk
    //'wp_posts'=>'wp_posts',                   //Wordpressová tabulka s příspěvky


    'trello_key' => '',
    'trello_token' => '',
    //https://trello.com/1/connect?key=9791836da2739b4a70b8036690080168&name=townsgame&response_type=token&expiration=never&scope=read,write


    //'fb_appid' =>'',                          //ID fb aplikace
    //'fb_secret' =>'',                    	    //Klíč fb aplikace

    //'paypal_username' => '',
    //'paypal_password' => '',
    //'paypal_signature' => '',
    //'paypal_enviroment' => '',


    //'analytics' => 'UA-16346522-15',          //ID Google Analytics
    'fb_page' => 'towns.cz',                	//ID stránky na FB
    'google_page' => '101630931721695253050',   //ID stránky na Google+
    'twitter_page' => 'townsgame',              //ID stránky na Twitter
    'forum' =>'http://forum.towns.cz/',         //URL nápovědy a fóra

    //Administrátorské účty Towns4Admin
    'admin' => array(
            'x' => 	array('password' => 'x', 'permissions' => '*', 'tlang' => array('cz','czx')),
            'y' => 	array('password' => 'y', 'permissions' => 'html', 'tlang' => array('cz','czx')),
            'public' =>	array( 'permissions' => array('unique'), 'tlang' => array('cz','czx')) //public=bez přihlášení

    ),

    //Logování
    'log_mail'=>'log@towns.cz',            	//logovat na mail
    'write_id'=>'1000077',                	//logovat jako zprávu uživateli Towns
    'log_func'=>array('register'),          //jaké funkce jádra logovat?

    //Boti 1.0
    'bot_password' => '1234',
    'bot_email' => '[name]@towns.cz',

    //Deploy
    'git_origin' => '', //URL adresa pro remote git origin
    //https://username:password@bitbucket.org/towns/towns4.git

	//URL pro vygenerování dumpu; URL pro stažení dumpu
    //'backup_url'=>'https://****/?page=dump&username=****&password=****&export=2&notime=1',
    //'backup_file'=>'https://****/backup.zip',

    //URL pro tvrdý restart serveru pokud něco nefunguje
    //'server_restart_url'=>'',

    //Odeslílání příspěvků do WP
    //'wp_xmlrpc' => 'http://.../xmlrpc.php',
    //'wp_username' => '',
    //'wp_password' => '',
    //'wp_categories_view' => array('Místa na mapě')	//Do které rubriky ve WP se odesílají pohledy z mapy.


);


//Nastavení časového pásma na EN serveru
date_default_timezone_set('Europe/Prague');

if(!defined('noinc'))
require($inc['core'].'/index.php');//Spuštění samotné aplikace...


?>

