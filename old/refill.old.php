<h3>Výměna stromů a skal ve <?php echo(w); ?> </h3>

<?php
set_time_limit(10000);



e('<form action="" method="GET">');

e('defence: ');
input_text('defence_from',$_GET['defence_from'],NULL,7);
e(' - ');
input_text('defence_to',$_GET['defence_to'],NULL,7);
br();
e('fuel: ');
input_text('fuel_from',$_GET['fuel_from'],NULL,7);
e(' - ');
input_text('fuel_to',$_GET['fuel_to'],NULL,7);
br();
e('wood: ');
input_text('wood_from',$_GET['wood_from'],NULL,7);
e(' - ');
input_text('wood_to',$_GET['wood_to'],NULL,7);
br();
e('stone: ');
input_text('stone_from',$_GET['stone_from'],NULL,7);
e(' - ');
input_text('stone_to',$_GET['stone_to'],NULL,7);
br();
e('iron: ');
input_text('iron_from',$_GET['iron_from'],NULL,7);
e(' - ');
input_text('iron_to',$_GET['iron_to'],NULL,7);
br();


e('<input type="submit" name="wtf" value="tree" />');
e('<input type="submit" name="wtf" value="rock" />');
e('</form>');

$onpage=1;

$count=sql_1data('SELECT count(1) FROM [mpx]objects WHERE type=\''.$_GET['wtf'].'\'');
echo('<b>'.$_GET['offset'].' / '.$count.'</b>');

if($_GET['wtf']){
	foreach(sql_array('SELECT id FROM [mpx]objects WHERE type=\''.$_GET['wtf'].'\' LIMIT '.($_GET['offset']?$_GET['offset']:0).','.$onpage.'',1) as $row){
		$defence=rand(intval($_GET['defence_from']),intval($_GET['defence_to']));

		$fuel=rand(intval($_GET['fuel_from']),intval($_GET['fuel_to']));
		$wood=rand(intval($_GET['wood_from']),intval($_GET['wood_to']));
		$stone=rand(intval($_GET['stone_from']),intval($_GET['stone_to']));
		$iron=rand(intval($_GET['iron_from']),intval($_GET['iron_to']));
		echo("$defence,$fuel,$wood,$stone,$iron");br();

		list($id)=$row;
		echo($id.',');
		$tmp=new object($id);
		
		$tmp->func->delete('defence');
		$params=new vals('defence='.$defence.',1');
		$hold=new hold("fuel=$fuel;wood=$wood;stone=$stone;iron=$iron;");

		$tmp->func->add('defence','defence',$params);

		$tmp->hold=$hold;


		$tmp->update();
		unset($tmp);
		unset($params);
		unset($hold);

	}
	if($_GET['offset']-1+1<=$count){
	echo('<script language="javascript">
    window.location.replace("?defence_from='.$_GET['defence_from'].'&defence_to='.$_GET['defence_to'].'&fuel_from='.$_GET['fuel_from'].'&fuel_to='.$_GET['fuel_to'].'&wood_from='.$_GET['wood_from'].'&wood_to='.$_GET['wood_to'].'&stone_from='.$_GET['stone_from'].'&stone_to='.$_GET['stone_to'].'&iron_from='.$_GET['iron_from'].'&iron_to='.$_GET['iron_to'].'&wtf='.$_GET['wtf'].'&offset='.($_GET['offset']-(-$onpage)).'");
    </script>');/**/
	}
}
?>


