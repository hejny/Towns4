<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/login/bot.php

   Automatičtí hráči
*/
//==============================

//error_reporting(E_ALL ^ E_NOTICE);
ini_set("max_execution_time","1000");
if($_GET['refresh'])header('refresh:'.($_GET['refresh'])); 

//e('bot');
//==============================

function bot(){
	hr();
	e(id2name($GLOBALS['ss']['logid']).' ('.$GLOBALS['ss']['logid'].')');
	br();
	
	//$buildings=sql_array('SELECT name,COUNT(id) FROM [mpx]objects WHERE own='.useid.' GROUP BY name');
	br();
$buildings=sql_array('SELECT name,x,y FROM [mpx]objects WHERE own='.$GLOBALS['ss']['useid'].' ORDER BY id DESC',2);
	br();
	//print_r($buildings);

	foreach(sql_array("SELECT id,name,func,fp,fs FROM [mpx]objects WHERE own='".$GLOBALS['ss']['useid']."' ORDER BY RAND()") as $tmprow){list($id,$name,$func,$fp,$fs)=$tmprow;
		e(textbr($name));br();

	
		//-------------------------- MAIN BUILDING

		if($GLOBALS['config']['register_building']){
		//if(1){

			if($hl=sql_array('SELECT id,ww,x,y FROM [mpx]objects WHERE ww='.$GLOBALS['ss']['ww'].' AND own='.$GLOBALS['ss']['useid'].' AND type=\'building\' and TRIM(name)=\''.id2name($GLOBALS['config']['register_building']).'\' LIMIT 1')){
			 //print_r($hl);
		    list($GLOBALS['hl'],$GLOBALS['hl_ww'],$GLOBALS['hl_x'],$GLOBALS['hl_y'])=$hl[0];
		}else{//e(1);
		    $GLOBALS['hl']=0; 
		}
		}else{//e(2);
		    $GLOBALS['hl']=0; 
		}
		//--------------------------



		//==============================EXT%BOT
		

		if($name=='{building_main}'){
		//--------------------------------------------------------------------------------{building_main}*
			/*xqr($id.'.xmine attack');
			xqr($id.'.xmine attack2');*/

			for($i=1;$i<20;$i++){
				$rota=(rand(1,360)/180)*3.1415;
				if(count($buildings)>4){
					$distance=rand(10,40)/10;
				}else{
					$distance=rand(10,15)/10;
				}
				$rotb=rand(1,360);
				//$rota=rand(1,10);
				//e($id.'.create 1000003,+'.(cos($rota)*$distance).',+'.(sin($rota)*$distance).','.$rotb);
				$success=xqr($id.'.create 1000003,+'.(cos($rota)*$distance).',+'.(sin($rota)*$distance).','.$rotb);
				if($success){success('POSTAVENO');break;}
			}
		//-----------------------------------------------------------------------------------------------
		}elseif($name=='{building_master_wall}' or $name=='{building_master_terrain}'){
		//-------------------------------------------------------------------------{building_master_wall}
			//create
			/*Palisáda(1000006)
			Malá kamenná hradba(1000007)
			Velká kamenná hradba(1000008)
			Temná hradba(1000009)*/
			if($name=='{building_master_wall}'){
				$walls=array(array(1000006,'building_palisade',2,5),array(1000007,'building_small_wall',8,10)/*,array(1000008,'building_big_wall',11,15),array(1000009,'building_dark_wall',15,20)*/);
			}else{
				$walls=array(array(1000111,'building_terrainx_t11',10,14)/*,array(1000008,'building_big_wall',11,15),array(1000009,'building_dark_wall',15,20)*/);
			}
			foreach($walls as $row){
				//print_r($row);
				list($wallid,$wallname,$min,$max)=$row;
				//echo("$wallid,$wallname,$min,$max");
				$distance=0;
				foreach($buildings as $building){
					list($name,$tmpx,$tmpy)=$building;
					if($name==$wallname){
						$x=$tmpx;$y=$tmpy;
						$distance=sqrt($x*$x+$y*$y);
						$rot=acos($x/$distance);
						if($y<0)$rot=(2*3.1415)-$rot;
						
						//break;
					}
				}
				if(!$distance){
					echo("$wallid,$wallname,$min,$max");
					e("[$min,$max]");
					$distance=rand($min,$max);
					$rot=(rand(1,360)/180)*3.1415;
					//$x=round(cos($rot)*$distance);
					//$y=round(sin($rot)*$distance);
				}
				
				//e("[$x,$y]($rot,$distance)");			

				$q=true;$i=rand(1,19);
				while(/*$q and */$i){$i--;

					$x=round(cos($rot)*$distance);
					$y=round(sin($rot)*$distance);
					
					//e("[$x,$y]($rot,$distance)");
					$q=xqr($id.'.create '.$wallid.',+'.$x.',+'.$y);
							

					$rot+=deg2rad(10);
					$distance+=(rand(0,10)-5)/10;
					/*if(rand(1,2)==1){
						if($x>0){
							$x--;
						}else{
							$x++;
						}
					}else{
						if($y>0){
							$y--;
						}else{
							$y++;
						}
					}*/
				}
			}
		//-----------------------------------------------------------------------------------------------
		}elseif($name=='{building_master}'){
		//------------------------------------------------------------------------------{building_master}
			//create
			/*Stavitel(1000003)*
			Ambasáda(1000005)*
			Těžba dřeva(1000014)*
			Těžba kamene(1000015)*
			Velká těžba dřeva(1000017)*

			Velká těžba kamene(1000018)*
			Rozhledna(1000020)*
			Expanzní věž(1000021)*
			Obranná věž(1000023)*
			Malý chrám(1000024)*

			Velký chrám(1000025)*
			Stavitel hradeb(1000026)*
			Stavitel mostů(1000027)*
			Stavitel mapy(1000029)**/
			if(!$bps or $bpi>=count($bps)){
				$bpi=0;
				/*$bpsx=sql_array('SELECT id FROM [mpx]objects WHERE ww=0 AND func LIKE \'%group=class[5]group[3]1[5]profile[3]profile[5]group[7]5[10]main%\' ORDER by fs');
				//print_r($bpsx);
				$bps=array();
				foreach($bpsx as $tmp){$tmp=$tmp[0];if($tmp!=1000002){$bps[]=$tmp;}}
				$bps[]=1000002;*/

/*array(1000015,1000014,1000026,1000027,1000029,1000017,1000018,1000005,1000020,1000021,1000023,1000024,1000025/*,1000003);*/

$bps=array(
1000014,
1000015,
//1000032,//sklad
//1000034,//sklad
//1000035,//sklad
//1000033,//sklad
1000037,
1000031,
1000020,
1000004,
1000029,
1000026,
1000027,
1000036,
1000002/*vlajka*/);
if(rand(1,10)>7)shuffle($bps);

			}

			for($i=1;$i<20;$i++){
				$rota=(rand(1,360)/180)*3.1415;
				$distance=rand(10,   10*(0+pow(count($buildings),gr-1))  )/10;
				e(id2name($bps[$bpi]).'distance='.$distance.' buildings='.count($buildings));
				$rotb=rand(1,360);
				$success=xqr($id.'.create '.($bps[$bpi]).',+'.(cos($rota)*$distance).',+'.(sin($rota)*$distance).','.$rotb);

			if($success){if(defined('join_id')){success('PŘISTAVENO K '.join_id);}else{success('POSTAVENO');}break;}
			}
			//e($id.'.create '.($bps[$bpi]).',+'.(cos($rota)*$distance).',+'.(sin($rota)*$distance).','.$rotb);
			$bpi++;



	
		//-----------------------------------------------------------------------------------------------
		}elseif($name=='{building_master_bridge}'){
		//-----------------------------------------------------------------------{building_master_bridge}
			//create
	
	
		//-----------------------------------------------------------------------------------------------
		//}elseif($name=='{building_master_terrain}'){
		//----------------------------------------------------------------------{building_master_terrain}
			//create
	
	
		//-----------------------------------------------------------------------------------------------
		}elseif($name=='{building_embassy}'){
		//-----------------------------------------------------------------------------{building_embassy}
			//change
	
	
		//-----------------------------------------------------------------------------------------------
		}elseif($name=='{building_lumberjack}'){
		//--------------------------------------------------------------------------{building_lumberjack}*
			//attack
			//xqr($id.'.xmine attack');
		//-----------------------------------------------------------------------------------------------
		}elseif($name=='{building_mine}'){
		//--------------------------------------------------------------------------------{building_mine}*
			//attack
			//xqr($id.'.xmine attack');
		//-----------------------------------------------------------------------------------------------
		}elseif($name=='{building_big_lumberjack}'){
		//----------------------------------------------------------------------{building_big_lumberjack}*
			//attack,attack2
			//xqr($id.'.xmine attack');
			//xqr($id.'.xmine attack2');
		//-----------------------------------------------------------------------------------------------
		}elseif($name=='{building_big_mine}'){
		//----------------------------------------------------------------------------{building_big_mine}*
			//attack,attack2
			xqr($id.'.xmine attack');
			xqr($id.'.xmine attack2');
		//-----------------------------------------------------------------------------------------------
		}elseif($name=='{building_tower}'){
		//-------------------------------------------------------------------------------{building_tower}
			//attack
	
	
		//-----------------------------------------------------------------------------------------------
		}elseif($name=='{building_expand_tower}'){
		//------------------------------------------------------------------------{building_expand_tower}
			//attack
	
	
		//-----------------------------------------------------------------------------------------------
		}elseif($name=='{building_defence_tower}'){
		//-----------------------------------------------------------------------{building_defence_tower}
			//attack
	
	
		//-----------------------------------------------------------------------------------------------
		}elseif($name=='{building_small_temple}'){
		//------------------------------------------------------------------------{building_small_temple}
			//attack
	
	
		//-----------------------------------------------------------------------------------------------
		}elseif($name=='{building_big_temple}'){
		//--------------------------------------------------------------------------{building_big_temple}
			//attack,attack2
	
	
		//----------------------------------------------------------------------------------------------- 
		}

		//----------------------------------------------------------------------------------------------- 

		if($fp!=$fs){
			xqr($id.'.repair');
		}
		
		//==============================

	}/**/

		//--------------------------------------------------------------------------ATTACK
		
		success('ATTACKING');
		$attack_buildings=sql_array('SELECT id,name,x,y,ww FROM [mpx]objects WHERE own=\''.$GLOBALS['ss']['useid'].'\' AND func LIKE \'%attack%\' AND func NOT LIKE \'%tree%\' AND func NOT LIKE \'%rock%\' ',2);br();
		foreach($attack_buildings as $row){
			list($id,$name,$x,$y,$ww)=$row;
			$aid=sql_1data('SELECT id FROM [mpx]objects WHERE ww='.$ww.' AND own!=\''.$GLOBALS['ss']['useid'].'\' AND type=\'building\' ORDER BY POW(x-'.$x.',2)+POW(y-'.$y.',2) ',2);br();
			e(textbr($name)."($id)[$x,$y] to $aid(".id2name($aid).')');br();			
			xqr($id.'.attack,'.$aid);

		}

		//--------------------------------------------------------------------------

}




