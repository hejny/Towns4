<?php
/* Towns4, www.towns.cz 
   © Pavol Hejný | 2011-2015
   _____________________________

   core/page/copy.php

   Dolní Copyright
*/
//==============================




if(logged or 1){
ahref(trr(lr('copy2'),0,1),'e=content;ee=profile;id='.$GLOBALS['inc']['write_id'],"none","x");
}else{
ahref(/*tcolorr(*/trr(lr('copy')/*,'777777'*/,0,1),"e=content;ee=help;page=copy","none","x");
}
?>

