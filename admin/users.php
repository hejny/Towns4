<?php
/* Towns4Admin, www.towns.cz 
   © Pavel Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================
?>

<h3>Uživatelé</h3>
<script type="text/javascript">
function confirm_click()
{
return confirm("Fakt?");
}

</script>
<a href="?page=users&amp;delete=alles">Smazat vše nologin+1day+11</a><br/>
<a href="?page=users&amp;delete=allesx">Smazat HL vše nologin+1day</a><br/>
<a href="?page=users&amp;nonoown=1">Smazat own no own budovy</a>
<?php


superown();

if($_GET['nonoown']){
    br(2);
    foreach(sql_array("SELECT id FROM `[mpx]objects` WHERE `type`='building' AND $wwhere AND `own`!='0' AND 0=(SELECT COUNT(`id`) FROM `[mpx]objects` AS X WHERE X.id=world1_objects.own)",2) as $row){
        $id=$row[0];
        sql_query("DELETE FROM [mpx]objects WHERE $wwhere AND `type`='building' AND id='$id'",2);br();
    }
}


if($_GET['login']){
   $GLOBALS['ss']["logid"]=$_GET['login'];
   $useid=sql_1data('SELECT `id` FROM [mpx]objects WHERE (`type`=\'town\' OR `type`=\'town2\') AND `own`=\''.$_GET['login'].'\' ');
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


$where="type='user' AND ww!=0 AND ww!=-1 AND 0=(SELECT count(1) FROM [mpx]login as x WHERE x.id=[mpx]objects.id AND x.`method`='bot')";
//$ad0='SELECT max(`t`) FROM [mpx]objects as x WHERE x.own=(SELECT y.id FROM [mpx]objects as y WHERE y.own=[mpx]objects.id AND type=\'town\' LIMIT 1) AND type=\'building\'';
$ad0='t';
//$ad1='SELECT count(1) FROM [mpx]objects as x WHERE x.own=(SELECT y.id FROM [mpx]objects as y WHERE y.own=[mpx]objects.id AND (type=\'town\' OR `type`=\'town2\') LIMIT 1) AND type=\'building\'';
//$ad2='SELECT sum(x.fs) FROM [mpx]objects as x WHERE x.own=(SELECT y.id FROM [mpx]objects as y WHERE y.own=[mpx]objects.id AND (type=\'town\' OR `type`=\'town2\') LIMIT 1) AND type=\'building\'';
$ad1='SELECT count(1) FROM [mpx]objects as x WHERE x.superown=[mpx]objects.id AND x.type=\'building\' AND '.objt('x');
$ad1x='SELECT count(1) FROM [mpx]objects as x WHERE x.superown=[mpx]objects.id AND x.type=\'building\' ';

$ad2='SELECT sum(x.fs) FROM [mpx]objects as x WHERE x.superown=[mpx]objects.id AND x.type=\'building\'  AND '.objt('x');

$ad3a='SELECT name FROM [mpx]users as u WHERE u.id=[mpx]objects.userid AND u.aac=1 ';
$ad3b='SELECT email FROM [mpx]users as u WHERE u.id=[mpx]objects.userid AND u.aac=1 ';
$ad3c='SELECT sendmail FROM [mpx]users as u WHERE u.id=[mpx]objects.userid AND u.aac=1 ';
//$ad3d='SELECT fbdata FROM [mpx]users as u WHERE u.id=[mpx]objects.userid AND u.aac=1 ';
//$ad3a='SELECT name FROM [mpx]users as u WHERE u.id=[mpx]objects.userid AND u.aac=1 ';



//$ad4='SELECT count(1) FROM [mpx]login as x WHERE x.id=[mpx]objects.id';
$ad5='SELECT fbdata FROM [mpx]users as u WHERE u.id=[mpx]objects.userid AND u.aac=1 ';
////'SELECT `text` FROM [mpx]login as x WHERE x.id=[mpx]objects.id AND x.`method`=\'facebook\'';
//$ad5b='SELECT count(1) FROM [mpx]login as x WHERE x.id=[mpx]objects.id AND x.`method`=\'bot\'';
$ad6='SELECT MAX(`questi`) FROM [mpx]questt as q WHERE q.id=(SELECT y.id FROM [mpx]objects as y WHERE y.own=[mpx]objects.id AND (y.`type`=\'town\' OR y.`type`=\'town2\') LIMIT 1) AND quest=1';
$ad0=',('.$ad0.') as ad0';
$ad1=',('.$ad1.') as ad1';
$ad1x=',('.$ad1x.') as ad1x';
$ad2=',('.$ad2.') as ad2';
$ad3a=',('.$ad3a.') as ad3a';
$ad3b=',('.$ad3b.') as ad3b';
$ad3c=',('.$ad3c.') as ad3c';
$ad5=',('.$ad5.') as ad5';
$ad5b=',('.$ad5b.') as ad5b';
$ad6=',('.$ad6.') as ad6';
$order="ad1x DESC, ad3a DESC"; 

//$array=sql_array("SELECT `id`,`name`,`type`,`dev`,`fs`,`fp`,`fr`,`fx`,`own`,`in`,`x`,`y`$ad0,`ww`,`profile`,`set`$ad1$ad1x$ad2$ad3$ad4$ad5$ad5b$ad6 FROM `".mpx."objects` WHERE ".$where." ORDER BY $order");

 






e('<table width="100%" border="1">');

e('<tr bgcolor="#ffffff">');
e('<td><b>id</b><a href="?page=users&amp;time=1">#</a></td>');
e('<td><b>jméno</b></td>');
e('<td><b>login</b></td>');
e('<td><b>facebook</b></td>');
e('<td><b>mail</b></td>');
e('<td><b>ref</b></td>');
e('<td><b>tutorial</b></td>');
if($_GET['time'])e('<td><b>čas</b></td>');
e('<td><b>Play</b></td>');
e('<td><b>Měst</b></td>');
e('<td><b>Budov</b></td>');
e('<td><b>BudovC</b></td>');
e('<td><b>lvl</b></td>');
e('<td><b>akce</b></td>');
e('</tr>');  



$array=sql_array("SELECT `id`,`name`,`type`,`fs`,`fp`,`fr`,`fx`,`own`,`in`,`x`,`y`$ad0,`pt`,`ww`,`profile`,`set`$ad1$ad1x$ad2$ad3a$ad3b$ad3c$ad5$ad6 FROM `".mpx."objects` WHERE ".$where." ORDER BY $order");
 
   
$faze=1;$tutorial=array();
foreach($array as $row){
   list($id,$name,$type,$fs,$fp,$fr,$fx,$own,$in,$x,$y,$t,$pt,$ww,$profile,$set,$ad1,$ad1x,$ad2,$ad3a,$ad3b,$ad3c,$ad5/*,$ad5b*/,$ad6)=$row;
   $lvl=fs2lvl($ad2);   

   if($ad5){
     $ad5=unserialize($ad5);
     $ad5='<a href="https://www.facebook.com/'.$ad5['id'].'" target="_blank">'.$ad5['name'].'</a>'; 
   }

   $profile=new vals($profile);
   $profile=$profile->vals2list();
   $mail=$ad3b;//$profile['mail'];
   $sendmail=$ad3c;//$sendmail=$profile['sendmail'];
   $mail=$ad3b;
   if($mail and $mail!='@'){
	if($sendmail)$mail=textbr($mail);
   }else{
	$mail='';
   }
   $set=new vals($set);
   $set=$set->vals2list();
   $ref=$set['ref'];



	if(($t>time()-(600*2))){//online
	   $bgcolor='ffbb55';
	}elseif(($t>time()-(3600*24))){//dau
	   $bgcolor='ffaa99';

	}elseif(($t>time()-(3600*24*30))){//mau
	   $bgcolor='bbbbff';
	}else{//nau
	   $bgcolor='cccccc';
	}

   //if(($ad3==1 and $ad1==1) or $ad5b){


	if($faze==1 and $ad1x==1){
		e('<tr bgcolor="#000000" height="5">');
		e('<td colspan="13"></td>');
		e('</tr>');
		$faze=2;	
	}
	if($faze==2 and $ad3a==0){
		e('<tr bgcolor="#000000" height="5">');
		e('<td colspan="13"></td>');
		e('</tr>');
		$faze=3;	
	}

   e('<tr bgcolor="#'.$bgcolor.'">');
   e('<td>'.$id.'</td>');
   e('<td>'.$name.'</td>');
   e('<td>'.($ad3a).'</td>');
   e('<td>'.($ad5).'</td>');
   e('<td>'.$mail.'</td>');
   e('<td>'.$ref.'</td>');
   e('<td>'.($ad6?$ad6:'').'</td>');
   if($_GET['time'])e('<td>'.contentlang(timer($t)).'</td>');
   e('<td>'.nbsp.(timesr($pt)).'</td>');
   e('<td>'.$ad3.'</td>');
   e('<td>'.$ad1.'</td>');
   e('<td>'.$ad1x.'</td>');
   e('<td>'.$lvl.'</td>');
      
   if($_GET['delete']=='alles' and (($t<time()-(3600*24)) and (!$ad3a) and (($ad3==1 and $ad1==1) or ($ad3==0 and $ad1==0)))){
      e('<td><b>smazáno</b></td>');
      deleteuser($id,0);
   }elseif($_GET['delete']=='allesx' and (($t<time()-(3600*24)) and (!$ad4))){
      e('<td><b>smazánoX</b></td>');
      deleteuserX($id,0);
   }else{
      e('<td><a href="?page=users&amp;login='.$id.'" target="_blank">přihlásit se</a> - <a href="?delete='.$id.'" onclick="return confirm_click();">smazat</a></td>');
   }
   e('</tr>');

	if($ad6){
		if($tutorial['x'.$ad6]){
			$tutorial['x'.$ad6]++;
		}else{
			$tutorial['x'.$ad6]=1;
		}
	}

}

		e('<tr bgcolor="#000000" height="5">');
		e('<td colspan="13"></td>');
		e('</tr>');

			//ksort($tutorial);
			$celek=array_sum($tutorial);
			//print_r($tutorial);
			for($i=1;$i<=29;$i++){

				$lang=lr('quest_1_'.$i.'_title');

				if(substr($lang,0,1)!='{'){
					$a='x'.$i;
					$b=$tutorial[$a];


					e('<tr bgcolor="#ffffff" height="5">');
					e('<td>');
							e(textbr($i.': '));
					e('</td><td colspan="1">');

					e($lang);

					e('</td>');
					e('</td><td colspan="9">');
							loadbar($b,$celek,0,0,0,14,'222222','ffffff');
					e('</td>');

					e('</td><td colspan="2">');
					if($b)e(nbsp3."$b / $celek");
					e('</td>');
					e('</tr>');
				}
			}


e('</table>');
?>
