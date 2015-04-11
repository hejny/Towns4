<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2015
   _____________________________

   core/page/test.php

   testování
*/

$result=sql_array('SELECT * FROM [mpx]map LIMIT 4');

print_r($result);


?>
