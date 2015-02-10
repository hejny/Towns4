<?php
/* Towns4Admin, www.towns.cz 
   © Pavel Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================
?>

<h3>Update FS/FP</h3>
Aktualizuje FS, FP, FC, FR, FX, hard, expand, collapse podle aktuálního času<br/>
<b>Upozornění: </b>Celý tento proces může trvat i několik hodin.<br/>
<b>Upozornění: </b>Tento proces aktualizuje všem budovám čas.<br/>

<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_WARNING );
ini_set("max_execution_time","1000");
$time=time();
//---------------------

if($_GET['actual']){
    $actual=$_GET['actual'];
}else{
    $actual=1;    
}

$total=sql_1data('SELECT count(1) FROM `[mpx]pos_obj` WHERE type=\'building\'');
$percent=intval($actual/$total*100);

$id=sql_1data('SELECT id FROM `[mpx]pos_obj` WHERE type=\'building\' LIMIT '.$actual.',1');

echo("<h2>$percent% ($actual/$total)</h2>");
echo("<b>$id</b><br>");
//echo("<table border=\"1\" width=\"424\" height=\"211\"><tr><td>");
$tmpobject=new object($id);
$tmpobject->update(true);
sql_query('UPDATE `[mpx]pos_obj` SET `fp`=`fs` WHERE id='.$id);

if($actual>=$total){
echo("<b>hotovo</b><br>");
echo("<a href=\"?actual=1\">znovu</a>");
}else{
echo("loading: ".($actual+1)."<br/>");
echo("<a href=\"?actual=1\">znovu</a><br/>");
if($actual!=1 or $_GET["start"]){
echo("<a href=\"?actual=".($actual+1)."\">next</a><br/>");
echo('<script language="javascript">
    window.location.replace("?actual='.($actual+1).'");
    </script>');/**/
}else{
echo("<a href=\"?actual=".($actual+1)."\"><h3>start</h3></a><br/>");
}
}



?>
