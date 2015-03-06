<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/func_vals.php

   Tento soubor slouží k deklaraci speciálních objektů na uložení seznamů, surovin, funkcí,... .
*/
//===============================================================================================================VALS




class vals{
    var $vals=array();
    var $numberindex=0;
    function __construct($str="",$q=false){
        if(is_array($str)){
            $this->vals=$str;
        }else{
	    //t('a1'); 
            //if($q){r($str);}
            $tmp=str_replace(";\r\n",";",$str);
            $tmp=explode(";",$tmp);
            foreach($tmp as $row){
            list($a,$b)=explode("=",$row,2);
            //$this->add($a,$b);
            //r($b);
	    if(strpos($b,',')!==false){
		    $b=explode(",",$b);
		    foreach($b as $bb){
		    //r($bb);
		    //t('a2'); 
		    $this->add($a,$bb,true);
		    //t('a3'); 
		}
	    }else{
	    	    $this->add($a,$b,true);
	    }
	    }
        }
    }
    //--------------------------------------------
     function r(){r($this->vals);}
    //--------------------------------------------
    function add($a,$b,$unserialize=false){
        //echo($a."=".$b.br);
        //r($this->nojoin);
        if($a and !$b and $b!=='0'){
            $q=true;
            //r("swichaandb",$this->numberindex,$a,$b);
            $b=$a;
            $a=$this->numberindex;
            $this->numberindex++;
        }
        if($a or $q){
            //r($b);
            //$b=xx2x($b);
            if($unserialize){$b=xx2x($b);}
            //r($b);
            //echo("$a=$b<br>");
            if(!$this->vals[$a] or ($this->nojoin and $a!="join")){
                if($this->nojoin==="plus"){
                    //echo("plus");
                    $this->vals[$a]=$this->vals[$a]+$b;
                }else{
                    //echo("equate");
                    $this->vals[$a]=$b;
                }
            }else{
                if(!is_array($this->vals[$a])){
                    $x=$this->vals[$a];
                    $this->vals[$a]=array();
                    $this->vals[$a][0]=$x;
                    $this->vals[$a][1]=$b;
                }else{
                    $this->vals[$a][count($this->vals[$a])]=$b;
                }
            }
        }
        //print_r($this->vals);
    }
    //--------------------------------------------
    function delete($a){
        unset($this->vals[$a]);
    }
    //--------------------------------------------
     function vals2str($conf=false){
        //r(1);
        $tmp=array();$i=0;
        foreach($this->vals as $a=>$b){
            if(is_object($b))$b=$b->vals2str();
            if(is_array($b)){
                $ii=0;while($b[$ii]){$b[$ii]=x2xx($b[$ii]);$ii=$ii+1;}
                $b=join(",",$b);
            }else{
                //r($b);
                $b=x2xx($b);
                //r($b);
            }
            $tmp[$i]="$a=$b";
            //r($tmp[$i]);
            $i=$i+1;
        }
        //r($tmp);
        if($conf){$sch=";".nln;$bch=";";}else{$sch=";";$bch="";}
        $tmp=join($sch,$tmp);
        $tmp=$tmp.$bch;
        //r($tmp);
        return($tmp);
    }
    //--------------------------------------------
     function vals2list(){
        return($this->vals);
    }
    //--------------------------------------------
     function val($val,$set=false){
		if(!$set){
		    $val=($this->vals[$val]);
		    if($val){
		        return($val);
		    }else{
		        return(false);
		    }
		}else{
			$this->vals[$val]=$set;
		}
    }
    //--------------------------------------------
     function ifnot($key,$default){
        $val=($this->vals[$key]);
        if(!$val and $val!=='0'){
            //$this->add($key,$default);
            //return($val);
            return($default);
        }
        return($val);
    }
    //--------------------------------------------
    function sort(){ksort($this->vals);}
    //--------------------------------------------
     function vals2strobj($head){
        $tmp=array();$i=0;
        foreach($this->vals as $a=>$b){
            if(is_array($b)){
                $i=0;while($b[$i]){$b[$i]=x2xx($b[$i]);$i=$i+1;}
                $b=join(",",$b);
            }else{
                $b=x2xx($b);
            }
            $tmp[$i]="$head:$a=$b;";
            $i=$i+1;
        }
        $tmp=join(nln,$tmp);
        return($tmp);
    }
}
/*t('a1'); 
$tmp=new vals("");
t('a2'); 
$tmp->add("qq","=;class=qqw;params=[equate];profile=[equate]");
t('a3'); 
print_r($tmp->vals2list());
exit;*/
/*$tmp = new vals("a=7;page=use;c=x;c=c");
$tmp->add("c","xxx");
$tmp->add("page","xxx2");
print_r($tmp->vals2list());
if($tmp->val("aa")){echo("hurá");}*/
//--------------------------------------------
//Natolik podstatná funkce, že je udělaná samostatně
function str2list($tmp){
/*t('a'); 
    if(!is_object($tmp))$tmp=new vals($tmp);
t('b'); 
    $tmp=$tmp->vals2list();
t('c'); 
    return($tmp);*/

	
    if(is_object($tmp)){
	    $tmp=$tmp->vals2list();
	    return($tmp);
    }

	if(is_array($tmp)){return($tmp);}
	//t('str2list - start'); 
	$array=array();
	if($tmp){
	    $i=0;
	    //hr();e($tmp);br();
            $tmp=str_replace(";\r\n",';',$tmp);
	    //e($tmp);br();
            $tmp=explode(';',$tmp);
	    //print_r($tmp);br();    

            foreach($tmp as $row){
	    //e("$row:");
            list($a,$b)=explode("=",$row,2);
            //$this->add($a,$b);
            //r($b);
	    //e("$a = $b");br();
	    if($a/* and $b*/){
		    if(!$b and $b!=='0'){
			    //$q=true;
			    $b=$a;
			    $a=$i;
			    $i++;
		    }
	    if(strpos($b,',')!==false){
		    $b=str_replace(',',',protected,',$b);
		    $b=xx2x($b);
		    $b=explode(',protected,',$b);
		    //foreach($b as &$bb){$bb=xx2x($bb);}
		    $array[$a]=$b;
	    }else{
		    //$this->add($a,$b,true);
		    $b=xx2x($b);
		    $array[$a]=$b;
	    }}
	    }
		//r($array);
	}
	
	//die();
	//t('str2list - end');
	return($array);

}
//--------------------------------------------
function list2str($tmp){
    if(!is_object($tmp))$tmp=new vals($tmp);
    $tmp=$tmp->vals2str();
    return($tmp);
}
//$r=str2list( "  =;x=15;y=110");
//r($r["x"]);
//--------------------------------------------
function valsintext($text,$vals,$x2xx=false){
    $list=str2list($vals);
 	//if(substr($text,'kodil')){e('xxx');print_r($list);}
	//$list=new vals($vals);
    //$list=$list->vals2list();

    foreach($list as $key=>$value){
        if($x2xx)$value=x2xx($value);
		$text=str_replace(array('[[',']]'),array('[',']'),$text);
        $text=str_replace("[$key]",$value,$text);
		$text=str_replace("[$key"."_]",$value,$text);
    }
    return($text);
}

