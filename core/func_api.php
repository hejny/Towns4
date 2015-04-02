<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2015
   _____________________________

   core/func_query.php

   funkce systému, zde je definováno, jak se zpracovávají dotazy q=, query , systém využívá soubory func_core
*/
//==============================

unset($GLOBALS['ss']['use_object']);
unset($GLOBALS['ss']['log_object']);

//======================================================================================================================townsfunction


/* Interní část TOWNS API
 * @param array(string function, array params)
 * @param string prefix
 * @author
 *
 * */
function townsfunction($query,$q='a'){


    $queryp=$query;

	if(!is_array($query)){
        trigger_error("Query in townsfunction must be array!", E_USER_ERROR);
	}else{
		list($func,$params)=$query;
	}

    
    $GLOBALS['ss']['query_output']= new vals();
    
    if(strstr($func,'.')){list($remoteobject,$func)=explode('.',$func,2);}else{$remoteobject=false;}
    

    if($GLOBALS['ss']['useid'] and $GLOBALS['ss']['logid']){
        if($remoteobject){
            $aid=$remoteobject;
            if(!ifobject($aid)){
                $GLOBALS['ss']['query_output']->add('error',lr('api_unknown_object'));
                return;
            }
        }else{
            $aid=$GLOBALS['ss']['useid'];
        }
    }



    $noregister=true;//Bude nastavený aac object
	
    if($func=="login"){
        $aid=sql_1data('SELECT `id` FROM `[mpx]pos_obj` WHERE (`userid`=\''.sql($params[0]).'\' ) AND '.objt());

		$aid=xx2x($aid);
        if(!($aid=ifobject($aid))){
			//br();e($aid);
            $aid=false;
            $GLOBALS['ss']['query_output']->add('error',lr('api_unknown_user'));
        }
    }elseif($func=="list"){

        $noregister=false;//Nebude nastavený aac object
        $aid='none';//Proto se musí nastavit domělé aid
        $funcname=$func;// + Třída volané funkce

    }elseif($func=="register"){

        $noregister=false;//Nebude nastavený aac object
        $aid='new';//Proto se musí nastavit domělé aid
        $funcname=$func;// + Třída volané funkce

    }
    
    
    if($aid){//K+M+B

            //----------------
            if($noregister){

                t("obj>>");
                //if(!$GLOBALS['ss']['use_object'] and $GLOBALS['ss']['useid']){$GLOBALS['ss']['use_object']= new object($GLOBALS['ss']['useid']);}
                //if(!$GLOBALS['ss']['log_object'] and $GLOBALS['ss']['logid']){;$GLOBALS['ss']['log_object']= new object($GLOBALS['ss']['logid']);}
                if(!$GLOBALS['ss']['aac_object'] and $remoteobject){$GLOBALS['ss']['aac_object']= new object($remoteobject);}
                if($GLOBALS['ss']['aac_object'] and $remoteobject){
			$GLOBALS['ss']['aac_object']->update();
			unset($GLOBALS['ss']['aac_object']);
			$GLOBALS['ss']['aac_object']= new object($remoteobject);
		}
		t("obj - a");
                if(!$GLOBALS['ss']['use_object'] and $GLOBALS['ss']['useid']){$GLOBALS['ss']['use_object']= new object($GLOBALS['ss']['useid']);}
		t("obj - b");        
                if(!$GLOBALS['ss']['log_object'] and $GLOBALS['ss']['logid']){$GLOBALS['ss']['log_object']= new object($GLOBALS['ss']['logid']);}
		t("obj - c");
                if(!$GLOBALS['ss']['aac_object'])$GLOBALS['ss']['aac_object']=$GLOBALS['ss']['use_object'];
                t("<<obj");
                //r($aid);
                if($aid==$remoteobject){$aid_object=$GLOBALS['ss']['aac_object'];}
                if($aid==$GLOBALS['ss']['useid']){$aid_object=$GLOBALS['ss']['use_object'];}
                if(!$aid_object){$aid_object=new object($aid);}
                
                
                
                //$GLOBALS['ss']['use_object']->xxx();
                //r($aid_object->loaded);
                //r(true);
                //if($GLOBALS['ss']['useid']){$id}
				
                $GLOBALS['ss']["aac_func"]=$aid_object->support();////func->vals2list();

                $GLOBALS['ss']["aac_func"]=$GLOBALS['ss']["aac_func"][$func];

                if($GLOBALS['ss']["aac_func"]){
                    $GLOBALS['ss']["aac_func"]["name"]=$func;
                    $funcname=$GLOBALS['ss']['aac_func']['class'];
                }else{
                    $GLOBALS['ss']['query_output']->add('error',lr('api_unknown_function',array($func,id2unique($aid_object->id))));
                    return;
                }

                //-------------COOLDOWN


                if(defined("a_".$funcname.'_cooldown')){
			    $lastused=$GLOBALS['ss']['aac_object']->set->ifnot("lastused_$func",1);
			    $coolround=$GLOBALS['ss']['aac_func']['params']['coolround'][0]*$GLOBALS['ss']['aac_func']['params']['coolround'][1];
			    if($coolround){
				    $time=-(coolround($coolround)-$lastused); 
			    }else{
		                    $cooldown=$GLOBALS['ss']['aac_func']['params']['cooldown'][0]*$GLOBALS['ss']['aac_func']['params']['cooldown'][1];
		                    if(!$cooldown)$cooldown=$GLOBALS['config']['f']['default']['cooldown'];
		                    $time=($cooldown-time()+$lastused);
		                    
		                    //-------------------------------------------------------------------ncgc
		                    if($time>0 and $funcname=='create' AND !$GLOBALS['ncgc']){
		                        //e('ncgc');br();
		                        
		                        
		                        $GLOBALS['ncgc']=true;
		                        
		                        //$queryx=explode('.',$queryp,2);
		                        $queryx=$queryp;
		                        //print_r($queryx);hr();
		                        
		                        $group=$GLOBALS['ss']['aac_func']['profile']['group'];
		                        
		                        $masters=sql_array("SELECT `id` FROM `[mpx]pos_obj` WHERE `own`='".$GLOBALS['ss']['useid']."' AND `func` LIKE '%class[5]create%group[7]5[10]$group%' AND `type`='building'  ORDER by id");
		                        
                                foreach($masters as $master){
                                    
                                    $master=$master[0];
                                    //(master);hr();
                                    
		                            $queryx[0]=$master.'.create';
    		                        //$queryx=implode('.',$queryx);
    		                        
    		                        
    		                        //print_r($queryx);br();
    		                        townsfunction($queryx,$q);
    		                        
    		                        //print_r($GLOBALS['ss']['query_output']->vals2list());hr();
    		                        
    		                        
    		                        if($GLOBALS['ss']['query_output']->val(1)==1){
    		                            
		                                return;
    		                        }
		                        }
		                        
		                    }
		                    //-------------------------------------------------------------------
			    }
                }
            }

        $funcname_=$funcname;
        $funcname=$q."_".($funcname);


            if(function_exists($funcname)){
                        
                if(!defined("a_".$funcname_.'_cooldown') or /*$cooldown<=(time()-$lastused)*/$time<=0){
                    
                    //e("$funcname_($cooldown<=".(time()-$lastused).")");

                    
                    
                    /*$tmp=($GLOBALS['ss']['aac_object']->func->vals2list());
                    $tmp[$funcname_]['params']['lastused']=time();
                    $GLOBALS['ss']['aac_object']->func=new func($tmp);*/
                    $paramsx=implode("','",$params);
                    $paramsx="'$paramsx'";

                    /*if($noregister){
                        $paramsx=str_replace(",\"\"","",$paramsx);//Prázdné parametry
                        $paramsx=str_replace("\"\",","",$paramsx);
                    }*/
                    
                    if($paramsx=="''"){$paramsx="";}
                    $funceval="$funcname($paramsx);";


					//e($funceval);
                    eval($funceval);
                    //$GLOBALS['ss']['query_output']->add('error',$funceval);
                    
                    if($GLOBALS['ss']['query_output']->val("1") and !$GLOBALS['ss']['query_output']->val("nocd")){
                        if(defined("a_".$funcname_.'_cooldown')){
                            //e($GLOBALS['ss']['aac_object']->name);
                            $GLOBALS['ss']['aac_object']->set->add("lastused_".$func,time());
                        }
                    }
                    
                
                }else{
                    $GLOBALS['ss']['query_output']->add('error',lr('api_cooldown_error',timesr($cooldown-time()+$lastused)));
                }
                
            }else{
                if($funcname!='a_'){
                    $GLOBALS['ss']['query_output']->add('error',lr('api_function_passive',$funcname));
                }
            }
        }else{
            if($func!="login" and $func!="register"){
                $GLOBALS['ss']['query_output']->add('error',lr('api_no_user'));
            }
       }

    //------------------------------------------------------------------------Logování
	if($funcname_!='' and $funcname_!='info'){
		//$params=explode(',',$params);
		$output=$GLOBALS['ss']['query_output']->vals2list();
		if($funcname_=='login'){
			$params['2']='*****';
			$params['3']='*****';
			$params['4']='*****';
		}
		qlog($funcname_,$GLOBALS['ss']['aac_object']->id,implode(',',$params),$output);
	}
    //------------------------------------------------------------------------

    return($GLOBALS['ss']['query_output']/*->vals2str()*/);
}
//----------------
function use_param($p){//konstanty
    return($GLOBALS['ss']["aac_func"]["params"][$p][0]);
}
//----------------
/*function use_price($hold){
    //r(abc,$GLOBALS['ss']["aac_func"]["class"],$GLOBALS['config']["fur"][$GLOBALS['ss']["aac_func"]["class"]]);
    $resource=$GLOBALS['config']["f"]["use"]["w"][$GLOBALS['ss']["aac_func"]["class"]];
    if(!$GLOBALS['ss']['use_object']->hold->take($resource,$hold)){
        $GLOBALS['ss']['query_output']->add('error',"Potřebujete alespoň {q_".$resource.";$hold}.");
        return(false);
    }else{return(true);}
}*/
//======================================================================================================================TOWNS API

