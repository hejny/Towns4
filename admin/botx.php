<h3>Boti X</h3>
<script type="text/javascript">
function confirm_click()
{
return confirm("Fakt?");
}

</script>

<?php

ini_set("max_execution_time","1000");


function deleteuser($id,$show=1){
      $array=sql_array('SELECT id FROM [mpx]objects WHERE `own`='.$id);
      foreach($array as $row){
         deleteuser($row[0],$show);  
      }
      sql_query('DELETE FROM [mpx]objects WHERE id='.$id,$show);
      sql_query('DELETE FROM [mpx]login WHERE id='.$id,$show);
      sql_query('DELETE FROM [mpx]text WHERE to='.$id,$show);
}


if($_GET['delete']){
   deleteuser($_GET['delete']);
}


if($_POST['create']){
$time=$_POST['time'];
   require(root.core."/login/func_core.php");
   $names=$_POST['create'];
   $names=str_replace(' ',',',$names);
   foreach(explode(',',$names) as $name){if($name){
   $key=rand(1111111,9999999);
   $GLOBALS['ss']['register_key']=$key;
   unset($GLOBALS['ss']["logid"]);
   unset($GLOBALS['ss']["useid"]);
   xqr('register new,'.$key);
    e('register new,'.$key);
   $GLOBALS['ss']['log_object']->name=$name;
   $GLOBALS['ss']['use_object']->name=$name;
   $GLOBALS['ss']['use_object']->type='town2';
   $GLOBALS['ss']['log_object']->update();
   $GLOBALS['ss']['use_object']->update();
   
   $logid=$GLOBALS['ss']['use_object']->own;
   sql_query("UPDATE [mpx]objects SET name='$name' WHERE id=".$logid,2);br();
   sql_query("INSERT INTO [mpx]login VALUES (".$logid.",'bot','".($time)."','',".time().",".time().",0)",2);br();
   sql_query("INSERT INTO [mpx]login VALUES (".$logid.",'towns','".md5(rand(1111,9999))."','bot',".time().",".time().",0)",2);br();
   $time=rand(3600,3600*24);
   }}
}


?>
<form action="?page=botx" method="POST">
<input type="text" name="create" size="100" value="">
<input type="text" name="time" value="<?php e(rand(3600,3600*24)); ?>">
<input type="submit" value="vytvořit">
<?php

//$where="type='user' AND ww!=0 AND ww!=-1 AND 'f561aaf6ef0bf14d4208bb46a4ccb3ad'=(SELECT `key` FROM [mpx]login as x WHERE x.id=[mpx]objects.id AND `method`='towns' LIMIT 1) ";
$where="type='user' AND ww!=0 AND ww!=-1 AND 1=(SELECT 1 FROM [mpx]login as x WHERE x.id=[mpx]objects.id AND `method`='bot' LIMIT 1) ";
$ad0='SELECT max(`t`) FROM [mpx]objects as x WHERE x.own=(SELECT y.id FROM [mpx]objects as y WHERE y.own=[mpx]objects.id LIMIT 1) AND type=\'building\'';
$ad1='SELECT count(1) FROM [mpx]objects as x WHERE x.own=(SELECT y.id FROM [mpx]objects as y WHERE y.own=[mpx]objects.id LIMIT 1) AND type=\'building\'';
$ad2='SELECT sum(x.fs) FROM [mpx]objects as x WHERE x.own=(SELECT y.id FROM [mpx]objects as y WHERE y.own=[mpx]objects.id LIMIT 1) AND type=\'building\'';
$ad3='SELECT count(1) FROM [mpx]objects as x WHERE x.own=[mpx]objects.id AND (type=\'town\' OR `type`=\'town2\')';
$ad4='SELECT count(1) FROM [mpx]login as x WHERE x.id=[mpx]objects.id';
$ad0=',('.$ad0.') as ad0';
$ad1=',('.$ad1.') as ad1';
$ad2=',('.$ad2.') as ad2';
$ad3=',('.$ad3.') as ad3';
$ad4=',('.$ad4.') as ad4';
$order="ad4 DESC, ad2 DESC";

$array=sql_array("SELECT `id`,`name`,`type`,`dev`,`fs`,`fp`,`fr`,`fx`,`own`,`in`,`x`,`y`$ad0,`ww`$ad1$ad2$ad3$ad4 FROM `".mpx."objects` WHERE ".$where." ORDER BY $order");

e('<table width="100%" border="1">');

e('<tr>');
e('<td>id</td>');
e('<td>jméno</td>');
e('<td>login</td>');
e('<td>čas</td>');
e('<td>Měst</td>');
e('<td>Budov</td>');
e('<td>lvl</td>');
e('<td>akce</td>');
e('</tr>');   
   

foreach($array as $row){
   list($id,$name,$type,$dev,$fs,$fp,$fr,$fx,$own,$in,$x,$y,$t,$ww,$ad1,$ad2,$ad3,$ad4)=$row;
   $lvl=fs2lvl($ad2);   
   
   e('<tr>');
   e('<td>'.$id.'</td>');
   e('<td>'.$name.'</td>');
   e('<td>'.$ad4.'</td>');
   e('<td>'.timer($t).'</td>');
   e('<td>'.$ad3.'</td>');
   e('<td>'.$ad1.'</td>');
   e('<td>'.$lvl.'</td>');
      
      e('<td><a href="?page=users&amp;login='.$id.'" target="_blank">přihlásit se</a> - <a href="?delete='.$id.'" onclick="return confirm_click();">smazat</a></td>');

   e('</tr>');
}
e('</table>');
?>



