<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/attack/mine.php

   Stránka pro zahájení těžby
*/
//==============================


//exit2(1111);

    $iconsize=35;
    $iconbrd=3;

/*e('<script>refreshMap();'.urlxr('e=miniprofile',false).';</script>');
w_close('content');
die();*/

if($GLOBALS['ss']['attack_report']){
    unset($GLOBALS['ss']['attack_report']);
    //e('<script>refreshMap();'/*.urlxr('e=miniprofile',false)*/.';</script>');

      ?>
<script>
setTimeout(function(){
   //alert('aaa');
    refreshMap();
    w_close('mine');
    w_close('window_mine');
},10);
</script>
<?php


    //w_close('content',true);
}else{

    //r($GLOBALS['get']);
    $limit=$GLOBALS['get']['attack_limit'];
    list($attack_master,$attack_function)=explode('-',$GLOBALS['get']['attack_mafu']);
    $object=new object($attack_master);
    if($object->loaded){
        $x=$object->x;$y=$object->y;$ww=$object->ww;
        $func=$object->func->func($attack_function);
        //r($func);
        $distance=$func['distance'];
        $attack=$func['attack'];
        $sql="SELECT id,func FROM [mpx]objects WHERE ww=$ww  AND type='$limit' AND POW(x-$x,2)+POW(y-$y,2)<=POW($distance,2) ORDER BY fr  DESC";
        $attack_id=false;
        foreach(sql_array($sql) as $row){
            list($tmpid,$tmpfunc)=$row;
            $tmpfunc=new func($tmpfunc);
            $defence=$tmpfunc->func('defence');
            $defence=$defence['defence'];
            
	    //e("$defence<$attack");
            //e($tmpid);
            if($defence<$attack){//e($tmpid.' - ok');
                $attack_id=$tmpid;
                break;
            }
            //br();
            
    }
   // e($attack_id);
    if($attack_id){
        $url="e=mine;ee=attack-mine;q=$attack_master.$attack_function $attack_id";
       urlx($url);
        //e($url);
    }else{error(lr('attack_no_'.$limit));}
    }else{}
}

?>
