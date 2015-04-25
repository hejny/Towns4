<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2015
   _____________________________

   core/page/projects.php

   testování
*/
//==============================

window(lr('title_windowopen'));


/*<div style="width:445px;height:100vh;overflow:hidden;">


<iframe src="../app/projects" width="480" height="100%" frameborder="0" marginheight="0" marginwidth="0" scrolling="yes">Načítání...</iframe>
</div>*/

$url='http://'.$GLOBALS['ss']['get']['url'];
$settime=$GLOBALS['ss']['get']['settime'];

if($settime){

   $GLOBALS['ss']['log_object']->set->add($settime,time());
}



?>
<script type="text/javascript">
   var win = window.open('<?= $url ?>', '_blank');
   if(win){
      //Browser has allowed it to be opened
      win.focus();
      //alert('close');
      w_close('windowopen');


   }else{
      //Browser has blocked it
      $('#windowopen').append('<?php le('to_open_window_click_here'); ?><br/><a href="<?= $url ?>" target="_blank"><?= $url ?></a>');
   }
</script>