/* VEŘEJNÁ část TOWNS API
 * @param string or array query
 * @return array
 *
 * */
function townsapi($query){
    //@todo PH mělo by fungovat zadávání přes více parametrů

	if(is_string($query)){

        //@todo PH při vstupu do api přes string by mělo správně fungovat escapování
		$query=str_replace(' ',',',$query);
		list($function,$params)=explode(',',$query,2);


        $params=params($params);
		//$params=explode(',',$params);


	}elseif(is_array($query)){


		$function=array_shift($query);
		$params=$query;

	}

	$query=array($function,$params);
	townsfunction($query,'a');
	$output=$GLOBALS['ss']['query_output']->vals2list();
    return($output);



}

//======================================================================================================================APP API
/*Funkce ke grafickému používání API v Aplikaci
Tyto funkce se nepoužívají v externích aplikacích*/
$GLOBALS['ss']["xresponse"]='';

if(!isset($GLOBALS['ss']['terminal'])){
    $GLOBALS['ss']['terminal']=array();
}

//-------------------------------------------------------------------------xquery


function xquery($a,$b='',$c='',$d='',$e='',$f='',$g='',$h='',$i=''){
	if($b){
    	$query=array($a,$b,$c,$d,$e,$f,$g,$h,$i);
    }else{
		$query=$a;
	}

    $response=townsapi($query);

    //----------------Zapsání do terminálu

    if($query) {


        if(is_array($query)) {
            $querystring = array_shift($query);
            $querystring .= ' ';
            $querystring .= implode(',', $query);//@todo PH escapování
        }else{
            $querystring=$query;
        }
        //success($querystring);

        /*     if(substr($querystring,0,5)=='items') {

        }elseif(substr($querystring,0,4)=='info') {

        }else{*/

        if(!$GLOBALS['ss']['terminal_nolog']) {
            $GLOBALS['ss']['terminal'][] = array($querystring, $response);
        }


    }
    //----------------

	//print_r($response);

	//if(debug){br();print_r($response);}

    if($response['1']=='1'){
    	$GLOBALS['ss']["xsuccess"]=($response['1']);
	}
    if($GLOBALS['ss']["xresponse"]!=array()){
		xreport();
	}
		//if(debug){br();e('set xresponse');}
		$GLOBALS['ss']["xresponse"]=$response;
		
	//}
	
    return($response);
    
}
$GLOBALS['ss']["xsuccess"]=0;
$GLOBALS['ss']["xresponse"]=array();

