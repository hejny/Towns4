<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/func_query.php

   funkce systému, zde je definováno, jak se zpracovávají dotazy q=, query , systém využívá soubory func_core
*/
//==============================



//======================================================================================townsfunction

unset($GLOBALS['ss']["use_object"]);
unset($GLOBALS['ss']["log_object"]);
//r($GLOBALS['ss']["useid"]);
//r($GLOBALS['ss']["logid"]);
function townsfunction($query,$q){$queryp=$query;
	
    //r($query);
    //print_r($query);
    //if(is_string($query)){echo($query);
	if(is_string($query)){
		die('!querystring');        
		/*$query=str_replace(' ',',',$query);
        $query=explode(",",$query,2);
		list($func,$params)=$query;*/
	}else{
		list($func,$params)=$query;
	}
	//if(debug)print_r($query);
    //}
    
    $GLOBALS['ss']["query_output"]= new vals();
    
    if(strstr($func,'.')){list($remoteobject,$func)=explode('.',$func,2);}else{$remoteobject=false;}
    
    //r("$remoteobject , $func , $params");
    //$params=str_replace(" ",",",$params);
    //$params=explode(",",$params);
    if($GLOBALS['ss']["useid"] and $GLOBALS['ss']["logid"]){
        if($remoteobject){
            $aid=$remoteobject;
        }else{
            $aid=$GLOBALS['ss']["useid"];
        }
    }
	//r($aid);

	
    if($func=="login"){//list($aid)
        $aid=$params[0];//explode(",",$params);
		//if(debug){br();print_r($params);br();e($aid);}
		//("($aid)");
		$aid=xx2x($aid);
        if(!($aid=ifobject($aid))){
			//br();e($aid);
            $aid=false;
            $GLOBALS['ss']["query_output"]->add("error","Tento uživatel neexistuje.");
        }
    }
    if($func=="register"){
        $aid=$params[0];//explode(",",$params);
        $funcname='register';
        $noregister=false;
    }else{
        $noregister=true;
    }
    
    
    if($aid){//K+M+B

            //----------------
            if($noregister){

                t("obj>>");
                //if(!$GLOBALS['ss']["use_object"] and $GLOBALS['ss']["useid"]){$GLOBALS['ss']["use_object"]= new object($GLOBALS['ss']["useid"]);}
                //if(!$GLOBALS['ss']["log_object"] and $GLOBALS['ss']["logid"]){;$GLOBALS['ss']["log_object"]= new object($GLOBALS['ss']["logid"]);}
                if(!$GLOBALS['ss']["aac_object"] and $remoteobject){$GLOBALS['ss']["aac_object"]= new object($remoteobject);} 
                if($GLOBALS['ss']["aac_object"] and $remoteobject){
			$GLOBALS['ss']["aac_object"]->update();
			unset($GLOBALS['ss']["aac_object"]);
			$GLOBALS['ss']["aac_object"]= new object($remoteobject);    
		}
		t("obj - a");
                if(!$GLOBALS['ss']["use_object"] and $GLOBALS['ss']["useid"]){$GLOBALS['ss']["use_object"]= new object($GLOBALS['ss']["useid"]);}
		t("obj - b");        
                if(!$GLOBALS['ss']["log_object"] and $GLOBALS['ss']["logid"]){$GLOBALS['ss']["log_object"]= new object($GLOBALS['ss']["logid"]);}
		t("obj - c");
                if(!$GLOBALS['ss']["aac_object"])$GLOBALS['ss']["aac_object"]=$GLOBALS['ss']["use_object"];
                t("<<obj");
                //r($aid);
                if($aid==$remoteobject){$aid_object=$GLOBALS['ss']["aac_object"];}
                if($aid==useid){$aid_object=$GLOBALS['ss']["use_object"];}
                if(!$aid_object){$aid_object=new object($aid);}
                
                
                
                //$GLOBALS['ss']["use_object"]->xxx();
                //r($aid_object->loaded);
                //r(true);
                //if($GLOBALS['ss']["useid"]){$id}
				
                $GLOBALS['ss']["aac_func"]=$aid_object->support();////func->vals2list();
		//r($aid_object->id);
		//r($GLOBALS['ss']['aac_func']);
                $GLOBALS['ss']["aac_func"]=$GLOBALS['ss']["aac_func"][$func];
                $GLOBALS['ss']["aac_func"]["name"]=$func;
            
		
                $funcname=$GLOBALS['ss']['aac_func']['class'];
                //r($GLOBALS['ss']['aac_func']);
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
		                        e('ncgc');br();
		                        
		                        
		                        $GLOBALS['ncgc']=true;
		                        
		                        //$queryx=explode('.',$queryp,2);
		                        $queryx=$queryp;
		                        print_r($queryx);hr();
		                        
		                        $group=$GLOBALS['ss']['aac_func']['profile']['group'];
		                        
		                        $masters=sql_array("SELECT `id` FROM [mpx]objects WHERE `own`='".useid."' AND `func` LIKE '%class[5]create%group[7]5[10]$group%' AND `type`='building'  ORDER by id",2);
		                        
                                foreach($masters as $master){
                                    
                                    $master=$master[0];
                                    //(master);hr();
                                    
		                            $queryx[0]=$master.'.create';
    		                        //$queryx=implode('.',$queryx);
    		                        
    		                        
    		                        print_r($queryx);br();
    		                        townsfunction($queryx,$q);
    		                        
    		                        print_r($GLOBALS['ss']['query_output']->vals2list());hr();
    		                        
    		                        
    		                        if($GLOBALS['ss']['query_output']->val(1)==1){
    		                            
		                                return;
    		                        }
		                        }
		                        
		                    }
		                    //-------------------------------------------------------------------
			    }           
                            /*if($time>0){
                                $countdown=$time;
                            }*/
                    /*$cooldown=$GLOBALS['ss']['aac_func']['params']['cooldown'][0]*$GLOBALS['ss']['aac_func']['params']['cooldown'][1];
                    if(!$cooldown)$cooldown=$GLOBALS['config']['f']['default']['cooldown'];
                    $lastused=$GLOBALS['ss']['aac_object']->set->ifnot("lastused_$func",1);
    
                    //r($cooldown.' / '.$lastused);*/
                }



                //r($GLOBALS['ss']['aac_func']);
                //r($GLOBALS['config']);
                //-------------
                
                /*r($GLOBALS['ss']["aac_object"]->func->vals2list()); 
                $a=($GLOBALS['ss']["aac_object"]->func->vals2list());     
                $a=new func($a);
                hr();
                r($a->vals2list());        
                hr();hr();*/
                
            }
            //r($GLOBALS['ss']["aac_func"]);
            
            
            //r($GLOBALS['ss']["aac_func"]);
            //----------------

        $funcname_=$funcname;
        $funcname=$q."_".($funcname);
        
        if(/*$func=="login" or */$GLOBALS['ss']["aac_func"] or !$noregister){
		
            if(function_exists($funcname)){
                        
                if(!defined("a_".$funcname_.'_cooldown') or /*$cooldown<=(time()-$lastused)*/$time<=0){
                    
                    //e("$funcname_($cooldown<=".(time()-$lastused).")");

                    
                    
                    /*$tmp=($GLOBALS['ss']["aac_object"]->func->vals2list());
                    $tmp[$funcname_]['params']['lastused']=time();
                    $GLOBALS['ss']["aac_object"]->func=new func($tmp);*/
                    
                    $paramsx=implode(',',$params);
                    $paramsx=str_replace(",","\",\"",$paramsx);
                    $paramsx="\"$paramsx\"";
                    $paramsx=str_replace(",\"\"","",$paramsx);//Prázdné parametry
                    $paramsx=str_replace("\"\",","",$paramsx);
                    if($paramsx=="\"\""){$paramsx="";}
                    $funceval="$funcname($paramsx);";
                    //r($funceval);
					//e('eval');
                    eval($funceval);
                    
                    if($GLOBALS['ss']["query_output"]->val("1") and !$GLOBALS['ss']["query_output"]->val("nocd")){
                        if(defined("a_".$funcname_.'_cooldown')){
                            //e($GLOBALS['ss']['aac_object']->name);
                            $GLOBALS['ss']['aac_object']->set->add("lastused_".$func,time());
                        }
                    }
                    
                
                }else{
                    $GLOBALS['ss']["query_output"]->add("error",'Tuto funkci lze použít za '.timesr($cooldown-time()+$lastused).'.');
                }
                
            }else{
                //r($GLOBALS['ss']["aac_func"]);
                if($funcname!='a_')$GLOBALS['ss']["query_output"]->add("error","tato funkce je pasivní - $funcname");
            }
        }else{
            //echo($func);
            $GLOBALS['ss']["query_output"]->add("error","$queryp: neexistující funkce u tohoto objektu($aid) $func");
        }
        }else{
            if($func!="login"){
                $GLOBALS['ss']["query_output"]->add("error","nepřihlášený uživatel");
            }
       }
	
	//qlog($logid,$useid,$aacid,$function,$params,$output)
	if($funcname_!='' and $funcname_!='info'){
		//$params=explode(',',$params);
		$output=$GLOBALS['ss']["query_output"]->vals2list();
		if($funcname_=='login'){
			$params['2']='*****';
			$params['3']='*****';
			$params['4']='*****';
		}
		qlog($GLOBALS['ss']['log_object']->id,$GLOBALS['ss']['use_object']->id,$GLOBALS['ss']['aac_object']->id,$funcname_,implode(',',$params),$output);
	}
	//r($GLOBALS['ss']["query_output"]->vals2str());
	
    return($GLOBALS['ss']["query_output"]/*->vals2str()*/);
}
//----------------
function use_param($p){//konstanty
    return($GLOBALS['ss']["aac_func"]["params"][$p][0]);
}
//----------------
/*function use_price($hold){
    //r(abc,$GLOBALS['ss']["aac_func"]["class"],$GLOBALS['config']["fur"][$GLOBALS['ss']["aac_func"]["class"]]);
    $resource=$GLOBALS['config']["f"]["use"]["w"][$GLOBALS['ss']["aac_func"]["class"]];
    if(!$GLOBALS['ss']["use_object"]->hold->take($resource,$hold)){
        $GLOBALS['ss']["query_output"]->add("error","Potřebujete alespoň {q_".$resource.";$hold}.");
        return(false);
    }else{return(true);}
}*/
//======================================================================================API