//--------------------------------------------
function xxx2conf($tmp){
    $tmp=new vals($tmp);
    $tmp=$tmp->vals2str(true);
    return($tmp);
}
/*$vals=new vals("a=1,2;b=3,4");
$vals->r();
$vals=$vals->vals2str();
r($vals);*/
//--------------------------------------------
/*function list2str($tmp){
    $sub=array(0);
    $i=0;
    while(!$sub[-1] and $i<1000){$i++;
        $value=$text;
        foreach($sub as $ii){
            $keys=array_keys($value);
            $key=$keys[$ii];
            $value=$value[$key];
        }
        if($value){
            if(!is_array($value)){
                $iii=1;$sp="";
                while($iii<sizeof($sub)){$iii++;
                    $sp=$key."=".x2xx($value);
                }
                $sub[sizeof($sub)-1]++;
            }else{
                $iii=1;$sp="";
                $sp=$key."=";
                $sub[sizeof($sub)]=0;
            }
        }else{
            array_pop($sub);
            $sub[sizeof($sub)-1]++;
        }
        //print_r($sub);
        //echo(br);

    }
}
$array=(array("hovno",array(array(array(1,2,3,4,5)),8),array(array(2,4)),array(7,abc=>"aaa")));
r($array);
$array=list2str($array);
r($array);*/
//===============================================================================================================FUNC
class func{
    function __construct($str=""){
	
         $this->funcs=new vals($str);
         $this->funcs->nojoin=true;
         $this->funcs->sort();
         //------------------------------
	//$this->funcs->r();
         $tmp=$this->funcs->vals2list();
	//r($tmp);
         //if(isset($tmp['login'])){
         if(!isset($tmp['chat']))$this->add('chat','chat');
         if(!isset($tmp['info']))$this->add('info','info');
         if(!isset($tmp['logout']))$this->add('logout','logout');
         if(!isset($tmp['login']))$this->add('login','login');
         if(!isset($tmp['profile_edit']))$this->add('profile_edit','profile_edit');
         if(!isset($tmp['set_edit']))$this->add('set_edit','set_edit');
         if(!isset($tmp['stat']))$this->add('stat','stat');
         if(!isset($tmp['text']))$this->add('text','text');
         if(!isset($tmp['use']))$this->add('use','use');
         if(!isset($tmp['finish']))$this->add('finish','finish');
         if(!isset($tmp['dismantle']))$this->add('dismantle','dismantle');
         if(!isset($tmp['repair']))$this->add('repair','repair');
         if(!isset($tmp['upgrade']))$this->add('upgrade','upgrade');
         if(!isset($tmp['design']))$this->add('design','design');
         if(!isset($tmp['xmine']))$this->add('xmine','xmine');
         //}
         //$emptyvals=new vals();
         //login=1;use=1;info=1;item=1;profile_edit=1;set_edit=1;move=2;message=1;image=1
         //if(!$this->funcs->val("")){$this->add("","",$emptyvals,$emptyvals);}
    }
    //--------------------------------------------add
    function add($name,$class,$params="",$profile=""){
        $func=new vals();
        if($params){$params=$params->vals2str();}else{$params=/*"qw"*/'';}
        if($profile){$profile=$profile->vals2str();}else{$profile=/*"qw"*/'';}
        //rn("name: ".$name);
        //rn("class: ".$class);
        //rn("params: ".$params);
        //rn("profile: ".$profile);
        $func->add("class",$class);
        $func->add("params",$params);
        $func->add("profile",$profile);
        $func=$func->vals2str();
        //rn($func);
        $this->funcs->add($name,$func);
        //r();
        //rn($this->funcs->vals2str());
        $this->funcs->sort();
        //r();
        //rn($this->funcs->vals2str());
        //r($this->funcs->vals2list());
    }
        //--------------------------------------------addF
    function addF($name,$key,$value,$wtf='params',$onlyplus=false){
        //r($this->vals2list());
        $func=$this->funcs->val($name);       
        if(!$func){
            $class=str_replace(array(0,1,2,3,4,5,6,7,8,9),'',$name);
            $this->add($name,$class);
            $func=$this->funcs->val($name);
            $func=new vals($func);  
        }
	if(gettype($func)=='string'){
		$func=new vals($func);
	}
        //r(gettype($func));
        //r($func);
        
        //r(1);
        $params=new vals($func->val($wtf));
        //r(2);
        if($wtf=='params')$value=array(floatval($value),1);
	$params->delete($key);
	if($onlyplus and $params->val($key)>$value){return(false);}
        $params->add($key,$value);
        //$params->add($key,1);
        //r(3);        
        $params=$params->vals2str();
        if($wtf=='profile')$params=str_replace('[2]',',',$params);        
        
        $func->delete($wtf);
        $func->add($wtf,$params);
        
        
        $this->funcs->delete($name);
        $this->funcs->add($name,$func);
        //r(4);
    }
    //--------------------------------------------delete
    function delete($func){$this->funcs->delete($func);}
    //--------------------------------------------
     function vals2str(){
         $return=$this->funcs->vals2str();
         return($return);
    }
    //--------------------------------------------vals2strobj
    function vals2strobj($head){
         $return=$this->funcs->vals2strobj($head);
         return($return);
    }
    //--------------------------------------------vals2list
    function vals2list($only=false){
        //r('vals2list');
        //r($this->funcs->vals2str());
        $return=$this->funcs->vals2list();
        t('funcs - after vals2list');
        //r(1);
        //r($return["login"]);
        foreach($return as $i=>$tmp){
            
            if(!$only or in_array($i,$only)){//e($i);
                //r($return[$i]);
                //r(gettype($return[$i]));
                $return[$i]=str2list($return[$i]);//funkce
                //r(2);

                //$return[$i][1]=str2list($return[$i][1]);//
                $return[$i]["params"]=str2list($return[$i]["params"]);//params
                foreach($return[$i]["params"] as $key=>$value){
                    if(!$return[$i]["params"][$key][0]){$return[$i]["params"][$key][0]=0;}
                    if(!$return[$i]["params"][$key][1]){$return[$i]["params"][$key][1]=1;}
                    //if(!$return[$i]["params"][$key][2]){$return[$i]["params"][$key][2]=1;}
                }
                $return[$i]["profile"]=str2list($return[$i]["profile"]);//profile
                /*foreach($return[$i]["params"] as $ii=>$tmp2){
                    $return[$i]["params"][$ii]=str2list($return[$i]["params"][$ii]);
                }*/
                //t('funcs - '.$i);
            }
            
        }
        //r($return["login"]);
        t('funcs - end');
        return($return);
    }
    //--------------------------------------------func
     function func($func){
         $return=$this->vals2list();
         $return=$return[$func];
         if($return){
             $return=$return["params"];
             foreach($return as $i=>$param){
                 $exp1=$param[0];//["exp1"];
                 $exp2=$param[1];//["exp2"];
                 //$exp3=$param[2];//["exp3"];
                 //r($exp1);
                 //r($param);
                 if(!$exp1){$exp1=0;}
                 if(!$exp2){$exp2=1;}
                 //if(!$exp3){$exp3=1;}
                 $value=($exp1*$exp2);
                 $return[$i]=$value;
             }
             return($return);
         }else{
             return(false);
        }
    }
    //--------------------------------------------profile
    function profile($func,$param){
        $return=$this->vals2list();
        $return=$return[$func]["profile"][$param];
        //r($return);
        return($return);
    }
    //--------------------------------------------param
    /*function param($func,$param){
        
        $return=$this->vals2list();
        return(serialize($return['attack']));
        $exp1=$return[$func]["params"][$param][0];
        $exp2=$return[$func]["params"][$param][1];
        if(!$exp1){$exp1=0;}
        if(!$exp2){$exp2=1;}
        $return=($exp1*$exp2);
        //r($return);
        return($return);
    }*/
    //--------------------------------------------fs
    function fs(){
        $fs=0;
        foreach($this->vals2list() as $func){
            $f=$func["class"];
            //$params=$params["class"];
            $tmp=($GLOBALS['config']['f'][$f]["create"]['q']);
	    //r($tmp);
	    //r($GLOBALS['config']["fs"]);
            if($tmp){
                //r($func["params"]);
                foreach($func["params"] as $key=>$v){
                    list($e1,$e2)=$v;
                    if($e2<1){$e2=1/$e2;}
                    $v=$e1*($e2*$e2);
                    //rn($v);
                    $tmp=str_replace('$_'.$key,$v,$tmp);
                }
                $tmp="\$tmp=".$tmp.";";
		//echo($tmp);
                eval($tmp);
                $fs=$fs+$tmp;
            }
        }
        return($fs);
    }
    //--------------------------------------------fc
    function fc(){
        $fc=new hold();
        foreach($this->vals2list() as $func){
            $f=$func["class"];
            //$params=$params["class"];
            $tmp=($GLOBALS['config']['f'][$f]["create"]['q']);
	    //r($GLOBALS['config']["fs"]);
            if($tmp){
                //r($func["params"]);
                foreach($func["params"] as $key=>$v){
                    list($e1,$e2)=$v;
                    if($e2<1){$e2=1/$e2;}
                    $v=$e1*($e2*$e2);
                    //rn($v);
                    $tmp=str_replace('$_'.$key,$v,$tmp);
                }
                $tmp="\$price=".$tmp.";";
		//echo($tmp);
                eval($tmp);
                $tmp=new hold();
		$wtf=($GLOBALS['config']['f'][$f]["create"]['w']);
		$cc=0;
		foreach(explode('+',$wtf) as $wtfx){
			list($c,$wtfx)=explode('*',$wtfx);
			if(!$wtfx){$wtfx=$c;$c=1;}
			$cc-=-$c;
		}
		//r($cc);
		foreach(explode('+',$wtf) as $wtfx){
			list($c,$wtfx)=explode('*',$wtfx);
			if(!$wtfx){$wtfx=$c;$c=1;}
			$tmp->add($wtfx,ceil($price*$c/$cc));
			//$tmp->add($wtfx,1);
		}
		

		$fc->addhold($tmp);
            }
        }
        return($fc);
    }
    //--------------------------------------------join
    function join($join){
	$join=$join->vals2list();
	//r($join);
	foreach($join as $name=>$function){

		if($oldfuncion=$this->funcs->val($name)){

			//r($oldfuncion);
			$oldfuncion=str2list($oldfuncion);


			//r($oldfuncion);
			$oldparams=str2list($oldfuncion['params']);
			$oldprofile=str2list($oldfuncion['profile']);

			if($oldprofile['limit']==$function['profile']['limit'] and $oldprofile['group']==$function['profile']['group']){
				foreach($function['params'] as $paramname=>$param){
					if($oldparams[$paramname]){
						list($a1,$b1)=$oldparams[$paramname];
						list($a2,$b2)=$param;
						
						//B parametry nefungují!!1
						switch($paramname){
						    case "eff":
						    case "xeff":
							$a3=$a1+((1-$a1)*$a2);
							$b3=$b1*$b2;
							break;
						    case "cooldown":
							$a3=1/((1/$a1)+(1/$a2));
							$b3=$b1*$b2;
							break;
						    case "distance":
						    case "radius"://?
							$a3=sqrt(pow($a1,2)+pow($a2,2));
							$b3=$b1*$b2;
							break;
						    case "total"://totálně zastaralý parametr
							$a3=($a1+$a2)?1:0;
							$b3=$b1*$b2;
							break;
						    case "limit":
							$a3=max($a1,$a2);
							$b3=$b1*$b2;
							break;
						    default://coolround,attack,defence...
						        $a3=$a1+$a2;
							$b3=$b1*$b2;
						}
						$a3=round($a3*100)/100;
						$b3=round($b3*100)/100;

						//e("$name -> params -> $paramname : old($a1,$b1) + new($a2,$b2) = ($a3,$b3)");br();
						//addF($name,$key,$value,$wtf='params',$onlyplus=false)
						$this->addF($name,$paramname,$a3);
					}else{
						list($a3,$b3)=$param;
						//e("$name -> params $paramname : add value ($a3,$b3)");br();
						$this->addF($name,$paramname,$a3);
					}
				}
				foreach($function['profile'] as $profilename=>$profile){
					if($oldprofile[$profilename]){
					
						//e("$name -> profile -> $profilename : stayold");br();
					}else{
						//e("$name -> profile -> $profilename : add value $profile");br();
						$this->addF($name,$profilename,$profile,'profile');
					}
				}
			}else{
				$class=str_replace(array(0,1,2,3,4,5,6,7,8,9),'',$name);
				$i=2;
				while($this->funcs->val($class.$i)){$i++;}
				$newname=$class.$i;
				//e("$name : add whole function(limit) as $newname");br();
				//add($name,$class,$params="",$profile="")
				$params=new vals($function['params']);
				$profile=new vals($function['profile']);

				if($class!='group')
				$this->add($newname,$class,$params,$profile);
			}

		
		}else{
				$class=str_replace(array(0,1,2,3,4,5,6,7,8,9),'',$name);
				if($this->funcs->val($name)){
					$i=2;
					while($this->funcs->val($class.$i)){$i++;}
					$newname=$class.$i;
				}else{
					$newname=$name;
				}
				//e("add whole function as $newname");br();
				$params=new vals($function['params']);
				$profile=new vals($function['profile']);

				if($class!='group')
				$this->add($newname,$class,$params,$profile);
		}


		//r();



	}
    }
    //--------------------------------------------level
}
function level($list){
    list($exp1,$exp2)=$list;
    if(!$exp2){$exp2=1;}
    //if(!$exp3){$exp3=1;}
    $value=($exp1*$exp2);
    return($value);
}
//--------------------------------------------func2list
function func2list($tmp,$only=false){
    $tmp=new func($tmp);
    $tmp=$tmp->vals2list($only);
    return($tmp);
}
//--------------------------------------------paramvalue