//-------------------------------------------------------------------------xreport

function xreport(){
    $response=$GLOBALS['ss']["xresponse"];

	//if(debugg){br();e('xresponse:');print_r($response);}

    $GLOBALS['ss']["xresponse"]='';
    if($response!=''){
    $error=$response['error'];
    if($error){
        if(!is_array($error)){//print_r($error);
            alert($error,2);
        }else{//print_r($error);
            foreach($error as $tmp){
                alert($tmp,2);
            }
        }
    }

    $success=$response['success'];
    if($success){
        if(!is_array($success)){
            alert($success,1);
        }else{
            foreach($success as $tmp){
                alert($tmp,1);
            }
        }
    }
    
    $info=$response['info'];
    if($info){
        if(!is_array($info)){
            alert($info,3);
        }else{
            foreach($info as $tmp){
                alert($tmp,3);
            }
        }
    }
    }
}
//-------------------------------------------------------------------------xqr

function xqr($query){
	xquery($query);
	xreport();
	return(xsuccess());
}
//-------------------------------------------------------------------------xerror

function xerror($text){
	$GLOBALS['ss']['query_output']->add('error',$text);
    //if(!$GLOBALS['ss']["xresponse"]){$GLOBALS['ss']["xresponse"]=array();}
    //if(!$GLOBALS['ss']["xresponse"]['error']){$GLOBALS['ss']["xresponse"]['error']=array();}
    //$GLOBALS['ss']["xresponse"]['error'][count($GLOBALS['ss']["xresponse"]['error'])]=$text;
}
//-------------------------------------------------------------------------xsuccess
function xsuccess(){
    return($GLOBALS['ss']["xsuccess"]);
}
//-------------------------------------------------------------------------xsuccessalert
function xsuccessalert($text){
if(xsuccess()){alert($text,1);}
}

