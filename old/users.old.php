<h3>Uživatelé</h3>
<script type="text/javascript">
function confirm_click()
{
return confirm("Fakt?");
}

</script>
<a href="?delete=alles">Smazat vše nologin+1day+11</a><br/>
<a href="?delete=allesx">Smazat HL vše nologin+1day</a>
<?php
if($_GET['login']){
   $GLOBALS['ss']["logid"]=$_GET['login'];
   $useid=sql_1data('SELECT `id` FROM [mpx]objects WHERE `type`=\'town\' AND `own`=\''.$_GET['login'].'\' ');
   $GLOBALS['ss']["useid"]=$useid;


$tmp=$_SERVER["REQUEST_URI"];
if(strpos($tmp,'?'))$tmp=substr($tmp,0,strpos($tmp,'?'));
$tmp=str_replace('admin','',$tmp);


?>
<script type="text/javascript">
location.replace('<?php echo($tmp); ?>');
</script>
<?php
exit2();
}


$GLOBALS['hl']=sql_1data('SELECT `name` FROM [mpx]objects WHERE id='.register_building);


function deleteuser($id,$show=1){
      $array=sql_array('SELECT id FROM [mpx]objects WHERE `own`='.$id);
      foreach($array as $row){
         deleteuser($row[0],$show);  
      }
      sql_query('DELETE FROM [mpx]objects WHERE id='.$id,$show);
      sql_query('DELETE FROM [mpx]login WHERE id='.$id,$show);
      sql_query('DELETE FROM [mpx]text WHERE to='.$id,$show);
}
function deleteuserX($id,$show=1){
      $array=sql_array('SELECT id FROM [mpx]objects WHERE `own`='.$id);
      foreach($array as $row){
         deleteuserx($row[0],$show);  
      }
      sql_query('DELETE FROM [mpx]objects WHERE id='.$id.' AND name=\''.$GLOBALS['hl'].'\'',$show);
      sql_query('UPDATE [mpx]objects SET own=0 WHERE id='.$id,$show);
      sql_query('DELETE FROM [mpx]login WHERE id='.$id,$show);
      sql_query('DELETE FROM [mpx]text WHERE to='.$id,$show);
}


if($_GET['delete'] and $_GET['delete']!='alles'){
   deleteuser($_GET['delete']);
}


$where="type='user' AND ww!=0 AND ww!=-1 ";
$ad0='SELECT max(`t`) FROM [mpx]objects as x WHERE x.own=(SELECT y.id FROM [mpx]objects as y WHERE y.own=[mpx]objects.id AND type=\'town\' LIMIT 1) AND type=\'building\'';
$ad1='SELECT count(1) FROM [mpx]objects as x WHERE x.own=(SELECT y.id FROM [mpx]objects as y WHERE y.own=[mpx]objects.id AND type=\'town\' LIMIT 1) AND type=\'building\'';
$ad2='SELECT sum(x.fs) FROM [mpx]objects as x WHERE x.own=(SELECT y.id FROM [mpx]objects as y WHERE y.own=[mpx]objects.id AND type=\'town\' LIMIT 1) AND type=\'building\'';
$ad3='SELECT count(1) FROM [mpx]objects as x WHERE x.own=[mpx]objects.id AND type=\'town\'';
$ad4='SELECT count(1) FROM [mpx]login as x WHERE x.id=[mpx]objects.id';
$ad5='SELECT `text` FROM [mpx]login as x WHERE x.id=[mpx]objects.id AND x.`method`=\'facebook\'';
$ad5b='SELECT count(1) FROM [mpx]login as x WHERE x.id=[mpx]objects.id AND x.`method`=\'bot\'';
$ad6='SELECT MAX(`questi`) FROM [mpx]questt as q WHERE q.id=(SELECT y.id FROM [mpx]objects as y WHERE y.own=[mpx]objects.id AND y.`type`=\'town\' LIMIT 1) AND quest=1';
$ad0=',('.$ad0.') as ad0';
$ad1=',('.$ad1.') as ad1';
$ad2=',('.$ad2.') as ad2';
$ad3=',('.$ad3.') as ad3';
$ad4=',('.$ad4.') as ad4';
$ad5=',('.$ad5.') as ad5';
$ad5b=',('.$ad5b.') as ad5b';
$ad6=',('.$ad6.') as ad6';
$order="ad2 DESC";//ad4 DESC, 