function paramvalue($param){
    return($param[0]*$param[1]);
}

//===============================================================================================================SET
class set extends vals{
    var $nojoin=true;
    var $vals=array(
    //$status=>new status()
    );
}
//----------
class windows extends vals{
    var $nojoin=true;
    var $vals=array(
    //$status=>new status()
    );
}
//===============================================================================================================PROFILE
class profile extends vals{
    var $nojoin=true;
    var $vals=array(
    realname=>"",
    gender=>"",
    age=>"",
    mail=>"@",
    showmail=>"",
    sendmail=>"1",
    web=>"",
    description=>"",
    join=>""
    //=>"",
    );
}
//===============================================================================================================HOLD
class hold extends vals{
    var $nojoin="plus";
    var $rebased=false;
    var $vals=array(
    fp=>0
    //=>"",
    );
    //-------------------rebase
    function rebase(){
	//$this->rebased=true;
	if(!$this->rebased){$this->rebased=true;
        foreach($this->vals as $key=>$value){//hu buc bud
            if($key and $key!='_time' and $key!='_change' and !is_numeric($key) and $value){
		if(substr($key,0,1)=='_' and substr($key,1,1)!='-'){
			$_key=$key;

			
			$key=substr($key,1);
			$bound=$this->vals['_-'.$key];

			if($this->vals[$key]<$bound){
				$this->vals[$key]=round($this->vals[$key]-(-$this->vals[$_key]*(1/3600)*(time()-$this->vals['_time'])));
				if($this->vals[$key]>$bound){$this->vals[$key]=$bound;}
			}

		}
	    }
        }
	$this->vals['_time']=time();
	}
    }
    //-------------------textr
    function textr($q=''){
	$this->rebase();
        $stream="";
        foreach($this->vals as $key=>$value){//hu buc bud
            if($key and $value)if(substr($key,0,1)!='_')$stream=$stream.(lr('res_'.$key.$q).': '.ir($value).' ');
    }
    if(!$stream)$stream=lr('res_no');
    return($stream);
    }
    //-------------------show
    function show(){
	$this->rebase();
        foreach($this->vals as $key=>$value){//hu buc bud
            if(substr($key,0,1)!='_')echo(textbr(lr('res_'.$key).': ').ir($value).br);
        }
    }
    //-------------------showjs
    /*function showjs(){
        //echo("<script type=\"text/javascript\"><!--");
        foreach($this->vals as $key=>$value){//hu buc bud
            echo("countdownto('res_$key',$value); ");
            //echo("\$(\"#res_$key\").html($value);");
        }
        //echo("--></script>");
    }*/
    //-------------------showimg
    function showimg($q=false,$notable=false,$trr=false,$border=false,$size=15,$bold=false){
	$this->rebase();
	$script='';
		
        if(!$notable)echo("<table width=\"0\" valign=\"middle\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr>");
        foreach($this->vals as $key=>$value){//hu buc bud
            if($key and $key!='_time' and !is_numeric($key) and ($value or $key=='wood' or $key=='stone' or $key=='iron' or $key=='fuel')){
		if(substr($key,0,1)!='_'){
			$value=ir($value);
			if($trr){
				$value=trr($value,12,1);
			}
			if(!$border){
				$img=imgr("icons/res_$key.png",lr('res_'.$key),$size,$size);
			}else{
			
				$img=borderr(imgr("icons/res_$key.png",lr('res_'.$key),$size,$size),$border,$size).nbsp2;
			}
			echo((!$notable?'<td>':'').$img.($bold?'<b>':'')."<span id=\"res_".($q?$key:'no')."\">".$value."</span>".($bold?'</b>':'').(!$notable?nbsp3.'</td>':' '));
		}else{
			if(substr($key,1,1)!='-'){
				$bound=$this->vals['_-'.substr($key,1)];
				if(($this->vals[substr($key,1)])<$bound){
					//countupto(id,base,x1,x2,bound)
					$script.="countupto('res_".($q?substr($key,1):'no')."',".$this->vals['_time'].",".($this->vals[substr($key,1)]).",$value,".($bound).");";
				}
			}

		}
	    }
        }
        if(!$notable)echo("</tr></table>");
	//e(rand(1,100));
	js($script);
    }
    //-------------------test
    function test($a,$b){
	if(substr($a,0,1)=='_')return(true);
	$this->rebase();
        //r($a,$b);
        $b=round($b);
        if($this->vals[$a]<$b){
            return(false);
        }else{
            return(true);
        }
    }
    //-------------------take
    function take($a,$b,$force=false){
	if(substr($a,0,1)=='_')return(true);
	$this->rebase();
        //r($a,$b);
        $b=round($b);
        if(!$force and $this->vals[$a]<$b){
            return(false);
        }else{
            $this->vals[$a]=$this->vals[$a]-$b;
	    if($this->vals[$a]<0 and $force)$this->vals[$a]=0;
            return(true);
        }
    }
    //-------------------fp
     function fp(){
	$this->rebase();
        $fp=0;
        foreach($this->vals as $key=>$value){
            if($key and substr($key,0,1)!='_' and !is_numeric($key) and $value){
		if(substr($key,0,1)!='_'){
            		$fp=$fp+$value;
		}}
        }
        return($fp);
    }
    //-------------------testhold
        function testhold($hold){
	$this->rebase();
        $hold=$hold->vals2list();
        foreach($hold as $key=>$value){
            if(!is_numeric($key) and !$this->test($key,$value))return(false);
        }
        return(true);
    }
    //-----
   function takehold($hold,$force=false){
	$this->rebase();
	
        if(!$force)if(!$this->change($hold))return(false);
        $hold=$hold->vals2list();
        foreach($hold as $key=>$value)$this->take($key,$value,$force);
        return(true);
    }
    //-------------------testchange
        function testchange($hold){
	$this->rebase();
        $hold=$hold->vals2list();
	$test=array();
	foreach($this->vals as $key=>$value){
		if($key and $key!=plus_res and substr($key,0,1)!='_' and !is_numeric($key) and $value){
            		$test[$key]=$value;
		}
        }
        foreach($hold as $key=>$value){
		if($key and $key!=plus_res and substr($key,0,1)!='_' and !is_numeric($key) and $value){
            		$test[$key]=$test[$key]-$value;
		}
        }
	$plus=0;
	$minus=0;
        foreach($test as $key=>$value){
		if($value>0){
			$plus+=$value;
		}else{
			$minus-=$value;
		}

        }
	$plus=$plus*$this->vals['_change']/100;
	if($plus>=$minus){
		return(true);
	}else{
		return(false);
	}
    }
    //-------------------change
        function change($hold){
	$this->rebase();
        $hold=$hold->vals2list();
	$test=array();
	foreach($this->vals as $key=>$value){
		if($key and $key!=plus_res and substr($key,0,1)!='_' and !is_numeric($key) and $value){
            		$test[$key]=$value;
		}
        }
        foreach($hold as $key=>$value){
		if($key and $key!=plus_res and substr($key,0,1)!='_' and !is_numeric($key) and $value){
            		$test[$key]=$test[$key]-$value;
		}
        }
	$plus=0;
	$minus=0;$cplus=0;
        foreach($test as $key=>$value){
		if($value>0){
			$plus+=$value;$cplus++;
		}else{
			$minus-=$value;
		}

        }
	if($minus==0)return(true);

	$plus=$plus*$this->vals['_change']/100;
	if($plus>=$minus){
		
		$minus1=floor($minus/($this->vals['_change']/100)/$cplus);
		//e($cplus);
		foreach($test as $key=>$value){
			if($value>0){
				$test[$key]-=$minus1;
				if($test[$key]<0)$test[$key]=0;
			}else{
				$test[$key]=0;
			}

		}

		//print_r($test);
		foreach($test as $key=>$value){
			$this->vals[$key]=$value;
		}

		return(true);
	}else{
		return(false);
	}
    }
    //-------------------addhold
   function addhold($hold){
	$this->rebase();
        $hold=$hold->vals2list();
        foreach($hold as $key=>$value)if(substr($key,0,1)!='_'){$this->add($key,$value);}
        return(true);
    }
    //-------------------rhold
   function rhold($hold){
	$this->rebase();
       $newvals=array(); 
       
        $hold=$hold->vals2list();
        foreach($hold as $key=>$value){
	    if(!is_numeric($key) and substr($key,0,1)!='_'){
            	$newvals[$key]=($this->vals[$key]-$value)*-1;
            	if($newvals[$key]<1)$newvals[$key]=0;
	    }//else{e("(($key))");}
            
        }
        
        $this->vals=$newvals;
        return(true);
    }
    //-------------------multiply
   function multiply($q){
	$this->rebase();
        foreach($this->vals as $key=>&$value)if(substr($key,0,1)!='_'){$value=round($value*$q);}
    }
}
    //-------------------showhold
