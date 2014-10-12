<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/terrain/build.php

   Terén před změnou
*/
//==============================


//die(1);

require_once(root.core."/func_map.php");
$id=$_GET["id"];
if(!$id and $GLOBALS['ss']["object_build_id"])$id=$GLOBALS['ss']["object_build_id"];
$GLOBALS['ss']["object_build_id"]=$id;

$func=$_GET["func"];
if(!$func and $GLOBALS['ss']["object_build_func"])$func=$GLOBALS['ss']["object_build_func"];
$GLOBALS['ss']["object_build_func"]=$func;

if(/*!$GLOBALS['ss']["master"] and */$_GET["master"])$GLOBALS['ss']["master"]=$_GET["master"];

//e(0);
if($id and $GLOBALS['ss']['master']){//e(1);





    ?>
	<script type="text/javascript">
    	$('#grid').css('display','block');
	</script>
    <?php

$js="\$.get('?y=".$_GET['y']."&e=map&q=".$GLOBALS['ss']['master'].".".$GLOBALS['ss']["object_build_func"]." $id,'+tbuild_x+','+tbuild_y, function(vystup){\$('#map').html(vystup);});";


?>
<div style="position:absolute;"><div style="position:relative;left:4;top:4;z-index:20;">
<?php icon(js2($hide="\$('#terrain-build').css('display','none');\$('#expandarea').css('display','none');".(!$q?"\$('#grid').css('display','none');":'')),"cancel","{cancel}",20); ?>
</div></div>
<!--==========-->
<div style="position:absolute;"><div style="position:relative;left:-1;top:260;">
<?php ahref(trr(nbsp2.'{f_create_building_submit}'.nbsp2,14,3),js2($hide.';'.$js)); ?>
</div></div>
<?php




    $terrainurl=map1($id,NULl,NULL,true);


   $hovnonad=116-50;


e('<div class="build_models" id="build_model_'.$rot.'" style="display:'.(($rot==$randrot or !$q)?'block':'none').';"><img src="'.imageurl('design/blank.png').'" border="0" width="'.(110*0.75).'"  height="'.($hovnonad).'"><br><img src="'.$terrainurl.'"  style="border: 2px solid #cccccc;border-radius: 4px;position:relative;left:-75px;" width="'.(110*0.75*(1+t_pofb+t_pofb)).'"></div>');





}
?>


