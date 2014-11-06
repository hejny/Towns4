<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/attack/func_core.php

   Útočné funkce systému
*/
//==============================




define('a_attack_cooldown',true);
function a_attack($id,$lowed=false){
    //r('attacking '.$id);
    if($lowed)$lowed=1/gr; else $lowed=1;
    r($id);
    //$id=sg("id");
    if(!$id){$GLOBALS['ss']["query_output"]->add("error",lr('attack_noid'));}
    elseif($id==useid){$GLOBALS['ss']["query_output"]->add("error",lr('attack_self'));}
    else{
    $attacked=new object($id);
    if(!$attacked->loaded){$GLOBALS['ss']["query_output"]->add("error",lr('attack_unknown'));
        }/*elseif($attacked->ww!=$GLOBALS['ss']["ww"]){$GLOBALS['ss']["query_output"]->add("error","{attack_ww}");}*/else{
        
        $attack_type=$GLOBALS['ss']["aac_func"]["name"];
        $attacked=new object($id);
        $type=$attacked->type;
        //-------
        $a_name=lr($GLOBALS['ss']["aac_object"]->type).' '.$GLOBALS['ss']["aac_object"]->name;
        $a_name_=$GLOBALS['ss']["aac_object"]->name;
        $b_name=lr($type).' '.$attacked->name;
        $b_name_=$attacked->name;
        $b_name4=lr($type,4).' '.$attacked->name;
        $attackname=lr('attack_'.$type.'2');
        $a_fp=$GLOBALS['ss']["aac_object"]->getFP();
        $b_fp=$attacked->getFP();
        $a_at=$GLOBALS['ss']["aac_object"]->supportF($attack_type,"attack");
        $b_at=$attacked->supportF("attack");
        //NOTOTAL
        if($type=='tree' or $type=='rock'){
            $a_att=$GLOBALS['ss']["aac_object"]->supportF($attack_type,"total");
            $b_att=$attacked->supportF("attack","total");//r($b_att);
        }else{
            $a_att=false;
            $b_att=true;
        }
        $a_cnt=$GLOBALS['ss']["aac_object"]->supportF($attack_type,"count");
        $b_cnt=$attacked->supportF("attack","count");//r($b_att);
        $a_de=$GLOBALS['ss']["aac_object"]->supportF("defence");
        $b_de=$attacked->supportF("defence");
        $xeff=$GLOBALS['ss']["aac_object"]->supportF($attack_type,"xeff");
	//echo($xeff);
	
        $steal=clone $attacked->hold;

	//echo($steal->textr());

	$steal->multiply($xeff*$lowed);
        
	//echo($steal->textr());
        //-------LIMIT
        $limit=$GLOBALS['ss']["aac_object"]->func->profile($attack_type,'limit');
        if($limit and $limit!=$attacked->type){
            $GLOBALS['ss']["query_output"]->add("error",lr('attack_limit_'.$limit));
            return;
        } 
      
        //-------NON SAME WORLD
        if($GLOBALS['ss']["aac_object"]->ww!=$attacked->ww){
            $GLOBALS['ss']["query_output"]->add("error",lr('attack_error_ww'));
            return;
        }           
        
        //-------DISTANCE
    
        $a_dist=$GLOBALS['ss']["aac_object"]->supportF($attack_type,"distance");
        list($ax,$ay)=$GLOBALS['ss']["aac_object"]->position();
        list($bx,$by)=$attacked->position();
        $dist=sqrt(pow($ax-$bx,2)+pow($ay-$by,2));
        //r($bx,$by,$dist,$a_dist);
        if($dist>$a_dist){
            $GLOBALS['ss']["query_output"]->add("error",lr('attack_error_distance',$a_dist));
            return;
        }
		//-------BLOCK

		if(block2test('A',$GLOBALS['ss']["aac_object"]->x,$GLOBALS['ss']["aac_object"]->y,$attacked->x,$attacked->y,$attacked->id)){
            $GLOBALS['ss']["query_output"]->add("error",lr('attack_error_block'));
            return;
		}
        
        //-------PRICE
        //e(1);
        list($q,$time,$a_fp2,$b_fp2,$a_tah,$b_tah,$a_atf,$b_atf)=attack_count(50,50,$a_fp,$b_fp,$a_at,$b_at,$a_cnt,$b_cnt,$a_de,$b_de,$a_att,$b_att);
        //e($a_tah);        
        $price=use_price("attack",array("time"=>$a_tah),$support[$attack_type]["params"],2);
        if(!$GLOBALS['ss']["use_object"]->hold->testchange($price)){
            $GLOBALS['ss']["query_output"]->add("error",lr('attack_error_price'));
            return;
        }
    
        //-------MAIN
		$conqueror=false;
        //e($attacked->name.'=='.mainname());
        if($attacked->name==mainname()/*id2name($GLOBALS['config']['register_building'])*/){
		if(sql_1data('SELECT count(id) FROM [mpx]objects WHERE own='.$attacked->own.' AND '.objt())!=1){

			if(sql_1data('SELECT type FROM [mpx]objects WHERE id='.$attacked->own.' AND '.objt())=='town2'){
				$conqueror=true;
			}else{
            		$GLOBALS['ss']["query_output"]->add("error",lr('attack_error_mainlast'));
					//xerror(lr('attack_error_mainlast'));
            		return;
			}			

		}
	}
        //-------

	$a_pos=sql_array('SELECT x,y FROM [mpx]objects WHERE `name`=\''.id2name($GLOBALS['config']['register_building']).'\' AND `own`='.$GLOBALS['ss']["aac_object"]->own.' AND '.objt());
	$b_pos=sql_array('SELECT x,y FROM [mpx]objects WHERE `name`=\''.id2name($GLOBALS['config']['register_building']).'\' AND `own`='.$attacked->own.' AND '.objt());
	
	$a_hlvz=sqrt(pow($a_pos[0][0]-$GLOBALS['ss']["aac_object"]->x,2)+pow($a_pos[0][1]-$GLOBALS['ss']["aac_object"]->y,2));
	$b_hlvz=sqrt(pow($b_pos[0][0]-$attacked->x,2)+pow($b_pos[0][1]-$attacked->y,2));


	$a_buildingsfs=sql_1data('SELECT count(fs) FROM [mpx]objects WHERE own='.$GLOBALS['ss']["aac_object"]->own.' AND res NOT LIKE \'%{%\''.' AND '.objt(),1)-1+1;
	$b_buildingsfs=sql_1data('SELECT count(fs) FROM [mpx]objects WHERE own='.$attacked->own.' AND res NOT LIKE \'%{%\''.' AND '.objt(),1)-1+1;

	r($a_buildingsfs,$b_buildingsfs);

        $a_seed=rand(intval(100/gr),intval(100*gr)*sqrt($b_buildingsfs)*sqrt($b_hlvz+gr));
        $b_seed=rand(intval(100/gr),intval(100*gr)*sqrt($a_buildingsfs)*sqrt($a_hlvz+gr));
	
	r($a_seed,$b_seed);

	$ab_seed=$a_seed+$b_seed;

	r($ab_seed);

	$a_seed=intval(100*$a_seed/$ab_seed);	
	$b_seed=intval(100*$b_seed/$ab_seed);

	r($a_seed,$b_seed);

/*SELECT sum(fs) FROM world1_objects WHERE own=1139618
   SELECT sum(fs) FROM world1_objects WHERE own=1139610
   73155
   43937
   6437640
   6678424
   13116064
   0
   0*/


        list($q,$time,$a_fp2,$b_fp2,$a_tah,$b_tah,$a_atf,$b_atf)=attack_count($a_seed,$b_seed,$a_fp,$b_fp,$a_at,$b_at,$a_cnt,$b_cnt,$a_de,$b_de,$a_att,$b_att);
        
        //r('abc');
        //textab("váš konečný počet životů:",$a_fp2);
        //textab("soupeřův k. počet životů:",$b_fp2);
        
        
        $GLOBALS['ss']["aac_object"]->fp=$a_fp2;
        $attacked->fp=$b_fp2;
        
		$GLOBALS['ss']["use_object"]->hold->takehold($price);
		//use_hold($price);

        if(!$b_fp2){
        $attacked->hold->take($steal);
        $steal->multiply(-1);
        use_hold($steal);//$steal->showimg();
        }else{
            $steal->multiply(0);
        }
        
        //$steal->showimg();
        //-----
        if($b_fp2==0 and $type!='user' and $type!='unit'){
            $attacked->delete();
            //Už ne//changemap($bx,$by);
            if($attacked->type=='building')
                changemap($bx,$by,true);//XXX
            else
                changemap($bx,$by,true);
        }
        //-----
        if($a_fp==0 and $type!='user' and $type!='unit' and (id2name($GLOBALS['config']['register_building'])!=$GLOBALS['ss']["aac_object"]->name)){
             $GLOBALS['ss']["aac_object"]->delete();
            //Už ne//changemap($bx,$by);
            if($GLOBALS['ss']["aac_object"]->type=='building')
                changemap($bx,$by,true);//XXX
            else
                changemap($bx,$by,true);
        }   
        //-----
	changemap($ax,$ay,true);
	changemap($bx,$by,true);
        if($b_fp2==1){
			//$attacked->fp=1;

			if($conqueror){
				$owner_id=sql_query('UPDATE [mpx]objects SET own='.$GLOBALS['ss']['logid'].' WHERE type=\'town2\' AND id='.$attacked->own.' AND '.objt());
				$GLOBALS['ss']["query_output"]->add("success",lr('attack_success_conq'));
				click('e=login-use');
		
			}else{
				if($attacked->name!=id2name($GLOBALS['config']['register_building'])){
						$GLOBALS['ss']["query_output"]->add("success",lr('attack_success'));
				    	$attacked->own=$GLOBALS['ss']["aac_object"]->own;
				}else{
				    
					list($x,$y,$q)=register_positiont();
					if($q){
						$attacked->x=$x;
						$attacked->y=$y;
						changemap($ax,$ay,true);
						changemap($x,$y,true);
					}
				}
		    }
		}
		trackobject($attacked->id);//záloha původního objektu, nastavení časů
        $attacked->update();
        //-----
        /*if($a_fp2==1){
             $GLOBALS['ss']["aac_object"]->own->$attacked->own;
        }*/  
        //-----
        
        
        
        //--------------------------------------
        $steal->multiply(-1);
	//$steal->showimg();
        $price=$price->textr();
        $steal=$steal->textr();
        $info=array('a_name'=>$a_name,'a_name_'=>$a_name_,'b_name'=>$b_name,'b_name_'=>$b_name_,'b_name4'=>$b_name4,'attackname'=>$attackname,'q'=>attack_name($q),'time'=>nn($time),'a_fp2'=>nn($a_fp2),'b_fp2'=>nn($b_fp2),'a_tah'=>nn($a_tah),'b_tah'=>nn($b_tah),'a_atf'=>nn($a_atf),'b_atf'=>nn($b_atf),'a_seed'=>nn($a_seed),'b_seed'=>nn($b_seed),'a_fp'=>nn($a_fp),'b_fp'=>nn($b_fp),'a_at'=>nn($a_at),'b_at'=>nn($b_at),'a_cnt'=>nn($a_cnt),'b_cnt'=>nn($b_cnt),'a_de'=>nn($a_de),'b_de'=>nn($b_de),'a_att'=>nn($a_att),'b_att'=>nn($b_att),'price'=>$price,'steal'=>$steal);
        $info=/*x2xx(*/list2str($info/*)*/);
         /*le('attack_report_title',$info);
         br();
        le('attack_report',$info);
        hr();*/



        //if($type!='tree' and $type!='rock'){
            send_report(useid,$id,'{'.('attack_report_title_q'.$q.';'.$info).'}','{'.('attack_report'.';'.$info).'}');
        //}



        $GLOBALS['ss']['attack_report']='{'.('attack_report'.';'.$info.'}');
        $GLOBALS['ss']["query_output"]->add("1",1);
        //--------------------------------------
    
    
    }}
}
//-------
/*function attack_count($a_seed,$b_seed,$a_fp,$b_fp,$a_at,$b_at,$a_cnt,$b_cnt,$a_de,$b_de,$a_att,$b_att){
    $a_min=1;
    $b_min=1;
    if($a_att)$b_min=0;
    if($b_att)$a_min=0;
    $a_atf=($a_at-$b_de)*0.01*$a_seed;
    $b_atf=($b_at-$a_de)*0.01*$b_seed;
    if($a_atf<=0){$a_atf=0;$b_tah=-1;}
    if($b_atf<=0){$b_atf=0;$a_tah=-1;}
    if($a_tah!=-1) $a_tah=$a_fp/$b_atf;//ZničeníA
    if($b_tah!=-1)$b_tah=$b_fp/$a_atf;
    $q=0;
    $time=-1;
    $a_fp2=$a_fp;$b_fp2=$b_fp;
           if($a_fp==0)                             {$q=1;$time=0;}
    elseif($b_fp==0)                             {$q=2;$time=0;}
    elseif($a_tah==-1 and $b_tah==-1){$q=3;}
    elseif($a_tah==-1)                          {$q=4;$b_fp2=$b_min;$time=$b_tah;}
    elseif($b_tah==-1)                          {$q=5;$a_fp2=$a_min;$time=$a_tah;}
    elseif($a_tah==$b_tah)                  {$q=6;$a_fp2=$a_min;$b_fp2=$b_min;$time=$a_tah;}
    elseif($a_tah>$b_tah)                    {$q=7;$a_fp2=$a_fp*(1-($b_tah/$a_tah));$b_fp2=$b_min;$time=$b_tah;}
    else                                                {$q=8;$a_fp2=$a_min;$b_fp2=$b_fp*(1-($a_tah/$b_tah));$time=$a_tah;}
    if($time==-1)$time=0;
    return(array($q,$time,$a_fp2,$b_fp2,$a_tah,$b_tah,$a_atf,$b_atf,$a_seed,$b_seed));
}*/
function attack_count($a_seed,$b_seed,$a_fp,$b_fp,$a_at,$b_at,$a_cnt,$b_cnt,$a_de,$b_de,$a_att,$b_att){
    $a_min=1;
    $b_min=1;
    if($a_att)$b_min=0;
    if($b_att)$a_min=0;
    $a_atf=($a_at-$b_de)*0.01*$a_seed;
    $b_atf=($b_at-$a_de)*0.01*$b_seed;
    $time=0;
    $a_tah=0;
    $b_tah=0;
    $a_fp2=$a_fp;
    $b_fp2=$b_fp;
    while(($a_fp2!=$a_min and $b_fp2!=$b_min) and (($a_cnt and $a_atf>0) or ($b_cnt and $b_atf>0))){$time++;
        if($a_cnt and $a_fp2>0 and $a_atf>0){$b_fp2=$b_fp2-$a_atf;$a_cnt--;$a_tah++;}
        if($b_cnt and $b_fp2>0 and $b_atf>0){$a_fp2=$a_fp2-$b_atf;$b_cnt--;$b_tah++;}
        if($a_fp2<$a_min)$a_fp2=$a_min;
        if($b_fp2<$b_min)$b_fp2=$b_min;
        
    }
    
    
    
        if($a_fp==$a_fp2 and $b_fp==$b_fp2)                                         {$q=1;}
    elseif($a_fp2!=0 and $b_fp2!=0 and $b_fp2!=1 and ($a_fp-$a_fp2)<($b_fp-$b_fp2)) {$q=2;}
    elseif($a_fp2!=0 and $b_fp2!=0 and $b_fp2!=1 and ($a_fp-$a_fp2)>($b_fp-$b_fp2)) {$q=3;}  
    elseif($a_fp2!=0 and $b_fp2!=0 and $b_fp2!=1 and ($a_fp-$a_fp2)==($b_fp-$b_fp2)){$q=4;}
    elseif($a_fp2!=0 and $b_fp2==0)                                                 {$q=5;}
    elseif($a_fp2==0 and $b_fp2!=0 and $b_fp2!=1)                                   {$q=6;}
    elseif($a_fp2==0 and $b_fp2==0)                                                 {$q=7;}
    elseif($b_fp2==1)                                                               {$q=8;} 
    else                                                                            {$q=9;}       
    
    
    return(array($q,$time,$a_fp2,$b_fp2,$a_tah,$b_tah,$a_atf,$b_atf));
}
//======================================================
    function attack_name($q){
        return(lr("attack_q$q"));
        /*switch($q){
          case 1: return("A má 0 životů");break;
          case 2: return("B má 0 životů");break;
          case 3: return("bez efektu");break;
          case 4:  return("totální výhra");break;
          case 5:  return("totální prohra");break;
          case 6: return("sebevražda");break;
          case 7:  return("výhra");break;
          case 8: return("prohra");break;
        }*/
    }
//======================================================
//towns 4 A_ctl function core
//$GLOBALS['ss']["query_output"]->add("1",1);
//$GLOBALS['ss']["query_output"]->add("error","");



function a_xmine($func1name){
	//r("xmine $func1name");
	$object=$GLOBALS['ss']["aac_object"];
        $x=$object->x;$y=$object->y;$ww=$object->ww;
        $func=$object->func->func($func1name);
        //r($func);
        $distance=$func['distance'];
        $attack=$func['attack'];
        $sql="SELECT id,func FROM [mpx]objects WHERE ww=$ww  AND type='".($object->func->profile($func1name,'limit'))."' AND POW(x-$x,2)+POW(y-$y,2)<=POW($distance,2) AND ".objt()." ORDER BY fr  DESC";
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
	//e($object->id.'.'.$func1name.' '.$attack_id);
	$response=query($object->id.'.'.$func1name.' '.$attack_id);
	$GLOBALS['ss']["query_output"]=$response;
	//xreport();
    }


}
?>
