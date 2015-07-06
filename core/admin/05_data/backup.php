<?php
/* Towns4Admin, www.towns.cz 
   © Pavol Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================
?>
<h3>Vytvoření a stažení záloh ze vzdáleného serveru </h3>
<?php

if($GLOBALS['inc']['backup_url'] and $GLOBALS['inc']['backup_file']){

	if($_GET['backup']){

		mkdir(adminfile.'files/backup');
		chmod(adminfile.'files/backup',0777);
		$nejm='backup_'.time().'_'.date('j_n_Y');
		$file_zip=adminfile."files/backup/".$nejm.".zip";

		file_get_contents($GLOBALS['inc']['backup_url']);
		copy($GLOBALS['inc']['backup_file'],$file_zip);
		chmod($file_zip,0777);

		echo('<b>Záloha uložena do '.$file_zip.'</b>');

		br();
		br();
	}
	echo('<a href="?page=backup&amp;backup=1">vytvořit a stáhnout</a>');

}else{
	e('!config');
}
?>
