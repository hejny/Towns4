<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/create/upgrade.php

   Opravit / vylepšit budovu
*/
//==============================




$fields="`id`, `name`, `type`, `origin`, `fs`, `fp`, `fr`, `fx`, `fc`, `func`, `hold`, `res`, `profile`, `set`, `hard`, `own`, (SELECT `name` from ".mpx."objects as x WHERE x.`id`=".mpx."objects.`own`) as `ownname`, `in`, `ww`, `x`, `y`, `t`";
/*if($_GET["id"]){
    $id=$_GET["id"];
}elseif($GLOBALS['get']["id"]){
    $id=$GLOBALS['get']["id"];
}else{
    $id=$GLOBALS['ss']["use_object"]->set->ifnot('upgradetid',0);
}*/
sg("id");
//echo($id);

//--------------------------
if($id?ifobject($id):false){
    $sql="SELECT $fields FROM ".mpx."objects WHERE id=$id";
    $array=sql_array($sql);
    list($id, $name, $type, $origin, $fs, $fp, $fr, $fx, $fc, $func, $hold, $res, $profile, $set, $hard, $own, $ownname, $in, $ww, $x, $y, $t)=$array[0];

 
    if($own==useid or $own==logid){
        //$GLOBALS['ss']["use_object"]->set->add('upgradetid',$id);
        //--------------------------
        if($fs==$fp){
	    //========================================UPGRADE
		//$tmpobject=new object($id);
		window(lr('title_upgrade'));
			contenu_a();


			/*$functions=array(
					'attack'=>array(array('attack',1,false,1),array('count',5,false,1),array('distance',0.2,false,1),array('cooldown',-20,1,3600),array('eff',0.01,0.9,0.1,'%'),array('xeff',0.01,0.9,0.1,'%')),
					'attack2'=>array(array('attack',1,false,1),array('count',5,false,1),array('distance',0.2,false,1),array('cooldown',-20,1,3600),array('eff',0.01,0.9,0.1,'%'),array('xeff',0.01,0.9,0.1,'%')),
					'attack3'=>array(array('attack',1,false,1),array('count',5,false,1),array('distance',0.2,false,1),array('cooldown',-20,1,3600),array('eff',0.01,0.9,0.1,'%'),array('xeff',0.01,0.9,0.1,'%')),
					'attack4'=>array(array('attack',1,false,1),array('count',5,false,1),array('distance',0.2,false,1),array('cooldown',-20,1,3600),array('eff',0.01,0.9,0.1,'%'),array('xeff',0.01,0.9,0.1,'%')),
					'create'=>array(array('cooldown',-20,1,3600),array('eff',0.01,0.9,0.1,'%')),
					'change'=>array(array('eff',0.01,0.9,0.1,'%')),
					'expand'=>array(array('distance',0.1,false,0.1)),
					'collpse'=>array(array('distance',0.1,false,0.1)),
					'resistance'=>array(array('hard',0.1,false,0.1))
					);*/


		
		
		//------------------------------------UPGRADE_FUNC
			xreport();
			if(xsuccess()){
			?>
			<script>
			<?php subjs('surkey'); ?>
			</script>
			<?php
			}


			//e($_GET['q']);
			$primes=array();
			$array=sql_array('SELECT `id`,`name`,`fc` FROM `[mpx]objects` WHERE `type`=\'prime\' ');
			foreach($array as $row){
				list($pid,$name,$fc)=$row;
				$primes[$pid]=array();
				$primes[$pid]['count']=0;
				$primes[$pid]['name']=$name;
				$primes[$pid]['fc']=$fc;
			}

			//print_r($primes);
			//echo($origin);
			$origin=explode(',',$origin);
			//sort($origin);
			//print_r($origin);
			foreach($origin as $prime){
				//if(!$primes[$origin])$primes[$origin]=array('count'=>0);
				//echo($prime.'++');br();
				$primes[$prime]['count']++;
			}

			//print_r($primes);
			info(lr('upgrade_info'));
			br();
			e('<table width="100%" border="0" cellpadding="0" cellspacing="0">');
			foreach($primes as $pid=>$prime){
				if($prime['count'] and strpos($prime['name'],'create_')===false and $prime['name']!='{block}' and $prime['name']!='{resistance}'){
					//print_r($prime);
					e('<tr>');
					//e('<td width="10">'.($prime['count']==1?'':$prime['count'].'x').'</td>');
					e('<td colspan="2">'.tfontr(($prime['count']==1?'':$prime['count'].'x'.nbsp).contentlang($prime['name']),20).'</td>');
					e('</tr>');

					$q=true;
					foreach(array(1,4,8,16,32,64,128,256) as $count){
						if($q){
						$buffer='';
						$buffer.=('<tr><td>');
	
						$buffer.=ahrefr(lr('add_prime',$count),'e=content;ee=create-upgrade;q='.$id.'.upgrade '/*$funcname.','.$paramname.','.($value-(-$step))*/.$pid.','.$count);

						$buffer.=('</td><td>');

						$fc=new hold($prime['fc']);
						$fc->multiply(20*$count);
						ob_start();    
						$fc->showimg(true);           
						$buffer.=ob_get_contents();
						ob_end_clean();
						if(!$GLOBALS['ss']['use_object']->hold->testhold($fc)){
							$buffer.=('<tr><td colspan="2">');
							if(!$GLOBALS['ss']['use_object']->hold->testchange($fc)){
								//break();
								if($count==1){
								e('<tr><td colspan="2">');
								$fc->showimg(true);
								error(lr('upgrade_error_price2'));
								e('</td></tr>');
								}
								$q=false;
								
								
							}else{
								ob_start();    
								blue(lr('upgrade_change'));          
								$buffer.=ob_get_contents();
								ob_end_clean();
								//ahref(lr('add_prime'.$count),'e=content;ee=create-upgrade;q='.$id.'.upgrade '/*$funcname.','.$paramname.','.($value-(-$step))*/.$pid.','.$count);
							}
							$buffer.=('</tr>');
						}else{
							

						}
						unset($fc);
						$buffer.=('</td></tr>');
						if($q)e($buffer);
						}
					}
					

					e('<tr><td colspan="2">&nbsp;</td></tr>');
				}
			}
			e('</table>');


			//print_r($primes);
			/*
			xreport();
			if(xsuccess()){
			  ?>
			<script>
			<?php urlx('e=map_context;ee=minimenu',false); ?>
			</script>
			<?php
			}
			textb(tr($name));br();
			e('{upgrade_title}');
			br();*/



		//------------------------------------
		contenu_b();
		}
		//------------------------------------
		
	    //========================================
        }else{
		error(lr('demaged'));

    }
	    //========================================
}

?>