//==============================

success('BOTS');

if($_GET['username']){



	xqr('login '.$_GET['username'].' towns '.$_GET['password']);
	
	//e('id');
	if(logged()){//echo($GLOBALS['ss']['useid']);
		bot();
	}






}else{
	foreach(sql_array('SELECT id FROM [mpx]login WHERE `method`=\'bot\' AND time_use<'.time().'-(`key`'.($_GET['speed']?'/'.$_GET['speed']:'').') ORDER BY time_use LIMIT 1') as $id){list($id)=$id;
		// LIMIT 1=sracka
		sql_query('UPDATE [mpx]login SET `time_use`='.time().' WHERE `method`=\'bot\' AND `id`='.$id);
		//if($reporting){success('ID'.$id."(".id2name($id)."):");br();}

		force_login($id);
		blue("force_login($id);");

        
		if($_GET['uc']){
			success('Unlimited Cooldown');
			sql_query("UPDATE [mpx]objects SET `set`='' WHERE type='building' AND own=".$GLOBALS['ss']['use_object']->id,2);br();
		}
		if($_GET['ul']){
			success('Unlimited Resource');
			//sql_query("UPDATE [mpx]objects SET `set`='' WHERE type='building' AND own=".$GLOBALS['ss']['use_object']->id,2);br();
			//$newhold=new hold('wood=10000;stone=10000;iron=10000;fuel=10000');
			$GLOBALS['ss']['use_object']->hold->val('wood',10000);
			$GLOBALS['ss']['use_object']->hold->val('stone',10000);
			$GLOBALS['ss']['use_object']->hold->val('iron',10000);
			$GLOBALS['ss']['use_object']->hold->val('fuel',10000);			
			//print_r($GLOBALS['ss']['use_object']->hold->vals2list());
			//$GLOBALS['ss']['use_object']->hold=$newhold;
		}

		bot();

		$GLOBALS['ss']["log_object"]->update();
		$GLOBALS['ss']["use_object"]->update();

		unset($GLOBALS['ss']["log_object"]);
		unset($GLOBALS['ss']["use_object"]);
		
	}
}

