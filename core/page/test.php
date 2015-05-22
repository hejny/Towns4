<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2015
   _____________________________

   core/page/test.php

   testování
*/

$border=2;
$terrains=array('t3','t4','t6','t7','t8','t9','t12');

list($x,$y)=sql_row('SELECT x,y FROM [mpx]pos_obj WHERE  type=\'town\' AND  ww='.$GLOBALS['ss']['ww'].' AND '.objt().' ORDER BY starttime DESC');
$px=$x=round($x);
$py=$y=round($y);

$limit=1000;
while($limit>0){$limit--;

    e("($x,$y)");

    $terrain=sql_1data('SELECT res FROM [mpx]pos_obj WHERE type=\'terrain\' AND  ww='.$GLOBALS['ss']['ww'].' AND x='.($x).' AND y='.($y).' AND '.objt());

    e(" - $terrain");
    if(in_array($terrain,$terrains)){


        $buildingcount=sql_1data('SELECT count(id) FROM [mpx]pos_obj WHERE (type=\'building\' or type=\'rock\' OR type=\'tree\') AND  ww='.$GLOBALS['ss']['ww'].' AND x>'.($x-$border).' AND y>'.($y-$border).' AND x<'.($x+$border).' AND y<'.($y+$border).' AND '.objt());

        e(" - $buildingcount");
        if($buildingcount<=2){


            e(" - <b>OK</b>");
            break;
        }


    }

    $q=(rand(1,2)==1)?1:-1;

    if(rand(1,2)==1){
        $x+=$q*$border;
        $y+=rand(-$border,$border);
    }else{
        $y+=$q*$border;
        $x+=rand(-$border,$border);
    }


    if($x<1)$x=$px;
    if($y<1)$y=$py;
    if($x>mapsize)$x=$px;
    if($y>mapsize)$y=$py;

    e('<br>');

}

print_r($result);


?>
