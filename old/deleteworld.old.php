<h3>Smazání sv&#283;ta <?php echo(w); ?> </h3>
<b>Upozornění: </b>Tato funkce smaže celý svět <?php echo(w); ?>, jeho podsvěty, konfiguraci, tabulky a pomocné soubory!<br />
<?php
if($_GET['total']==w){
	sql_query('DROP TABLE `'.mpx.'map`;');
	sql_query('DROP TABLE `'.mpx.'text`;');
	sql_query('DROP TABLE `'.mpx.'objects`;');
	sql_query('DROP TABLE `'.mpx.'memory`;');
	sql_query('DROP TABLE `'.mpx.'login`;');
	unlink(root.'world/'.w.'.txt');
	emptydir(root.cache);
	echo('Svět '.w.' smazán.');
	session_destroy();
}
?>

<a href='?total=<?php echo(w); ?>'>smazat</a>
