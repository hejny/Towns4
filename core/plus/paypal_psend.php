<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný, Přemysl Černý | 2011-2015
   _____________________________

   core/plus/paypal/psend.php

   PayPal
*/
//==============================




/* 
  Copyright Injection Computers, Inc.
  
  PayPal send
  GETs first and second
  first - offer id
  second - user id
  then redirects to PayPal
*/

// important
require_once('paypal_pconfig.php');
require_once('paypal_plog.php');   
    
// YOU do not have to define the following, just edit if necessary    
// send
// line_id - coin offer in DB
// user_id - user in DB
$line_id = 0;
$user_id = 0;
if (isset($_GET['first']))
  $line_id = (int)$_GET['first'];
if (isset($_GET['second']))
  $user_id = (int)$_GET['second'];   
// order
// must exist 
if ($line_id > 0 && $user_id > 0)
{
  $line = getOffer($line_id);
  $user = getUser($user_id);
  // must exist
  if ($line != NULL && $user != NULL)
  {
      // if one way, check if the offer is already purchased
  
      // prepare
      // data
      $nvp = "&PAYMENTREQUEST_0_AMT=" . $line['amount'] . "&PAYMENTREQUEST_0_CURRENCYCODE=USD&RETURNURL=" . urlencode(RECEIVEURL) . "&CANCELURL=" . urlencode(CANCELURL) . "&PAYMENTREQUEST_0_PAYMENTACTION=Sale";
      $nvp .= "&PAYMENTREQUEST_0_DESC=" . urlencode((string)$line['title']); // description
      $nvp .= "&PAYMENTREQUEST_0_CUSTOM=" . urlencode($line_id . '+' . $user_id); // custom
      // send request
      $httpParsedResponseAr = prepareRequest('SetExpressCheckout', $nvp);
      // check
      if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) 
      {
        if (isset($httpParsedResponseAr["TOKEN"]))
        {   
          // place for log? ---
          writeLog('The order of ' . $line_id  . ' started to be delivered to: ' . $user_id . ' (' . $user['realname'] . ')');                       
          // redirect
          $redirect = "https://www." . ENVIRONMENT . "paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=";
          // redirect here
          header("Location: " . ($redirect  . $httpParsedResponseAr["TOKEN"]));
        }
        else
          exit("A token error occured.");
       } 
       else  
       {
          exit("The payment failed.");
       }               
   }
}    
    
?>
