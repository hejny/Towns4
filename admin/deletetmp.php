<?php
/* Towns4Admin, www.towns.cz 
   © Pavel Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================
?>
<h3>Smazání pomocných souborů sv&#283;ta <?php echo(w); ?> </h3>
<b>Upozornění: </b>Tato funkce pomocné soubory světa <?php echo(w); ?>.<br />
<?php
if($_GET['total']==w){
	emptydir(root.cache);
	echo('Pomocné soubory světa '.w.' byly smazány.');
	session_destroy();
}
?>

<a href='?total=<?php echo(w); ?>'>smazat</a>
