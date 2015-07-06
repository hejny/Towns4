<?php
/* Towns4, www.towns.cz 
   © Pavol Hejný | 2011-2015
   _____________________________

   core/page/output.php

   Výstup v debug módu
*/
//==============================
?>
<?php window(lr('title_app')); ?>

<div style="height:<?=$GLOBALS['ss']['screenheight']?>px;overflow: hidden;">
<iframe src="<?=$_GET['simulate']?>" style="width:calc(100% + 22px);height:<?=$GLOBALS['ss']['screenheight']+22?>px;" frameborder="0"></iframe>
</div>