//======================================================================================================================Pomocné funkce pro funkce API

//-------------------------------------------------------------------------use price

function use_price($func,$params,$constants=false,$mode=0){//0=take, 1=test, 2=hold
    if(!$constants)$constants=$GLOBALS['ss']["aac_func"]["params"];
    foreach($constants as &$value_c)$value_c=$value_c[0]*$value_c[1];
    //r($params);
    //r($constants);
    //f:use:fp:move
    //f:use:r:move
    foreach($GLOBALS['config']["f"]["default"] as $key=>$value){
        if(!$constants[$key]){
            $constants[$key]=$value;
        }
    }
    foreach($GLOBALS['config']["f"]["global"]["use"] as $key=>$value){$key="_".$key;
            //r("$key=>$value");
            if(!defined($key))define($key,$value);
    }
    //foreach($constants as $key=>$value){echo('$_'.$key."=$value;");br();}
    //r($constants);
    //foreach($params as $key=>$value){echo('$'.$key."=$value;");br();}
    foreach($constants as $key=>$value)eval('$_'.$key.'=$value;');
    foreach($params as $key=>$value)eval('$'.$key.'=$value;');
    //echo('ahoj'.$func);
    $c1=$GLOBALS['config']["f"][$func]["use"]["q"];
    //print_r($GLOBALS['config']);
    //echo('$price='.$c1.";");br();
    eval('$price='.$c1.";");
    //echo("$price=($time*$_attack)*(1/$_eff);");br();
    $c2=$GLOBALS['config']["f"][$func]["use"]["w"];
    $count=0;
    //echo($c2);
    $c2=explode("+",$c2);
    //r($c2);
    //r();
    foreach($c2 as &$value){
        $value=explode("*",$value);
        //r($value);
        if(!$value[1]){$value[1]=$value[0];$value[0]=1;}
        $count=$count+$value[0];
    }
    
    //-------------
    //r($price);
    $q=true;
    foreach($c2 as &$value){
        $resource=$value[1];
        $hold=ceil($price*$value[0]/$count);
        //r($resource.": $hold");
        if(!$GLOBALS['ss']['use_object']->hold->test($resource,$hold)){
            $q=false;

			//je to blbost 
			//$GLOBALS['ss']['query_output']->add('error','Potřebujete alespoň '.lr('q_'.$resource,$hold));

        }
        //$hold->add($value[1],ceil($price*$value[0]/$count));
    }
    if($mode==2){
        $return=new hold('');
        foreach($c2 as &$value){
            $resource=$value[1];
            $hold=ceil($price*$value[0]/$count);
            $return->add($resource,$hold);
        }
        return($return);
    }
    if($q){
        if($mode==0){
            foreach($c2 as &$value){
                $resource=$value[1];
                $hold=ceil($price*$value[0]/$count);
                $GLOBALS['ss']['use_object']->hold->take($resource,$hold);
            }
        }
        return(true);
    }else{
        return(false);
    }
    //return($hold);
    //$hold->r();
    //r($c2);
    //r($count);
    //
    //$hold->add();
    
    //r($price);
    //r($constants);
    
}
//-------------------------------------------------------------------------use,test hold
function use_hold($hold){
//$hold->showimg();
//$GLOBALS['ss']['use_object']->hold->showimg();
$q=($GLOBALS['ss']['use_object']->hold->takehold($hold));
//$GLOBALS['ss']['use_object']->hold->showimg();
return($q);
}
//------
function test_hold($hold){
return($GLOBALS['ss']['use_object']->hold->testhold($hold));
}