$array=sql_array("SELECT `id`,`name`,`type`,`dev`,`fs`,`fp`,`fr`,`fx`,`own`,`in`,`x`,`y`$ad0,`ww`,`profile`,`set`$ad1$ad2$ad3$ad4$ad5$ad5b$ad6 FROM `".mpx."objects` WHERE ".$where." ORDER BY $order");

e('<table width="100%" border="1">');

e('<tr bgcolor="#ffffff">');
e('<td><b>id</b><a href="?page=html&amp;time=1">#</a></td>');
e('<td><b>jméno</b></td>');
e('<td><b>login</b></td>');
e('<td><b>mail</b></td>');
e('<td><b>ref</b></td>');
e('<td><b>tutorial</b></td>');
e('<td><b>čas</b></td>');
//e('<td><b>Měst</b></td>');
e('<td><b>Budov</b></td>');
e('<td><b>lvl</b></td>');
e('<td><b>akce</b></td>');
e('</tr>');   
   

foreach($array as $row){
   list($id,$name,$type,$dev,$fs,$fp,$fr,$fx,$own,$in,$x,$y,$t,$ww,$profile,$set,$ad1,$ad2,$ad3,$ad4,$ad5,$ad5b,$ad6)=$row;
   $lvl=fs2lvl($ad2);   

   if($ad5){
     $ad5=unserialize($ad5);
     $ad5=' - <a href="https://www.facebook.com/'.$ad5['id'].'" target="_blank">'.$ad5['name'].'</a>'; 
   }

   $profile=new vals($profile);
   $profile=$profile->vals2list();
   $mail=$profile['mail'];
   $sendmail=$profile['sendmail'];
   if($mail and $mail!='@'){
	if($sendmail)$mail=textbr($mail);
   }else{
	$mail='';
   }
   $set=new vals($set);
   $set=$set->vals2list();
   $ref=$set['ref'];

   if(($ad3==1 and $ad1==1) or $ad5b){
	if($ad5b){
    	    $bgcolor='ffdddd';
	}else{
	    if(($t>time()-(3600*24))){
    	    	$bgcolor='eeffee';
	    }else{
    	    	$bgcolor='ffffff';
	    }
	}
   }else{
	if(($t>time()-(3600*24))){
	   $bgcolor='bbbbff';
	}else{
	   $bgcolor='cccccc';
	}
   }

   e('<tr bgcolor="#'.$bgcolor.'">');
   e('<td>'.$id.'</td>');
   e('<td>'.$name.'</td>');
   e('<td>'.$ad4.($ad5).'</td>');
   e('<td>'.$mail.'</td>');
   e('<td>'.$ref.'</td>');
   e('<td>'.($ad6?$ad6:'').'</td>');
   e('<td>'.($_GET['time']?contentlang(timer($t)):'').'</td>');
   //e('<td>'.$ad3.'</td>');
   e('<td>'.$ad1.'</td>');
   e('<td>'.$lvl.'</td>');
      
   if($_GET['delete']=='alles' and (($t<time()-(3600*24)) and (!$ad4) and (($ad3==1 and $ad1==1) or ($ad3==0 and $ad1==0)))){
      e('<td><b>smazáno</b></td>');
      deleteuser($id,0);
   }elseif($_GET['delete']=='allesx' and (($t<time()-(3600*24)) and (!$ad4))){
      e('<td><b>smazánoX</b></td>');
      deleteuserX($id,0);
   }else{
      e('<td><a href="?page=users&amp;login='.$id.'" target="_blank">přihlásit se</a> - <a href="?delete='.$id.'" onclick="return confirm_click();">smazat</a></td>');
   }
   e('</tr>');
}
e('</table>');
?>
















