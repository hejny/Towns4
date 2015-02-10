<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/hold/func_core.php

   Výměna surovin - funkce systému
*/
//==============================




//define('a_change_cooldown',true);
function a_change($from,$to,$count){//e("$from,$to,$count");
    $count=intval($count);
    if(!$count or $count<2){
        $GLOBALS['ss']["query_output"]->add("error",lr('change_error_nocount'));
         return;
    }
        if($from==$to){
        $GLOBALS['ss']["query_output"]->add("error",lr('change_error_same'));
         return;
    }
    
    $eff=$GLOBALS['ss']["aac_object"]->supportF('change','eff');
    if($from==plus_res){$eff=1;}

     $price=new hold($from.'='.$count);
     if(!use_hold($price)){
        $GLOBALS['ss']["query_output"]->add("error",lr('change_error_price'));
         return;
     }
     

     $GLOBALS['ss']["use_object"]->hold->add($to,floor($count*$eff));
     //use_hold($price2);
     
     $GLOBALS['ss']["query_output"]->add("success",lr('change_success',($from).";".($to).";$count;".floor($count*$eff)));
     $GLOBALS['ss']["query_output"]->add("1",1);
}
    
?>