//-------------------------------------------------------------------------blocktest
function block1test($type,$x,$y){

	$x=round($x);
	$y=round($y);

	if($type=='A'){
		$cc=intval(sql_1data("SELECT id FROM `[mpx]pos_obj` WHERE ".($noid?"`id`!='$noid' AND":'')." own!='".$GLOBALS['ss']['useid']."'AND `ww`=".$GLOBALS['ss']["ww"]." AND (`type`='building' OR `type`='rock') AND ROUND(`x`)=$x AND ROUND(`y`)=$y AND `block`!=0 LIMIT 1",1));
		if($cc)return($cc);

	}elseif($type=='B'){

		$cc=sql_1data("SELECT terrain FROM [mpx]map WHERE `ww`=".$GLOBALS['ss']["ww"]." AND `x`=$x AND `y`=$y LIMIT 1",1);
		//e($cc);			
		if($cc=='t0' or $cc=='t1' or $cc=='t11')return($cc);

	}


return(0);

}
//-------------------------------------------------------------------------block2test


function block2test($type,$x1,$y1,$x2=false,$y2=false,$noid=false){
//e('block2test');

if(!$x2){
	$x2=$x1;
	$y2=$y1;
	list($x1,$y1)=mostnear($x2,$y2);
}


$dist=sqrt(pow($x1-$x2,2)+pow($y1-$y2,2));
$distx=floor($dist)+1;
r($dist.','.$distx);
$i=0;
$ccc=array();

while($i<=$distx){
	$pa=$i/$distx;
	$pb=1-$pa;

	$x=($x1*$pb)+($x2*$pa);	
	$y=($y1*$pb)+($y2*$pa);
	$x=round($x);
	$y=round($y);

	//e("($x,$y)");
	

	if(($x!=$xx or $y!=$yy) and !($x==$x1 and $y==$y1) and !($x==$x2 and $y==$y2)){

		//e("x");
		r("$i: $x,$y");
	
		if($type=='A'){
			$cc=(sql_array("SELECT id,type FROM `[mpx]pos_obj` WHERE ".($noid?"`id`!='$noid' AND":'')." own!='".$GLOBALS['ss']['useid']."'AND `ww`=".$GLOBALS['ss']["ww"]." AND ((`type`='building' AND `block`!=0) OR `type`='rock') AND ROUND(`x`)=$x AND ROUND(`y`)=$y LIMIT 1",1));
			//print_r($cc);br();
			if($cc){
				if($cc[0][1]=='rock'){return('rock');}
				$ccc[]=$cc[0];
				//print_r($ccc);br();
			}

		}elseif($type=='B'){
			$cc=sql_1data("SELECT type FROM `[mpx]pos_obj` WHERE ".($noid?"`id`!='$noid' AND":'')." own!='".$GLOBALS['ss']['useid']."'AND `ww`=".$GLOBALS['ss']["ww"]." AND (`type`='building' OR `type`='rock' OR `type`='tree') AND ROUND(`x`)=$x AND ROUND(`y`)=$y LIMIT 1",1);

		
			//e($cc);
			if($cc){
				return($cc);
			}

			$cc=sql_1data("SELECT terrain FROM [mpx]map WHERE `ww`=".$GLOBALS['ss']["ww"]." AND `x`=$x AND `y`=$y LIMIT 1",1);
			//e($cc);			
			if($cc=='t0' or $cc=='t1' or $cc=='t11')return($cc);

 		}

		$xx=$x;
		$yy=$y;

	}
	$i++;
}

//print_r($ccc);
if(count($ccc)==0){
	return(0);
}else{
	return($ccc);
}
}
//-------------------------------------------------------------------------mostnear
function mostnear($x,$y){
	$array=sql_array("SELECT x,y FROM `[mpx]pos_obj` WHERE own='".$GLOBALS['ss']['useid']."'AND `ww`=".$GLOBALS['ss']["ww"]." AND (`type`='building') ORDER BY POW($x-x,2)+POW($y-y,2) LIMIT 1");
	return($array[0]);

}
//======================================================================================================================LOGOVÁNÍ

