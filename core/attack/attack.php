<?php
/* Towns4, www.towns.cz 
   © Pavol Hejný | 2011-2015
   _____________________________

   core/attack/attack.php

   Okno útoků
*/
//==============================




    $iconsize=35;
    $iconbrd=3;
    $ns=($GLOBALS['get']['noshit']);

if($GLOBALS['ss']['attack_report']){
    if($GLOBALS['get']['noshit']){
    r('noshit');    
    w_close('content');
    }else{
        contenu_a(true);
        window(lr('title_attack_report')/*,400,200*/);
	//e($GLOBALS['ss']['attack_report']);
        e(contentlang($GLOBALS['ss']['attack_report']));
        contenu_b();
    }
    unset($GLOBALS['ss']['attack_report']);
    e('<script type="text/javascript">refreshMap();'.urlxr('e=miniprofile',false).'</script>');
}else{
    //xreport();
//==============================================================================================================
//window("{title_attack}",600);
//blue("{no_module}");exit;
//r($get);

r($GLOBALS['settings']['attack_mafu']);
list($attack_master,$attack_function)=explode('-',$GLOBALS['settings']['attack_mafu']);

/*if($get['id']) $GLOBALS['ss']['use_object']->set->add('attack_id',$get['id']);
if($get['master'])$attack_master=$get['master'];
if($get['function'])$attack_function=$get['function'];
if($get['master'] or $get['function'])$GLOBALS['ss']['use_object']->set->add('attack_mafu',$attack_master.'-'.$attack_function);*/
//if($_POST['attack_id'])$GLOBALS['ss']['use_object']->set->add('attack_id',unique2id($_POST['attack_id']));

$attack_id=$GLOBALS['settings']['attack_id'];
//$attack_master=($GLOBALS['ss']['use_object']->set->ifnot('attack_master',false));
//$attack_function=($GLOBALS['ss']['use_object']->set->ifnot('attack_function',false));
 
if(!$ns)window(lr('title_attack_master'));
if(!$ns)info(lr('attack_on').' '.contentlang(liner_($attack_id,4,true))/*,500,300*/);
if(!$ns)contenu_a();

/*/if(!$attack_id){
    form_a();
    le('attack_on');
    input_text('attack_id',id2unique($attack_id),200,NULL,'border: 2px solid #000000; background-color: #ffffff');
    form_sb();
//}/*/

//if(/*$attackmenu*/true){

//e($GLOBALS['ss']['useid']);br();
//e($GLOBALS['ss']['logid']);br();

$attack_buildings=sql_array('SELECT id,name,func FROM `[mpx]pos_obj` WHERE own=\''.$GLOBALS['ss']['useid'].'\' AND func LIKE \'%attack%\' AND func NOT LIKE \'%tree%\' AND func NOT LIKE \'%rock%\' AND '.objt());

if(count($attack_buildings)==1){
	$attack_master=$attack_buildings[0][0];
	$attack_function='attack';
}

if(!$ns){

foreach($attack_buildings as $row){
    list($id,$name,$func)=$row;
    //echo($attack_master);
    if($id==$attack_master)
        $brd=$iconbrd;
    else
        $brd=0;
    
                //--------------------------------------------
                $funcs=func2list($func);
                $func=$funcs['attack'];
                $profile=$func["profile"];
                if($profile["icon"]){
                    $icon=$profile["icon"];
                }else{
                        $icon="f_".$func["class"];
                }
                $xname=$profile["name"];
                if(!$xname){$xname=lr('f_'.$func["class"]);}
                //--------------------------------------------
                //eattack_mafu,cho("$id-attack-$xname-$icon");
    ahref(imgr("id_$id"."_icon",$name,$iconsize,$iconsize,NULL,$brd),"e=content;ee=attack-attack;set=attack_mafu,$id-attack-$xname-$icon","none",'x');
}
br();

}
//r($attack_id);r($attack_master);r($attack_function);
//===============================================================================================================ATTACK
    
$id=$attack_id;

//$ownown=sql_1data('SELECT id ');
if(topobject($id)==$GLOBALS['ss']['logid']){
blue(lr('attack_same_'.$GLOBALS['ss']['logid']));
}

xreport();

if($attack_buildings==array()){error(lr('attack_nomaster'));}
elseif(!$id or !$attack_master){error(lr('attack_wtf'));}
elseif($id==$attack_master){error(lr('attack_self'));}
else{
$attacker=new object($attack_master);r($attack_master);
$attacked=new object($id);


//--------------------------------------------------------------------------------------------------BLOCK
//e($attacked->id);
$block=block2test('A',$attacker->x,$attacker->y,$attacked->x,$attacked->y,$attacked->id);

if($block){
	$noconfirm=1;
	br();
	if(is_array($block)){

		foreach($block as $block1){
			error(lr('attack_error_block_object',id2name($block1['id'])));
			ahref(lr('attack_unblock_button'),'e=content;ee=attack-attack;set=attack_id,'.$block1['id']);

		}
	
	}else{
		error(lr('attack_error_block_'.$block));
	}
	br(2);
}
//--------------------------------------------------------------------------------------------------

if(!$attacked->loaded or !$attacker->loaded){error(lr('attack_wtf'));
    }/*elseif($attacked->ww!=$GLOBALS['ss']["ww"]){error('{attack_ww}');}*/else{
    $type=$attacked->type;
    
    //window("{attack_on} ".liner_($id,4,true),500,300);
    //textb("útok na ".liner($id,4));
    //r($funcs);
    $funcs=$attacker->func->vals2list();
    //$images=array();
    //$names=array();
    //$names2=array();
    //r($funcs);
    $q=0;
    foreach($funcs as $name=>$func){
        if($func["class"]=="attack"){
            //$icon='f_'.$func["class"];
            //if($func["profile"]["icon"])/*$images[count($images)-1]*/$icon=$func["profile"]["icon"];
                //--------------------------------------------
                $profile=$func["profile"];
                if($profile["icon"]){
                    $icon=$profile["icon"];
                }else{
                        $icon="f_".$func["class"];
                }
                $xname=$profile["name"];
                if(!$xname){$xname=lr("f_$class");}
                //--------------------------------------------
             $set_key='attack_mafu';$set_value=$attack_master.'-'.$name.'-'.$xname.'-'.$icon;
    
             list($a,$b)=explode('-',$GLOBALS['settings']['attack_mafu']);
             if($a==$attack_master and $b==$name){
                 $brd=$iconbrd;
                 //echo("<script>$stream</script>");
             }else{$brd=0;}
             if(!$ns and $attackmenu){//border(iconr("e=attack-attack;set=$set_key,$set_value"/*;js=".x2xx($stream).';'*/,$icon,$func["profile"]["name"],$iconsize),$brd,$iconsize,$set_value,$set_key/*class*/);
             //ahref(labelr(imgr("id_$id"."_icon","",50,50),$name),"e=attack-attack;set=attack_mafu,$attack_master-$name","none",'x');
             ahref(imgr('icons/'.$icon.'.png',$xname,$iconsize,$iconsize,NULL,$brd),"e=content;ee=attack-attack;set=attack_mafu,$set_value");
             }
        }
    }
    
    
    
    
    
    //tableab_a('left',400);
    //r($images);
    
    //$q=submenu_img(array('content','attack-attack'),"typ útoku",$images,$names,"attack");
    //$attack_function=$names2[$q-1];
    $a_id=$attack_master;//$GLOBALS['ss']['useid'];
    $b_id=$id;
    $attack_type=$attack_function;//$names2[$q-1];
    //===================================================================
    r($attack_id);
    r($attack_master);
    r($attack_function);
    //===================================================================
    $a_fp=$attacker->fp;
    $b_fp=$attacked->fp;
    $a_at=$attacker->supportF($attack_type,"attack");
    $b_at=$attacked->supportF("attack");
    //$a_att=$attacker->supportF($attack_type,"total");
    //$b_att=$attacked->supportF("attack","total");//r($b_att);
    $a_att=false;//NOTOTAL
    $b_att=true;
    $a_cnt=$attacker->supportF($attack_type,"count");
    $b_cnt=$attacked->supportF("attack",'count');
    $a_de=$attacker->supportF("defence");
    $b_de=$attacked->supportF("defence");
    $xeff=$attacker->supportF($attack_type,"xeff");
    $steal=clone $attacked->hold;$steal->multiply($xeff);
    if($b_at)$ns=false;
    //if($a_at<=$b_de)$ns=false;
    if($a_at-$b_de<1)$ns=false;
    //-------TYPE


    if($attacked->type=='user'){$noconfirm=1;
        blue(lr("attack_lock"));
    }
    
    //-------NON SAME WORLD

    if($attacker->ww!=$attacked->ww){$noconfirm=1;
        error(lr('attack_error_ww',$a_dist));
    }    
    
    //-------DISTANCE

    $a_dist=$attacker->supportF($attack_type,"distance");
    
    list($ax,$ay)=$attacker->position();
    list($bx,$by)=$attacked->position();
    $dist=sqrt(pow($ax-$bx,2)+pow($ay-$by,2));
    if($dist>$a_dist){$noconfirm=1;
        error(lr('attack_error_distance',$a_dist));
    }
    
    //-------PRICE
    
    list($q,$time,$a_fp2,$b_fp2,$a_tah,$b_tah,$a_atf,$b_atf)=attack_count(50,50,$a_fp,$b_fp,$a_at,$b_at,$a_cnt,$b_cnt,$a_de,$b_de,$a_att,$b_att);
    $price=use_price("attack",array("time"=>$time),$support[$attack_type]["params"],2);
    if(!test_hold($price)){$noconfirm=1;
        blue(lr('attack_error_price'));
    }

    //-------
    //tableab_b("right","center");
    
     //-------------------------
   if(!isset($noconfirm)){
       $url="e=content;ee=attack-attack;q=$attack_master.$attack_type $b_id";
       if($ns)urlx($url.';noshit=1');
       //$confirm=trr(ahrefr("{attack_$type}",$url,"none","x"),20,3);
       $confirm=ahrefr(trr(nbsp.lr("attack".($b_fp2!=1?'_p':'')."_$type").nbsp,19,3,'style="background: rgba(30,30,30,0.9);border: 2px solid #777777;border-radius: 2px;"'),$url);
       br();
       if($attackmenu){
            moveby($confirm,360,-35);
       }
    }else{
      $ns=false;
    }
   //-------------------------
    //tableab_c();
    
     //-------
    
    if($attackmenu)hr(contentwidth);
    
    //if($a_att)$a_attt="(+)";//NOTOTAL
    //if($b_att)$b_attt="(+)";
    
    tableab_a('left',113);
    
    
    
    vprofile($a_id,array(lr('life').': '=>round($a_fp), lr('attack')."$a_attt: "=>$a_at,lr('attack_count').': '=>$a_cnt, lr('defence').': '=>$a_de, lr('dist_a').': '=>$a_dist));
    tfont('vs.',30);
    vprofile($b_id,array(lr('life').': '=>round($b_fp), lr('attack')."$b_attt: "=>$b_at,lr('attack_count').': '=>$b_cnt, lr('defence').': '=>$b_de, lr('distance').': '=>round($dist,1)));

    
    tableab_b();

   
    $qs=array(4=>0,7=>0,3=>0,6=>0,8=>0,5=>0);
    for($i=0;$i<=100;$i++){
        if($i!=50){
            list($q,$time,$a_fp2,$b_fp2,$a_tah,$b_tah,$a_atf,$b_atf)=attack_count($i,100-$i,$a_fp,$b_fp,$a_at,$b_at,$a_cnt,$b_cnt,$a_de,$b_de,$a_att,$b_att);
            $qs[$q]++;
        }
    }
    textab(lr('attack_expected'));
    br(2);
    foreach($qs as $q=>$tmp){
        //$tmp=$tmp/array_sum($qs)*100;
        if($tmp)info($tmp."%: ".attack_name($q));
    }
    list($q,$time,$a_fp2,$b_fp2,$a_tah,$b_tah,$a_atf,$b_atf)=attack_count(50,50,$a_fp,$b_fp,$a_at,$b_at,$a_cnt,$b_cnt,$a_de,$b_de,$a_att,$b_att);
    //r($a_fp2);
    if($a_fp2==0)error(lr("attack_warning_total_kill"));
    //elseif($b_att)error("{attack_warning_total}");//NOTOTAL
    textab(lr('attack_expected_a').':',$a_fp2);br();
    textab(lr('attack_expected_b').':',$b_fp2);br();
    //textab("doba trvání:",timesr($time));
    //r($support[$attack_type]);
    
    if($price->fp()){textb(lr('attack_price').':');
    $price->showimg();}
    if($steal->fp()){textb(lr('attack_steal'));
    $steal->showimg();}

   if(!$attackmenu){
       br();
       e($confirm);
   }


    tableab_c();
    
    
}}

if(!$ns)contenu_b();
}

?>
