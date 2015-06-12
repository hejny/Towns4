<?php
/* Towns4, www.towns.cz 
   © Pavol Hejný | 2011-2015
   _____________________________

   core/page/surkey.php

   Surovinová lišta
*/
//==============================


$border4=array(2,'444444');


//==========================================================================NORMAL
ob_start();
$GLOBALS['ss']['use_object']->hold->showimg(true,false,false,$border4);
$buffer = ob_get_contents();
ob_end_clean();

ahref($buffer,'e=content;ee=create-create_master;submenu=2');


?>
