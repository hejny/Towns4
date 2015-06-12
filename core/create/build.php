<?php
/* Towns4, www.towns.cz 
   © Pavol Hejný | 2011-2015
   _____________________________

   core/create/build.php

   Budova před postavením
*/
//==============================
/*if(!$GLOBALS['mapzoom']){
	if(!$GLOBALS['mobile']){
	    $GLOBALS['mapzoom']=1;
	}else{
	    $GLOBALS['mapzoom']=pow(gr,(1/2));
	}
}*/
//==============================



$id=$_GET["id"];
if(!$id and $GLOBALS['ss']["object_build_id"])$id=$GLOBALS['ss']["object_build_id"];
$GLOBALS['ss']["object_build_id"]=$id;

$func=$_GET["func"];
if(!$func and $GLOBALS['ss']["object_build_func"])$func=$GLOBALS['ss']["object_build_func"];
$GLOBALS['ss']["object_build_func"]=$func;

//print_r($_GET);

if(/*!$GLOBALS['ss']["master"] and */$_GET["master"])$GLOBALS['ss']["master"]=$_GET["master"];

//e($GLOBALS['ss']["master"]);

//e(0);
if($id and $GLOBALS['ss']['master']){//e(1);


//e($GLOBALS['ss']['master']);
/*<script type="text/javascript">
    _rot=0;
</script>*/

    //e($id);
    $object_build=new object($id);
    //e($object_build->name);
    $res=$object_build->res;//$object_build->resc($GLOBALS['ss']['useid']);


    $res=explode(':',$res);
    if($res[3]){
	$randrot=round(($res[3]-1+1)/15)*15;
    }else{
	$randrot=rand(-75/15,75/15)*15;
	
    }
    if($randrot<0)$randrot=$randrot+360;
    $res=$res[0].':'.$res[1].':'.$res[2];
    //model($res,$s=1,$rot=0,$slnko=1,$ciary=1,$zburane=0,$hore=0)
    //r($res);
    //e($GLOBALS['ss']['master']);
    //$js="\$.get('?token=".$_GET['token']."&e=map&q=".$GLOBALS['ss']['master'].".".$GLOBALS['ss']["object_build_func"]." $id,'+build_x+','+build_y+','+_rot, function(vystup){\$('#map').html(vystup);});";
    if(substr($res,0,1)!='{' and (substr($res,0,1)!='(' or strpos($res,'1.png'))){$q=true;}else{$q=false;}

    $js="buildx('".$GLOBALS['ss']['master']."','$id','".$GLOBALS['ss']["object_build_func"]."',build_x,build_y,".($q?('_rot'):(0)).");";

    
    if(!$q){
    ?>
	<script type="text/javascript">
        turnmap('grid',true);
	</script>
    <?php
    }

    if(strpos($res,'1.png')){$qq=true;}else{$qq=false;}
    $angle=(!$qq)?360:7*15;
?>

<?php /*<div style="position:absolute;"><div style="position:relative;left:-25;top:120;">
<?php icon("e=object_build;rot=".($rot-5).";noi=1","none","{rotate}",25); ?>
</div></div>
<!--==========-->
<div style="position:absolute;"><div style="position:relative;left:80;top:120;">
<?php icon("e=object_build;rot=".($rot+5).";noi=1","none","{rotate}",25); ?>
</div></div>*/
$hidex="\$('#create-build').css('display','none');turnmap('expand',false);";
if($q){
	$hide=$hidex;
}else{
	$hide='';
}

?>
<?php if($q){ ?>
<!--==========-->
<div style="position:absolute;"><div style="position:relative;left:-18;top:<?php e(round(95/$GLOBALS['mapzoom'])); ?>;">
<?php icon(js2('_rot=_rot-15;if(_rot<0){_rot=_rot+360;}build_model_rot(_rot);'),"rotate_left",lr('rotate_left'),25); ?>
</div></div>
<!--==========-->
<div style="position:absolute;"><div style="position:relative;left:<?php e(round(80/$GLOBALS['mapzoom'])); ?>;top:<?php e(round(95/$GLOBALS['mapzoom'])); ?>;">
<?php icon(js2('_rot=_rot+15;if(_rot>=360){_rot=_rot-360;}build_model_rot(_rot);'),"rotate_right",lr('rotate_right'),25); ?>
</div></div>
<!--==========-->
<?php } ?>
<div style="position:absolute;"><div style="position:relative;left:4;top:4;z-index:20;">
<?php icon(js2($hidex.(!$q?"turnmap('grid',false);map_units_time=0;":'')),"cancel",lr('cancel'),20); ?>
</div></div>
<!--==========-->
<div style="position:absolute;"><div id="build_button" style="display:none;position:relative;left:-1;top:<?php e(round(160/$GLOBALS['mapzoom'])); ?>;">
<?php /*icon(js2(($q?$hide.',':'').$js),"f_create_building_submit","{f_create_building_submit}",25);*/
    ahref(trr(nbsp2.lr('f_create_building_submit').nbsp2,14,3),js2(/*($q?$hide.';':$hide.';'."build('".$GLOBALS['ss']['master']."$master','$id','".$GLOBALS['get']['func']."');".';')*/$hide.';'.$js.';'.(!$q?'nmr=true;':'')));
 ?>
</div></div>
<div style="position:absolute;"><div style="position:relative;left:<?php e(round(-150+(110*0.75*0.5))); ?>px;width:300px;text-align:center;top:<?php e(round(160/$GLOBALS['mapzoom'])+20); ?>;">
<div id="create-build_message"><div style="border-width: 2px; border-style: solid; border-color: #222222;"><?php info(lr('create_move'),false); ?></div></div>
</div></div>
<?php
if($qq){
    $randrot=0;
}  else {
    //$randrot=rand(0,345/15)*15;
}

//BLBOST//$width=false;

for($rot=0;($q?($rot<$angle):($rot==0));$rot=$rot+15){
$rotx=$rot;
if($qq)$rotx=($rotx/15)+1;
//$img=model($res.':'.$rotx,0.75);
//$modelurl=(rebase(url.base.$GLOBALS['model_file']));
    $modelurl=modelx($res.':'.$rotx);
	//r($res.':'.$rotx);
	//r($modelurl);
	
	///BLBOST//if(!$width){
    //BLBOST//    list($width, $height) = getimagesize($modelurl);
	//BLBOST//}
	if(substr($res,0,1)=='['){
		$width=133;
		$height=254;
	}else{
		$width=82;
		$height=123;
	}
	
	
	
   $hovnonad=intval((110*0.75)/133*(254))-$height;

//if($rot==0 and (-$height+157)){e('<img src="'.imageurl('design/blank.png').'" border="0"  width="82" height="'.(-$height+157).'"><br/>');}
e('<div class="build_models" id="build_model_'.$rot.'" style="display:'.(($rot==$randrot or !$q)?'block':'none').';">'.(strpos($res,'{}')?'<img src="'.imageurl('design/blank.png').'" border="0" width="'.(110*0.75).'"  height="'.($hovnonad).'"><br>':'').'<img src="'.$modelurl.'"  style="border: 2px solid #cccccc;border-radius: 4px;" width="'.(round(110*0.75/$GLOBALS['mapzoom'])).'"></div>');
}
?>

<script type="text/javascript">
    _rot=<?php e($randrot); ?>;
    <?php if($q){ ?>
    build_model_rot=function(rot){
        $('.build_models').css('display','none');
        $('#build_model_'+rot).css('display','block');
    }
    bind_modelrot_function=function(event, delta){
    
        if(delta > 0) {
            _rot=_rot-15;if(_rot<0){_rot=_rot-(-<?php e($angle); ?>);}build_model_rot(_rot);    
        }else{
            _rot=_rot-(-15);if(_rot>=<?php e($angle); ?>){_rot=_rot-<?php e($angle); ?>;}build_model_rot(_rot);    
        }
    
    
    }
    $(document).unbind('mousewheel');
    $(document).bind('mousewheel',bind_modelrot_function);
    <?php }else{ ?>
    build_model_rot=function(rot){1;}
    bind_modelrot_function=function(event, delta){1;}
    $(document).unbind('mousewheel');
    $(document).bind('mousewheel', bind_modelrot_function);
    <?php } ?>
</script>
<?php } ?>
