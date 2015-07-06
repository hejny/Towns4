<?php
/* Towns4Admin, www.towns.cz 
   © Pavol Hejný | 2011-2015
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================

//@todo Wtf
//require2("text/func_core.php");
?>

<table>
<?php

if($_GET['accept']){
	sql_query('UPDATE `[mpx]pos_obj` SET ww=0 WHERE id='.$_GET['accept'].' AND '.objt());
	$tmpobject=new object(sql_1data("SELECT id FROM `[mpx]pos_obj` WHERE `own`=".$_GET['own']." AND (`type`='town' OR `type`='town2') AND ".objt()));
	$tmpobject->hold->add('gold',$_GET['gold']);
	$tmpobject->update();
	unset($tmpobject);
	send_report($GLOBALS['inc']['write_id'],$_GET['own'],'Budova '.$_GET['name'].' byla schválená','Vámi navržená budova '.$_GET['name'].' byla schválená a vám byla připočtena '.$_GET['gold'].' odměna zlata. Děkujeme za váš návrh.');
}
if($_GET['reject']=='alles'){
	sql_query('UPDATE `[mpx]pos_obj` SET ww=-2 WHERE ww=-1 AND name NOT LIKE \'{register_%\' AND '.objt());
}elseif($_GET['reject']){
	sql_query('UPDATE `[mpx]pos_obj` SET ww=-2 WHERE id='.$_GET['reject'].' AND '.objt());
}



$all='';
foreach(sql_array('SELECT id,name,origin,res,profile,own,type FROM [mpx]pos_obj WHERE ww=-1 AND name NOT LIKE \'{register_%\' AND '.objt().' ORDER BY id') as $row){
	list($id,$name,$origin,$res,$profile,$own,$type)=$row;

    if($bgcolor=='eeeeee')
        $bgcolor='ffffff';
    else
        $bgcolor='eeeeee';


	e('<tr bgcolor="#'.$bgcolor.'"><td valign="top">');

    if($type=='building') {
        $modelurl = modelx($res);
        e('<img src="' . $modelurl . '" />');
    }

	e('</td><td valign="bottom">');

	$profile=new profile($profile);
	$profile=$profile->vals2list();
	
	echo("<b>$name</b> ($id)<br/>");
	echo(tr($profile['description'])."<br/>");
	echo('<b>autor:</b> '.id2name($own)."<br/>");
	echo($origin."<br/>");
	foreach(array(2000,4000,6000,8000,10000,12000) as $gold){
	echo('<a href="?page=acceptunique&amp;own='.$own.'&amp;name='.$name.'&amp;gold='.$gold.'&amp;accept='.$id.'">schválit('.$gold.')</a> - ');
	}
	echo('<a href="?page=acceptunique&amp;own='.$own.'&amp;name='.$name.'&amp;reject='.$id.'">zamítnout</a>');
	br(2);

	
	e('</td></tr>');
}
?>
<table>
<a href="?page=acceptunique&amp;reject=alles">zamítnout vše</a>
