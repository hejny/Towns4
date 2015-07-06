<?php
/* Towns4Admin, www.towns.cz 
   © Pavol Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================

$scale=1;


//---------------------------------------------------------

?>
<h3>Hráči a návštěvnost</h3>
vzorek návštěvnosti ( z <?php e(mpx) ?>log )<br><br>
<?php
e('<table border="1" width="40%">');
e('<tr>');
e('<td width="10"><!--<a href="?page=html&amp;scale=1&amp;countip='.$_GET['countip'].'&amp;dd='.$_GET['dd'].'">#</a>--></td>');
e('<td><b>MAU</b></td>');
e('<td><b>DAU</b></td>');
e('<td><b>MAUx</b></td>');
e('<td><b>DAUx</b></td>');
e('<td><b>total</b></td>');
e('<td><b>refresh</b></td>');
e('<td><b>build</b></td>');
e('<td><b>attack</b></td>');
e('<td><b>users</b></td>');
e('<td><b>new</b></td>');
e('<td><b>none</b></td>');
e('<td><b>none%</b></td>');
e('</tr>');



date_default_timezone_set('Europe/Prague');


$dd=$_GET['dd']?$_GET['dd']:7;
$tm=floor(time()/(3600*24))*(3600*24);
$tm=$tm-(3600*1);

//e(contentlang(timer($tm)));

$tm-=($dd)*(3600*24);
//$tm+=-3600;//GMT+1
$where=($_GET['countip']?'':"`ip`!='".$_SERVER['REMOTE_ADDR']."' AND ")."`user_agent` NOT LIKE '%facebook%' AND `user_agent` NOT LIKE '%google%' AND `user_agent` NOT LIKE '%Bot%' AND `user_agent` NOT LIKE '%bot%'";

$i=0;
while($i<=$dd){

	//-----------------------------------------MAU

    $maus=array();
	$mausx=array();
	$name="(SELECT name FROM `[mpx]pos_obj` WHERE `[mpx]log`.logid=`[mpx]pos_obj`.id)";
	$mau=sql_array("SELECT DISTINCT(`ip`),$name AS name,logid FROM `[mpx]log` WHERE (`function`='create' OR `function`='attack') AND `time`>$tm-(3600*24*29) AND `time`<$tm+(3600*24)-1 AND logid!='0' AND $name NOT LIKE 'NPC%' AND $where");//br();//MAU
	foreach($mau as $mau1){
		if($mau1[1]==$mau1[2]){
			$mausx[$mau1[0]]='['.$mau1[0].']';//id2name($mau1[1]);
		}else{
			$maus[$mau1[0]]=$mau1[1];//id2name($mau1[1]);
			$mausx[$mau1[0]]=$mau1[1];//id2name($mau1[1]);
		}
	}
	$maus = array_unique($maus);
	$mausx = array_unique($mausx);

	$mau=round($scale*count($maus));//round($scale*sql_1data("SELECT COUNT(DISTINCT(`ip`)) FROM `[mpx]log` WHERE (`function`='create' OR `function`='attack') AND `time`>$tm-(3600*24*29) AND `time`<$tm+(3600*24)-1 AND $GLOBALS['ss']['logid']!='0' AND $where"));//MAU
	$maux=round($scale*count($mausx));
	$maus=implode(', ',$maus);
	$mausx=implode(', ',$mausx);
	//-----------------------------------------DAU
	$daus=array();
	$dausx=array();
	$name="(SELECT name FROM `[mpx]pos_obj` WHERE `[mpx]log`.logid=`[mpx]pos_obj`.id)";
	$dau=sql_array("SELECT DISTINCT(`ip`),$name AS name,logid FROM `[mpx]log` WHERE (`function`='create' OR `function`='attack') AND `time`>$tm AND `time`<$tm+(3600*24)-1 AND logid!='0' AND $name NOT LIKE 'NPC%' AND $where");//br();//dau
	foreach($dau as $dau1){
		if($dau1[1]==$dau1[2]){
			$dausx[$dau1[0]]='['.$dau1[0].']';//id2name($dau1[1]);
		}else{
			$daus[$dau1[0]]=$dau1[1];//id2name($dau1[1]);
			$dausx[$dau1[0]]=$dau1[1];//id2name($dau1[1]);
		}
	}
	$daus = array_unique($daus);
	$dausx = array_unique($dausx);

	$dau=round($scale*count($daus));
	$daux=round($scale*count($dausx));
	$daus=implode(', ',$daus);
	$dausx=implode(', ',$dausx);

	//-----------------------------------------



	//$dau=round($scale*sql_1data("SELECT COUNT(DISTINCT(`ip`)) FROM `[mpx]log` WHERE (`function`='create' OR `function`='attack') AND `time`>$tm AND `time`<$tm+(3600*24)-1 AND $GLOBALS['ss']['logid']!='0' AND $where"));//DAU

    $ips1=round($scale*sql_1data("SELECT COUNT(DISTINCT(`ip`)) FROM `[mpx]log` WHERE `function`='html' AND `time`>$tm AND `time`<$tm+(3600*24)-1 AND $where"));//total
    $ips2=round($scale*sql_1data("SELECT COUNT(`ip`) FROM `[mpx]log` WHERE `function`='html' AND `time`>$tm AND `time`<$tm+(3600*24)-1  AND $where"));//refresh
    
    $ipsxb=round($scale*sql_1data("SELECT COUNT(1) FROM `[mpx]log` WHERE `function`='create' AND `time`>$tm AND `time`<$tm+(3600*24)-1  AND $where"));//build
    $ipsxa=round($scale*sql_1data("SELECT COUNT(1) FROM `[mpx]log` WHERE `function`='attack' AND `time`>$tm AND `time`<$tm+(3600*24)-1  AND $where"));//attack   
    
    $ips3=round($scale*sql_1data("SELECT COUNT(DISTINCT(`ip`)) FROM `[mpx]log` WHERE logid!='0' AND `time`>$tm AND `time`<$tm+(3600*24)-1  AND $where"));//users
    $ips4=round($scale*sql_1data("SELECT COUNT(DISTINCT(`ip`)) FROM `[mpx]log` WHERE `function`='register' AND `time`>$tm AND `time`<$tm+(3600*24)-1  AND $where"));//new
    $ips5=$ips1-$ips3;//sql_1data("SELECT COUNT(DISTINCT(`ip`)) FROM `[mpx]log` WHERE `function`='html' AND `time`>$tm AND `time`<$tm+(3600*24) ");//none
   
    
    
    if($ips1 or $i==$dd ){
        
        e('<tr>');
        e('<td><a title="'.('od '.date('d.m.Y H:i',$tm).' do '.date('d.m.Y H:i',$tm+(3600*24)-1)).'">'.date('d.m',$tm)/*contentlang(timer($tm))*/.'</a></td>');
        e('<td><a title="'.$maus.'">'.$mau.'</a></td>');//maux
        e('<td><a title="'.$daus.'">'.$dau.'</a></td>');
		e('<td><a title="'.$mausx.'">'.$maux.'</a></td>');
		e('<td><a title="'.$dausx.'">'.$daux.'</a></td>');
        //e('<td>'.$dau.'</td>');//total
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
<b>*MAU</b> = Počet aktivních(attack, build) hráčů s unikátním IP a s uživatelským jménem za posledních 30 dnů (končí v uvedený den).<br>
<b>*MAUx</b> = Počet aktivních(attack, build) hráčů s unikátním IP za posledních 30 dnů (končí v uvedený den).<br>
<!--<b>*DAU</b> = Počet aktivních(attack, build) hráčů s unikátním IP za den.<br>-->
<b>*total</b> = Počet unikátních IP za daný den<br>
<b>*refresh</b> = Počet obnovení stránky (vyvolání hlavního html)<br>
<b>*build</b> = Počet postavených budov<br>
<b>*attack</b> = Počet útoků<br>
<b>*users</b> = Počet aktivních hráčů za daný den (jakákoliv akce i registrace) (pouze unikátní IP)<br>
<b>*new</b> = Počet nových hráčů za daný den (pouze unikátní IP)<br>
<b>*none</b> = Počet unikátních ip bez jakéhokoliv uživatele<br>

<?php
br(2);
$speeds=array(7,30);
$separator='';
foreach($speeds as $tmp){
	e($separator);
	if($speed==$tmp){
		e('<b><u>List '.$tmp.'</u></b>');
	}else{
		e('<a href="?page=html&amp;scale='.$_GET['scale'].'&amp;countip='.$_GET['countip'].'&amp;dd='.$tmp.'">List '.$tmp.'</a>');
	}
	$separator=nbsp.'-'.nbsp;
}
br();
?>

<br>
<b>*</b> <a href="?page=html&amp;scale=<?php e($_GET['scale']); ?>&amp;countip=<?php e($_GET['countip']?0:1); ?>&amp;dd=<?php e($_GET['dd']); ?>"><?php e($_GET['countip']?'Je':'Není'); ?> započítána vaše IP 
<?php e($_SERVER['REMOTE_ADDR']); ?><a><br>


