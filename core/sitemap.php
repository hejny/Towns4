<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2015
   _____________________________

   core/sitemap.php

//==============================
*/

header('Content-Type: application/xml');



e('<urlset>');



e('<url>');
e('<loc>'.url.'</loc>');
e('<lastmod>'.date(DATE_W3C,filemtime(core.'/page/aac.php')).'</lastmod>');
//e('<priority>1.0</priority>');
//e('<changefreq>always</changefreq>');
e('</url>');


$objects=sql_array('SELECT id,starttime FROM [mpx]pos_obj WHERE type=\'story\' AND '.objt());
foreach($objects as $object){

    e('<url>');
    e('<loc>'.url.$object['id'].'</loc>');
    e('<lastmod>'.date(DATE_W3C,$object['starttime']).'</lastmod>');
    e('</url>');

}


e('</urlset>');


?>