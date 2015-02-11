<?php
$inc=array(
    'base' => '',                     
    'core' => 'core',                     
    'app' => 'app',                     
    'world' => 'small',                		//Deafultní svět
    'url' => 'http://localhost/www/[world]/',   //URL adresa [world] se nahradí za jméno světa
    'cache' => 'tmp/[world]',             	//Pomocné soubory

    'mysql_host' => 'localhost',            
    'mysql_user' => 'root',                
    'mysql_password' => '',                
    'mysql_global' => array('lang','key','users','emails'), //Tabulky s globálním prefixem
    'mysql_global_prefix' => 'towns_',
    'mysql_prefix' =>'[world]_',

    'debug' => true,                		//Zda je povolené zapnout debug mód
    //'timeplan' => array('sql_query','sql_1data','sql_array','sql_csv'),

    'lang' => 'cz',                   		//deafultní jazyk
    'wp_posts'=>'wp_posts',                	//Wordpressová tabulka s příspěvky

    'fb_appid' =>'',                    	//ID fb aplikace
    'fb_secret' =>'',                    	//Klíč fb aplikace

    'paypal_username' => '',                	//Paypal
    'paypal_password' => '',                	//Paypal
    'paypal_signature' => '',                	//Paypal
    'paypal_enviroment' => '',            	//Paypal

    'ad_url' => '',                    		//URL adresa pro reklamu
    //'analytics' => 'UA-16346522-15',          //ID Google Analytics
    'fb_page' => 'towns.cz',                	//ID stránky na FB
    'google_page' => '101630931721695253050',   //ID stránky na Google+
    'twitter_page' => 'townsgame',              //ID stránky na Twitter
    'forum' =>'http://forum.towns.cz/',         //URL nápovědy a fóra

    'admin' => array(                	  	//Administrátorské účty Towns4Admin, public=bez přihlášení
            'x' => array('password' => 'x', 'permissions' => '*', 'tlang' => array('cz','czx')),
            'y' => array('password' => 'y', 'permissions' => 'html', 'tlang' => array('cz','czx')),
            'public' => array( 'permissions' => array('unique'), 'tlang' => array('cz','czx'))

            ), 
    'log_mail'=>'ph@towns.cz',            	//logovat na mail
    'write_id'=>'1000077',                	//logovat jako zprávu uživateli Towns
    'log_func'=>array('register'),              //jaké funkce jádra logovat?

    'bot_password' => '1234',                   //heslo botů
    'bot_email' => '[name]@towns.cz',

    'restart_url'=>'',                		//URL pro tvrdý restart serveru pokud něco nefunguje
    
     						//Přístup na vzdálený server pro funkci Push CORE
    'ftp_host' => '',                    	//FTP server
    'ftp_user' => '',                    	//jméno
    'ftp_password' => '',                	//heslo
    'ftp_path' => ''                    	//cesta    
);



date_default_timezone_set('Europe/Prague');	//Nastavení časového pásma na EN serveru

if(!defined('noinc'))				// @deprecated
require($inc['core'].'/index.php');             //Spuštění samotné aplikace...

?>

