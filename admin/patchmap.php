<?php
/* Towns4Admin, www.towns.cz 
   © Pavel Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================
?>
<h3>PatchMap</h3>
"Vypálení" modelů na mapový podklad<br/>
<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_WARNING );
ini_set("max_execution_time","1000");
//==========================================
if($_GET['start']==1){
/*echo('<hr>');
//require2('func_vals.php');
foreach(sql_array('SELECT id,`set`,x,y FROM '.mpx.'objects WHERE `type`=\'building\'') as $row){list($id,$set,$x,$y)=$row;
	$set=new vals($set);
	$set=$set->vals2list();
	if($set['x']=='x' or $set[0]=='x' or $set['x']=='0'){
		echo($id.'<br/>');
		sql_query('UPDATE '.mpx.'objects SET `set`=\'\' WHERE id='.$id);
		changemap($x,$y);
	}
}
		echo('<script language="javascript">
    window.location.replace("?world='.w.'&page=createtmp&start=1");
    </script>');*/

		emptydir(root.cache.'/mapunits');
		emptydir(root.cache.'/mapbg');

		$file=tmpfile2("mapunitstime","txt","text");
		file_put_contents2($file,time());

		echo('<script language="javascript">
    window.location.replace("?world='.w.'&page=createtmp&start=1");
    </script>');
}
if($_GET['start']==2){
		$file=tmpfile2("mapunitstime","txt","text");
		file_put_contents2($file,time());
		echo('Čas nastavený na '.time().'.<br>');
}
?>

<a href="?page=patchmap&start=1">Smazat tmp/mapunits, aktualizovat čas, spustit CreateTmp</a><br>
<a href="?page=patchmap&start=2">Aktualizovat čas</a>
