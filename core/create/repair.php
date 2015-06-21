<?php
/* Towns4, www.towns.cz 
   © Pavol Hejný | 2011-2015
   _____________________________

   core/create/upgrade.php

   Opravit / vylepšit budovu
*/
//==============================




$fields="`id`, `name`, `type`, `origin`, `fs`, `fp`, `fr`, `fx`, `fc`, `func`, `hold`, `res`, `profile`, `set`, `hard`, `own`, (SELECT `name` from `[mpx]pos_obj` as x WHERE x.`id`=`[mpx]pos_obj`.`own`) as `ownname`, `in`, `ww`, `x`, `y`, `t`";
/*if($_GET["id"]){
    $id=$_GET["id"];
}elseif($GLOBALS['get']["id"]){
    $id=$GLOBALS['get']["id"];
}else{
    $id=$GLOBALS['ss']['use_object']->set->ifnot('upgradetid',0);
}*/
sg("id");
//echo($id);

//--------------------------
if($id?ifobject($id):false){
    $sql="SELECT $fields FROM `[mpx]pos_obj` WHERE id=$id";
    $array=sql_array($sql);
    list($id, $name, $type, $origin, $fs, $fp, $fr, $fx, $fc, $func, $hold, $res, $profile, $set, $hard, $own, $ownname, $in, $ww, $x, $y, $t)=$array[0];

 
    if($own==$GLOBALS['ss']['useid'] or $own==$GLOBALS['ss']['logid']){
        //$GLOBALS['ss']['use_object']->set->add('upgradetid',$id);
        //--------------------------
        if($fs==$fp){
		success(lr('repaired'));
        }else{
	    //========================================REPAIR
            window(lr('title_repair'));
            
            
            
            infob(ahrefr(nbsp.lr('repair_ok'),'e=content;ee=create-repair;q='.$id.'.repair'));
            contenu_a();
            xreport();
if(xsuccess()){
  ?>
<script type="text/javascript">
setTimeout(function(){
    w_close('content');
},10);
<?php urlx('e=objectmenu;ee=objectmenu',false); ?>
</script>
<?php
}
	    //$origin=explode(',',$origin);
	    //$price=l(($fp)/count($origin));cei
		$repair_fuel=repair_fuel($id);
		$price=round((1-($fp/$fs))*$repair_fuel);
		
            $price=new hold(/*$fc*/'fuel='.$price);
            //$price->multiply($fp/$fs);
            textb(lr('repair_price').': ');
            $price->showimg();
		
		if(!$GLOBALS['ss']['use_object']->hold->testhold($price)){
		if($GLOBALS['ss']['use_object']->hold->testchange($price)){
			blue(lr('repair_change'));
		}
		}
            //hr();
            //profile($id);
            contenu_b();
            
        }
        //--------------------------
    }
	    //========================================
}

?>
