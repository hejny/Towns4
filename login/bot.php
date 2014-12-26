<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/login/bot.php

   Automatičtí hráči
*/
//==============================

die('nene');

//error_reporting(E_ALL ^ E_NOTICE);
ini_set("max_execution_time","1000");
if($_GET['refresh'])header('refresh:'.($_GET['refresh'])); 

//e('bot');
//==============================

function bot($targetx,$targety,$targetww){
	hr();
	e(id2name($GLOBALS['ss']['logid']).' ('.$GLOBALS['ss']['logid'].')'.' . ('.$GLOBALS['ss']['useid'].')');
	br();
	
	//$buildings=sql_array('SELECT name,COUNT(id) FROM [mpx]objects WHERE own='.useid.' GROUP BY name');
	br();
        $buildings=sql_array('SELECT name,x,y FROM [mpx]objects WHERE own='.$GLOBALS['ss']['useid'].'  AND '.objt().' ORDER BY id DESC');
        /*foreach($buildings as $building){
            if($name=='{building_main}'){
                $targetx=$targetx-$building['x'];
                $targety=$targety-$building['y'];
                break;
            }
        }*/
        t('bot.php - bot() - buildings');
	//br();
	//print_r($buildings);
            
        
       
        //-------------------------- MAIN BUILDING

        if($GLOBALS['config']['register_building']){
        //if(1){

                if($hl=sql_array('SELECT id,ww,x,y FROM [mpx]objects WHERE ww='.$GLOBALS['ss']['ww'].' AND own='.$GLOBALS['ss']['useid'].'  AND '.objt().' AND type=\'building\' and TRIM(name)=\''.id2name($GLOBALS['config']['register_building']).'\' LIMIT 1')){
                 //print_r($hl);
            list($GLOBALS['hl'],$GLOBALS['hl_ww'],$GLOBALS['hl_x'],$GLOBALS['hl_y'])=$hl[0];
        }else{//e(1);
            $GLOBALS['hl']=0; 
        }
        }else{//e(2);
            $GLOBALS['hl']=0; 
        }
        t('bot.php - bot() - main building');
        //--------------------------
        
                
                
	if(true)
	foreach(sql_array("SELECT id,name,func,fp,fs,x,y FROM [mpx]objects WHERE own='".$GLOBALS['ss']['useid']."'  AND ".objt()." ORDER BY RAND()",2) as $tmprow){list($id,$name,$func,$fp,$fs,$x,$y)=$tmprow;
		e(textbr($name));br();

                t('bot.php - bot() - start building');
                

		//==============================EXT%BOT
		

		if($name=='{building_main}'){
		//--------------------------------------------------------------------------------{building_main}*
			/*xqr($id.'.xmine attack');
			xqr($id.'.xmine attack2');*/

			/*for($i=1;$i<20;$i++){
				
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
			}*/
                        for($i=5;$i>0;$i--){

                                $pathx=(-$targetx+$GLOBALS['hl_x'])*($i/10)+(rand(-600,600)/100);
                                $pathy=(-$targety+$GLOBALS['hl_y'])*($i/10)+(rand(-600,600)/100);
                                
                                $rotb=rand(1,360);
                                
                                $success=xqr($id.'.create 1000003,+'.($pathx).',+'.($pathy).','.$rotb);
				if($success){success('STAVITEL POSTAVENO');break;}
			}
                        
                        
                        if(!$success){error('NEPOSTAVENO');}
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

			for($i=5;$i>0;$i--){
				//$rota=(rand(1,360)/180)*3.1415;
				//$distance=rand(10,   10*(0+pow(count($buildings),gr-1))  )/10;
                                 
                                   //e($targetx.','.$GLOBALS['hl_x']);

                                $pathx=($targetx-$GLOBALS['hl_x'])*($i/10)+(rand(-400,400)/100);
                                $pathy=($targety-$GLOBALS['hl_y'])*($i/10)+(rand(-400,400)/100);
                                
				//e(id2name($bps[$bpi]).'pathx='.$pathx.' buildings='.count($buildings));
				$rotb=rand(1,360);
                                
                                blue($id.'.create '.($bps[$bpi]).',+'.($pathx).',+'.($pathy).','.$rotb);
				$success=xqr($id.'.create '.($bps[$bpi]).',+'.($pathx).',+'.($pathy).','.$rotb);

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
                
                t('bot.php - bot() - end building');

	}/**/
		//die();
		//--------------------------------------------------------------------------ATTACK
		
		success('ATTACKING');
		$attack_buildings=sql_array('SELECT id,name,x,y,ww FROM [mpx]objects WHERE own=\''.$GLOBALS['ss']['useid'].'\'  AND '.objt().' AND func LIKE \'%attack%\' AND func NOT LIKE \'%tree%\' AND func NOT LIKE \'%rock%\' ',2);br();
		foreach($attack_buildings as $row){
			list($id,$name,$x,$y,$ww)=$row;
			$aid=sql_1data('SELECT id FROM [mpx]objects WHERE ww='.$ww.' AND own!=\''.$GLOBALS['ss']['useid'].'\'  AND '.objt().' AND type=\'building\' ORDER BY POW(x-'.$x.',2)+POW(y-'.$y.',2) ',2);br();
			e(textbr($name)."($id)[$x,$y] to $aid(".id2name($aid).')');br();			
			xqr($id.'.attack,'.$aid);

		}
                t('bot.php - bot() - attacking');

		//--------------------------------------------------------------------------DISMANTLE
		
		success('DISMANTLE');
		//sql_array('SELECT name,x,y FROM [mpx]objects WHERE own='.$GLOBALS['ss']['useid'].' ORDER BY id DESC');
		$dismantle_buildings=sql_array('SELECT id,x,y,name FROM [mpx]objects WHERE own='.$GLOBALS['ss']['useid'].' AND `name`!=\''.mainname().'\' AND '.objt().' ORDER BY RAND()',2);
		//print_r($dismantle_buildings);
		//e('<table>');
		foreach($dismantle_buildings as $buildingA){
			textb($buildingA['name']);br();
			foreach($dismantle_buildings as $buildingB){
				//e('<td>');
				if($buildingA['id']!=$buildingB['id']){
					$distance=sqrt(pow($buildingA['x']-$buildingB['x'],2)+pow($buildingA['y']-$buildingB['y'],2));
					echo($buildingA['name'].' -('.$distance.')- '.$buildingB['name']);br();
					if($distance<1 and $distance!=0){
						$efA=substr_count('{and}',$buildingA['name']);
						$efB=substr_count('{and}',$buildingB['name']);
						if($efA>$efb){
							$query=('dismantle '.$buildingA['id']);
						}else{
							$query=('dismantle '.$buildingB['id']);
						}
						blue($query);
						xqr($query);
						break(2);
					}
				}
				//e('</td>');
			}
			//e('</tr>');
		}
                t('bot.php - bot() - dismantle');
		//e('</table>');

		//--------------------------------------------------------------------------

}




//==============================

//----------------------------------------------------------------------------------------------------------------------------------BOTS
success('BOTS');

if($_GET['username']){
//-------------------------------------------------------------------------------------------------------KONKRETNI UZIVATEL

	xqr('login '.$_GET['username'].' towns '.$_GET['password']);
	
	//e('id');
	if(logged()){//echo($GLOBALS['ss']['useid']);
		bot();
	}

}else{
//-------------------------------------------------------------------------------------------------------TARGET
	// AND time_use<'.time().'-(`key`'.($_GET['speed']?'/'.$_GET['speed']:'').')


	//---------------------------------
	t('bot.php - start');
	$bots=array();
	foreach(sql_array("SELECT `id` FROM `[mpx]objects` WHERE 1=(SELECT 1 FROM [mpx]login as x WHERE x.id=[mpx]objects.id AND `method`='bot' AND ".objt()." LIMIT 1)") as $row){
	$bots[]=$row[0];
	}
	$botsw=implode("' OR superown='",$bots);
	$botsw="(superown='$botsw')";
	//e($botsw);
	//die();

        t('bot.php - select bots');
	//starttime DESC
	
	foreach(sql_array('SELECT id,name,own,x,y,ww FROM [mpx]objects WHERE `own`!=0 AND `ww`>0 AND `name`!=\''.mainname().'\' AND !'.$botsw.' AND `type`=\'building\' AND starttime>'.(time()-(3600*24*2)).' ORDER BY RAND() LIMIT 100') as $row){
		//hr();
		//print_r($row);
		$targetx=$row['x'];
		$targety=$row['y'];
		$targetww=$row['ww'];
		//hr();

	}
        t('bot.php - select near');
        
	//GROUP BY superown ,name,own,superown,x,y
	$botids=array();
	foreach(sql_array('SELECT superown FROM [mpx]objects WHERE `own`!=0 AND `ww`='.$targetww.' AND `name`=\''.mainname().'\' AND '.$botsw.' AND `type`=\'building\' AND '.objt().' ORDER BY POW(`x`-'.$targetx.',2)+POW(`y`-'.$targety.',2) LIMIT 2') as $row){
		/*hr();
		print_r($row);
		br();
		e(id2name($row['superown']));
		hr();*/
		$botids[]=$row['superown'];

	}

        t('bot.php - select bot');
	//print_r($botids);
	//die();
//-------------------------------------------------------------------------------------------------------TARGET -> BOT

	//Všichni roboti//foreach(sql_array('SELECT id FROM [mpx]login WHERE `method`=\'bot\' ORDER BY time_use LIMIT 1') as $id){list($id)=$id;//LIMIT 1=sracka

	foreach($botids as $id){
		sql_query('UPDATE [mpx]login SET `time_use`='.time().' WHERE `method`=\'bot\' AND `id`='.$id);
		//if($reporting){success('ID'.$id."(".id2name($id)."):");br();}

		force_login($id);
		blue("force_login($id);");
                t('bot.php - force login');
        
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
                t('bot.php - ul/uc');
                t('bot.php - before bot()');
		bot($targetx,$targety,$targetww);
                t('bot.php - after bot');

		$GLOBALS['ss']["log_object"]->update();
		$GLOBALS['ss']["use_object"]->update();

		unset($GLOBALS['ss']["log_object"]);
		unset($GLOBALS['ss']["use_object"]);
                
                t('bot.php - logout');
		
	}
}



//-------------------------------------------------Vlastní statistika
qlog(0,0,0,'bot',NULL,NULL);

//-------------------------------

?>