<h3>Uživatelé</h3>
<script type="text/javascript">
function confirm_click()
{
return confirm("Fakt?");
}

</script>
<a href="?delete=alles">Smazat vše nologin+1day+11</a><br/>
<a href="?delete=allesx">Smazat HL vše nologin+1day</a>
<?php
if($_GET['login']){
   $GLOBALS['ss']["logid"]=$_GET['login'];
   $useid=sql_1data('SELECT `id` FROM [mpx]objects WHERE `type`=\'town\' AND `own`=\''.$_GET['login'].'\' ');
   $GLOBALS['ss']["useid"]=$useid;


$tmp=$_SERVER["REQUEST_URI"];
if(strpos($tmp,'?'))$tmp=substr($tmp,0,strpos($tmp,'?'));
$tmp=str_replace('admin','',$tmp);


?>
<script type="text/javascript">
location.replace('<?php echo($tmp); ?>');
</script>
<?php
exit2();
}


$GLOBALS['hl']=sql_1data('SELECT `name` FROM [mpx]objects WHERE id='.register_building);


function deleteuser($id,$show=1){
      $array=sql_array('SELECT id FROM [mpx]objects WHERE `own`='.$id);
      foreach($array as $row){
         deleteuser($row[0],$show);  
      }
      sql_query('DELETE FROM [mpx]objects WHERE id='.$id,$show);
      sql_query('DELETE FROM [mpx]login WHERE id='.$id,$show);
      sql_query('DELETE FROM [mpx]text WHERE to='.$id,$show);
}
function deleteuserX($id,$show=1){
      $array=sql_array('SELECT id FROM [mpx]objects WHERE `own`='.$id);
      foreach($array as $row){
         deleteuserx($row[0],$show);  
      }
      sql_query('DELETE FROM [mpx]objects WHERE id='.$id.' AND name=\''.$GLOBALS['hl'].'\'',$show);
      sql_query('UPDATE [mpx]objects SET own=0 WHERE id='.$id,$show);
      sql_query('DELETE FROM [mpx]login WHERE id='.$id,$show);
      sql_query('DELETE FROM [mpx]text WHERE to='.$id,$show);
}


if($_GET['delete'] and $_GET['delete']!='alles'){
   deleteuser($_GET['delete']);
}


$where="type='user' AND ww!=0 AND ww!=-1 ";
$ad0='SELECT max(`t`) FROM [mpx]objects as x WHERE x.own=(SELECT y.id FROM [mpx]objects as y WHERE y.own=[mpx]objects.id AND type=\'town\' LIMIT 1) AND type=\'building\'';
//$adip='SELECT `ip` FROM [mpx]log as x WHERE x.logid=[mpx]objects.id ORDER BY `time` DESC LIMIT 1';
//$ad0='SELECT max(`time`) FROM [mpx]log as x WHERE x.ip=(SELECT y.`ip` FROM [mpx]log as y WHERE y.logid=[mpx]objects.id ORDER BY `time` DESC LIMIT 1)';
//$ad0='SELECT max(`time`) FROM [mpx]log as x WHERE x.ip=adip';
$adip="SELECT ''";
//$ad0='SELECT max(`time`) FROM [mpx]log as x WHERE x.logid=[mpx]objects.id';
$ad1='SELECT count(1) FROM [mpx]objects as x WHERE x.own=(SELECT y.id FROM [mpx]objects as y WHERE y.own=[mpx]objects.id AND type=\'town\' LIMIT 1) AND type=\'building\'';
$ad2='SELECT sum(x.fs) FROM [mpx]objects as x WHERE x.own=(SELECT y.id FROM [mpx]objects as y WHERE y.own=[mpx]objects.id AND type=\'town\' LIMIT 1) AND type=\'building\'';
$ad3='SELECT count(1) FROM [mpx]objects as x WHERE x.own=[mpx]objects.id AND type=\'town\'';
$ad4='SELECT count(1) FROM [mpx]login as x WHERE x.id=[mpx]objects.id';
$ad5='SELECT `text` FROM [mpx]login as x WHERE x.id=[mpx]objects.id AND x.`method`=\'facebook\'';
$ad5b='SELECT count(1) FROM [mpx]login as x WHERE x.id=[mpx]objects.id AND x.`method`=\'bot\'';
$ad6='SELECT MAX(`questi`) FROM [mpx]questt as q WHERE q.id=(SELECT y.id FROM [mpx]objects as y WHERE y.own=[mpx]objects.id AND y.`type`=\'town\' LIMIT 1) AND quest=1';

