<?php
/* Towns4, www.towns.cz 
   © Pavol Hejný | 2011-2015
   _____________________________

   core/page/ctable.php

   *stará verze systému* tabulka tvrdosti v debug modu
*/
//==============================
?><?php window("{items}",10); ?>
<div id="ctable_content">
</div>
<script type="text/javascript">
function dechex (number) {
    if (number < 0) {
        number = 0xFFFFFFFF + number + 1;
    }
   return parseInt(number, 10).toString(16);
}
    //setInterval(function(){
            s=0;
            stream='<table border="0" cellpadding="3" cellspacing="0">';
            for (var y=0; y<area.length;y++){
                stream=stream+'<tr>';
                for (var x=0; x<area[y].length;x++){
                    /*bg='cccccc';*/
                    bg=dechex(area[y][x]*255);
                    bg=bg+bg+bg;
                    /*if(Math.round(_xc-area_x)==x && Math.round(_yc-area_y)==y){
                        bg='ff0000';
                    }*/
                    stream=stream+'<td width="'+s+'" height="'+s+'" bgcolor="#'+bg+'"></td>';
                }
                stream=stream+'</tr>';
            }
            stream=stream+'</table>';
            $("#ctable_content").html(stream);
    //},500);
</script>
