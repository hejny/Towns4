<?php
/* Towns4Admin, www.towns.cz
   © Pavol Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================

?>

<h3>Aktualizace verze pomocí Git Pull</h3>
Stažení a nasazení aktuální verze kódu na tento server<br/>
<b>Upozornění: </b>Tento proces zruší všechny změny, které jsou natvrdo provedené (např. pomocí FTP).<br/>
<b>Upozornění: </b>Tento proces znefunkční server, pokud je kód v hlavním repozitáři nefunkční.<br/>
<b>Upozornění: </b>Tento proces může trvat i několik minut.<br/>
<br>
<a href="?page=git_pull&amp;start=1">Aktualizovat</a><br>

<?php

//mkdir('wwwww');
//chmod('wwwww',0777);
//echo shell_exec("git init 2>&1;");
//echo '<hr/>';
//chdir('/var/www/towns_cz/wwwww/')
//;

if($_SERVER['REMOTE_ADDR']=='127.0.0.1'){
   error('Z bezpečtnostních důvodů je tato funkce znemožněna na localhostu.');
   die();
}


if($GLOBALS['inc']['git_origin']) {
   if ($_GET['start']) {

      ini_set('memory_limit', '256M');
      ini_set("max_execution_time","1000");


      function terminal($command,$show=false){

         if(!$show)$show=$command;
         e('<span style="color: #aaffaa;">');
         e(nl2br(htmlspecialchars($show.nln)));
         e('</span>');

         $response=shell_exec($command.' 2>&1;');
         e(nl2br(htmlspecialchars($response.nln)));
      }

      /*function recursive_chmod_all($loc,$ignore){

	foreach(scandir($loc) as $file){
		if(!in_array($file,$ignore)){

			$file=$loc.'/'.$file;
			$file=substr($file,2);
			//chown($file,0777);
			echo('chmod 0777 '.$file);
			terminal('chmod 0777 '.$file);
			br();
			if(is_dir($loc)){
				 recursive_chmod_all('./'.$file,$ignore);

			}
		}
	}}*/

      ob_start();
      terminal("whoami");
      terminal("git remote rm origin");//Smazání propojení na vzdálený server
      terminal("git reset --hard");//Zrušení všech ještě necommitnutých změn
      terminal("git remote add origin ".$GLOBALS['inc']['git_origin'],"git remote add origin ****");//Napojení repozitáře na DigitalOcean k BitBucketu
      terminal("git pull origin master --force");//Tvrdá aktualizace
      terminal("git rm origin"); //Smazání propojení na vzdálený server


      //chown('index.php','ftpuser');
      /*hr();
      $ignore=array('.','..','.git','tmp');
      recursive_chmod_all('.',$ignore);*/


      $contents=ob_get_contents();
      ob_end_clean();

      ?>
      <div style="color: #ffffff; background-color: #000000; width: 800px;height: 600px;overflow-x: hidden;overflow-y: auto;">
         <?=$contents ?>
      </div>
   <?php


       require(core.'/admin/03_deploy/locale.php');//Po deploy je potřeba aktualizovat jazykové soubory

   }
}else{
   error('Není nastavený git_origin!');
}


?>
