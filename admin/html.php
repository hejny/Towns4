<?php
/* Towns4Admin, www.towns.cz 
   © Pavel Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================
?>
<h3>Návštěvnost</h3>
vzorek návštěvnosti ( z <?php e(mpx) ?>log )<br><br>
<?php
e('<table border="1" width="40%">');
e('<tr>');
e('<td width="10"><a href="?page=html&amp;scale=1">#</a></td>');
e('<td><b>total</b></td>');
e('<td><b>refresh</b></td>');
e('<td><b>build</b></td>');
e('<td><b>attack</b></td>');
e('<td><b>users</b></td>');
e('<td><b>new</b></td>');
e('<td><b>none</b></td>');
e('<td><b>none%</b></td>');
e('</tr>');

if($_GET['scale']){
    $scale=1;
}else{
    $scale=600/gr;
}

date_default_timezone_set('Europe/Prague');


$dd=19;
$tm=ceil(time()/(3600*24))*(3600*24);
$tm=$tm-(3600*2);

//e(contentlang(timer($tm)));

$tm-=($dd)*(3600*24);
//$tm+=-3600;//GMT+1
$where="`ip`!='".$_SERVER['REMOTE_ADDR']."' AND `user_agent` NOT LIKE '%facebook%' AND `user_agent` NOT LIKE '%google%' AND `user_agent` NOT LIKE '%Bot%' AND `user_agent` NOT LIKE '%bot%'";

$i=0;
while($i<=$dd){
    
    
    
    $ips1=round($scale*sql_1data("SELECT COUNT(DISTINCT(`ip`)) FROM `[mpx]log` WHERE `function`='html' AND `time`>$tm AND `time`<$tm+(3600*24) AND $where"));//total
    $ips2=round($scale*sql_1data("SELECT COUNT(`ip`) FROM `[mpx]log` WHERE `function`='html' AND `time`>$tm AND `time`<$tm+(3600*24)  AND $where"));//refresh
    
    $ipsxb=round($scale*sql_1data("SELECT COUNT(1) FROM `[mpx]log` WHERE `function`='create' AND `time`>$tm AND `time`<$tm+(3600*24)  AND $where"));//build
    $ipsxa=round($scale*sql_1data("SELECT COUNT(1) FROM `[mpx]log` WHERE `function`='attack' AND `time`>$tm AND `time`<$tm+(3600*24)  AND $where"));//attack   
    
    $ips3=round($scale*sql_1data("SELECT COUNT(DISTINCT(`ip`)) FROM `[mpx]log` WHERE `logid`!='0' AND `time`>$tm AND `time`<$tm+(3600*24)  AND $where"));//users
    $ips4=round($scale*sql_1data("SELECT COUNT(DISTINCT(`ip`)) FROM `[mpx]log` WHERE `function`='register' AND `time`>$tm AND `time`<$tm+(3600*24)  AND $where"));//new
    $ips5=$ips1-$ips3;//sql_1data("SELECT COUNT(DISTINCT(`ip`)) FROM `[mpx]log` WHERE `function`='html' AND `time`>$tm AND `time`<$tm+(3600*24) ");//none
   
    
    
    if($ips1 or $i==$dd ){
        
        e('<tr>');
        e('<td>'.date('d.m',$tm)/*contentlang(timer($tm))*/.'</td>');
        e('<td>'.$ips1.'</td>');//total
        e('<td>'.$ips2.'</td>');//refresh
        e('<td>'.$ipsxb.'</td>');//build
        e('<td>'.$ipsxa.'</td>');//attack
        e('<td>'.$ips3.'</td>');//users
        e('<td>'.$ips4.'</td>');//new
        e('<td>'.$ips5.'</td>');//none
        e('<td>'.(round($ips5/$ips1*100)).'%</td>');//none%
        
        e('</tr>');
        
    }
    
    $tm+=(3600*24);
    $i++;
}

e('</table>');

//
//$ipsx=sql_1data("SELECT COUNT(`ip`) FROM `world1_log` WHERE `function`='html' AND `time`>".(time()-(3600*24)));
//$ips2=sql_1data("SELECT COUNT(DISTINCT(`ip`)) FROM `world1_log` WHERE `function`='html' AND `time`>".(time()-(3600*24*2)));
//$ipsx2=sql_1data("SELECT COUNT(`ip`) FROM `world1_log` WHERE `function`='html' AND `time`>".(time()-(3600*24*2)));

?>
<br>
<b>*total</b> = Počet unikátních IP za daný den<br>
<b>*refresh</b> = Počet obnovení stránky (vyvolání hlavního html)<br>
<b>*build</b> = Počet postavených budov<br>
<b>*attack</b> = Počet útoků<br>
<b>*users</b> = Počet aktivních hráčů za daný den (jakákoliv akce i registrace) (pouze unikátní IP)<br>
<b>*new</b> = Počet nových hráčů za daný den (pouze unikátní IP)<br>
<b>*none</b> = Počet unikátních ip bez jakéhokoliv uživatele<br>
<br>
<b>*</b> Není započítána vaše IP <?php e($_SERVER['REMOTE_ADDR']); ?><br>


