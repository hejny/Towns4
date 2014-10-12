<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/page/mobilecontent.php

   Mobilni obsah
*/
//==============================

?>
<table height="104%" width="104%" border="0" style="position:relative;top:-2px;left:-2px;background: rgba(7,7,7,0.85);border: 2px solid #222222;border-radius: 7px;" cellpadding="0" cellspacing="0">
<tr><td align="left" valign="top">
<?php
infob(ahrefr(textbr(lr('close')),js2("\$('#mobilecontent').css('display','none');")),'221730');
?>
<div style="width: 100%; height: 100%; overflow: hidden">
<span id="content">
<?php
//e($GLOBALS['mcontent']);
if($GLOBALS['mcontent']){
	eval(subpage($GLOBALS['mcontent']));
}else{
	e('&nbsp;');
}
?>
</span>
</div>
</td>
</tr>
</table>
<?php if(!$GLOBALS['mcontent']){ ?>
<script>
setTimeout(function(){
    $('#mobilecontent').css('display','none');
},10);
</script>
<?php } ?>
