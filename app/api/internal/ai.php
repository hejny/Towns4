<?php
/**
 * Ukázková Towns API Aplikace - AI - automatická inteligence - stavění hradeb kolem města
 *
 * @copyright 2015 Towns.cz
 * @link http://api.towns.cz/
 * @author     Pavol Hejný
 * @version    1.0
 *
 */


if(!TownsBackend) {

    //============================================================================================Frontend
    /*Tato část funguje jako klasická aplikace, v této ukázce je využita k nastavení intervalu spouštění.*/
//----------------------------------------------------------------HTML
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>AI | Ukázka Towns API</title>
        <meta charset="UTF-8">
        <meta name="description" content="Ukázkový příklad, jakým způsobem pracuje AI skript."/>
        <?php TownsStyle(); ?><!--Zde funkce natvrdo vrátí styl, v Towns se místo toho použije externí .css soubor. -->
    </head>
    <body>

    <div class="description">
        Každá aplikace může běžet na pozadí. tzn. být spouštěna v pravidelném intervalu bez ohledu na to, zda je hráč online či ne.
        <br><br>
        Tuto vlastnost nastavíte pomocí proměnné $TownsUser['delay'].<br>
        Ta nastavuje počet sekund za kdy se má (znovu) spustit běh aplikace na pozadí. Defultně je nastavená hodnota false tzn, aplikace nepoběží na pozadí. Pokud je hodnota příliš nízká, bude aplikace znovu spuštěna za nejnižší možnou dobu, která je obvykle 300 (5 minut). Tato hodnota se zachovává a aplikace se spouští ve stejném intervalu, dokud není hodnota $TownsUser['delay'] přestavena.
        <br><br>
        Tato  konkrétní ukázka staví hradby kolem města.
    </div>

    <?php
    //----------------------------------------------------------------Kontrola přihlášení

    //Při umístění aplikace na server Towns si můžete nastavit, že bude přístupná pouze po přihlášení.
    //Každá aplikace má své pevné URL, které je dostupné každému a proto je dobré odchytit případ nepřihlášeného hráče.
    //Zadáním URL se vždy spustí Frontend aplikace.

    if (!TownsLogged) {
        die('<div class="error">Tato aplikace vyžaduje přihlášení.</div></body></html>');
    }

    //----------------------------------------------------------------Nastavení AI skriptu

    if($_POST['delay_change'])
        $TownsUser['delay']=intval($_POST['delay']);

    if(!$TownsUser['delay'])
        $TownsUser['delay']=300;

    ?>

    <form method="post" action="?">
        <input type="hidden" name="delay_change" value="r">
        V jakém intervalu se má spouštět aplikace na pozadí:<br>
        <input type="number" name="delay" value="<?=$TownsUser['delay']?>"><br>
        <input type="submit" value="Změnit">
    </form>
    <br>

    <?php
    //----------------------------------------------------------------Výběr typů zdí

    //print_r($_GET);

    //-------------------------Zjištění všech šablon zdí
    $buildings = TownsApi('list', 'id,_name,resurl,x,y,ww,func,group',array('unique',"group='wall'"), '',100);
    $buildings = $buildings['objects'];

    //-------------------------Aktuálně zvolená zeď

    if($_GET['selected_wall'])
        $TownsUser['selected_wall']=$_GET['selected_wall'];

    //-------------------------Defaultní zeď

    if(!$TownsUser['selected_wall'])
        $TownsUser['selected_wall']=$buildings[0]['id'];

    //-------------------------Vykreslení výběru zdí
    if($buildings)
        foreach($buildings as $building){
            ?>
            <a href="?selected_wall=<?=$building['id']?>">
                <img width="50" src="<?=$building['resurl']?>" border="<?=($building['id']==$TownsUser['selected_wall']?2:0)?>" alt="<?=$building['_name']?>" title="<?=$building['_name']?>">
            </a>
        <?php
        }

    //----------------------------------------------------------------Rozebrat všechny hradby
    ?>

    <br><br>

    <?php
    if(isset($_GET['dismantleAll'])) {
        $buildings = TownsApi('list', 'id', array('mybuildings', 'group=\'wall\''));
        $buildings = $buildings['objects'];
        foreach ($buildings as $building) TownsApi('dismantle', $building['id']);
    }
    ?>
    <a href="?dismantleAll"  onclick="return confirm('Rozebrat všechny hradby kolem města?');">Rozebrat jednoránově všechny hradby</a>



    </body>
    </html>


<?

    //============================================================================================
}else{
    //============================================================================================Backend
    /*Tato část se bude pravidelně spouštět sama.  */

    $dist=2;//V jaké vzdálenosti od budov bude postaveno opevnění

    //--------------------------------Všechny budovy (kromě hradeb) kolem kterých se bude stavět opevnění

    $buildings = TownsApi('list', 'id,x,y,name',array('mybuildings','group!=\'wall\''));
    $buildings = $buildings['objects'];

    //--------------------------------Rozsah budov

    $min_x=$buildings[0]['x'];
    $min_y=$buildings[0]['y'];
    $max_x=$buildings[0]['x'];
    $max_y=$buildings[0]['y'];

    //----------------

    foreach($buildings as $building){
        if($building['x']<$min_x)$min_x=$building['x'];
        if($building['y']<$min_y)$min_y=$building['y'];
        if($building['x']>$max_x)$max_x=$building['x'];
        if($building['y']>$max_y)$max_y=$building['y'];
    }

    //----------------

    $min_x=round($min_x);
    $min_y=round($min_y);
    $max_x=round($max_x);
    $max_y=round($max_y);

    //----------------

    $range_x=$max_x-$min_x+$dist*2;
    $range_y=$max_y-$min_y+$dist*2;

    //--------------------------------Vytvoření prázné mapy v potřebném rozsahu
    /*  Na mapě jsou tyto hodnoty:
     *
     * 0 Nic
     * 1 Vaše budova
     * 2 Vaše oblast kolem budov
     * 3 Okraj oblasti - tam kde se mají stavět hradby
     * */

    $map=array();
    for($y=0;$y<=$range_y;$y++){
        $map[$y]=array();
        for($x=0;$x<=$range_x;$x++){
            $map[$y][$x]=0;
        }
    }

    //--------------------------------Zjištění oblasti kolem budov

    foreach($buildings as $building) {

        $x = round($building['x']) - $min_x + $dist;
        $y = round($building['y']) - $min_y + $dist;


        for ($y_ = $y - $dist; $y_ <= $y + $dist; $y_++) {
            for ($x_ = $x - $dist; $x_ <= $x + $dist; $x_++) {
                $map[$y_][$x_] = 2;
            }
        }
    }

    //--------------------------------Vaše budovy - z hlediska fungování nemá význam, slouží k zobrazení tabulky

    foreach($buildings as $building){

        $x=round($building['x'])-$min_x+$dist;
        $y=round($building['y'])-$min_y+$dist;

        $map[$y][$x]=1;
    }


    //--------------------------------Kde se vaše oblast dotýká okrajů mapy

    //----------------Horní okraj
    for($x=0;$x<=$range_x;$x++)
        if($map[0][$x]==2)$map[0][$x]=3;
    //----------------Dolní okraj
    for($x=0;$x<=$range_x;$x++)
        if($map[$range_y][$x]==2)$map[$range_y][$x]=3;
    //----------------Pravý okraj
    for($y=0;$y<=$range_y;$y++)
        if($map[$y][0]==2)$map[$y][0]=3;
    //----------------Levý okraj
    for($y=0;$y<=$range_y;$y++)
        if($map[$y][$range_x]==2)$map[$y][$range_x]=3;
    //----------------


    //--------------------------------Kde se vaše oblast dotýká "ničeho"

    for($y=0;$y<=$range_y;$y++){
        for($x=0;$x<=$range_x;$x++){
            if($map[$y][$x]==0){

                for($y_=$y-1;$y_<=$y+1;$y_++){
                    for($x_=$x-1;$x_<=$x+1;$x_++){


                        if($map[$y_][$x_]==2)
                            $map[$y_][$x_]=3;
                    }
                }



            }
        }
    }

    //--------------------------------Zobrazení - slouží pouze k debugování. Běžný hráč neuvidí výstupy z běhu aplikace na pozadí.


    $cell=22;
    ?>
    <table border="0" cellpadding="10" cellspacing="0">
        <?php
        for($y=0;$y<=$range_y;$y++){
            echo('<tr>');
            for($x=0;$x<=$range_x;$x++){

                $colors=array('eeeeee','6666ff','9999cc','444444');


                echo('<td width="'.$cell.'" height="'.$cell.'" bgcolor="'.$colors[$map[$y][$x]].'">&nbsp;</td>');
            }
            echo('</tr>');
        }
        ?>


    </table>
    <?php

    //--------------------------------Postavení hradeb tam, kde je okraj oblasti

    for($y=0;$y<$range_y+2;$y++) {
        for ($x = 0; $x < $range_x + 2; $x++) {
            if ($map[$y][$x] == 3) {

                echo("<div>TownsApi('create',{$TownsUser['selected_wall']},$min_x+$x-1,$min_y+$y-1);</div>");
                TownsApi('create', $TownsUser['selected_wall'], $min_x + $x - $dist, $min_y + $y - $dist);
                //break(2);

            }
        }
    }
    //--------------------------------

    //============================================================================================
}

?>