success('AUTO_REPAIR');

$towns=sql_array("SELECT id,name,`set` FROM [mpx]objects WHERE (type='town' OR type='town2')");
foreach($towns as $town){
	list($townid,$townname,$townset)=$town;
	textb($townname);br();
	if(strpos($townset,'global_repair=off')===false){
		
		$buidings=sql_array("SELECT id,name,fp,fs,`set` FROM [mpx]objects WHERE own='".$townid."' ORDER BY RAND()");
		$object=new object($townid);
		foreach($buidings as $buiding){
			list($id,$name,$fp,$fs,$set)=$buiding;
			if($fp!=$fs){
				if(strpos($set,'auto_repair=off')===false){
				$repair_fuel=repair_fuel($id);
				$repair_fuel=round((1-($fp/$fs))*$repair_fuel);
				e($name.' - '.$repair_fuel);br();
				$hold=new hold('fuel='.$repair_fuel);
				if($object->hold->takehold($hold)){
					sql_query('UPDATE [mpx]objects SET fp=fs WHERE id='.$id);
				}else{
					error('nedostategg suregg');
				}
				unset($hold);
				}else{
					blue('budova se neopravuje');
				}
			}
		}
		$object->update();
		unset($object);


	}else{
		blue('auto opravy vypnute');
	}

}

