<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2015
   _____________________________

   core/page/cache_loading.php

   načítání pomocnych souboru
*/
//==============================

$style=' style="position:relative;top:-2px;left:-2px;background: rgba(30,30,30,0.9);border: 2px solid #222222;border-radius: 2px;"';

?>


<table height="20" width="200" border="0" cellpadding="0" cellspacing="0" <?php e($style); ?>>
<tr><td valign="middle" align="center">

<?php



imge('design/loading.gif',lr('loading'),17,17);
tee(lr('cache_loading'),13,1);

?>

</td>
</tr>
</table>

