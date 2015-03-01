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
				if($tid=sql_1data("SELECT `id` FROM `[mpx]pos_obj` WHERE (`type`='town' OR `type`='town2') AND `name`='$reg2'".' AND '.objt())){
					$rid=sql_array("SELECT `x`,`y` FROM `[mpx]pos_obj` WHERE `type`='building' AND `own`='$tid' ORDER BY rand();".' AND '.objt());
					list($rx,$ry)=$rid[0];
/*$array=sql_array("
                    SELECT `x`,`y` FROM [mpx]map where `ww`='".$GLOBALS['ss']["ww"]."' AND
                    (`terrain`='t3' OR `terrain`='t4' OR `terrain`='t7' OR `terrain`='t8' OR `terrain`='t9' OR `terrain`='t12' OR `terrain`='t13')
                    AND
                    0=(SELECT COUNT(1) FROM `[mpx]pos_obj` AS X where X.`ww`='".$GLOBALS['ss']["ww"]."' AND  X.`own`!='0' AND (X.`x`+4>[mpx]map.`x` AND X.`y`+4>[mpx]map.`y` AND X.`x`-4<[mpx]map.`x` AND X.`y`-4<[mpx]map.`y`))
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
                    0=(SELECT COUNT(1) FROM `[mpx]pos_obj` AS X where X.`ww`='".$GLOBALS['ss']["ww"]."' AND  X.`own`!='0' AND (X.`x`+4>[mpx]map.`x` AND X.`y`+4>[mpx]map.`y` AND X.`x`-4<[mpx]map.`x` AND X.`y`-4<[mpx]map.`y`) AND ".objt('X').")
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
                fpc($file,serialize($array));
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
		if(!$GLOBALS['buildin_count']){
			$GLOBALS['buildin_count']=sql_1data("SELECT count(1) FROM `[mpx]pos_obj` WHERE `ww`='".$GLOBALS['ss']["ww"]."' AND type='building' AND ".objt());
			r($GLOBALS['buildin_count']);
		}
		$array=sql_array("SELECT `x`,`y` FROM `[mpx]pos_obj` WHERE `ww`='".$GLOBALS['ss']["ww"]."' AND type='building' AND ".objt()." ORDER BY ABS(x-$x)+ABS(y-$y) LIMIT 1");
			/** /foreach($array as $row){
				list($xt,$yt)=$row;
				$distance=sqrt(pow($x-$xt,2)+pow($y-$yt,2));
				e(nbspo."$xt,$yt - $distance");br();
			}/**/
		list($xt,$yt)=$array[0];
		$distance=sqrt(pow($x-$xt,2)+pow($y-$yt,2));
		
		if($distance<register_min_distance){$ok=false;if(debug)e('min distance '.$distance.'<'.register_min_distance);}
		//if($GLOBALS['buildin_count'] and $distance>register_max_distance){$ok=false;if(debug)e('max distance '.$distance.'>'.register_max_distance);}
	}
	//-------------------Zda není pozice moc daleko stromům/skalám
	if($ok){
		//zatím ne
	}
	//-------------------
	return($ok);
}

//-------------------------------------------------REGISTER POSITIONT - už otestované pozice

