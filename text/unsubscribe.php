<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2014
   _____________________________

   core/text/unsubscribe.php

   odhlseni mailu
*/
//==============================

if($_GET['unsubscribe']){

send_message(logid,$GLOBALS['inc']['write_id'],'unsubscribe',$_GET['unsubscribe']);
backup_text($_GET['unsubscribe']);


success('Váš E-mail byl odhlášen...');

}
?>