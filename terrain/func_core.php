<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/terrain/func_core.php

   Změny terénu
*/
//==============================




//define('a_terrain_cooldown',true);
function a_terrain($terrain,$x=0,$y=0){
  
    $x=intval($x);
    $y=intval($y);
    
    if(!intval(sql_1data("SELECT COUNT(1) FROM `[mpx]map`  WHERE `ww`=".$GLOBALS['ss']["ww"]." AND `x`=$x AND `y`=$y AND terrain='$terrain' LIMIT 1"))){   


    if(!intval(sql_1data("SELECT COUNT(1) FROM `[mpx]pos_obj`  WHERE `ww`=".$GLOBALS['ss']["ww"]." AND  ROUND(`x`)=$x AND ROUND(`y`)=$y LIMIT 1"))){

    
    if(intval(sql_1data("SELECT COUNT(1) FROM `[mpx]pos_obj` WHERE own!='".$GLOBALS['ss']['useid']."'AND `ww`=".$GLOBALS['ss']["ww"]." AND POW($x-x,2)+POW($y-y,2)<=POW(collapse,2)"))==0){
       
    if(intval(sql_1data("SELECT COUNT(1) FROM `[mpx]pos_obj` WHERE own='".$GLOBALS['ss']['useid']."'AND `ww`=".$GLOBALS['ss']["ww"]." AND POW($x-x,2)+POW($y-y,2)<=POW(expand,2)"))>=1){
        

    //if($GLOBALS['ss']["use_object"]->hold->takehold($fc)){
            

	sql_query("UPDATE [mpx]map SET terrain='$terrain' WHERE `ww`=".$GLOBALS['ss']["ww"]." AND `x`=$x AND `y`=$y");
        //define('terrain_error',"($x,$y)");
	changemap($x,$y,2);
        define('terrain_build',true);

//==============================
$GLOBALS['ss']["query_output"]->add("1",1);
    
         /*}else{
            define('terrain_build',true);
            define('terrain_error','{terrain_error_price}');
            $GLOBALS['ss']["query_output"]->add("error","{terrain_error_price}");
        }*/
    }else{
        define('terrain_build',true);
        define('terrain_error','{terrain_error_expand}'."($x,$y)");
        $GLOBALS['ss']["query_output"]->add("error","{terrain_error_expand}");
    }}else{
        define('terrain_build',true);
        define('terrain_error','{terrain_error_collapse}');
        $GLOBALS['ss']["query_output"]->add("error","{terrain_error_collapse}");
    }}else{
        define('terrain_build',true);
        define('terrain_error','{terrain_error_building}'."($x,$y)");
        $GLOBALS['ss']["query_output"]->add("error","{terrain_error_building}");
    }}else{
        define('terrain_build',true);
        define('terrain_error','{terrain_error_duplicite}');
        $GLOBALS['ss']["query_output"]->add("error","{terrain_error_duplicite}");
    }



}
?>
