<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2015
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
	    //========================================UPGRADE
		//$tmpobject=new object($id);
		window(lr('title_design'));
		$q=submenu(array("content","create-design"),array("upgrade_origin","upgrade_profile"),1,'upgrade');


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





			$reference=sql_array("SELECT `id`,`name`,`res`,`profile`,`own` FROM `[mpx]pos_obj` WHERE `ww`=0 AND `origin`='$origin' ORDER BY `id`");
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
	$reference=array_merge($reference,array(array($id,$name,$res,$profile_str,$GLOBALS['ss']['logid'])));
	$prompt='prompt='.lr('discard_edited');
}else{
	$prompt='';
}
//-----------

?>
<table width="<?=contentwidth?>"><tr>

<?php
/**/

$limit=10;
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
	$ahref='e=content;ee=create-design;reference='.($_id).';'.$prompt;
    }

    //e($ahref);
    ahref('<img src="'.modelx($_res).'" width="'.(70*0.75).'" border="'.$border.'">',$ahref,'none',true);
    e('</td>');
    e('<td valign="middle">');
    //e($i);
	//die('abc');

	//e(base64_encode($_name));
	ahref(trr($_name,13),$ahref,'none',true);

    //br();
    //ahref(lr('unique_profile'),'e=content;ee=profile;id='.$_id,'none',true);

   if($_own and $_own!=-1 and $_own!=$GLOBALS['ss']['logid']){
	br();
	e(lr('author').': '.liner($_own));
    }elseif($_own==$GLOBALS['ss']['logid'] and $name==$_name and $myversion){
	br();
	blue(lr('author_edited'));
    }elseif($_own==$GLOBALS['ss']['logid']){
	br();
	success(lr('author_'.$GLOBALS['ss']['logid']));
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
    if(!sql_1data("SELECT id FROM `[mpx]pos_obj` WHERE `own`='".$GLOBALS['ss']['logid']."' AND `ww`=-1 AND `name`='$name'")){

	if(/*sql_1data("SELECT id FROM `[mpx]pos_obj` WHERE `own`='".$GLOBALS['ss']['logid']."' AND `ww`=-2 AND `name`='$name'") and */false){
		error(lr('public_unique_reject'));	
	}else{
		blue(lr('public_unique_info'));
	}
	if($GLOBALS['get']["public"]){
		$nid=nextid();
		success('{public_unique_ok}');








		$nextid=nextid();
		//------------------REINSERT objects
		sql_reinsert('objects',"id='$id'",array(
			'id' => $nid,
			'name' => true,
			'type' => true,
			'userid' => true,
			'origin' => true,
			'fp' => true,
			'func' => true,
			'hold' => true,
			'res' => true,
			'profile' => true,
			'set' => 'x',
			'own' => $GLOBALS['ss']['logid'],
			't' => time(),
			'pt' => true
		));
		//------------------REINSERT objects_tmp
		sql_reinsert('objects_tmp',"id='$id'",array(
			'id' => $nid,
			'fs' => true,
			'fc' => true,
			'fr' => true,
			'fx' => true,
			'superown' => $GLOBALS['ss']['logid'],
			'expand' => true,
			'block' => true,
			'attack' => true,
			'create_lastused' => true,
			'create_lastobject' => true,
			'create2_lastused' => true,
			'create2_lastobject' => true,
			'create3_lastused' => true,
			'create3_lastobject' => true,
			'create4_lastused' => true,
			'create4_lastobject' => true
		));
		//------------------REINSERT positions
		sql_reinsert('positions',"id='$id' AND ".objt(),array(
			'id' => $nid,
			'ww' => -1,
			'x' => true,
			'y' => true,
			'traceid' => true,
			'starttime' => true,
			'readytime' => true,
			'stoptime' => true
		));
		//------------------

		//(`id`, `name`, `type`, `origin`, `fs`, `fp`, `fc`, `fr`, `fx`, `func`, `hold`, `res`, `profile`, `set`, `hard`, `expand`, `own`, `in`, `ww`, `x`, `y`, `t`)
		//$nid, `name`, `type`, `origin`, `fs`, `fp`, `fc`, `fr`, `fx`, `func`, `hold`, `res`, `profile`, 'x', `hard`, `expand`,'".$GLOBALS['ss']['logid']."', `in`, -1, `x`,`y`, ".time()."

		/*sql_query("INSERT INTO `[mpx]pos_obj` (`id`, `name`, `type`, `origin`, `fs`, `fp`, `fc`, `fr`, `fx`, `func`, `hold`, `res`, `profile`, `set`, `hard`, `expand`, `own`, `in`, `ww`, `x`, `y`, `t`)
SELECT $nid, `name`, `type`, `origin`, `fs`, `fp`, `fc`, `fr`, `fx`, `func`, `hold`, `res`, `profile`, 'x', `hard`, `expand`,'".$GLOBALS['ss']['logid']."', `in`, -1, `x`,`y`, ".time()." FROM `[mpx]pos_obj` WHERE id='$id'");*/







		$tmp=new object($nid);
		
		$tmp->func->addF('group','group','extended','profile');
		$tmp->update();
		unset($tmp);
		//mail('ph@towns.cz','Towns public','name: '.$name.nln.'id: '.$id.nln.'id(-1): '.$nid.nln.'user: '.id2name($GLOBALS['ss']['logid']).nln.'townid: '.$GLOBALS['ss']['useid']);
		//e('name: '.$name.nln.'id: '.$id.nln.'id(-1): '.$nid.nln.'user: '.id2name($GLOBALS['ss']['logid']).nln.'townid: '.$GLOBALS['ss']['useid']);
		br();
	}

	br();
	ahref(buttonr(lr('public_unique')),'e=content;ee=create-design;public=1');
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


			<tr><td><b><?php le("name"); ?>:</b></td><td><?php input_text("name",contentlang($info["name"])); ?></td></tr>
			<tr><td><b><?php le("description"); ?>:</b></td><td><?php input_textarea("description",contentlang($p["description"]),44,17); ?></td></tr>
			
			<?php /*			
			<tr><td></td><td><?php if($p["public"]=='y'){le('public_y');te(' - ');$ch='n';}else{$ch='y';} ahref("{public_ch$ch}",'e=content;ee=create-design;public='.$ch); ?></b></td></tr>
			<tr><td></td><td><?php infob(lr('public_info')); ?></td></tr>
			*/ ?>

			<tr><td colspan="2"><input type="submit" value="OK" /></td>
			</tr></table>

			<?php
			form_b();
			form_js('content','?e=create-design&profile_edit=1',array('name','description','public'));




		//------------------------------------
		contenu_b(true);}elseif($q==3){contenu_a();
		//------------------------------------UPGRADE_RES
			textb(tr($name));br();
			e(lr('upgrade_res_by_editor'));

			error(lr('noeditor'));

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
				sql_query("INSERT INTO `[mpx]login` (`id`, `method`, `key`, `text`, `time_create`, `time_change`, `time_use`) VALUES ('$id', 'editor', '$p_pass', '', '".time()."', '".time()."', '".time()."');");
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
		contenu_b();}
	    //========================================
        }else{
		error(lr('demaged'));

    }}
	    //========================================
}

?>
