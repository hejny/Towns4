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


		
		//------------------------------------BUILDING_TYPE
		if($q==1){contenu_a();
		require_once(root.core."/func_map.php");





			$reference=sql_array("SELECT `id`,`name`,`res`,`profile`,`own` FROM `[mpx]objects` WHERE `ww`=0 AND `origin`='$origin' ORDER BY `id`");
			if(!$reference){
				$GLOBALS['ss']["helppage"]='upgrade_start';
	   			$GLOBALS['nowidth']=true;
	   			eval(subpage('help'));
				br();
			}else{

				$GLOBALS['ss']["helppage"]='upgrade';
	   			$GLOBALS['nowidth']=true;
	   			eval(subpage('help'));
				br();



				//blue('{public_unique_info}');

			}

$profile_str=$profile;
$profile=new profile($profile);
$description=$profile->val('description');
$author=$profile->val('author');
$set=new set($set);
$auto_name=$set->val('auto_name');
$auto_description=$set->val('auto_description');
$auto_profile=new profile();
$auto_profile->add('description',$auto_description);
$auto_profile=$auto_profile->vals2str();
$auto_res=$set->val('auto_res');

$reference=array_merge($reference,array(array(-1,$auto_name,$auto_res,$auto_profile,$author)));

//----------------check changes
$myversion=true;
foreach($reference as $row){
    list($_id,$_name,$_res,$_profile,$_own)=$row;
    $_profile=new profile($_profile);
    $_description=trim($_profile->val('description'));

			if($GLOBALS['get']['reference'] and $GLOBALS['get']['reference']==$_id){
				$name=$_name;
				$description=$_description;
				$res=$_res;
				$tmpobject=new object($id);
				$tmpobject->name=$name;
				$tmpobject->profile->add('description',$description);
				$tmpobject->res=$res;
				$tmpobject->update();
				unset($tmpobject);
				
			}


    if($_name==$name and $_description==$description and $_res==$res){$myversion=false;}
}

if($myversion){
	$reference=array_merge($reference,array(array($id,$name,$res,$profile_str,logid)));
	$prompt='prompt='.lr('discard_edited');
}else{
	$prompt='';
}
//-----------

?>
<table width="<?php e((!$GLOBALS['mobile']?contentwidth:'100%')); ?>"><tr>

<?php
/**/
foreach($reference as $row){$i++;$ii--;
	//`id`,`name`,`res`,`profile`,`own`
    list($_id,$_name,$_res,$_profile,$_own)=$row;
    $_profile=new profile($_profile);
    $_description=trim($_profile->val('description'));

    e('<td  width="'.(70*0.75).'">');

    

    if($_name==$name and $_description==$description and $_res==$res){
	$border='3';
	$ahref='';
    }else{
	$border='0';
	$ahref='e=content;ee=create-upgrade;reference='.($_id).';'.$prompt;   
    }

    //e($ahref);
    ahref('<img src="'.modelx($_res).'" width="'.(70*0.75).'" border="'.$border.'">',$ahref,'none',true);
    e('</td>');
    e('<td valign="middle">');
    //e($i);
    ahref(trr($_name,13),$ahref,'none',true);

    //br();
    //ahref(lr('unique_profile'),'e=content;ee=profile;id='.$_id,'none',true);
    
   if($_own and $_own!=-1 and $_own!=logid){
	br();
	e(lr('author').': '.liner($_own));
    }elseif($_own==logid and $name==$_name and $myversion){
	br();
	blue(lr('author_edited'));
    }elseif($_own==logid){
	br();
	success(lr('author_logid'));
    }elseif($_own==-1){
	br();
	info(lr('author_towns'));
    }
    /*if($description){
        br();
        te($description);   
    }*/

    e('</td>');
    e('</tr><tr '.($iii?$iii=0:$iii='style="background: rgba(50,50,50,0.25);"').'>');
    
}
?>

</tr>
<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
</table>

<?php


if($myversion){
    if(!sql_1data("SELECT id FROM `[mpx]objects` WHERE `own`='".logid."' AND `ww`=-1 AND `name`='$name'")){

	if(/*sql_1data("SELECT id FROM `[mpx]objects` WHERE `own`='".logid."' AND `ww`=-2 AND `name`='$name'") and */false){
		error(lr('public_unique_reject'));	
	}else{
		blue(lr('public_unique_info'));
	}
	if($GLOBALS['get']["public"]){
		$nid=nextid();
		success('{public_unique_ok}');

sql_query("INSERT INTO `".mpx."objects` (`id`, `name`, `type`, `dev`, `origin`, `fs`, `fp`, `fc`, `fr`, `fx`, `func`, `hold`, `res`, `profile`, `set`, `hard`, `expand`, `own`, `in`, `ww`, `x`, `y`, `t`) 
SELECT $nid, `name`, `type`, `dev`, `origin`, `fs`, `fp`, `fc`, `fr`, `fx`, `func`, `hold`, `res`, `profile`, 'x', `hard`, `expand`,'".logid."', `in`, -1, `x`,`y`, ".time()." FROM `".mpx."objects` WHERE id='$id'");
		$tmp=new object($nid);
		
		$tmp->func->addF('group','group','extended','profile');
		$tmp->update();
		unset($tmp);
		mail('ph@towns.cz','Towns public','name: '.$name.nln.'id: '.$id.nln.'id(-1): '.$nid.nln.'user: '.id2name(logid).nln.'townid: '.useid);
		//e('name: '.$name.nln.'id: '.$id.nln.'id(-1): '.$nid.nln.'user: '.id2name(logid).nln.'townid: '.useid);
		br();
	}

	br();
	ahref(buttonr(lr('public_unique')),'e=content;ee=create-upgrade;public=1');
   }else{
	success(lr('public_unique_ok2'));
   }

}



		contenu_b();



		
		}elseif($q==2){contenu_a();
		//------------------------------------PROFILE_EDIT


			    $info=array();
			    $tmpinfo=xquery("info",$id);
			    $info["profile"]=new profile($tmpinfo["profile"]);
			    $info["name"]=$tmpinfo["name"];
			    $p=$info["profile"]->vals2list();

			if($_GET["profile_edit"]){
			    if($_POST["name"] and $info["name"]!=$_POST["name"]){
				xquery("profile_edit",$id,"name",$_POST["name"]);
				xreport();
				$info["name"]=$_POST["name"];
			    }
			    if($_POST["description"] and $p["description"]!=$_POST["description"]){xquery("profile_edit",$id,"description",$_POST["description"]);xreport();$p["description"]=$_POST["description"];}
		            //if($_POST["public"]){xquery("profile_edit",$id,"public",$_POST["public"]);xreport();$p["public"]=$_POST["public"];}
			    
			    
			}
			/*if($GLOBALS['get']["public"]){
				xquery("profile_edit",$id,"public",$GLOBALS['get']["public"]);xreport();$p["public"]=$GLOBALS['get']["public"];
			}*/

			form_a(urlr('profile_edit=1'),'profile_edit');
			?>


			<table>


			<tr><td><b><?php le("name"); ?>:</b></td><td><?php input_text("name",$info["name"]); ?></td></tr>
			<tr><td><b><?php le("description"); ?>:</b></td><td><?php input_textarea("description",$p["description"],(!$GLOBALS['mobile']?44:'30'),17); ?></td></tr>
			
			<?php /*			
			<tr><td></td><td><?php if($p["public"]=='y'){le('public_y');te(' - ');$ch='n';}else{$ch='y';} ahref("{public_ch$ch}",'e=content;ee=create-upgrade;public='.$ch); ?></b></td></tr>
			<tr><td></td><td><?php infob(lr('public_info')); ?></td></tr>
			*/ ?>

			<tr><td colspan="2"><input type="submit" value="OK" /></td>
			</tr></table>

			<?php
			form_b();
			form_js('content','?e=create-upgrade&profile_edit=1',array('name','description','public'));




		//------------------------------------
		contenu_b(true);}elseif($q==3){contenu_a();
		//------------------------------------UPGRADE_RES
			textb(tr($name));br();
			e(lr('upgrade_res_by_editor'));
			if($GLOBALS['mobile']){error(lr('mobile_noeditor'));}else{error(lr('noeditor'));}
			blue(lr('editor_info'));
			br();br();

			//echo(url);
			//$wurl=str_replace(w,'',url);
			//$wurl=str_replace('/','',$wurl);
			//$wurl='http://'.$wurl;
			$wurl='../..';
			//echo($wurl);
			$p_url='../../'.w.'/';
			//$p_url=str_replace('http://','',url);
			//$p_url=str_replace('https://','',$p_url);
			//$p_url=str_replace('[world]',w,$p_url);
			//$p_url=str_replace(w,'',$p_url);
			$p_id=$id;
			$p_pass='328678';

			$p_pass=sql_1data('SELECT `key` FROM `[mpx]login` WHERE `id`='.$id);
			if(!$p_pass){
				$p_pass=md5(rand(1,999999999));
				sql_query("INSERT INTO `world1_login` (`id`, `method`, `key`, `text`, `time_create`, `time_change`, `time_use`) VALUES ('$id', 'editor', '$p_pass', '', '".time()."', '".time()."', '".time()."');");
			}


			
			tfont('<a href="#" onclick="window.open(\''.$wurl.'/app/mdlrot/index.php?url='.$p_url.'&id='.$p_id.'&pass='.$p_pass.'&lang='.$GLOBALS['ss']["lang"].'\', \'_blank\', \'width=150, height=300,resizable=yes\');">'.lr('mdlrot').'</a>',20);
			br();
			le('mdlrot_description');

			br();br();

			tfont('<a href="#" onclick="window.open(\''.$wurl.'/app/easy/index.php?url='.$p_url.'&id='.$p_id.'&pass='.$p_pass.'&lang='.$GLOBALS['ss']["lang"].'\', \'_blank\', \'width=430, height=320,resizable=yes\');">'.lr('easyeditor').'</a>',20);
			br();
			le('easyeditor_description');

			br();br();

			tfont('<a href="#" onclick="window.open(\''.$wurl.'/app/editor/index.php?url='.$p_url.'&id='.$p_id.'&pass='.$p_pass.'&lang='.$GLOBALS['ss']["lang"].'\', \'_blank\', \'width=630, height=550,resizable=yes\');">'.lr('editor').'</a>',20);
			br();
			le('editor_description');


			br();br();
			info(lr('upgrade_res_info'));
		//------------------------------------
		contenu_b();}/*elseif($q==2){contenu_a();
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
		contenu_b();}elseif($q==2 or false){contenu_a();
		//------------------------------------UPGRADE_ADD

			xreport();
			if(xsuccess()){
			  ?>
			<script>
			<?php urlx('e=map_context;ee=minimenu',false); ?>
			</script>
			<?php
			}
			textb($name);br();
			e('{upgrade_title}');
			br();

			$functions=array(
					'attack'=>array(array('attack',1),array('count',5),array('distance',0.2),array('cooldown',-120,1),array('eff',0.05,0.9,'%'),array('xeff',0.05,0.9,'%')),
					'create'=>array(array('cooldown',-20,1),array('eff',0.05,0.9,'%')),
					'change'=>array(array('eff',0.05,0.9,'%')),
					'expand'=>array(array('distance',0.2)),
					'collpse'=>array(array('distance',0.2)),
					'resistance'=>array(array('hard',0.1))
					);
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
							if($step>0)$step_='(+'.nbsp.($type=='%'?ceil($step*100).'%':$step).nbsp.')'; else $step_='('.nbsp.($type=='%'?ceil($step*100).'%':$step).nbsp.')';
							$value=$func[$funcname]['params'][$paramname];
							$value=$value[0]*$value[1];
							//e($paramname.': '.$value);

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
						
							e('<tr bgcolor="222222"><td width="150"><b>{f_'.$func[$funcname]['class'].'_'.$paramname.'}: </b></td><td width="50">'.($type=='%'?ceil($value*100).'%':$value).'</td><td>'.ahrefr($step_,'e=content;ee=create-upgrade;q='.$id.'.upgrade '.$funcname.','.$paramname.','.($value-(-$step))).'</td></tr>');
							e('<tr><td colspan="3">');

							if($step_){
		
								$pricex->showimg(true);

								if(!$GLOBALS['ss']['use_object']->hold->testhold($pricex)){
									//br();
									tfont('{upgrade_error_price}',NULL,'FF0000');
								}
							}else{
								e('{upgrade_noupgrade}');
							}
							e('</td></tr>');
						
						}
					}
					e('<tr><td colspan="3"><br></td></tr>');
					//print_r($tmp);
				
				}
				
			}

			e('</table>');
		contenu_b();}*/
		//------------------------------------
		
	    //========================================
        }else{
	    //========================================REPAIR
            window(lr('title_repair'));
            
            
            
            infob(ahrefr(nbsp.lr('repair_ok'),'e=content;ee=create-upgrade;q='.$id.'.repair'));
            contenu_a();
            xreport();
if(xsuccess()){
  ?>
<script>
setTimeout(function(){
    w_close('content');
},3000);
<?php urlx('e=map_context;ee=minimenu',false); ?>
</script>
<?php
}
	    $origin=explode(',',$origin);
	    $price=ceil(($fs-$fp)/count($origin));
            $price=new hold(/*$fc*/'fuel='.$price);
            //$price->multiply($fp/$fs);
            textb(lr('repair_price').': ');
            $price->showimg();
		
		if(!$GLOBALS['ss']['use_object']->hold->testhold($price)){
		if($GLOBALS['ss']['use_object']->hold->testchange($price)){
			blue(lr('repair_change'));
		}
		}
            hr();
            profile($id);
            contenu_b();
            
        }
        //--------------------------
    }
	    //========================================
}

?>
