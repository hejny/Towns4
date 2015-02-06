<?php
/* Towns4Admin, www.towns.cz 
   © Pavel Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================

ob_end_flush();

?>
<h3>multibuild </h3>
Hromadné stavění budov<br/>
<?php


if(!logged()){

error('Tato funkce funguje pouze s přihlášením!');

}else{

require_once(root.core."/create/func_core.php");

if($_POST['grid'] and $_POST['func'] and $_POST['id']){
	$y=$_POST['y']-1;
	foreach(explode(nln,$_POST['grid']) as $row){
		$y++;
		$x=$_POST['x']-1;
		foreach(str_split($row) as $char){
			$x++;
			if($char=='1'){
				$query=$_POST['func'].' '.$_POST['id'].",$x,$y";
				info($query);
				xqr($query);
			}
		}
	}
}
//-------------------------- MAIN BUILDING

if(!$GLOBALS['hl']){
if($GLOBALS['config']['register_building']){
//if(1){

	if($hl=sql_array('SELECT id,ww,x,y FROM `[mpx]pos_obj` WHERE ww='.$GLOBALS['ss']['ww'].' AND own='.$GLOBALS['ss']['useid'].' AND type=\'building\' and TRIM(name)=\''.id2name($GLOBALS['config']['register_building']).'\' LIMIT 1')){
	 //print_r($hl);
    list($GLOBALS['hl'],$GLOBALS['hl_ww'],$GLOBALS['hl_x'],$GLOBALS['hl_y'])=$hl[0];
}else{//e(1);
    $GLOBALS['hl']=0; 
}
}else{//e(2);
    $GLOBALS['hl']=0; 
}
}
//-------------------------- 
?>

<form id="form1" name="form1" method="post" action="">
<b>func:</b><input name="func" id="func" type="text" value="<?php if($_POST['func']){echo($_POST['func']);}else{echo(sql_1data("SELECT id FROM `[mpx]pos_obj` WHERE name='{building_master_wall}' and own=".$GLOBALS['ss']['useid']).'.create');} ?>">
<b>id:</b><input name="id" id="id" type="text" value="<?php if($_POST['id']){echo($_POST['id']);}else{echo('1000006');} ?>">
<b>x:</b><input name="x" id="x" type="text" value="<?php if($_POST['x']){echo($_POST['x']);}else{echo($GLOBALS['hl_x']);} ?>">
<b>y:</b><input name="y" id="y" type="text" value="<?php if($_POST['y']){echo($_POST['y']);}else{echo($GLOBALS['hl_y']);} ?>">
<textarea name="grid" id="grid" style="width: 90%;height: 70%;"><?php echo($_POST['grid']); ?></textarea><br/>
<input type="submit" name="Submit" value="Postavit" />
</form>
<?php } ?>
