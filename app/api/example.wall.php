<?php
/**
 * Ukázková Towns API Aplikace - Zdi
 *
 * @copyright 2015 Towns.cz
 * @link http://api.towns.cz/
 * @author     Pavol Hejný
 * @version    1.0
 *
 */

echo '<div class="pageDescription"><b>Správce opevnění města:</b> Nejdříve vyberte typ hradby, potom klikáním na mapu stavíte nebo rozebíráte jednotlivé hradební články.<br>
[W] Vaše hradby<br>
[O] Jiná vaše budova<br>
[X] Nepřátelská budova
</div>';


//----------------------------------------------------------------Výběr typů zdí

//-------------------------Zjištění všech šablon zdí
$objects = TownsApi('list', 'id,_name,resurl,x,y,ww,func,group',array('unique',"group='wall'"), '',100);
$objects = $objects['objects'];

//-------------------------Aktuálně zvolená zeď

if($_GET['selected_object'])
$_SESSION['selected_object']=$_GET['selected_object'];

//-------------------------Vykreslení výběru zdí
if($objects)
foreach($objects as $object){
?>
<a href="?selected_object=<?=$object['id']?>">
<img width="50" src="<?=$object['resurl']?>" border="<?=($object['id']==$_SESSION['selected_object']?2:0)?>" alt="<?=$object['_name']?>" title="<?=$object['_name']?>">
</a>
<?php
}

//----------------------------------------------------------------Změna zdí

if($_GET['wx'] AND $_GET['wy']){
	
	//-------------------------Zjištění, co na dané pozici je
	$wx=intval($_GET['wx']);
	$wy=intval($_GET['wy']);
	

	$result = TownsApi('list', 'id', array("group='wall'","box($wx,$wy,$wx,$wy)"), 'y,x');
	$result = $result['objects']; 
	if($result){
		//-------------------------Pokud zeď - je zavolaná akce pro rozebrání.
		$result=TownsApi('dismantle',$result[0]['id']);
		//-------------------------
	}else{
		//-------------------------Pokud nic, je zeď postavena.
		$result=TownsApi('create',$_SESSION['selected_object'],$wx,$wy);
		//-------------------------
	}
	//-------------------------Vypsání výsledku akce
	report($result);
	

}

//----------------------------------------------------------------Pozice na mapě a zoom


require('positions.php');


//----------------------------------------------------------------Data mapy

//-------------------------Vytvoření prázdné mapy

$map=array();
for($y=0;$y<$_SESSION['zoom'];$y++){
	$map[$y]=array();
	for($x=0;$x<$_SESSION['zoom'];$x++){
		$map[$y][$x]=array();
	}
}


//-------------------------Terény
$result = TownsApi('list', 'x,y,resurl', array("terrain","box({$_SESSION['x']},{$_SESSION['y']},".($_SESSION['x']+$_SESSION['zoom']).",".($_SESSION['y']+$_SESSION['zoom']).")"), 'y,x');
$result = $result['objects'];
if($result)
foreach($result as $row){

    $row['x']=intval($row['x']-$_SESSION['x']);
    $row['y']=intval($row['y']-$_SESSION['y']);

	$map[$row['y']][$row['x']][0]='';
    $map[$row['y']][$row['x']][1]=$row['resurl'];

}

//-------------------------Všechny moje bodovy
$result = TownsApi('list', 'x,y,resurl', array("mybuildings","box({$_SESSION['x']},{$_SESSION['y']},".($_SESSION['x']+$_SESSION['zoom']).",".($_SESSION['y']+$_SESSION['zoom']).")"), 'y,x');
$result = $result['objects'];
if($result)
foreach($result as $row){

    $row['x']=intval($row['x']-$_SESSION['x']);
    $row['y']=intval($row['y']-$_SESSION['y']);

	$map[$row['y']][$row['x']][0]='O';
    $map[$row['y']][$row['x']][1]=$row['resurl'];

}
//-------------------------Všechny ostatní budovy - Nepřítel
$result = TownsApi('list', 'x,y,resurl', array("notmybuildings","box({$_SESSION['x']},{$_SESSION['y']},".($_SESSION['x']+$_SESSION['zoom']).",".($_SESSION['y']+$_SESSION['zoom']).")"), 'y,x');
$result = $result['objects'];
if($result)
foreach($result as $row){

    $row['x']=intval($row['x']-$_SESSION['x']);
    $row['y']=intval($row['y']-$_SESSION['y']);

	$map[$row['y']][$row['x']][0]='X';
    $map[$row['y']][$row['x']][1]=$row['resurl'];

}

