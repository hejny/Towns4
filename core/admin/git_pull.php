<?php
/* Towns4Admin, www.towns.cz 
   © Pavel Hejný | 2011-2014
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

      br();
      echo shell_exec("git remote rm origin 2>&1;");//Smazání propojení na vzdálený server
      br();
      echo shell_exec("git reset --hard 2>&1;");//Zrušení všech ještě necommitnutých změn
      br();
      echo shell_exec("git remote add origin ".$GLOBALS['inc']['git_origin']." 2>&1;");//Napojení repozitáře na DigitalOcean k BitBucketu
      br();
      echo shell_exec("git pull origin master --force 2>&1;");//Tvrdá aktualizace
      br();
      echo shell_exec("git rm origin 2>&1;"); //Smazání propojení na vzdálený server
      br();

   }
}else{
   error('Není nastavený git_origin!');
}


?>
