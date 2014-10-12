<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný, Přemysl Černý | 2011-2013
   _____________________________

   core/plus/paypal/pconfig.php

   PayPal
*/
//==============================




/* 
  NEVER SHOW THIS FILE TO ANYONE
  Copyright Injection Computers, Inc.
  
  YOU: define CANCELURL (in case the payment is cancelled), RECEIVEURL (from redirection), and OFFERSURL (where the user could select the offer using the button "Checkout with PayPal")
  YOU: define getUser, getOffer (coin offer), and updateUser functions
  
  PayPal config
  SANDBOX - testing
  LIVE - live 
  
  Language detection
  read files in "lang" directory
  
  PayPal post
  PHP or cURL - select 1.) or 2.)


http://test.towns.cz/world1/debug?e=plus-paypal-psend&first=1&second=1
*/

// YOU -------------------------------------------------------------------------
// define return URL (absolute!)

//define('CANCELURL', 'http://www.myurl.com/');
//define('RECEIVEURL', 'http://www.myurl.com/preceive.php'); 

$wurl=str_replace('[world]',w,url);
define('CANCELURL', $wurl);
define('RECEIVEURL', $wurl.'?e=plus-paypal_preceive'); 
define('OFFERSURL', $wurl); 

// define getUser, getOffer (coin offer), and updateUser
// get user from database
function getUser($id)
{

  $tmpobject=new object($id);
  if($tmpobject->loaded){
     $hold=$tmpobject->hold->vals2list();
     $credit=$hold[plus_res];
     
     $user=array('name' => $tmpobject->name, 'realname' => $tmpobject->name, 'credit' => (int)$credit);
  }else{
     $user=false;
  }

  //print_r($user);
  //$user = array('name' => 'admin_me', 'realname' => 'Master Boss', 'credit' => 0); // TEST
  return $user;
  
  // return an array with keys: name, realname (the user's full name), credit (integer; how much the user has now)
  // if different edit keys in $user variable in psend.php and preceive.php
}
// get coin offer from database
function getOffer($id)
{
  $offer=$GLOBALS['config']['plus'][$id];
  $offer = array('title' => contentlang($offer['title']), 'amount' => (double)$offer['amount'], 'credit' => (int)$offer['credit']); // TEST
  //print_r($offer);
  return $offer;
  // return an array with keys: title, amount (double; how much the offer costs), credit (integer; how much credit for the offer)
  // if different edit keys in $line variable in psend.php and preceive.php
}
function updateUser($id, $credit)
{
  $tmpobject=new object($id);
  //$minus=new hold(plus_res.'='.$credit);
  $tmpobject->hold->add(plus_res,$credit);
  $tmpobject->update();
  mail('paypal@towns.cz','Paypal',"updated user ".w."/".($tmpobject->name)."($id) + $credit ".plus_res);
}

//updateUser(1050804,1000);

// -----------------------------------------------------------------------------
// CHANGE ----------------------------------------------------------------------
// PayPal config   
// SANDBOX
define('USERNAME', $GLOBALS['inc']['paypal_username']);
define('PASSWORD', $GLOBALS['inc']['paypal_password']);
define('SIGNATURE', $GLOBALS['inc']['paypal_signature']);
define('ENVIRONMENT', $GLOBALS['inc']['paypal_enviroment']);
/*echo(USERNAME.',');
echo(PASSWORD.',');
echo(SIGNATURE.',');
echo(ENVIRONMENT);*/
// LIVE
// -----------------------------------------------------------------------------

// YOU do not have to define the following, just edit if necessary
// language detection & load


/*$lang = array();
$langa = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
// file
$file = 'lang/' . $langa . '.ini';             
$lang = parse_ini_file($file, true);
// english must exist
if (!$lang)
{
    $lang = parse_ini_file('lang/en.ini', true); 
}*/

// don't define
// PayPal post
function prepareRequest($method, $nvp)
{
    // config
    $data = array();
    $data['METHOD'] = $method;
    $data['USER'] = USERNAME;
    $data['PWD'] = PASSWORD;
    $data['SIGNATURE'] = SIGNATURE;
    $environment = ENVIRONMENT;
    $url = "https://api-3t." . $environment . "paypal.com/nvp";
    
    // request
    // version
    $data['VERSION'] = '72.0';  
    // urlencode
    foreach ($data as $k => $v)
        $data[$k] = urlencode($v);       
    // data
    $req = "METHOD=" . $data['METHOD']  . "&VERSION=" . $data['VERSION']  . "&PWD=" . $data['PWD']  . "&USER=" . $data['USER']  . "&SIGNATURE=" . $data['SIGNATURE']  . $nvp;  

    // 1.) PHP (allow_url_fopen)
    /*
    // ini_set('allow_url_fopen', '1');
    // setting
    $params = array(
      'http' => array(
          'method' => 'POST',
          'content' => $req,
          'header' => "Content-type: application/x-www-form-urlencoded"
      )
    );
    if ($optional_headers !== null) 
    {
      $params['http']['header'] = $optional_headers;
    }
    // sending
    $ctx = stream_context_create($params);          
    $fp = fopen($url, 'rb', false, $ctx);
    // once more
    if (!$fp) 
    {
      $fp = fopen($url, 'rb', false, $ctx);
    } 
    // return
    $httpResponse = stream_get_contents($fp);
    // once more
    if ($httpResponse == false)
    {
      $httpResponse = stream_get_contents($fp);
    }
    */            

    // 2.) cURL (cURL enabled)
    // setting and sending       
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    $httpResponse = curl_exec($ch);
    curl_close($ch);

    // getting
    if(!$httpResponse) 
    {
      exit("An error occured.");
    }   
    // extract the response details
    $httpResponseAr = explode("&", $httpResponse);
    $httpParsedResponseAr = array();
    foreach ($httpResponseAr as $value) 
    {
      $tmpAr = explode("=", $value);
      if(sizeof($tmpAr) > 1) 
      {
        $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
      }
    }
    if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) 
    {
      exit("An error occured.");
    }

    // return
    return $httpParsedResponseAr;
}

?>
