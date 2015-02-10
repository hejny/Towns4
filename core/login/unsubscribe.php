<?php
if($_GET['email']){

sql_query("INSERT INTO  `[mpx]mail` (`mail` ,`active` ,`time`)VALUES ('".addslashes($_GET['email'])."',  '0',  '".time()."')");
//echo(mysql_error());

echo('Va&scaron;e e-mailov&aacute; adresa '.htmlspecialchars($_GET['email']).' byla vy&#345;azena z rozes&iacute;l&aacute;n&iacute;.');
}
?>
