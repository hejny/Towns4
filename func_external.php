<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/func_external.php

    funkce pracující s externími službami
*/
//==============================

//=============================================================facebook SDK

  eval('req'.'uire_once(root."lib/facebook_sdk/base_facebook.php");');
  eval('req'.'uire_once(root."lib/facebook_sdk/facebook.php");');

  $fb_config = array();
  $fb_config['appId'] = fb_appid;
  $fb_config['secret'] = fb_secret;

  $facebook = new Facebook($fb_config);
  


  eval('req'.'uire_once(root."lib/facebook_sdk/base_facebook.php");');
  eval('req'.'uire_once(root."lib/facebook_sdk/facebook.php");');

  $fb_config = array();
  $fb_config['appId'] = fb_appid;
  $fb_config['secret'] = fb_secret;

  $GLOBALS['facebook'] = new Facebook($fb_config);
  
//=============================================================fb_notify
  
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


//==========================================================================================post_request

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

//==========================================================================================mailx

function mailx($to,$subject,$text,$world=false){
	if(!$world){$world=w;}
    if($to and $to!='@'){
        //$url=url.'/corex/?e=mailx&to='.urlencode($to).'&subject='.urlencode($subject).'&body1='.urlencode($body1).'&body2='.urlencode($body2).'&body3='.urlencode($body3).'&body4='.urlencode($body4);
        //e($url);
        //get_headers($url);
		$uz=sql_1data("SELECT COUNT(1) FROM  `[mpx]emails` WHERE `to`=".($to-1+1)." AND `subject`='".sql($subject)."' AND `text`='".sql($text)."'");

		if(!$uz){
			sql_query("INSERT INTO `[mpx]emails` (`to`, `subject`, `text`, `world`, `key`, `start`, `try`, `stop`) VALUES (".($to-1+1).", '".sql($subject)."', '".sql($text)."', '".sql($world)."', '".md5(rand(111111,999999))."', now(), 0, NULL);");

				$url=url.'?e=text-email';
				//e($url);
				get_headers($url);

			return(true);

		}else{
			return(false);
		}

		
    }

}

//==========================================================================================Odeslání do wp

function wpPostXMLRPC($title,$body,$rpcurl,$username,$password,$category,$keywords='',$encoding='UTF-8') {
    //$title = htmlentities($title,ENT_NOQUOTES,$encoding);
    $keywords = htmlentities($keywords,ENT_NOQUOTES,$encoding);

    $content = array(
        'title'=>$title,
        'description'=>$body,
        'mt_allow_comments'=>0,  // 1 to allow comments
        'mt_allow_pings'=>1,  // 1 to allow trackbacks
        'post_type'=>'post',
        'mt_keywords'=>$keywords,
        'categories'=>array($category)
    );
    //r($content);
    $params = array(0,$username,$password,$content,true);
    $request = xmlrpc_encode_request('metaWeblog.newPost',$params,
                    array('encoding'=>$encoding,'escaping'=>'markup'));
    r($params);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
    curl_setopt($ch, CURLOPT_URL, $rpcurl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);
    $results = curl_exec($ch);
    curl_close($ch);
    return $results;
}

//==========================================================================================
        
?>
