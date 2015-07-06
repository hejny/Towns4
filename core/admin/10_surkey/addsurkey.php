<?php
/* Towns4Admin, www.towns.cz 
   © Pavol Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================
?>
<h3>Suroviny +</h3>
Tato funkce slouží k připsání surovin konkrétnímu městu.<br/>
<?php
br();

//--------------------------
if($_GET['hold_send']){
	$object=new object($_POST['town'],NULL,'town');
	//print_r($object);


	if(!$object->loaded){
		error('Tento objekt neexistuje!');
	}elseif($object->type!='town' and $object->type!='town2'){
		error('Tento objekt není město!');
	}else{
			$object->hold->add('wood',$_POST['wood']);
			$object->hold->add('stone',$_POST['stone']);
			$object->hold->add('iron',$_POST['iron']);
			$object->hold->add('fuel',$_POST['fuel']);
			$object->hold->add('gold',$_POST['gold']);
			$object->update();
		success('Přidáno!');
	}
}
?>

<form id="addsurkey" name="addsurkey" method="POST" action="?page=addsurkey&amp;hold_send=1">
<table border="0">
<tr><td><b>Město:</b></td><td><?php input_text('town',$_POST['town']); ?></td></tr>

<tr><td colspan="2"><b>Suroviny:</b></td></tr>
<tr><td>Dřevo +=</td><td><?php input_text('wood',''); ?></td></tr>
<tr><td>Kámen +=</td><td><?php input_text('stone',''); ?></td></tr>
<tr><td>Železo +=</td><td><?php input_text('iron',''); ?></td></tr>
<tr><td>Palivo +=</td><td><?php input_text('fuel',''); ?></td></tr>
<tr><td>Zlato +=</td><td><?php input_text('gold',''); ?></td></tr>
<tr><td colspan="2"><input type="submit" value="Přidat" /></td></tr>
</table>
</form>