//====================================================================================================================================================================================


		/*$func=func2list($func);
		foreach($func as $func1name=>$func1){



			e($func1name." (".$func1['class'].")");
			     if($func1['class']=='attack' and ($func1['profile']['limit']=='tree' or $func1['profile']['limit']=='rock')){
				e(textbr('-mine'));
				//-----------------------------------mine
				
				$object=new object($id);
				if($object->loaded){
				        $x=$object->x;$y=$object->y;$ww=$object->ww;
				        $func=$object->func->func($func1name);
				        //r($func);
				        $distance=$func['distance'];
				        $attack=$func['attack'];
				        $sql="SELECT id,func FROM [mpx]objects WHERE ww=$ww  AND type='".$func1['profile']['limit']."' AND POW(x-$x,2)+POW(y-$y,2)<=POW($distance,2) ORDER BY fr  DESC";
				        $attack_id=false;
				        foreach(sql_array($sql) as $row){
				            list($tmpid,$tmpfunc)=$row;
				            $tmpfunc=new func($tmpfunc);
				            $defence=$tmpfunc->func('defence');
				            $defence=$defence['defence'];
				            
					    //e("$defence<$attack");br();
				            //e($tmpid);br();
				            if($defence<$attack){//e($tmpid.' - ok');
				                $attack_id=$tmpid;
				                break;
				            }
				            //br();
				            
				    }
				    if($attack_id){
        				//e($id.'.'.$func1name.' '.$attack_id);
        				xquery($id.'.'.$func1name,$attack_id);
					xreport();
				    }
				}
				//---------------------------------------	
			}elseif($func1['class']=='attack'){
				//e(textbr('-attack'));
				//---------------------------------attack
				
				
				
				//---------------------------------------	
			}elseif($func1['class']=='create'){
				e(textbr('-create'));
				//---------------------------------create
				
				
				
				//---------------------------------------	
			}elseif($func1['class']=='repair' and $fp!=$fs){
				e(textbr('-repair'));
				//---------------------------------repair
        				xquery($id.'.'.$func1name);
					xreport();
				//---------------------------------------
			}elseif($func1['class']=='upgrade'){
				//e(textbr('-upgrade'));
				//--------------------------------upgrade
				
				
				
				//---------------------------------------
			}elseif($func1['class']=='dismantle'){
				//e(textbr('-dismantle'));
				//------------------------------dismantle
				
				
				
				//---------------------------------------
			}

			br();
		}

		unset($func);*/


?>
