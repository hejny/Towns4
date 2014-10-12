<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/attack/mine.php

   Stránka pro automatické zahájení těžby
*/
//==============================


header('refresh:60'); 


// AND (".time()."-ad0) 
$ad0='SELECT max(`t`) FROM [mpx]objects as x WHERE x.own=(SELECT y.id FROM [mpx]objects as y WHERE y.own=[mpx]objects.id LIMIT 1) AND type=\'building\'';
//$ad1='SELECT count(1) FROM [mpx]objects as x WHERE x.own=(SELECT y.id FROM [mpx]objects as y WHERE y.own=[mpx]objects.id LIMIT 1) AND type=\'building\'';
$ad3='SELECT max(x.id) FROM [mpx]objects as x WHERE x.own=[mpx]objects.id AND (type=\'town\' OR type=\'town2\')';

$where="type='user' AND ((".($ad0).")>".(time()-(3600*24)).") AND ww!=0 AND ww!=-1 ";

$ad0=',('.$ad0.') as ad0';
$ad3=',('.$ad3.') as ad3';
$order="id";

$array=sql_array("SELECT `id`,`name`,`type`,`dev`,`fs`,`fp`,`fr`,`fx`,`own`,`in`,`x`,`y`$ad0,`ww`$ad2$ad3 FROM `".mpx."objects` WHERE ".$where." ORDER BY $order");




   

foreach($array as $row){
   list($id,$name,$type,$dev,$fs,$fp,$fr,$fx,$own,$in,$x,$y,$t,$ww,$ad3)=$row;


   

   br();
   e('<b>'.$name.'</b>('.$id.') - town '.$ad3.' - '.timer($t));   

   $array2=sql_array("SELECT `id`,`func` FROM `".mpx."objects` WHERE `type`='building' AND (`func` LIKE  '%tree%' OR `func` LIKE  '%rock%') AND `own`='$ad3'");
   foreach($array2 as $row2){
	list($id2,$func2)=$row2;
	br();
	echo(nbspo.'<b>'.$id2.':</b>');
	$func2=func2list($func2);
	foreach($func2 as $funcname=>$tmp){
		if($tmp['class']=='attack' and ($tmp['profile']['limit']=='rock' or $tmp['profile']['limit']=='tree')){

			   force_login($id);
			//---------------------------------------------------mine
			$limit=$tmp['profile']['limit'];
			$attack_master=$id2;
			$attack_function=$funcname;
			$object=new object($attack_master);
				$x=$object->x;$y=$object->y;$ww=$object->ww;
				$func=$object->func->func($attack_function);
				//r($func);
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
   			       unset($GLOBALS['ss']["use_object"]);
   			       unset($GLOBALS['ss']["log_object"]);
		
			   //---------------------------------------------------
			br();
			echo(nbspo.nbspo.'func '.$funcname.'('.$tmp['profile']['name'].') - type '.$tmp['profile']['limit'].' - attack to '.$attack_id);
			   //-------------------------------------
			    if($attack_id){
				xquery($attack_master.'.'.$attack_function,$attack_id);
				xreport();
				//br();
				//e(nbspo.nbspo."$attack_master.$attack_function $attack_id");
				//$url="e=content;ee=attack-mine;q=$attack_master.$attack_function $attack_id";
			        //urlx($url);
				//e($url);
			    }else{br();e(lr('attack_no_'.$limit));}
			   //-------------------------------------
		}
	}



   }


   

}

?>
