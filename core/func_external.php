<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/func_external.php

    funkce pracující s externími službami
*/
//==============================

//======================================================================================================================facebook SDK
if(!defined('noinc')){
    /*
     *  Načtení FB API
     * @author PH
     *
     * */

      require_once(root."lib/facebook_sdk/base_facebook.php");
      require_once(root."lib/facebook_sdk/facebook.php");

      $fb_config = array();
      $fb_config['appId'] = fb_appid;
      $fb_config['secret'] = fb_secret;

      $facebook = new Facebook($fb_config);


      $fb_config = array();
      $fb_config['appId'] = fb_appid;
      $fb_config['secret'] = fb_secret;

      $GLOBALS['facebook'] = new Facebook($fb_config);
}
//======================================================================================================================fb_notify
/*
 *  Odeslání upozornění FB uživateli
 * @author PH
 *
 * @param integer Uživetelské ID na FB
 * @param string Text upozornění
 * @param bool Vypsat pole pomocí $print_r
 *
 * */
function fb_notify($user,$template,$print_r=0){
    //print_r($print_r);
    //e("($user,$template)");
    //if($user===true)$user=fb_user();
    if($user and $template){
        
        try {
    
        $app_access_token = $GLOBALS['inc']['fb_appid'] . '|' . $GLOBALS['inc']['fb_secret'];
        $response = $GLOBALS['facebook']->api( '/'.$user.'/notifications', 'POST', array(
                    'template' => $template,
                    'href' => url,
                    'access_token' => $app_access_token
                ) );    
         
         if($print_r){print_r($response);br();}
         
        } catch (Exception $e) {
            //echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
   
}


//======================================================================================================================post_request
/*
 * Odeslání HTTP POST requestu
 * @author PH
 *
 * @param string URL adresa
 * @param array Pole POST hodnot
 * @return string http odpověď
 * */
function post_request($url,$data){
    //$url = 'http://server.com/path';
    //$data = array('key1' => 'value1', 'key2' => 'value2');
    
    // use key 'http' even if you send the request to https://...
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ),
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    
    //var_dump($result);
    return($result);

}

//======================================================================================================================mailx
/*
 * Odeslání e-mailu
 * @author PH
 *
 * @param integer Jákému uživateli (ID uživatele userid)
 * @param string předmět
 * @param string html text
 * @param string svět
 * @return bool true=odesláno / false=tento e-mail se už jednou posílal
 * */

function mailx($to,$subject,$text,$world=false){
	if(!$world){$world=w;}
    if($to and $to!='@'){
        //$url=url.'/corex/?e=mailx&to='.urlencode($to).'&subject='.urlencode($subject).'&body1='.urlencode($body1).'&body2='.urlencode($body2).'&body3='.urlencode($body3).'&body4='.urlencode($body4);
        //e($url);
        //get_headers($url);
		$uz=sql_1data("SELECT COUNT(1) FROM  `[mpx]emails` WHERE `to`=".($to-1+1)." AND `subject`='".sql($subject)."' AND `text`='".sql($text)."'");

		if(!$uz){
                        $id=sql_1number('SELECT MAX(`id`) FROM `[mpx]emails`')+1;
			sql_query("INSERT INTO `[mpx]emails` (`id`, `to`, `subject`, `text`, `world`, `key`, `start`, `try`, `stop`) VALUES (`id`, ".($to-1+1).", '".sql($subject)."', '".sql($text)."', '".sql($world)."', '".md5(rand(111111,999999))."', now(), 0, NULL);");

				$url=url.'?e=text-email';
				//e($url);
				get_headers($url);

			return(true);

		}else{
			return(false);
		}

		
    }

}

//======================================================================================================================wppost
/*
 * Odeslání příspěvku do WordPressu
 * @author PH
 * @link http://www.hurricanesoftwares.com/wordpress-xmlrpc-posting-content-from-outside-wordpress-admin-panel/ článek o různých možnostech WP
 *
 * @param string Nadpis
 * @param string Html kód
 * @param array Kategorie
 * @param string Štítky oddělené ,
 * */

function wppost($title,$body,$categories='',$keywords='') {


    require_once 'lib/wordpress/IXR_Library.php';


    $encoding='UTF-8';
    // $title variable will insert your blog title
    // $body will insert your blog content (article content)
    // Comma seperated pre existing categories. Ensure that these categories exists in your blog.

    $customfields=array(/*'key'=>'Author-bio', 'value'=>'Autor Bio Here'*/); // Insert your custom values like this in Key, Value format

    $title = htmlentities($title,ENT_NOQUOTES,$encoding);
    $keywords = htmlentities($keywords,ENT_NOQUOTES,$encoding);

    $content = array(
        'title'=>$title,
        'description'=>$body,
        'mt_allow_comments'=>0, // 1 to allow comments
        'mt_allow_pings'=>1, // 1 to allow trackbacks
        'post_type'=>'post',
        'mt_keywords'=>$keywords,
        'categories'=>$categories,
        'custom_fields' => array($customfields)
    );

// Create the client object
    $client = new IXR_Client($GLOBALS['inc']['wp_xmlrpc']);
    $username = $GLOBALS['inc']['wp_username'];
    $password = $GLOBALS['inc']['wp_password'];

    $params = array(0,$username,$password,$content,true); // Last parameter is 'true' which means post immediately, to save as draft set it as 'false'

    // Run a query for PHP
    if (!$client->query('metaWeblog.newPost', $params)) {
    return('Something went wrong - '.$client->getErrorCode().' : '.$client->getErrorMessage());
    } else { return "Article Posted Successfully"; }
}

//======================================================================================================================
        
?>