function api($query){
	if(is_string($query)){
		//echo($query);
		$query=str_replace(' ',',',$query);
		list($function,$params)=explode(',',$query,2);
		$params=explode(',',$params);		
	}else{
		$function=$query['function'];
		$params=$query['params'];
	}
	$query=array($function,$params);
	//e('a');
	townsfunction($query,"a");
	//e('b');
	$output=$GLOBALS['ss']["query_output"]->vals2list();
    return($output);

}

//======================================================================================XAPI
//-----------------------------------------------------xapi
/*function xapi($function,$params){
    $query=array('function'=>$function,'params'=>$params);
	$output=api($query);
    if($output['success']){
		success($output['success']);
	}
    if($output['error']){
		error($output['error']);
	}
	return($output['1']);

}*/
//-----------------------------------------------------xquery
$GLOBALS['ss']["xresponse"]='';
function xquery($a,$b='',$c='',$d='',$e='',$f='',$g='',$h='',$i=''){
	if($b){
    	$query=array('function'=>$a,'params'=>array($b,$c,$d,$e,$f,$g,$h,$i));    
    }else{
		//die('nob');
		$query=$a;
	}

    $response=api($query);
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
//-----------------------------------------------------xreport
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
    }
}
//-----------------------------------------------------xqr
function xqr($query){
	xquery($query);
	xreport();
	return(xsuccess());
}
//-----------------------------------------------------xerror

