<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/login/func_core.php

   Přihlášení / Registrace - funkce systému
*/
//==============================


//======================================================================================REGISTER POSITIONX
function register_positionx($reg1,$reg2,$iiii=0){
			if($reg1==4){
				if($tid=sql_1data("SELECT `id` FROM [mpx]objects WHERE (`type`='town' OR `type`='town2') AND `name`='$reg2'".' AND '.objt())){
					$rid=sql_array("SELECT `x`,`y` FROM [mpx]objects WHERE `type`='building' AND `own`='$tid' ORDER BY rand();".' AND '.objt());
					list($rx,$ry)=$rid[0];
/*$array=sql_array("
                    SELECT `x`,`y` FROM [mpx]map where `ww`='".$GLOBALS['ss']["ww"]."' AND
                    (`terrain`='t3' OR `terrain`='t4' OR `terrain`='t7' OR `terrain`='t8' OR `terrain`='t9' OR `terrain`='t12' OR `terrain`='t13')
                    AND
                    0=(SELECT COUNT(1) FROM [mpx]objects AS X where X.`ww`='".$GLOBALS['ss']["ww"]."' AND  X.`own`!='0' AND (X.`x`+4>[mpx]map.`x` AND X.`y`+4>[mpx]map.`y` AND X.`x`-4<[mpx]map.`x` AND X.`y`-4<[mpx]map.`y`))
                    ORDER BY POW(`x`-$rx,2)+POW(`y`-$ry,2) LIMIT 1");*/
//  AND 9=(SELECT COUNT(1) FROM [mpx]map AS Y where Y.`ww`='".$GLOBALS['ss']["ww"]."' AND (Y.`terrain`='t3' OR Y.`terrain`='t4' OR Y.`terrain`='t7' OR Y.`terrain`='t8' OR Y.`terrain`='t9' OR Y.`terrain`='t12' OR Y.`terrain`='t13') AND (Y.`x`+1>=[mpx]map.`x` AND Y.`y`+1>=[mpx]map.`y` AND Y.`x`-1<=[mpx]map.`x` AND Y.`y`-1<=[mpx]map.`y`))
					//$q=true;            
                    //list($x,$y)=$array[0];
                    //return(array($x,$y,$q));


				}else{
					return(false);
				}

			}


            //-------------------------------------------------------------------------CREATE NEW TOWN
                $file=tmpfile2("registerx_list","txt","text");
                if(!file_exists($file) or unserialize(file_get_contents($file))==array()){
                    $array=sql_array("
                    SELECT `x`,`y` FROM [mpx]map where `ww`='".$GLOBALS['ss']["ww"]."' AND 
            RAND()>0.90 AND
                    (`terrain`='t3' OR `terrain`='t4' OR `terrain`='t7' OR `terrain`='t8' OR `terrain`='t9' OR `terrain`='t12' OR `terrain`='t13')  AND 
                    9=(SELECT COUNT(1) FROM [mpx]map AS Y where Y.`ww`='".$GLOBALS['ss']["ww"]."' AND (Y.`terrain`='t3' OR Y.`terrain`='t4' OR Y.`terrain`='t7' OR Y.`terrain`='t8' OR Y.`terrain`='t9' OR Y.`terrain`='t12' OR Y.`terrain`='t13') AND (Y.`x`+1>=[mpx]map.`x` AND Y.`y`+1>=[mpx]map.`y` AND Y.`x`-1<=[mpx]map.`x` AND Y.`y`-1<=[mpx]map.`y`))
                    AND
                    0=(SELECT COUNT(1) FROM [mpx]objects AS X where X.`ww`='".$GLOBALS['ss']["ww"]."' AND  X.`own`!='0' AND (X.`x`+4>[mpx]map.`x` AND X.`y`+4>[mpx]map.`y` AND X.`x`-4<[mpx]map.`x` AND X.`y`-4<[mpx]map.`y`) AND ".objt('X').")
                    ORDER BY RAND()");
                    
                }else{
                    $array=unserialize(file_get_contents($file));
   
                }
                if($array){ 
                    $q=true;

					if($reg1==4){
						$i=0;$min=100*100;$sp=0;
						while($array[$i]){
							list($x,$y)=$array[$i];
							$dist=sqrt(pow($rx-$x,2)+pow($ry-$y,2));
							if($dist<=$min){
								$min=$dist;
								$sp=$i;
							}
							$i++;
						}
					}else{
						$sp=$iiii;
					}

                    list($x,$y)=$array[$sp];
                    array_splice($array,$sp,1);
                }
                file_put_contents2($file,serialize($array));
               //--------------------------------------------------------------------------------
		return(array($x,$y,$q));
}

//-------------------------------------------------REVIDOVAT POZICE

define('register_min_distance',2);
define('register_max_distance',15);

function register_test($x,$y){
	$ok=true;
	//-------------------Zda není pozice moc blízko nebo daleko ostatním
	if($ok){
		$array=sql_array("SELECT `x`,`y` FROM [mpx]objects WHERE `ww`='".$GLOBALS['ss']["ww"]."' AND type='building' AND ".objt()." ORDER BY ABS(x-$x)+ABS(y-$y) LIMIT 1");
			/** /foreach($array as $row){
				list($xt,$yt)=$row;
				$distance=sqrt(pow($x-$xt,2)+pow($y-$yt,2));
				e(nbspo."$xt,$yt - $distance");br();
			}/**/
		list($xt,$yt)=$array[0];
		$distance=sqrt(pow($x-$xt,2)+pow($y-$yt,2));
		
		if($distance<register_min_distance){$ok=false;if(debug)e('min distance '.$distance.'<'.register_min_distance);}
		if($distance>register_max_distance){$ok=false;if(debug)e('max distance '.$distance.'>'.register_max_distance);}
	}
	//-------------------Zda není pozice moc daleko stromům/skalám
	if($ok){
		//zatím ne
	}
	//-------------------
	return($ok);
}

//-------------------------------------------------REGISTER POSITIONT - už otestované pozice

function register_positiont($reg1,$reg2){
	
	$iiii=0;
	$i=100;
	while($i>1){$i--;
		$position=register_positionx($reg1,$reg2,$iiii);
		$iiii++;
		if(!$position){return(false);}

		list($x,$y)=$position;
		if(register_test($x,$y)){
			return($position);
		}
	}
	return(array(0,0,0));

}

/*    $file=tmpfile2("registerx_list","txt","text");
    if(!file_exists($file) or unserialize(file_get_contents($file))==array()){
        if(debug)e('No rewid pos to rewid');
    }else{
        $array=unserialize(file_get_contents($file));

    }
    $i=0;
    while($array[$i]){
     	list($x,$y)=$array[$i];



		$i++;
    }*/
//======================================================================================REGISTER
define("a_register_help","user,key,fbid");
function a_register($param1,$param2='key',$fbid=false,$reg1=1,$reg2=''){
    if(defined('countdown') and countdown-time()>0){return;}
    //$GLOBALS['ss']["query_output"]->add("success",'register '.$param1);
    if(!defined('register_block') and $GLOBALS['ss']['register_key']==$param2){ $GLOBALS['ss']['register_key']=0;
    
    
    
       //------------------------------------------------------------------------ZNOVUUSER     
/*$where="type='user'  AND ww=1  AND `name`=`id`";
$ad0='SELECT max(`t`) FROM [mpx]objects as x WHERE x.own=(SELECT y.id FROM [mpx]objects as y WHERE y.own=[mpx]objects.id LIMIT 1) AND type=\'building\'';
$ad1='SELECT count(1) FROM [mpx]objects as x WHERE x.own=(SELECT y.id FROM [mpx]objects as y WHERE y.own=[mpx]objects.id LIMIT 1) AND type=\'building\'';
$ad3='SELECT count(1) FROM [mpx]objects as x WHERE x.own=[mpx]objects.id AND type=\'town\'';
$ad4='SELECT count(1) FROM [mpx]login as x WHERE x.id=[mpx]objects.id';
$cond0='('.$ad0.') <'.(time()-(3600*24));
$cond1='('.$ad1.')=1';
$cond3='('.$ad3.') =1';
$cond4='('.$ad4.')=0';
$order="ad4 DESC, ad2 DESC";

$recid=sql_1data("SELECT `id` FROM `".mpx."objects` WHERE $where AND $cond0 AND $cond1 AND $cond3 AND $cond4 ORDER BY RAND() LIMIT 1");
//die($recid);
if($recid){
    a_login($recid);
    return;
}*/
//------------------------------------------------------------------------
    
    
    
    
    if($param1='new' or !($error=name_error($param1))){
        //$GLOBALS['ss']["query_output"]->add("success",'register '.$param1);
        //--------------------
        if(defined('register_user') and defined('register_building') and ifobject(register_user) and ifobject(register_building)){
             $q=false;             
            /*$limit=100;          
            while(!$g and $limit>0){$limit--;       
                $x=rand(1,mapsize);            
                $y=rand(1,mapsize);
                $hard=sql_1data("SELECT `hard` FROM ".mpx."map where `ww`='".$GLOBALS['ss']["ww"]."' AND `x`='$x' AND `y`='$y'");
                if(floatval($hard)<0.3)$q=true;
            }*/

            //-------------------------------------------------------------------------CREATE NEW TOWN
		if(list($x,$y,$q)=register_positiont($reg1,$reg2)){
	
               //--------------------------------------------------------------------------------
            
            
            
            if($q){
                $set='tutorial=1;ref='.$GLOBALS['ss']['ref'];
                $id=nextid();
		if($param1='new'){$param1=$id;}        
		$randomcolor='';//rand_color();
                $rows='`type`, `dev`, `fs`, `fp`, `fr`, `fx`, `fc`, `func`, `hold`, `res`, `hard`, `expand`';                
                sql_query/*e*/("INSERT INTO ".mpx."objects (`id`,`name` ,$rows, `profile`, `set`, `own`, `in`, `ww`, `x`, `y`, `t`, `starttime`) SELECT '$id','$param1',$rows, 'color=$randomcolor','$set', '0', '0', '".$GLOBALS['ss']["ww"]."', '0', '0', '".time()."', '".time()."' FROM ".mpx."objects WHERE id='".register_user."';");
                $id2=nextid();                
                sql_query/*e*/("INSERT INTO ".mpx."objects (`id`,`name` ,$rows, `profile`, `set`, `own`, `in`, `ww`, `x`, `y`, `t`, `starttime`) SELECT '$id2','$param1',$rows, 'color=$randomcolor','coolround=".time()."', '$id', '0', '".$GLOBALS['ss']["ww"]."', '$x', '$y', '".time()."', '".time()."' FROM ".mpx."objects WHERE id='".register_town."';");
                $id3=nextid();                
                sql_query/*e*/("INSERT INTO ".mpx."objects (`id`,`name` ,$rows, `profile`, `set`, `own`, `in`, `ww`, `x`, `y`, `t`, `starttime`) SELECT '$id3',`name`,$rows, `profile`,'', '$id2', '0', '".$GLOBALS['ss']["ww"]."', '$x', '$y', '".time()."', '".time()."' FROM ".mpx."objects WHERE id='".register_building."';");
		if(defined('register_flag')){
			$alpha=(rand(1,360)/180)*3.1415;
			$xf=$x-(cos($alpha)*(register_flagx-1+1));
			$yf=$y-(sin($alpha)*(register_flagx-1+1));
		        $id4=nextid();
			$rows='`type`, `dev`, `fr`, `fx`, `fc`, `func`, `hold`, `res`, `profile`, `hard`, `expand`';              
		        sql_query/*e*/("INSERT INTO ".mpx."objects (`id`,`name` ,$rows, `fs`, `fp`, `set`, `own`, `in`, `ww`, `x`, `y`, `t`, `starttime`) SELECT '$id4',`name`,$rows, `fs`/5, `fp`/5,'', '0', '0', '".$GLOBALS['ss']["ww"]."', '$xf', '$yf', '".time()."' FROM ".mpx."objects WHERE id='".register_flag."';");
			//die();
		}
		if(defined('register_quest')){
			sql_query("INSERT INTO ".mpx."questt (`id`,`quest`,`questi`,`time1`,`time2`) VALUES ('$id2','".register_quest."','1','".time()."','')");
			//die();
                }


		if($fbid){
                	sql_query("INSERT INTO `[mpx]login` (`id`,`method`,`key`,`text`,`time_create`,`time_change`,`time_use`) VALUES ('".($id)."','facebook','".$fbid."','".serialize($GLOBALS['user_profile'])."','".time()."','".time()."','".time()."')");
		}
                //------LOGIN                
                $GLOBALS['ss']["query_output"]->add("1",1);
                $GLOBALS['ss']["log_object"]=new object($id);
                $GLOBALS['ss']["log_object"]->func->delete('login');
                $GLOBALS['ss']["log_object"]->func->add('login','login');
                $GLOBALS['ss']["log_object"]->update();           
                $GLOBALS['ss']["logid"]=$GLOBALS['ss']["log_object"]->id;
                a_use($id2/*$param1*/);/**/
            }else{
                $GLOBALS['ss']["query_output"]->add("error",lr('register_error_nospace')); 
            }
            }else{
                $GLOBALS['ss']["query_output"]->add("error",lr('register_error_nonear')); 
            }
        }else{
            $GLOBALS['ss']["query_output"]->add("error",lr('config_error')); 
        }
        //--------------------
    }else{
        $GLOBALS['ss']["query_output"]->add("error",$error);
    }
    }else{
        $GLOBALS['ss']["query_output"]->add("error",lr('register_block_error')/*.$GLOBALS['ss']['register_key'].','.$param2*/);
    }
    
}
//======================================================================================LOGIN
define("a_login_help","user[,method,password,newpassword,newpassword2]");
function a_login($param1,$param2='towns',$param3='',$param4='',$param5=''){
	
	//br();e("$param1,$param2='towns',$param3='',$param4='',$param5=''");

	$param1=sql(xx2x($param1));
	$param2=sql($param2);

    //if(defined('countdown') and countdown-time()>0){return;}
    //$GLOBALS['ss']["query_output"]->add("success",$param1);
    //e("$param1,$param2,$param3,$param4,$param5");
    if($param2=='towns'){
        $GLOBALS['ss']["log_object"] = new object(NULL,"type='user' AND (id='$param1' OR name='$param1')");
        $pass=sql_1data('SELECT `key` FROM `[mpx]login` WHERE `id`=\''.($GLOBALS['ss']["log_object"]->id).'\' AND `method`=\'towns\' LIMIT 1');
        //e("$pass==md5($param3)");
        if($pass==md5($param3) or !$pass){
            if(!$param4 and !$param5 and !$param6)$GLOBALS['ss']["query_output"]->add("1",1);
            //--------------------CREATEPASSWORD
            if(!$pass and $param4){
            if($param4==$param5){
                sql_query("INSERT INTO `[mpx]login` (`id`,`method`,`key`,`text`,`time_create`,`time_change`,`time_use`) VALUES ('".($GLOBALS['ss']["log_object"]->id)."','towns','".md5($param4)."','','".time()."','".time()."','".time()."')");
                $GLOBALS['ss']["query_output"]->add("success",lr('f_login_createpass'));
                $GLOBALS['ss']["query_output"]->add("1",1);           
            }else{
                $GLOBALS['ss']["query_output"]->add("error",lr('f_login_nochangepass'));
            }
                
                $param4=false;$param5=false;
            }            
            //--------------------
            sql_query('UPDATE `[mpx]login` SET  `time_use`=\''.time().'\' WHERE `id`=\''.($GLOBALS['ss']["log_object"]->id).'\' AND `method`=\'towns\'');
            //--------------------CHANGEPASSWORD            
            if($param4){
                if($param4==$param5){
                    sql_query('UPDATE `[mpx]login` SET `key`=\''.md5($param4).'\', `time_change`=\''.time().'\' WHERE `id`=\''.($GLOBALS['ss']["log_object"]->id).'\' AND `method`=\'towns\'');

                    $GLOBALS['ss']["query_output"]->add("success",lr('f_login_changepass'));
                    $GLOBALS['ss']["query_output"]->add("1",1);
                }else{
                    $GLOBALS['ss']["query_output"]->add("error",lr('f_login_nochangepass'));
                }
            }
            //--------------------
            
            
            //die($GLOBALS['ss']["logid"]);
            //echo("abc");
            $use=sql_1data('SELECT `id` FROM [mpx]objects WHERE `own`=\''.($GLOBALS['ss']["log_object"]->id).'\' AND `type`=\'town\' AND '.objt().' ORDER BY ABS('.($GLOBALS['ss']["log_object"]->id).'-`id`) LIMIT 1');
            //die($use);            
            if($use){
                
                $GLOBALS['ss']["logid"]=$GLOBALS['ss']["log_object"]->id;
                a_use($use/*$param1*/);
            }else{
                $GLOBALS['ss']["query_output"]->add("error",lr('f_login_notown'));
            }
            
        }else{
            xerror(lr('f_login_nologin'));
        }
    }elseif($param2=='facebook'){
        //echo('aaa'.$param3);
        //echo('UPDATE [mpx]login SET time_use = \''.time().'\' WHERE `id`=\''.($param1).'\' AND `method`=\'facebook\' AND `key`=\''.($param3).'\' ');        
        
        //--------------------CREATEPASSWORD
        $pass=sql_1data('SELECT `key` FROM `[mpx]login` WHERE `id`=\''.($GLOBALS['ss']["log_object"]->id).'\' AND `method`=\'facebook\' LIMIT 1');
        if(!$pass and $param4){
        if($param4){
            sql_query("INSERT INTO `[mpx]login` (`id`,`method`,`key`,`text`,`time_create`,`time_change`,`time_use`) VALUES ('".($GLOBALS['ss']["log_object"]->id)."','towns','".md5($param4)."','','".time()."','".time()."','".time()."')");
        }
            $GLOBALS['ss']["query_output"]->add("success",lr('f_login_createfb'));
            $param4=false;$param5=false;
        }            
        //--------------------        
        
        if(1==sql_query('UPDATE [mpx]login SET time_use = \''.time().'\' WHERE `id`=\''.($param1).'\' AND `method`=\'facebook\' AND `key`=\''.($param3).'\' ')){      
            //if(!$param4)$GLOBALS['ss']["query_output"]->add("1",1);            
            //--------------------CHANGEPASSWORD
            if($param4){
                sql_query('UPDATE `[mpx]login` SET `key`=\''.($param4).'\', `time_change`=\''.time().'\' WHERE `id`=\''.($GLOBALS['ss']["log_object"]->id).'\' AND `method`=\'facebook\'');
                $GLOBALS['ss']["query_output"]->add("success",lr('f_login_changefb'));
                $GLOBALS['ss']["query_output"]->add("1",1); 
            }      
            //--------------------
        
            $GLOBALS['ss']["log_object"] = new object($param1);
            $GLOBALS['ss']["query_output"]->add("1",1); 
            $GLOBALS['ss']["logid"]=$GLOBALS['ss']["log_object"]->id;


            $use=sql_1data('SELECT `id` FROM [mpx]objects WHERE `own`=\''.($GLOBALS['ss']["log_object"]->id).'\' AND (`type`=\'town\' OR `type`=\'town2\') AND '.objt().' ORDER BY ABS('.($GLOBALS['ss']["log_object"]->id).'-`id`) LIMIT 1');
            //die($use);            
            if($use){
                
                $GLOBALS['ss']["logid"]=$GLOBALS['ss']["log_object"]->id;
                a_use($use/*$param1*/);
            }else{
                $GLOBALS['ss']["query_output"]->add("error",lr('f_login_notown'));
            }

            //a_use($param1);
        }else{
            $GLOBALS['ss']["query_output"]->add("error",lr('f_login_nofblogin'));
        }   
    }
}
//======================================================================================LOGIN
define("a_login_help","user,method,password[,newpassword,newpassword2]");
function force_login($param1){
	$param1=sql($param1);
        $GLOBALS['ss']["log_object"] = new object(NULL,"type='user' AND (id='$param1' OR name='$param1')");
        $use=sql_1data('SELECT `id` FROM [mpx]objects WHERE `own`=\''.($GLOBALS['ss']["log_object"]->id).'\' AND (`type`=\'town\' OR `type`=\'town2\') AND '.objt());        
        $GLOBALS['ss']["logid"]=$GLOBALS['ss']["log_object"]->id;
        a_use($use);     
}
//======================================================================================LOGOUT
define("a_logout_help","");
function a_logout(){
    $key=$GLOBALS['ss']['register_key'];
    $lang=$GLOBALS['ss']["lang"];
    $GLOBALS['ss']=array();
    $GLOBALS['ss']["lang"]=$lang;
    $GLOBALS['ss']['register_key']=$key;
    //session_destroy();
    setcookie('towns_login_username','',1);
    setcookie('towns_login_password','',1);
    	//reloc(true);
    	//exit2();
    //urlx('e=-html_fullscreen');
	click(js2('reloc()'),1);
	exit2();
    //exit2();
}
//======================================================================================USE
define("a_use_help","user");
function a_use($param1){
    //echo("use($param1)");
    $GLOBALS['ss']["use_object"] = new object($param1);
    //$GLOBALS['ss']["use_object"]->xxx();
    //$GLOBALS['ss']["query_output"]->add("1",1);
    $GLOBALS['ss']["useid"]=$GLOBALS['ss']["use_object"]->id;
    if($GLOBALS['ss']["use_object"]->own!=$GLOBALS['ss']["logid"] and $GLOBALS['ss']["logid"]!=$GLOBALS['ss']["useid"]){
        $GLOBALS['ss']["query_output"]->add("error","Tento objekt vám nepatří!");
        $GLOBALS['ss']["useid"]=$GLOBALS['ss']["logid"];
        unset($GLOBALS['ss']["use_object"]);
        
    }
}/**/
?>