$ada='SELECT count(1) FROM [mpx]log as x WHERE x.`logid`=[mpx]objects.id AND function=\'attack\'';
$adb='SELECT count(1) FROM [mpx]log as x WHERE x.`logid`=[mpx]objects.id AND function=\'build\'';
$ad0=',('.$ad0.') as ad0';
$ad1=',('.$ad1.') as ad1';
$ad2=',('.$ad2.') as ad2';
$ad3=',('.$ad3.') as ad3';
$ad4=',('.$ad4.') as ad4';
$ad5=',('.$ad5.') as ad5';
$ad5b=',('.$ad5b.') as ad5b';
$ad6=',('.$ad6.') as ad6';
$adip=',('.$adip.') as adip';
$ada=',('.$ada.') as ada';
$adb=',('.$adb.') as adb';
$order="ad2 DESC";//ad4 DESC, 
$sql="SELECT `id`,`name`,`type`,`dev`,`fs`,`fp`,`fr`,`fx`,`own`,`in`,`x`,`y`,`ww`,`profile`,`set`$adip$ad0$ad1$ad2$ad3$ad4$ad5$ad5b$ad6$ada$adb FROM `".mpx."objects` WHERE ".$where." ORDER BY $order";

$sql=str_replace('[mpx]',mpx,$sql);
//e($sql);
//die();
/*$sql="
SELECT `id`,`name`,`type`,`dev`,`fs`,`fp`,`fr`,`fx`,`own`,`in`,`x`,`y`,`ww`,`profile`,`set`,
(SELECT '') as adip,
(SELECT max(`time`) FROM world1_log as x WHERE x.logid=world1_objects.id) as ad0,
(SELECT count(1) FROM world1_objects as x WHERE x.own=(SELECT y.id FROM world1_objects as y WHERE y.own=world1_objects.id AND type='town' LIMIT 1) AND type='building') as ad1,
(SELECT sum(x.fs) FROM world1_objects as x WHERE x.own=(SELECT y.id FROM world1_objects as y WHERE y.own=world1_objects.id AND type='town' LIMIT 1) AND type='building') as ad2,
(SELECT count(1) FROM world1_objects as x WHERE x.own=world1_objects.id AND type='town') as ad3,
(SELECT count(1) FROM world1_login as x WHERE x.id=world1_objects.id) as ad4,
(SELECT `text` FROM world1_login as x WHERE x.id=world1_objects.id AND x.`method`='facebook') as ad5,
(SELECT count(1) FROM world1_login as x WHERE x.id=world1_objects.id AND x.`method`='bot') as ad5b,
(SELECT MAX(`questi`) FROM world1_questt as q WHERE q.id=(SELECT y.id FROM world1_objects as y WHERE y.own=world1_objects.id AND y.`type`='town' LIMIT 1) AND quest=1) as ad6,
(SELECT count(1) FROM world1_log as x WHERE x.`logid`=world1_objects.id AND function='attack') as ada,
(SELECT count(1) FROM world1_log as x WHERE x.`logid`=world1_objects.id AND function='build') as adb
FROM `world1_objects` WHERE type='user' AND ww!=0 AND ww!=-1 ORDER BY ad2 DESC
";*/

$array=sql_array($sql);

 






e('<table width="100%" border="1">');

