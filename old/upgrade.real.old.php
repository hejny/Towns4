<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/create/upgrade.php

   Opravit / vylepšit budovu
*/
//==============================




$fields="`id`, `name`, `type`, `dev`, `origin`, `fs`, `fp`, `fr`, `fx`, `fc`, `func`, `hold`, `res`, `profile`, `set`, `hard`, `own`, (SELECT `name` from ".mpx."objects as x WHERE x.`id`=".mpx."objects.`own`) as `ownname`, `in`, `ww`, `x`, `y`, `t`";
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
    list($id, $name, $type, $dev, $origin, $fs, $fp, $fr, $fx, $fc, $func, $hold, $res, $profile, $set, $hard, $own, $ownname, $in, $ww, $x, $y, $t)=$array[0];

 
    if($own==useid or $own==logid){
        //$GLOBALS['ss']["use_object"]->set->add('upgradetid',$id);
        //--------------------------
        if($fs==$fp){
	    //========================================UPGRADE
		//$tmpobject=new object($id);
		window('{title_upgrade}');
		$q=submenu(array("content","create-upgrade"),array(/*"upgrade_help",*/"upgrade_origin","upgrade_profile"/*,"upgrade_func","upgrade_funcadd"*/,"upgrade_res"/*,"upgrade_rot"/*,"messages_report","messages_new"*/),1,'upgrade');


			$functions=array(
					'attack'=>array(array('attack',1,false,1),array('count',5,false,1),array('distance',0.2,false,1),array('cooldown',-20,1,3600),array('eff',0.01,0.9,0.1,'%'),array('xeff',0.01,0.9,0.1,'%')),
					'attack2'=>array(array('attack',1,false,1),array('count',5,false,1),array('distance',0.2,false,1),array('cooldown',-20,1,3600),array('eff',0.01,0.9,0.1,'%'),array('xeff',0.01,0.9,0.1,'%')),
					'attack3'=>array(array('attack',1,false,1),array('count',5,false,1),array('distance',0.2,false,1),array('cooldown',-20,1,3600),array('eff',0.01,0.9,0.1,'%'),array('xeff',0.01,0.9,0.1,'%')),
					'attack4'=>array(array('attack',1,false,1),array('count',5,false,1),array('distance',0.2,false,1),array('cooldown',-20,1,3600),array('eff',0.01,0.9,0.1,'%'),array('xeff',0.01,0.9,0.1,'%')),
					'create'=>array(array('cooldown',-20,1,3600),array('eff',0.01,0.9,0.1,'%')),
					'change'=>array(array('eff',0.01,0.9,0.1,'%')),
					'expand'=>array(array('distance',0.1,false,0.1)),
					'collpse'=>array(array('distance',0.1,false,0.1)),
					'resistance'=>array(array('hard',0.1,false,0.1))
					);


		
		
		//------------------------------------UPGRADE_FUNC

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
			br();


			br();
			e('<table width="100%" border="0" cellpadding="0" cellspacing="0">');
		
			$funcp=$func;
			$tmp=new func($funcp);
			$pricep=$tmp->fc();
			$func=func2list($func);
			foreach($functions as $funcname=>$data){
				if($func[$funcname]){
					if($func[$funcname]['profile']['name']){
						$funcname_=$func[$funcname]['profile']['name'];
					}else{
						$funcname_='{f_'.$func[$funcname]['class'].'}';
					}	

					//alert('<h2>'.$funcname_.'</h2>','000000');
					e('<tr bgcolor="111111"><td colspan="3"><h2>'.$funcname_.'</h2></td></tr>');
					//foreach($func[$funcname]['params'] as $paramname=>$value){
				
					foreach($data as $tmp){list($paramname,$step_base,$max,$start,$type)=$tmp;
						if($func[$funcname]['params'][$paramname]){
							//$func[$funcname]['params']


							$value=$func[$funcname]['params'][$paramname];
							$value=$value[0]*$value[1];
							e('<tr bgcolor="222222"><td width="150"><b>{f_'.$func[$funcname]['class'].'_'.$paramname.'}: </b></td><td>'.($type=='%'?ceil($value*100).'%':$value).'</td></tr>');
							
							$qq=true;
							foreach(array(1,3,9,9*3,9*9,9*9*3) as $step_m){$step=($step_m*$step_base);
								if($step>0)$step_='(+'.nbsp.($type=='%'?ceil($step*100).'%':$step).nbsp.')'; else $step_='('.nbsp.($type=='%'?ceil($step*100).'%':$step).nbsp.')';

								if($max and ((($value-(-$step))>=$max and $step>0) or (($value-(-$step))<=$max) and $step<0)){$step_='';}
									//-------výpočet ceny
									$funcx=new func($funcp);
									//e("addF($funcname,$paramname,$value-(-$step))");
									$funcx->addF($funcname,$paramname,$value-(-$step));
									$pricex=$funcx->fc();
									//$pricep->showimg(true);
									//$pricex->showimg(true);

									$pricex->takehold($pricep);
									//e($funcx->fs().','.$pricep);
									//-------
						
		
								e('<tr><td>');

								if($step_){
									if($pricex->fp()){
										$pricex->showimg(true);
									}else{
										tfont('{upgrade_free}',NULL,'36bb29');
									}
									e('</td><td>');

									if(!$GLOBALS['ss']['use_object']->hold->testhold($pricex)){
										//br();
										tfont('{upgrade_error_price}',NULL,'FF0000');
									}else{
										ahref($step_,'e=content;ee=create-upgrade;q='.$id.'.upgrade '.$funcname.','.$paramname.','.($value-(-$step)));
									}
									$qq=false;
								}else{
								
								}
								e('</td></tr>');
							}
							if($qq)e('<tr><td colspan="2">{upgrade_noupgrade}</td></tr>');
						
						}
					}
					e('<tr><td colspan="3"><br></td></tr>');
					//print_r($tmp);
				
				}
				
			}

			e('</table>');

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