function showhold($hold,/*$cols=false,*/$notable=false){
    //e($hold);
    $hold=new hold($hold);
	//if($notable)e('notable');
    $hold->showimg(NULL,$notable/*,$cols*/);
    unset($hold);
}

function showholdr($hold,/*$cols=false,*/$notable=false){
	ob_start();
	//if($notable){e('notable=true;');}else{e('notable=false;');}
	showhold($hold/*,$cols*/,$notable);
	$buffer = ob_get_contents();
    ob_end_clean();
	return($buffer);
}
//--------------------------------------------
/*$hold=new hold("a=1");
$hold->add("a",1);
$hold->add("c",1);
echo($hold->fp());
exit;*/
//===============================================================================================================
/*class status{
    function __construct($str="actual="){
         $this->status=new vals($str);
         $this->status->nojoin=true;
    }
    //--------------------------------------------
    function add($name,$vals){
        $vals=vals2list($vals);
        $this->status->add($name,$vals);
    }
    function delete($name){$this->status->delete($name);}
    //--------------------------------------------
     function vals2str(){
         $return=$this->status->vals2str();
         return($return);
    }
    //--------------------------------------------
    function vals2list(){
        $return=$this->status->vals2list();
        foreach($return as $i=>$tmp){
            $return[$i]=str2list($return[$i]);
        }
        return($return);
    }
    //--------------------------------------------
}*/
?>
