<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný, Přemysl Černý | 2011-2015
   _____________________________

   core/plus/paypal/plog.php

   PayPal
*/
//==============================




/* 
  Copyright Injection Computers, Inc.
  
  PayPal log
  log file log.csv
*/
    
// YOU do not have to define the following, just edit if necessary    
function writeLog($text)
{
    $str = '';
    $str .= date(DATE_RFC822) . ';' . time() . ';' . $text . "\n";
    $file = 'log/paypal.csv';
    fpc($file, file_get_contents($file).$str);
}
    
?>
