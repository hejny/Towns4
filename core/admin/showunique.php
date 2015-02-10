<?php
/* Towns4Admin, www.towns.cz 
   © Pavel Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================

ob_end_flush();

$all='';
foreach(sql_array('SELECT id,name,type,origin FROM '.mpx.'objects WHERE ww=0 ORDER BY id') as $row){list($id,$name,$type,$origin)=$row;
	echo("$name($id)".($type=='building'?'':"($type)")."".($origin?"(origin: $origin)":'')."<br/>");
	$all.=$id.',';
}
echo('<hr/>'.$all);
?>