//-------------------------Všechny moje zdi
$result = TownsApi('list', 'x,y,resurl', array("mybuildings","group='wall'","box({$_SESSION['x']},{$_SESSION['y']},".($_SESSION['x']+$_SESSION['zoom']).",".($_SESSION['y']+$_SESSION['zoom']).")"), 'y,x');
$result = $result['objects'];
if($result)
foreach($result as $row){

    $row['x']=intval($row['x']-$_SESSION['x']);
    $row['y']=intval($row['y']-$_SESSION['y']);

	$map[$row['y']][$row['x']][0]='W';
    $map[$row['y']][$row['x']][1]=$row['resurl'];

}



//----------------------------------------------------------------Vykreslení mapy

$cell=22;//Velikost jednoho políčka mapy
$moveby=2;//Posouvat o xx políček

?>




<table width="500" height="500" border="0" cellpadding="0" cellspacing="0" style="position:relative;left:120px;">
    <tr>
        <td align="center" valign="middle"><a class="move" href="?xm=<?=-$moveby?>&ym=<?=-$moveby?>"	onclick="return loadMap(<?=-$moveby?>,<?=-$moveby?>);"></a></td>
        <td align="center" valign="middle"><a class="move" href="?ym=<?=-$moveby?>"						onclick="return loadMap(0,<?=-$moveby?>);"></a></td>
        <td align="center" valign="middle"><a class="move" href="?xm=<?=$moveby?>&ym=<?=-$moveby?>"		onclick="return loadMap(<?=$moveby?>,<?=-$moveby?>);"></a></td>
    </tr>
    <tr>
        <td align="center" valign="middle"><a class="move" href="?xm=<?=-$moveby?>"						onclick="return loadMap(<?=-$moveby?>,0);"></a></td>
        <td align="center" valign="middle">

            <table border="0" cellpadding="0" cellspacing="0">
                <?php
                for($y=0;$y<$_SESSION['zoom'];$y++){
                    echo('<tr>');
                    for($x=0;$x<$_SESSION['zoom'];$x++){
                        echo('<td width="'.$cell.'" height="'.$cell.'" style="background-image: url('.($map[$y][$x][1]).');background-position: center 80%;"><a href="?wx='.($_SESSION['x']+$x).'&wy='.($_SESSION['y']+$y).'">['.($map[$y][$x][0]?$map[$y][$x][0]:'&nbsp;&nbsp;').']</a></td>');
                    }
                    echo('</tr>');
                }
                ?>


            </table>

        </td>
        <td align="center" valign="middle"><a class="move" href="?xm=<?=$moveby?>"						onclick="return loadMap(<?=$moveby?>,0);"></a></td>
    </tr>
    <tr>
        <td align="center" valign="middle"><a class="move" href="?xm=<?=-$moveby?>&ym=<?=$moveby?>"		onclick="return loadMap(<?=-$moveby?>,<?=$moveby?>);"></a></td>
        <td align="center" valign="middle"><a class="move" href="?ym=<?=$moveby?>"						onclick="return loadMap(0,<?=$moveby?>);"></a></td>
        <td align="center" valign="middle"><a class="move" href="?xm=<?=+$moveby?>&ym=<?=$moveby?>"		onclick="return loadMap(<?=$moveby?>,<?=$moveby?>);""></a></td>
    </tr>
</table>