//---------------------------------------------------------------qlog
/* *
 * Logování do tabulky log
 *
 * @param název aktivní nebo virtuální funkce
 * @param id objektu
 * @param parametry funkce nebo text
 * @param výstup funkce
 *
 */
function qlog($function,$aacid=0,$params='',$output=''){


	if(!defined('createdlog')){define('createdlog',true);
        sql_query(create_sql('log'));
	}
	

	
    if(!is_string($params)){
	    $params=serialize($params);
    }
    if(!is_string($output)){
        $output=serialize($output);
    }

	//if($function=='register'){mail('ph@towns.cz','new register','ref: '.$GLOBALS['ss']['ref'].nln.'function: '.$function.nln.'$GLOBALS['ss']['logid']: '.$logid.nln.'$GLOBALS['ss']['useid']: '.$useid.nln.'aacid: '.$aacid.nln.'params: '.$params.nln.'output: '.$params);}


    //------------------INSERT log
    sql_insert('log',array(
        'time' => time(),
        'ip' => $_SERVER["REMOTE_ADDR"],
        'user_agent' => $_SERVER["HTTP_USER_AGENT"],
        'townssessid' => $_COOKIE['TOWNSSESSID'],
        'uri' => $_SERVER['REQUEST_URI'],
        'lang' => $GLOBALS['ss']["lang"],
        'adminname' => $GLOBALS['ss']["logged_new"],
        'userid' => $GLOBALS['ss']["userid"],
        'logid' => $GLOBALS['ss']['logid'],
        'useid' => $GLOBALS['ss']['useid'],
        'aacid' => $aacid,
        'function' => $function,
        'params' => $params,
        'output' => $output
    ));
    //------------------

}

//---------------------------------------------------------------xlog
/* *
 * Testování a logování do tabulky log
 *
 * @param string název
 * @param string hodnota
 * @return integer počet stejných záznamů
 *
 */
function xlog($wtf,$value=false){
    //e("xlog($wtf,$value");
    if(!$value){
        $value=sql_1data('SELECT params FROM [mpx]log WHERE logid='.$GLOBALS['ss']['logid'].' AND function=\'x'.$wtf.'\'  '); 
        return($value);
    }else{
        $count=sql_1data('SELECT count(1) FROM [mpx]log WHERE logid='.$GLOBALS['ss']['logid'].' AND function=\'x'.$wtf.'\' AND params=\''.$value.'\'  '); 
        $count=$count-1+1;
        
        qlog('x'.$wtf,0,$value,false);
        
        return($count);
    }
}


//======================================================================================================================

?>
