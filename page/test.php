<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/page/test.php

   testování
*/
//==============================

?>
ddd
<?php

exec('ls', $out);
var_dump($out);
// Look an array

$out = shell_exec('ls');
var_dump($out);


$a=shell_exec('ifconfig');
print_r($a);

exec('service mysql stop');

br();

exec('sudo poweroff', $out);
var_dump($out);
// Look an array
/*
function mailx($to,$subject,$message){

    $url=url.'?e=mailx&to='.urlencode($to).'&subject='.urlencode($subject).'&message='.urlencode($message);
    get_headers($url);

}

//-----------------------------------

/*echo('test');br();
mailx('hejny.pavel@gmail.com','Funguje to','corehurá'.nln.'Funguje to'.nln.'ěščřžýáíéúů');
echo('rychlosti');br();
echo('mailu');*/
?>