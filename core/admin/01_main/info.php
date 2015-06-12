<?php
/* Towns4Admin, www.towns.cz 
   © Pavol Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================
?>
<h3>Informace </h3>
<?php

$total = disk_total_space("/");
$free = disk_free_space("/");

$totalx = floor($total/1000/1000)/1000;
$freex = ceil($free/1000/1000)/1000;

$percent=floor(100*$free/$total);

e("<b>volné místo na disku:</b> $freex "."GB / $totalx "."GB ( $percent %) ");
br();
?>
<a href='<?php e(url); ?>?e=admin-phpinfo' target="_blank">phpinfo()</a>