function register_positiont($reg1=1,$reg2=0){
	
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


//======================================================================================REGISTER WORLD 
function register_on_world($userid,$username){
    if(defined('register_user') and defined('register_building') and ifobject(register_user) and ifobject(register_building)){
     $q=false;             

    //-------------------------------------------------------------------------CREATE NEW TOWN
    list($x,$y,$q)=register_positiont(/*$reg1,$reg2*/);


    if($q){
        $showsql=0;
        $set='tutorial=1;ref='.$GLOBALS['ss']['ref'];
        $randomcolor='';//rand_color();
        //------------------------------------register_user
        $id=nextid();
        if($username=='new'){$username=$id;}

        //------------------REINSERT objects register_user
        sql_reinsert('objects',"id='".register_user."'",array(
            'id' => $id,
            'name' => sqlx($username),
            'type' => true,
            'userid' => $userid,
            'origin' => true,
            'fp' => true,
            'func' => true,
            'hold' => true,
            'res' => true,
            'profile' => sqlx("color=$randomcolor"),
            'set' => sqlx($set),
            'own' => 0,
            't' => time(),
            'pt' => true
        ),$showsql);
        //------------------REINSERT objects_tmp register_user
        sql_reinsert('objects_tmp',"id='".register_user."'",array(
            'id' => $id,
            'fs' => true,
            'fc' => true,
            'fr' => true,
            'fx' => true,
            'superown' => 0,
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
        ),$showsql);
        //------------------INSERT positions register_user
        sql_insert('positions',array(
            'id' => $id,
            'ww' => $GLOBALS['ss']["ww"],
            'x' => 0,
            'y' => 0,
            'traceid' => true,
            'starttime' => time(),
            'readytime' => time(),
            'stoptime' => 0
        ),$showsql);
        //------------------


        //`id`,`name`, `userid` ,$rows, `profile`, `set`, `own`, `in`, `ww`, `x`, `y`, `t`, `starttime`
        //'$id','$username','$userid',$rows, 'color=$randomcolor','$set', '0', '0', '".$GLOBALS['ss']["ww"]."', '0', '0', '".time()."', '".time()."'
        /*$rows='`type`, `fs`, `fp`, `fr`, `fx`, `fc`, `func`, `hold`, `res`, `hard`, `expand`';
        sql_query("INSERT INTO `[mpx]pos_obj` (`id`,`name`, `userid` ,$rows, `profile`, `set`, `own`, `in`, `ww`, `x`, `y`, `t`, `starttime`) SELECT '$id','$username','$userid',$rows, 'color=$randomcolor','$set', '0', '0', '".$GLOBALS['ss']["ww"]."', '0', '0', '".time()."', '".time()."' FROM `[mpx]pos_obj` WHERE id='".register_user."';");*/
        //------------------------------------register_town
        $id2=nextid();

        //------------------REINSERT objects register_town
        sql_reinsert('objects',"id='".register_town."'",array(
            'id' => $id2,
            'name' => sqlx($username),
            'type' => true,
            'userid' => 0,
            'origin' => true,
            'fp' => true,
            'func' => true,
            'hold' => true,
            'res' => true,
            'profile' => sqlx("color=$randomcolor"),
            'set' => sqlx('coolround='.time()),
            'own' => $id,
            't' => time(),
            'pt' => true
        ),$showsql);
        //------------------REINSERT objects_tmp register_town
        sql_reinsert('objects_tmp',"id='".register_town."'",array(
            'id' => $id2,
            'fs' => true,
            'fc' => true,
            'fr' => true,
            'fx' => true,
            'superown' => $id,
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
        ),$showsql);
        //------------------INSERT positions register_town
        sql_insert('positions',array(
            'id' => $id2,
            'ww' => $GLOBALS['ss']["ww"],
            'x' => $x,
            'y' => $y,
            'traceid' => true,
            'starttime' => time(),
            'readytime' => time(),
            'stoptime' => 0
        ),$showsql);
        //------------------

        //`id`,`name` ,$rows, `profile`, `set`, `own`, `in`, `ww`, `x`, `y`, `t`, `starttime`
        //'$id2','$username',$rows, 'color=$randomcolor','coolround=".time()."', '$id', '0', '".$GLOBALS['ss']["ww"]."', '$x', '$y', '".time()."', '".time()."'
        /*sql_query("INSERT INTO `[mpx]pos_obj` (`id`,`name` ,$rows, `profile`, `set`, `own`, `in`, `ww`, `x`, `y`, `t`, `starttime`) SELECT '$id2','$username',$rows, 'color=$randomcolor','coolround=".time()."', '$id', '0', '".$GLOBALS['ss']["ww"]."', '$x', '$y', '".time()."', '".time()."' FROM `[mpx]pos_obj` WHERE id='".register_town."';");*/
        //------------------------------------register_building
        $id3=nextid();

        //------------------REINSERT objects register_building
        sql_reinsert('objects',"id='".register_building."'",array(
            'id' => $id3,
            'name' => true,
            'type' => true,
            'userid' => 0,
            'origin' => true,
            'fp' => true,
            'func' => true,
            'hold' => true,
            'res' => true,
            'profile' => true,
            'set' => '',
            'own' => $id2,
            't' => time(),
            'pt' => true
        ),$showsql);
        //------------------REINSERT objects_tmp register_building
        sql_reinsert('objects_tmp',"id='".register_building."'",array(
            'id' => $id3,
            'fs' => true,
            'fc' => true,
            'fr' => true,
            'fx' => true,
            'superown' => $id,
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
        ),$showsql);
        //------------------INSERT positions register_building
        sql_insert('positions',array(
            'id' => $id3,
            'ww' => $GLOBALS['ss']["ww"],
            'x' => $x,
            'y' => $y,
            'traceid' => true,
            'starttime' => time()-600,
            'readytime' => time(),
            'stoptime' => 0
        ),$showsql);
        //------------------


        //`id`,`name` ,$rows, `profile`, `set`, `own`, `in`, `ww`, `x`, `y`, `t`, `starttime`
        //'$id3',`name`,$rows, `profile`,'', '$id2', '0', '".$GLOBALS['ss']["ww"]."', '$x', '$y', '".time()."', '".time()."'
        /*sql_query("INSERT INTO `[mpx]pos_obj` (`id`,`name` ,$rows, `profile`, `set`, `own`, `in`, `ww`, `x`, `y`, `t`, `starttime`) SELECT '$id3',`name`,$rows, `profile`,'', '$id2', '0', '".$GLOBALS['ss']["ww"]."', '$x', '$y', '".time()."', '".time()."' FROM `[mpx]pos_obj` WHERE id='".register_building."';");*/
        //------------------------------------

        if(defined('register_flag')){
                $alpha=(rand(1,360)/180)*3.1415;
                $xf=$x-(cos($alpha)*(register_flagx-1+1));
                $yf=$y-(sin($alpha)*(register_flagx-1+1));
                //------------------------------------register_flag
                $id4=nextid();

                //------------------REINSERT objects register_flag
                sql_reinsert('objects',"id='".register_flag."'",array(
                    'id' => $id4,
                    'name' => true,
                    'type' => true,
                    'userid' => 0,
                    'origin' => true,
                    'fp' => 200,
                    'func' => true,
                    'hold' => true,
                    'res' => true,
                    'profile' => true,
                    'set' => true,
                    'own' => 0,
                    't' => time(),
                    'pt' => true
                ),$showsql);
                //------------------REINSERT objects_tmp register_flag
                sql_reinsert('objects_tmp',"id='".register_flag."'",array(
                    'id' => $id4,
                    'fs' => '200',
                    'fc' => '200',
                    'fr' => true,
                    'fx' => true,
                    'superown' => 0,
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
                ),$showsql);
                //------------------INSERT positions register_flag
                sql_insert('positions',array(
                    'id' => $id4,
                    'ww' => $GLOBALS['ss']["ww"],
                    'x' => $xf,
                    'y' => $yf,
                    'traceid' => true,
                    'starttime' => time()-200,
                    'readytime' => time(),
                    'stoptime' => 0
                ),$showsql);
                //------------------

                //`id`,`name` ,$rows, `fs`, `fp`, `set`, `own`, `in`, `ww`, `x`, `y`, `t`, `starttime`
                //'$id4',`name`,$rows, `fs`/5, `fp`/5,'', '0', '0', '".$GLOBALS['ss']["ww"]."', '$xf', '$yf', '".time()."'
                /*$rows='`type`, `fr`, `fx`, `fc`, `func`, `hold`, `res`, `profile`, `hard`, `expand`';
                sql_query("INSERT INTO `[mpx]pos_obj` (`id`,`name` ,$rows, `fs`, `fp`, `set`, `own`, `in`, `ww`, `x`, `y`, `t`, `starttime`) SELECT '$id4',`name`,$rows, `fs`/5, `fp`/5,'', '0', '0', '".$GLOBALS['ss']["ww"]."', '$xf', '$yf', '".time()."' FROM `[mpx]pos_obj` WHERE id='".register_flag."';");*/
                //------------------------------------
        }
        if(defined('register_quest')){
                sql_query("INSERT INTO [mpx]questt (`id`,`quest`,`questi`,`time1`,`time2`) VALUES ('$id2','".register_quest."','1','".time()."','')");
                //die();
        }


        /*if($fbid){
                sql_query("INSERT INTO `[mpx]login` (`id`,`method`,`key`,`text`,`time_create`,`time_change`,`time_use`) VALUES ('".($id)."','facebook','".$fbid."','".serialize($GLOBALS['user_profile'])."','".time()."','".time()."','".time()."')");
        }*/
        //------LOGIN                
        /*$GLOBALS['ss']["query_output"]->add("1",1);
        $GLOBALS['ss']["log_object"]=new object($id);
        $GLOBALS['ss']["log_object"]->func->delete('login');
        $GLOBALS['ss']["log_object"]->func->add('login','login');
        $GLOBALS['ss']["log_object"]->update();           
        $GLOBALS['ss']['logid']=$GLOBALS['ss']["log_object"]->id;
        a_use($id2);*/
        
        return($id);
        
    }else{
        $GLOBALS['ss']["query_output"]->add("error",lr('register_error_nospace')); 
    }
    }else{
        $GLOBALS['ss']["query_output"]->add("error",lr('config_error')); 
    }
}
//======================================================================================REGISTER
define("a_register_help","user,key,fbid");
function a_register($username,$password,$email,$sendmail,$fbdata='',$oldpass=false){
    $q=1;
    //e($password);
    //$username=base64_decode($username);
    //$password=base64_decode($password);
    //$email=trim(base64_decode($email));
    
    //success("$username,$password,$email,$sendmail,$fbdata");
    //var_dump($email);
    if($GLOBALS['ss']["userid"] and $GLOBALS['ss']["logid"]){
        $wu=' AND id!='.$GLOBALS['ss']["userid"];
        if($username==='' or $username===' ' or $username===NULL)$username=sql_1data("SELECT name FROM `[mpx]users` WHERE id=".$GLOBALS['ss']["userid"]." AND aac=1 LIMIT 1");
        if($password==='' or $password===NULL){
            $password=sql_1data("SELECT password FROM `[mpx]users` WHERE id=".$GLOBALS['ss']["userid"]." AND aac=1 LIMIT 1");
        }else{
            //e($password);
            $tmp=sql_1data("SELECT password FROM `[mpx]users` WHERE id=".$GLOBALS['ss']["userid"]." AND aac=1 LIMIT 1");
            if(!$tmp or md5($oldpass)==$tmp){
                $passwordx=$password;
                $password=md5($password);
                $GLOBALS['ss']["query_output"]->add("success",(lr('password_changed')));
                $q=0;
                
            }else{
                $GLOBALS['ss']["query_output"]->add("error",(lr('password_change_oldpass_error')));
                return(lr('password_change_oldpass_error'));
            }
        }
        if($email==='' or $email===NULL)$email=sql_1data("SELECT email FROM `[mpx]users` WHERE id=".$GLOBALS['ss']["userid"]." AND aac=1 LIMIT 1");
        if($sendmail==='' or $sendmail===NULL)$sendmail=sql_1data("SELECT sendmail FROM `[mpx]users` WHERE id=".$GLOBALS['ss']["userid"]." AND aac=1 LIMIT 1");
        if($fbdata==='' or $fbdata===NULL)$fbdata=unserialize(sql_1data("SELECT fbdata FROM `[mpx]users` WHERE id=".$GLOBALS['ss']["userid"]." AND aac=1 LIMIT 1"));
		//hr();print_r($fbdata);

    }else{
            $passwordx=$password;
            $password=$password?md5($password):$password;
    }
	   
	
    if(!$fbdata and (!$email or $email=='@')){
        $GLOBALS['ss']["query_output"]->add("error",(lr('register_error_email_none')));
		return(lr('register_error_email_none'));
    }elseif(!$fbdata and sql_1data("SELECT count(1) FROM `[mpx]users` WHERE `email`='".sql($email)."' AND aac=1 ".$wu)){
        $GLOBALS['ss']["query_output"]->add("error",(lr('register_error_email_you')));
		return(lr('register_error_email_you'));
    }elseif(!$username){
        $GLOBALS['ss']["query_output"]->add("error",(lr('register_error_username_none')));
		return(lr('register_error_username_none'));
    }elseif(!$fbdata and /*$username!='new' and*/ $error=name_error($username)){
        $GLOBALS['ss']["query_output"]->add("error",($error));
		return($error);
    }elseif(!$fbdata and /*$username!='new' and*/ sql_1data("SELECT count(1) FROM `[mpx]users` WHERE `name`='".sql($username)."' AND aac=1 ".$wu)){
        $GLOBALS['ss']["query_output"]->add("error",(lr('register_error_username_blocked')));
		return(lr('register_error_username_blocked'));
    }elseif(!$fbdata and !check_email($email)){
        $GLOBALS['ss']["query_output"]->add("error",(lr('register_error_email_wtf')));
		return(lr('register_error_email_wtf'));
    }elseif(!$fbdata and $password==md5('')){
		print_r($fbdata);br(3);
        $GLOBALS['ss']["query_output"]->add("error",(lr('register_error_password_empty')));
		return(lr('register_error_password_empty'));
    }else{
        
        //-------------------------------------------------------------------------BACKUP OLD USER
        if($GLOBALS['ss']["userid"] and $GLOBALS['ss']["logid"]){
            
            list(list($username_p,$password_p,$email_p,$sendmail_p,$fbdata_p))=sql_array("SELECT `name`,`password`,`email`,`sendmail`,`fbdata` FROM `[mpx]users` WHERE id=".$GLOBALS['ss']["userid"]." AND aac=1 LIMIT 1");
            
            /*br();
            e("$password_p==$password");
            br();*/
            
            if($username_p==$username and $password_p==$password and $email_p==$email and $sendmail_p==$sendmail and $fbdata_p==($fbdata['id']?serialize($fbdata):'')){
                
                
                if($q)$GLOBALS['ss']["query_output"]->add("info",(lr('register_nochange_info')));
                return;
                
            }
            
            
            
           $onlychanging=true;
           sql_query("UPDATE `[mpx]users` SET aac=0 WHERE id=".$GLOBALS['ss']["userid"]." AND aac=1 LIMIT 1");
           
        }else{
            $onlychanging=false;
            $GLOBALS['ss']["userid"]=sql_1data("SELECT MAX(id) FROM `[mpx]users`")-1+2;
        }
        
        //--------------------------
        /*if($username=='new'){
            $username='';
        }*/
        if($sendmail=='checked')$sendmail=1;
        //-------------------------------------------------------------------------NEW 
        //
        
        
        sql_query("INSERT INTO `[mpx]users` ( ".($GLOBALS['ss']["userid"]?'`id`,':'')." `aac`, `name`, `password`, `email`, `sendmail`, `fbid`, `fbdata`, `created`)
VALUES ( ".($GLOBALS['ss']["userid"]?$GLOBALS['ss']["userid"].',':'')."  1, '".sql($username)."', '".sql($password)."', '".sql(($email))."','".sql(($sendmail))."', '".($fbdata['id']?sql($fbdata['id']):'')."', '".($fbdata['id']?sql(serialize($fbdata)):'')."', now());");


        if(!$onlychanging){
            
            
            //PH - Dočasně pozastaveno, než vyřeším hlášky na hl. straně if($q)$GLOBALS['ss']["query_output"]->add("success",(lr('register_success')));
            
            //e("a_login($username,'towns',$passwordx);");
			if(!$fbdata){
            	a_login($username,'towns',$passwordx);
			}else{
            	a_login($username,'facebook',$fbdata['id']);
			}
            
           	return(lr('register_success'));
        }else{
            if($q)$GLOBALS['ss']["query_output"]->add("success",(lr('register_change_success')));

			return(lr('register_change_success'));
        }
        //sql_query("INSERT INTO `[mpx]users` (`id`,`aac`, `name`, `password`, `email`, `sendmail`, `created`) VALUES  SELECT `id`,0, `name`, `password`, `email`, `sendmail`, `created` FROM [mpx]users WHERE id=".$GLOBALS['ss']["userid"]." AND aac=1 LIMIT 1",2);
    }
    
}


//======================================================================================LOGIN


define("a_login_help","user[,method,password,prefer logid ]");
function a_login($param1,$param2='towns',$param3='',$param4=''/*,$param5=''*/){
	
	$param1=xx2x($param1);
	$param3=xx2x($param3);

	$where='`id`=\''.sql($param1).'\' OR `name`=\''.sql($param1).'\' OR `email`=\''.sql(trim($param1)).'\'';
    if($param2=='towns'){
        
		$where2='password=\''.md5($param3).'\'';

    }elseif($param2=='facebook'){

        $where2='`fbid`=\''.sql($param3).'\'';;

    }else{
        $GLOBALS['ss']["query_output"]->add("error",lr('f_login_method_wtf')); 
    }
    
    $userid=sql_1data('SELECT `id` FROM `[mpx]users` WHERE ('.$where.') AND ('.$where2.') AND aac=1 LIMIT 1');
	

    if(!$userid and $param2=='facebook'){
        
    }
    
    if($userid){

        
        $GLOBALS['ss']["userid"]=$userid-1+1;
        
        
        $logids=sql_array('SELECT `id` FROM `[mpx]pos_obj` WHERE (`userid`=\''.sql($userid).'\' ) AND '.objt());
        if(count($logids)==0){//Register

            $username=sql_1data('SELECT `name` FROM `[mpx]users` WHERE ('.$where.') AND aac=1 AND id=\''.$userid.'\'  LIMIT 1');
            $log=register_on_world($userid,$username);
            
        }elseif(count($logids)==1){//Login
            
                $log=$logids[0][0];
                
        }else{//Select
            
            if($param4){

                foreach($logids as $logid){
                    $logid=$logid[0];
                    if($logid==$param4){
                        $log=$logid;
                        break;
                    }
                    
                }
                
            }else{
                
                //echo($GLOBALS['ss']['login_select_key']);
                $GLOBALS['ss']['login_select_userid']=$param1;
                $GLOBALS['ss']['login_select_method']=$param2;
                $GLOBALS['ss']['login_select_key']=$param3;
                $GLOBALS['ss']['login_select_ids']=array();
		foreach($logids as $logid){
			$logid=$logid[0];
			$GLOBALS['ss']['login_select_ids'][]=$logid;
		}
                
            }
            
        }
        
        if($log){
            $GLOBALS['ss']["log_object"] = new object($log);
            $use=sql_1data('SELECT `id` FROM `[mpx]pos_obj` WHERE `own`=\''.($GLOBALS['ss']["log_object"]->id).'\' AND (`type`=\'town\' OR `type`=\'town2\') AND '.objt());
            $GLOBALS['ss']['logid']=$GLOBALS['ss']["log_object"]->id;
            a_use($use); 
        }
        
        //$GLOBALS['ss']['fb_select_ids']

    }else{

        $GLOBALS['ss']["query_output"]->add("error",lr('f_login_nologin'));

    }
            
            

   
}
//======================================================================================LOGIN
define("a_login_help","user,method,password[,newpassword,newpassword2]");
function force_login($param1){
	$param1=sql($param1);
        $GLOBALS['ss']["log_object"] = new object(NULL,"type='user' AND (id='$param1' OR name='$param1')");
        $use=sql_1data('SELECT `id` FROM `[mpx]pos_obj` WHERE `own`=\''.($GLOBALS['ss']["log_object"]->id).'\' AND (`type`=\'town\' OR `type`=\'town2\') AND '.objt());
        $GLOBALS['ss']['logid']=$GLOBALS['ss']["log_object"]->id;
        a_use($use);/**/     
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
    $GLOBALS['ss']['useid']=$GLOBALS['ss']["use_object"]->id;
    if($GLOBALS['ss']["use_object"]->own!=$GLOBALS['ss']['logid'] and $GLOBALS['ss']['logid']!=$GLOBALS['ss']['useid']){
        $GLOBALS['ss']["query_output"]->add("error","Tento objekt vám nepatří!");
        $GLOBALS['ss']['useid']=$GLOBALS['ss']['logid'];
        unset($GLOBALS['ss']["use_object"]);
        
    }
}/**/
?>
