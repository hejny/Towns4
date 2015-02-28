<?php
/* Towns4Admin, www.towns.cz
   © Pavel Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================

?>

<h3>Převod .po Souborů</h3>
Kompilace jazykových .po souborů do .mo souborů.<br/>
<br>
<a href="?page=locale&amp;start=1">Aktualizovat</a><br>

<?php

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

   ob_start();

   foreach(glob('ui/locale/*/LC_MESSAGES/towns.po') as $pofile) {
      terminal('msgfmt '.$pofile);


      $mofile = str_replace('towns.po', 'towns.mo', $pofile);
      e("rename( messages.mo , $mofile ) ");
      rename('messages.mo', $mofile);
      br();
   }


   $contents=ob_get_contents();
   ob_end_clean();

   ?>
   <div style="color: #ffffff; background-color: #000000; width: 800px;height: 600px;overflow-x: hidden;overflow-y: auto;">
      <?=$contents ?>
   </div>
   <?php


}

?>