e('<tr bgcolor="#ffffff">');
e('<td><b>id</b><a href="?page=html&amp;time=1">#</a></td>');
e('<td><b>jméno</b></td>');
e('<td><b>login</b></td>');
e('<td><b>mail</b></td>');
e('<td><b>ref</b></td>');
e('<td><b>tutorial</b></td>');
if($_GET['time'])e('<td><b>čas</b></td>');
if($_GET['time'])e('<td><b>IP</b></td>');
e('<td><b>B</b></td>');
e('<td><b>A</b></td>');
//e('<td><b>Měst</b></td>');
e('<td><b>Budov</b></td>');
e('<td><b>lvl</b></td>');
e('<td><b>akce</b></td>');
e('</tr>');  




 $array=sql_array("SELECT `id`,`name`,`type`,`dev`,`fs`,`fp`,`fr`,`fx`,`own`,`in`,`x`,`y`,`ww`,`profile`,`set`$adip$ad0$ad1$ad2$ad3$ad4$ad5$ad5b$ad6$ada,$adb FROM `".mpx."objects` WHERE ".$where." ORDER BY $order");
 
   

foreach($array as $row){
   list($id,$name,$type,$dev,$fs,$fp,$fr,$fx,$own,$in,$x,$y,$t,$ww,$profile,$set,$ad1,$ad2,$ad3,$ad4,$ad5,$ad5b,$ad6,$adip,$ada,$adb)=$row;
   $lvl=fs2lvl($ad2);   

   if($ad5){
     $ad5=unserialize($ad5);
     $ad5=' - <a href="https://www.facebook.com/'.$ad5['id'].'" target="_blank">'.$ad5['name'].'</a>'; 
   }

   $profile=new vals($profile);
   $profile=$profile->vals2list();
   $mail=$profile['mail'];
   $sendmail=$profile['sendmail'];
   if($mail and $mail!='@'){
	if($sendmail)$mail=textbr($mail);
   }else{
	$mail='';
   }
   $set=new vals($set);
   $set=$set->vals2list();
   $ref=$set['ref'];

   if(($ad3==1 and $ad1==1) or $ad5b){
	if($ad5b){
    	    $bgcolor='ffdddd';
	}else{
	    if(($t>time()-(3600*24))){
    	    	$bgcolor='eeffee';
	    }else{
    	    	$bgcolor='ffffff';
	    }
	}
   }else{
	if(($t>time()-(600*2))){
	   $bgcolor='ffbb55';
	}elseif(($t>time()-(3600*24*2))){
	   $bgcolor='bbbbff';
	}else{
	   $bgcolor='cccccc';
	}
   }

   e('<tr bgcolor="#'.$bgcolor.'">');
   e('<td>'.$id.'</td>');
   e('<td>'.$name.'</td>');
   e('<td>'.$ad4.($ad5).'</td>');
   e('<td>'.$mail.'</td>');
   e('<td>'.$ref.'</td>');
   e('<td>'.($ad6?$ad6:'').'</td>');
   if($_GET['time'])e('<td>'.contentlang(timer($t)).'</td>');
   if($_GET['time'])e('<td>'.$adip.'</td>');
   e('<td>'.$adb.'</td>');
   e('<td>'.$ada.'</td>');
   //e('<td>'.$ad3.'</td>');
   e('<td>'.$ad1.'</td>');
   e('<td>'.$lvl.'</td>');
      
   if($_GET['delete']=='alles' and (($t<time()-(3600*24)) and (!$ad4) and (($ad3==1 and $ad1==1) or ($ad3==0 and $ad1==0)))){
      e('<td><b>smazáno</b></td>');
      deleteuser($id,0);
   }elseif($_GET['delete']=='allesx' and (($t<time()-(3600*24)) and (!$ad4))){
      e('<td><b>smazánoX</b></td>');
      deleteuserX($id,0);
   }else{
      e('<td><a href="?page=users&amp;login='.$id.'" target="_blank">přihlásit se</a> - <a href="?delete='.$id.'" onclick="return confirm_click();">smazat</a></td>');
   }
   e('</tr>');
}
e('</table>');
?>

