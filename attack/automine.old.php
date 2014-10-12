<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/attack/mine.php

   Stránka pro automatické zahájení těžby
*/
//==============================


$cooldown=-(time()-$GLOBALS['ss']["use_object"]->set->ifnot("automine_time",0)-(600));
if($cooldown>0){}else{

$GLOBALS['ss']["use_object"]->set->add("automine_time",time());

   $array2=sql_array("SELECT `id`,`func` FROM `".mpx."objects` WHERE `type`='building' AND (`func` LIKE  '%tree%' OR `func` LIKE  '%rock%') AND `own`='".useid."'");
   foreach($array2 as $row2){
	list($id2,$func2)=$row2;
	//br();
	//echo(nbspo.'<b>'.$id2.':</b>');
	$func2=func2list($func2);
	foreach($func2 as $funcname=>$tmp){
		if($tmp['class']=='attack' and ($tmp['profile']['limit']=='rock' or $tmp['profile']['limit']=='tree')){

			//---------------------------------------------------mine
			$limit=$tmp['profile']['limit'];
			$attack_master=$id2;
			$attack_function=$funcname;
			$object=new object($attack_master);
				$x=$object->x;$y=$object->y;$ww=$object->ww;
				$func=$object->func->func($attack_function);
				$distance=$func['distance'];
				$attack=$func['attack'];
				$sql="SELECT id,func FROM [mpx]objects WHERE ww=$ww  AND type='$limit' AND POW(x-$x,2)+POW(y-$y,2)<=POW($distance,2) ORDER BY fr  DESC";
				$attack_id=false;
				foreach(sql_array($sql) as $row){
				    list($tmpid,$tmpfunc)=$row;
				    $tmpfunc=new func($tmpfunc);
				    $defence=$tmpfunc->func('defence');
				    $defence=$defence['defence'];
				    
				    if($defence<$attack){//e($tmpid.' - ok');
					$attack_id=$tmpid;
					break;
				    }
				    
			    	}
			  //---------------------------------------------------
				
				$GLOBALS['ss']["aac_object"]->update();
			       unset($GLOBALS['ss']["aac_object"]);
		
			   //---------------------------------------------------
			   //-------------------------------------
			    if($attack_id){
				//echo($attack_master.'.'.$attack_function.' , '.$attack_id);
				xquery($attack_master.'.'.$attack_function,$attack_id,"1");
				$GLOBALS['ss']["use_object"]->update(true);
				//xreport();
			    }else{/*br();e("{attack_no_".$limit."}");*/}
			   //-------------------------------------
		}
	}



   }


}


?>

<script>
setTimeout(function(){
   //alert('aaa');
    refreshMap();
    w_close('mine');
    w_close('window_mine');
},10);
</script>


