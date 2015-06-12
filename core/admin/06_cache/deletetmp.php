<?php
/* Towns4Admin, www.towns.cz 
   © Pavol Hejný | 2011-2014
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
	if($_GET['dir']){
		
		if(strpos($_GET['dir'],root.cache)===0 and !strpos($_GET['dir'],'..')){
			emptydir($_GET['dir']);
			echo('Pomocné soubory '.$_GET['dir'].' byly smazány.');br(2);
		}else{
			echo('!');br(2);
		}


	}else{
		emptydir(root.cache);
		echo('Pomocné soubory světa '.w.' byly smazány.');br(2);
		session_destroy();
	}
}
?>

<a href='?total=<?php echo(w); ?>'>smazat</a>

<?php
foreach(glob(root.cache.'/*') as $dir){
br();
e("<a href='?total=".w."&dir=".$dir."'>smazat $dir</a>");

}
?>
