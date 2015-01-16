<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/quest/quest_core.php

   Ukoly
*/
//==============================


window(lr('title_quest'));
contenu_a();


foreach(sql_array('SELECT quest FROM [mpx]questt WHERE id='.useid) as $row){

e($row[0]);
br();

}
?>
quest


<?php
contenu_b();

?>
