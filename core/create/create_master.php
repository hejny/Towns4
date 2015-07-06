<?php
/* Towns4, www.towns.cz 
   © Pavol Hejný | 2011-2014
   _____________________________

   core/create/new.php

   správce budov
*/
//==============================

window(lr('title_create_master'));


$q=submenu(array("content","create-create_master"),array('create_master_new','create_master_surkey','create_master_repair'),1,'create_master');


if($q==1){
contenu_a();
//=======================================================================NEW BUILDING
//počet, volných, čas uvolnění, id, fname
$masters=array('master'=>array(0,0,0,0),'main'=>array(0,0,0,0),'wall'=>array(0,0,0,0),'bridge'=>array(0,0,0,0),'path'=>array(0,0,0,0),'terrain'=>array(0,0,0,0),'extended'=>array(0,0,0,0));

$array=sql_array("SELECT `id`,`func`,`set` FROM `[mpx]pos_obj` WHERE `own`='".$GLOBALS['ss']['useid']."' AND `func` LIKE '%class[5]create%' AND `type`='building' AND ".objt());
foreach($array as $row){
    list($id,$func,$set)=$row;
    //echo($func);
    //br();
    //echo($set);
    //br();
    
    $set=str2list($set);
    //print_r($set);
    
    $func=func2list($func);
    foreach($func as $fname=>$func1){
        if($func1['class']=='create'){
            if($func1['profile']['group']){
                //print_r($func1);
                $group=$func1['profile']['group'];
                $masters[$group][0]++;
                $no=0;$refresh=0;
                $lastused=$set['lastused_'.$fname];
                if($lastused){
                    $refresh=ceil($lastused+($func1['params']['cooldown'][0]*$func1['params']['cooldown'][1]));
                    //e($lastused.' '.$refresh);
                    if($refresh>time()){
                        $no=1;
                    }
                }
                if(!$no){
                    $masters[$group][1]++;
                    $masters[$group][3]=$id;
                    $masters[$group][4]=$fname;
                }else{
                    $masters[$group][2]=$refresh;
                }
            }
        }
    }
    
    //hr();
}

infob(lr('create_building_info'));
br();

e('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
foreach($masters as $group=>$row){
    list($total,$free,$time,$id,$fname)=$row;
    e('<tr>');
    if($free){
        
        //$href=js2('alert(\'#create-unique_'.$id.'\');');
        //$href=js2('alert('.$id.'+$(\'#create-unique_'.$id.'\').html());');
        //$href=js2('if(ifcache(\'create-unique_'.$group.'\')){$(\'#content\').html(cache(\'create-unique_'.$group.'\'));}else{}');
        $href=('e=content;ee=create-unique;cache=create-unique_'.$group.';type=building;master='.$id.';func='.$fname);
    }else{
        $href='';
    }
    
    
    $image='icons/f_create_'.$group.'.png';
    if(!file_exists('ui/image/'.$image)){
        $image='icons/f_create.png';
    }

    e('<td>'.ahrefr(imgr($image,lr('build_'.$group),20,20,NULL,NULL,$free?0:1),$href/*,NULL,NULL,'build_'.$group*/).'</td>');
    e('<td>'.ahrefr(nbsp3.trr(lr('build_'.$group),14),$href,NULL,NULL,'build_'.$group).'</td>');


    if($total==0){
        e('<td>&nbsp;</td></tr><tr>');
        e('<td colspan="3">');
        infobb(lr('build_nomaster_'.$group));
        e('</td>');
    }elseif($free==0){
        e('<td align="right">'.timejsr($time,'e=content;ee=create-create_master').'</td>');
    }elseif($total==free){
        e('<td align="right" valign="middle">'.ahrefr(trr($free,12),$href).'</td>');
    }else{
        e('<td align="right" valign="middle">'.ahrefr(trr($free.'/'.$total,12),$href).'</td>');
    }
    e('</tr>');
    e('<tr><td colspan="3">&nbsp;</td></tr>');
}
e('</table>');

//=======================================================================
contenu_b();
}elseif($q==2){
contenu_a();
//=======================================================================SURKEY

	infob(lr('surkey_info'));

	$GLOBALS['ss']['use_object']->hold->rebase();
	$list=$GLOBALS['ss']['use_object']->hold->vals2list();
	//print_r($list);

	$script='';
	$table=array('wood'=>false,'stone'=>false,'iron'=>false,'fuel'=>false);

    foreach($list as $key=>$value){//hu buc bud
		//e("$key=>$value".br);
        if($key and $key!='_time' and !is_numeric($key) and ($value or $key=='wood' or $key=='stone' or $key=='iron' or $key=='fuel')){
	if(substr($key,0,1)!='_'){
	//------------------------------------
		$valuex=ir($value);
		if($trr){
			$value=trr($value,12,1);
		}
		if(!$border){
			$img=imgr("icons/res_$key.png",lr('res_'.$key),$size,$size);
		}else{
		
			$img=borderr(imgr("icons/res_$key.png",lr('res_'.$key),$size,$size),$border,$size).nbsp2;
		}
		//e('_-'.$key);
		$bound=$list['_-'.$key];
		$plus=$list['_'.$key];
		if(!$plus){$plus=0;}
		if(!$bound){$bound=$value;}

		$row=array();	
		$row[]=imgr('icons/res_'.$key.'.png',lr('res_'.$key),35);//.trr(lr('res_'.$key),array(20,'999999'));
		//<b>'."<span id=\"res_".$key."\">".$valuex."</span>".' / '.$bound.'</b>

		if($key=='wood'){$color='998866';}
		if($key=='stone'){$color='778877';}
		if($key=='iron'){$color='999999';}
		if($key=='fuel'){$color='333333';}
		if($key=='gold'){$color='cccc22';}

		$row[]=(nln.nln.loadbarr($value,$bound,$plus,1,0,0,$color,'030303').nln.nln.ir($plus).' '.lr('res_per_hour').'<br/>'.nbsp);

		$table[$key]=$row;
	//------------------------------------
	}else{
	//------------------------------------	
		/*if(substr($key,1,1)!='-'){
			$bound=$list['_-'.substr($key,1)];
			if(($list[substr($key,1)])<$bound){
				//countupto(id,base,x1,x2,bound)
				$script.="countupto('res_".$key."',".$list['_time'].",".($list[substr($key,1)]).",$value,".($bound).");";
			}
		}*/

	//------------------------------------
	}}}

	br();
	array2table($table/*,200*/);
	//js($script);


//=======================================================================
contenu_b();
}elseif($q==3){
contenu_a();
//=======================================================================REPAIR


if($GLOBALS['get']['global_repair']){
	$status=$GLOBALS['get']['global_repair'];

	/*$set=sql_1data("SELECT `set` FROM `[mpx]pos_obj` WHERE `id`=".$GLOBALS['ss']['useid']);
	$set=new set($set);
	$set->val('global_repair',$status);
	$set=$set->vals2str();
	sql_query("UPDATE `[mpx]pos_obj` SET `set`='".sql($set)."'  WHERE `id`=".$GLOBALS['ss']['useid']);*/
	$GLOBALS['ss']['use_object']->set->val('global_repair',$status);

	//success('global_repair_turned'.$status);
}
if($GLOBALS['get']['auto_repair']){
	$status=$GLOBALS['get']['auto_repair'];
	$id=$GLOBALS['get']['auto_repair_id'];
	$name=id2name($id);

	$set=sql_1data("SELECT `set` FROM `[mpx]pos_obj` WHERE `id`=".sql($id));
	$set=new set($set);
	$set->val('auto_repair',$status);
	$set=$set->vals2str();
	sql_query("UPDATE `[mpx]pos_obj` SET `set`='".sql($set)."'  WHERE own='".$GLOBALS['ss']['useid']."' AND `name`='".sql($name)."'");

	//success('auto_repair_turned'.$status);
}

//e(nln.nln.nln.nln);
//e(loadbarr(20,100));
//e(nln.nln.nln.nln);

//$set=sql_1data("SELECT `set` FROM `[mpx]pos_obj` WHERE `id`=".$GLOBALS['ss']['useid']);
//$set=str2list($set);
$global_repair_status=$GLOBALS['ss']['use_object']->set->val('global_repair');
if(/*$set['global_repair']*/$global_repair_status!='off'){
	$global_repair=lr('global_repair_on').nbsp.'-'.nbsp.ahrefr(lr('global_repair_turnoff'),'e=content;ee=create-create_master;submenu=3;global_repair=off');
}else{
	$global_repair=lr('global_repair_off').nbsp.'-'.nbsp.ahrefr(lr('global_repair_turnon'),'e=content;ee=create-create_master;submenu=3;global_repair=on');
}



$array=sql_array("SELECT id,name,fp,fs,origin,func,`set`,res,profile,x,y FROM `[mpx]pos_obj` WHERE own='".$GLOBALS['ss']['useid']."' and `name`!='".mainname()."' ORDER BY name");
$table=array();
$buildig_count=array();
//$price_once_total=0;
$price_day_total=0;
foreach($array as $row){
	list($id,$name,$fp,$fs,$origin,$func,$set,$res,$profile,$x,$y)=$row;
	//------------------------------------model
	$model=imageurl('id_'.$id.'_icon');	
	//$model=modelx($res,$fp/$fs);
	$model='<img src="'.$model.'" border="2" />';
	//------------------------------------loadbar
	$loadbar=loadbarr($fp,$fs,0,0,'100%');
	//------------------------------------price
	$repair_fuel=repair_fuel($id);

	
	$price_day=lr('repair_day').showholdr('fuel='.round((1/30)*$repair_fuel),true);
	$price_day_total+=round((1/30)*$repair_fuel);

	//------------------------------------repair
	/*if($fs!=$fp){
		/*$repair=ahrefr(lr('repair_once'),'e=content;ee=create-create_master;submenu=3;repair_id='.$id.';repair=on');
		$repair.=$price_aac=showholdr('fuel='.round((1-($fp/$fs))*$repair_fuel),true).'<br/>';* /
		$price_once_total.=round((1-($fp/$fs))*$repair_fuel);
	}else{
		//$repair='';
	}*/
	//------------------------------------auto_repair
	$set=str2list($set);
	if($set['auto_repair']!='off'){
		$auto_repair=lr('auto_repair_on').nbsp.'-'.nbsp.ahrefr(lr('auto_repair_turnoff'),'e=content;ee=create-create_master;submenu=3;auto_repair_id='.$id.';auto_repair=off');
	}else{
		$auto_repair=lr('auto_repair_off').nbsp.'-'.nbsp.ahrefr(lr('auto_repair_turnon'),'e=content;ee=create-create_master;submenu=3;auto_repair_id='.$id.';auto_repair=on');
	}
	//------------------------------------table
	if(!$buildng_count[$name])$buildng_count[$name]=0;
	$buildng_count[$name]++;
	if($buildng_count[$name]>1){
		$xx=$buildng_count[$name].nbsp.'x'.nbsp;
	}else{
		$xx='';
	}
	$table[$name]=array($model,"<b>".$xx.contentlang(xx2x($name))."</b>".$loadbar.$price_day.(($global_repair_status!='off')?$auto_repair:'').'<br/>'.nbsp);

}

infob(lr('auto_repair_info'));
br();
tfont($global_repair,18);
br();
tfont(lr('repair_day_total').showholdr('fuel='.$price_day_total,true),17);
br(2);
/*e(ahrefr(lr('repair_once_total'),'e=content;ee=create-create_master;submenu=3;repair_all=1').showholdr('fuel='.$price_once_total));

$price=new hold($price_once_total);

if(!$GLOBALS['ss']['use_object']->hold->testhold($price)){
if($GLOBALS['ss']['use_object']->hold->testchange($price)){
	blue(lr('repair_change'));
}
}*/


array2table($table,array(20),/*array('left','middle')*/0,1);


//=======================================================================
contenu_b();
}


?>