function xerror($text){
	$GLOBALS['ss']["query_output"]->add("error",$text);
    //if(!$GLOBALS['ss']["xresponse"]){$GLOBALS['ss']["xresponse"]=array();}
    //if(!$GLOBALS['ss']["xresponse"]['error']){$GLOBALS['ss']["xresponse"]['error']=array();}
    //$GLOBALS['ss']["xresponse"]['error'][count($GLOBALS['ss']["xresponse"]['error'])]=$text;
}
//-----------------------------------------------------xsuccess
function xsuccess(){
    return($GLOBALS['ss']["xsuccess"]);
}
//-----------------------------------------------------xsuccessalert
function xsuccessalert($text){
if(xsuccess()){alert($text,1);}
}
//=========================================================================================OLD query

function query($query){
	die('OLD!-query');
    //r($query);
    return(townsfunction($query,"a"));
}

//=========================================================================================use price
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
        if(!$GLOBALS['ss']["use_object"]->hold->test($resource,$hold)){
            $q=false;

			//je to blbost 
			//$GLOBALS['ss']["query_output"]->add('error','Potřebujete alespoň '.lr('q_'.$resource,$hold));

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
                $GLOBALS['ss']["use_object"]->hold->take($resource,$hold);
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
//=========================================================================================use,test hold
function use_hold($hold){
//$hold->showimg();
//$GLOBALS['ss']["use_object"]->hold->showimg();
$q=($GLOBALS['ss']["use_object"]->hold->takehold($hold));
//$GLOBALS['ss']["use_object"]->hold->showimg();
return($q);
}
//------
function test_hold($hold){
return($GLOBALS['ss']["use_object"]->hold->testhold($hold));  
}
//==========================================================================================OLD xquery
/*$GLOBALS['ss']["xresponse"]='';
function xquery($a,$b="",$c="",$d="",$e="",$f="",$g="",$h="",$i=""){
	r('OLD!-xquery');
    xreport();
    $b=x2xx($b);$c=x2xx($c);$d=x2xx($d);$e=x2xx($e);$f=x2xx($f);$g=x2xx($g);$h=x2xx($h);$i=x2xx($i);
    /*$query=$a;
    if($b){$query="$a $b";}
    if($c){$query="$a $b $c";}
    if($d){$query="$a $b $c $d";}
    if($e){$query="$a $b $c $d $e";}
    if($f){$query="$a $b $c $d $e $f";}
    if($g){$query="$a $b $c $d $e $f $g";}
    if($h){$query="$a $b $c $d $e $f $g $h";}
    if($i){$query="$a $b $c $d $e $f $g $h $i";}
    //r($query);* /
    //$query=array($a,$b,$c,$d,$e,$f,$g,$h,$i);
    //$query=implode(',',$query);
    $query=("$a $b,$c,$d,$e,$f,$g,$h,$i");    
    
    $response=query($query);
  
    if($response->val("1")=='1')
    $GLOBALS['ss']["xsuccess"]=($response->val("1"));
    //e($query.' - '.$GLOBALS['ss']["xsuccess"]);br();
    $response=$response->vals2list();
    //print_r($response);
    if($GLOBALS['ss']["xresponse"]=='')$GLOBALS['ss']["xresponse"]=$response;
    return($response);
    
}
$GLOBALS['ss']["xsuccess"]=0;
$GLOBALS['ss']["xresponse"]=array();
//-----------------------------------------------------OLD
function xreport(){
	r('OLD!-xreport');
    //r('xreport');
    $response=$GLOBALS['ss']["xresponse"];
    $GLOBALS['ss']["xresponse"]='';
    if($response!=''){//r($response);
    //r($response);
    //$response=new vals($response);
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
    //r($response->own2);
    //$response->func=new func($response->func);
    //$response->res=new res($response->res);
    //$response->profile=new profile($response->profile);
    //$response->hold=new hold($response->hold);
    }
}
//-----------------------------------------------------OLD
function xqr($query){
	r('OLD!-xqr');
	xquery($query);
	xreport();
	return(xsuccess());
}
//-----------------------------------------------------OLD

function xerror($text){//echo($text);
	r('OLD!-xerror');
    if(!$GLOBALS['ss']["xresponse"]){$GLOBALS['ss']["xresponse"]=array();}
    if(!$GLOBALS['ss']["xresponse"]['error']){$GLOBALS['ss']["xresponse"]['error']=array();}
    $GLOBALS['ss']["xresponse"]['error'][count($GLOBALS['ss']["xresponse"]['error'])]=$text;
}
//-----------------------------------------------------OLD
function xsuccess(){
	r('OLD!-xsuccess');
    return($GLOBALS['ss']["xsuccess"]);
}
//-----------------------------------------------------OLD
function xsuccessalert($text){
r('OLD!-xsuccessalert');
if(xsuccess()){alert($text,1);}
}*/
//=================================================================================================blocktest
function block1test($type,$x,$y){

	$x=round($x);
	$y=round($y);

	if($type=='A'){
		$cc=intval(sql_1data("SELECT id FROM ".mpx."objects WHERE ".($noid?"`id`!='$noid' AND":'')." own!='".$GLOBALS['ss']['useid']."'AND `ww`=".$GLOBALS['ss']["ww"]." AND (`type`='building' OR `type`='rock') AND ROUND(`x`)=$x AND ROUND(`y`)=$y AND `block`!=0 LIMIT 1",1));
		if($cc)return($cc);

	}elseif($type=='B'){

		$cc=sql_1data("SELECT terrain FROM ".mpx."map WHERE `ww`=".$GLOBALS['ss']["ww"]." AND `x`=$x AND `y`=$y LIMIT 1",1);
		//e($cc);			
		if($cc=='t0' or $cc=='t1' or $cc=='t11')return($cc);

	}


return(0);

}
//-----------------------------------------------------
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
			$cc=(sql_array("SELECT id,type FROM ".mpx."objects WHERE ".($noid?"`id`!='$noid' AND":'')." own!='".$GLOBALS['ss']['useid']."'AND `ww`=".$GLOBALS['ss']["ww"]." AND ((`type`='building' AND `block`!=0) OR `type`='rock') AND ROUND(`x`)=$x AND ROUND(`y`)=$y LIMIT 1",1));
			//print_r($cc);br();
			if($cc){
				if($cc[0][1]=='rock'){return('rock');}
				$ccc[]=$cc[0];
				//print_r($ccc);br();
			}

		}elseif($type=='B'){
			$cc=sql_1data("SELECT type FROM ".mpx."objects WHERE ".($noid?"`id`!='$noid' AND":'')." own!='".$GLOBALS['ss']['useid']."'AND `ww`=".$GLOBALS['ss']["ww"]." AND (`type`='building' OR `type`='rock' OR `type`='tree') AND ROUND(`x`)=$x AND ROUND(`y`)=$y LIMIT 1",1);

		
			//e($cc);
			if($cc){
				return($cc);
			}

			$cc=sql_1data("SELECT terrain FROM ".mpx."map WHERE `ww`=".$GLOBALS['ss']["ww"]." AND `x`=$x AND `y`=$y LIMIT 1",1);
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
//-------------------------------------------------mostnear
function mostnear($x,$y){
	$array=sql_array("SELECT x,y FROM ".mpx."objects WHERE own='".$GLOBALS['ss']['useid']."'AND `ww`=".$GLOBALS['ss']["ww"]." AND (`type`='building') ORDER BY POW($x-x,2)+POW($y-y,2) LIMIT 1");
	return($array[0]);

}
//===================================================================================================qlog
function qlog($logid,$useid,$aacid,$function,$params,$output){
	if(!defined('createdlog')){define('createdlog',true);
    	sql_query('CREATE TABLE IF NOT EXISTS `[mpx]log` (
  `time` int(11) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `user_agent` text NOT NULL,
  `townssessid` varchar(32) NOT NULL,
  `lang` varchar(4) NOT NULL,
  `logid` int(20) NOT NULL,
  `useid` int(20) NOT NULL,
  `aacid` int(20) NOT NULL,
  `function` varchar(20) NOT NULL,
  `params` text NOT NULL,
  `output` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;');
	}
	

	
    if(!is_string($params)){
	    $params=serialize($params);
    }
	$output=serialize($output);

	//if($function=='register'){mail('ph@towns.cz','new register','ref: '.$GLOBALS['ss']['ref'].nln.'function: '.$function.nln.'logid: '.$logid.nln.'useid: '.$useid.nln.'aacid: '.$aacid.nln.'params: '.$params.nln.'output: '.$params);}


	sql_query("INSERT INTO `[mpx]log` (`time`, `ip`, `user_agent`, `townssessid`, `lang`, `logid`, `useid`, `aacid`, `function`, `params`, `output`) VALUES (
	'".time()."',
	'".addslashes($_SERVER["REMOTE_ADDR"])."',
	'".addslashes($_SERVER["HTTP_USER_AGENT"])."',
	'".addslashes($_COOKIE['TOWNSSESSID'])."',
	'".addslashes($GLOBALS['ss']["lang"])."',
	'".addslashes($logid)."',
	'".addslashes($useid)."',
	'".addslashes($aacid)."',
	'".addslashes($function)."',
	'".addslashes($params)."',
	'".addslashes($output)."'
	);");



}

//--------------
function xlog($wtf,$value=false){
    //e("xlog($wtf,$value");
    if(!$value){
        $value=sql_1data('SELECT params FROM [mpx]log WHERE logid='.logid.' AND function=\'x'.$wtf.'\'  '); 
        return($value);
    }else{
        $count=sql_1data('SELECT count(1) FROM [mpx]log WHERE logid='.logid.' AND function=\'x'.$wtf.'\' AND params=\''.$value.'\'  '); 
        $count=$count-1+1;
        
        qlog(logid,useid,0,'x'.$wtf,$value,false); 
        
        return($count);
    }
}
//===================================================================================================

?>
