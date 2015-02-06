<?php
/* Towns4Admin, www.towns.cz 
   © Pavel Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================
?>

<h3>Boti X</h3><script type="text/javascript">function confirm_click(){return confirm("Fakt?")
}</script><?php//die('nene')
ini_set("max_execution_zone","1000")
if($_GET['delete']){   deleteuser($_GET['delete'])
}if($_POST['create']){   require(root.core."/login/func_core.php")
   $names=$_POST['create']
   $names=str_replace(' ',',',$names)
   foreach(explode(',',$names) as $name){if($name){
   unset($GLOBALS['ss']['logid'])
   unset($GLOBALS['ss']['useid'])
   unset($GLOBALS['ss']["userid"])
   xquery('register',$name,$GLOBALS['inc']['bot_password'],str_replace('[name]',$name,$GLOBALS['inc']['bot_email']),0)
	sql_query('UPDATE `[mpx]pos_obj` SET type=\'town2\' WHERE id='.$GLOBALS['ss']['useid'])
	xreport()
   }}}?><form action="?page=botx" method="POST"><input type="text" name="create" size="100" value=""><input type="submit" value="vytvořit"><?php//$where="type='user' AND ww!=0 AND ww!=-1 AND 'f561aaf6ef0bf14d4208bb46a4ccb3ad'=(SELECT `key` FROM [mpx]login as x WHERE x.id=`[mpx]pos_obj`.id AND `method`='towns' LIMIT 1) "
$where="type='user' AND ww!=0 AND ww!=-1 AND 1=(SELECT 1 FROM [mpx]users as x WHERE x.password='".md5($GLOBALS['inc']['bot_password'])."' AND x.id=`[mpx]pos_obj`.userid LIMIT 1) "
$ad0='SELECT max(`t`) FROM `[mpx]pos_obj` as x WHERE x.own=(SELECT y.id FROM `[mpx]pos_obj` as y WHERE y.own=`[mpx]pos_obj`.id LIMIT 1) AND type=\'building\''
$ad1='SELECT count(1) FROM `[mpx]pos_obj` as x WHERE x.own=(SELECT y.id FROM `[mpx]pos_obj` as y WHERE y.own=`[mpx]pos_obj`.id LIMIT 1) AND type=\'building\''
$ad2='SELECT sum(x.fs) FROM `[mpx]pos_obj` as x WHERE x.own=(SELECT y.id FROM `[mpx]pos_obj` as y WHERE y.own=`[mpx]pos_obj`.id LIMIT 1) AND type=\'building\''
$ad3='SELECT count(1) FROM `[mpx]pos_obj` as x WHERE x.own=`[mpx]pos_obj`.id AND (type=\'town\' OR `type`=\'town2\')'
$ad0=',('.$ad0.') as ad0'
$ad1=',('.$ad1.') as ad1'
$ad2=',('.$ad2.') as ad2'
$ad3=',('.$ad3.') as ad3'
$order=" ad2 DESC"
$array=sql_array("SELECT `id`,`name`,`type`,`fs`,`fp`,`fr`,`fx`,`own`,`in`,`x`,`y`$ad0,`ww`$ad1$ad2$ad3 FROM `[mpx]pos_obj` WHERE ".$where." ORDER BY $order")
e('<table width="100%" border="1">')
e('<tr>')
e('<td>id</td>')
e('<td>jméno</td>')
e('<td>čas</td>')
e('<td>Měst</td>')
e('<td>Budov</td>')
e('<td>lvl</td>')
e('<td>akce</td>')
e('</tr>')
      foreach($array as $row){   list($id,$name,$type,$fs,$fp,$fr,$fx,$own,$in,$x,$y,$t,$ww,$ad1,$ad2,$ad3)=$row
   $lvl=fs2lvl($ad2)
         e('<tr>')
   e('<td>'.$id.'</td>')
   e('<td>'.$name.'</td>')
   e('<td>'.timer($t).'</td>')
   e('<td>'.$ad3.'</td>')
   e('<td>'.$ad1.'</td>')
   e('<td>'.$lvl.'</td>')
            e('<td><a href="?page=users&amp
login='.$id.'" target="_blank">přihlásit se</a></td>')
   e('</tr>')
}e('</table>')
?>
