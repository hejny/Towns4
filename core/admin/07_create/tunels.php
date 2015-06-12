<?php
/* Towns4Admin, www.towns.cz 
   © Pavol Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================
?>
<h3>Tunely</h3>
Tento nástroj slouží k analýze a vytváření tunelů skrz mapu.<br/>
<br/>

<?php

//------------------------------------Terrains
if($_GET['terrain']){

    $GLOBALS['ss']['tunel_terrain']=$_GET['terrain'];
}


$terrains=explode(',',tunel_terrains);
foreach($terrains as $terrain){

    if(!$GLOBALS['ss']['tunel_terrain']){
        $GLOBALS['ss']['tunel_terrain']=$terrain;
    }

    if($terrain==$GLOBALS['ss']['tunel_terrain']){
        textb($terrain);
    }else{
        e('<a href="?page=tunels&amp;terrain='.$terrain.'">'.$terrain.'</a>');
    }
    e(nbsp2);

}


//------------------------------------Create tunels

if($_GET['create']){

    $positions=array();
    $terrains=explode(',',tunel_terrains);
    list($ww1,$ww2)=explode(',',$_GET['create']);

    //------------------Create 2+ positons
    while(count($positions)<2) {//@todo Dokončit převod Mapy do objektů
        list(list($start_x, $start_y)) = sql_array("SELECT x,y FROM [mpx]pos_obj WHERE res='" . $GLOBALS['ss']['tunel_terrain'] . "' AND ww=" . sqlx($ww1) . " ORDER BY RAND() LIMIT 1");

        foreach (array($ww1, $ww2) as $aac_ww) {

            $aac_x = $start_x;
            $aac_y = $start_y;
            $aac_rot = pi() * (rand(0, 100) / 100);
            $aac_mx = cos($aac_rot);
            $aac_my = sin($aac_rot);
            $aac_limit = 100;
            //---------
            while ($aac_limit > 0) {


                $aac_terrain = sql_1data("SELECT res FROM [mpx]pos_obj WHERE x=" . round($aac_x) . " AND y=" . round($aac_y) . " AND ww=" . sqlx($aac_ww),3);
                e($aac_terrain.',');

                if ($aac_terrain and !in_array($aac_terrain, $terrains)) {
                    $positions[] = array($aac_x, $aac_y, $aac_ww);
                    break;
                }

                $aac_x += $aac_mx;
                $aac_y += $aac_my;


                if($aac_x<1)$aac_x=mapsize+$aac_x;
                if($aac_y<1)$aac_y=mapsize+$aac_y;
                if($aac_x>mapsize)$aac_x=1;
                if($aac_y>mapsize)$aac_y=1;

                $aac_limit--;
            }
            //die();
            //---------


        }
        hr();
    }
    //------------------
    array_splice($positions, 2);
    //------------------INSERT objects
    $nextid=nextid();


    sql_insert('objects',array(
        'id' => $nextid,
        'name' => tunel_name,
        'type' => 'building',
        'userid' => '',
        'origin' => '',
        'fp' => '',
        'func' => '',
        'hold' => '',
        'res' => tunel_res,
        'profile' => '',//@todo doplnit popis tunelů
        'set' => '',
        'own' => '',
        't' => time(),
        'pt' => ''
    ),3);
    //------------------INSERT positions
    foreach($positions as $position){
        list($aac_x, $aac_y, $aac_ww)=$position;
        sql_insert('positions', array(
            'id' => $nextid,
            'ww' => $aac_ww,
            'x' => $aac_x,
            'y' => $aac_y,
            'traceid' => '',
            'starttime' => time(),
            'readytime' => '',
            'stoptime' => ''
        ),3);
    }
    //------------------
}
//------------------------------------

$maxlayer=10;
$maxtunels=5;


th('#');
for($wwp=0;$wwp<=$maxtunels;$wwp++) {
    th('+'.$wwp);
}
tr();

for($ww=1;$ww<=$maxlayer;$ww++) {

    th($ww);



    for($wwp=0;$wwp<=$maxtunels;$wwp++) {

        $number=sql_1number('SELECT count(1) FROM [mpx]objects WHERE (SELECT count(1) FROM [mpx]positions WHERE [mpx]positions.id=[mpx]objects.id AND (ww='.($ww+$wwp).' OR ww='.($ww).') AND'.objt('[mpx]positions').')>=2 ');
        td('<a href="?page=tunels&amp;create='.($ww).','.($ww+$wwp).'">'.$number.'</a>');


    }

    tr();

}
table($maxtunels*100,array('right','top'));




?>
