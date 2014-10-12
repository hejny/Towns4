<h3>Object</h3>
Tato funkce slouží k úpravě jednotlivých objektů.<br/>
<?php
$post=$_POST;
$get=$_GET;
?>

<?php
function admin_s_input($name,$value){
    $input="s_input_".$name;
    if($_POST[$input])$GLOBALS['ss'][$name]=$_POST[$input];
	if($_GET[$input])$GLOBALS['ss'][$name]=$_GET[$input];
    if($GLOBALS['ss'][$name])$value=$GLOBALS['ss'][$name];
    ?>
    <form id="login" name="login" method="POST" action="?page=object">
    <?php
    input_text($input,$value);
    form_sb();
    //echo($GLOBALS['ss'][$name]);
    return($GLOBALS['ss'][$name]);
}
//--------------------------
$object=new object(admin_s_input("admin",$GLOBALS['ss']["useid"]));
//print_r($object);

//-----------------COPY
if($post['copy'] and $post['copyid'] and ($post['copy']!=mpx or $post['copyid']!=$object->id)){
   list($row)=sql_array('SELECT * FROM [mpx]objects WHERE id=\''.$object->id.'\'');
   $row[0]=$post['copyid'];
   //print_r($row);br();
   $row2=array();
   foreach($row as $key=>$field){if(is_int($key)){$row2[$key]="'$field'";}}
   //print_r($row2);br();
   $row2=implode(',',$row2);
   sql_query('INSERT INTO '.$post['copy'].'objects VALUES ('.$row2.')',2);
}
?>
<form id="login" name="login" method="POST" action="">
<b>Zkopírovat do[mpx]:</b>
<?php input_text("copy",mpx,40); ?>
(id: <?php input_text("copyid",$object->id,40); ?>)
<input type="submit" value="OK" /><hr>
</form>
<?php
//----------------------------------------
if($post["edit_func"]){
    $edit_func=$post["edit_func"];
    $edit_class=$post["edit_class"];
    $edit_params=new vals($post["edit_params"],true);
    //r($edit_func,$edit_class);
    //r("qwerty");
    //$edit_params->r();
    $edit_profile=new vals($post["edit_profile"]);
    //r($object->func->vals2list());
    //r($edit_params->vals2list());
    $object->func->add($edit_func,$edit_class,$edit_params,$edit_profile);
    //r($object->func->vals2list());
}
//----------------------------------------
/*if($get["toall"]){
foreach(sql_array("SELECT id,name FROM ".mpx."objects WHERE type='".$get["toall"]."'") as $id){list($id,$name)=$id;
	echo("$name($id)<br/>");
	$object=new object($id);
	
	unset($object);
}
}
echo("<a href=\"?toall=user\">Na všechny uživatele</a><br/>");
echo("<a href=\"?toall=tree\">Na všechny stromy</a><br/>");
echo("<a href=\"?toall=rock\">Na všechny skály</a><br/>");*/
//----------------------------------------
if($get["hold_send"]){//echo("aaa");

    $edit_hold=new hold($post["edit_hold"],true);
//$edit_hold->r();
    $object->hold=$edit_hold;
}
//----------------------------------------
if($get["delete"]){
    $object->func->delete($get["delete_func"]);
}
//----------------------------------------
if($_POST["name"]){
    $object->setName($_POST["name"]);
}
if($_POST["fp"]){
    $object->setFP($_POST["fp"]);
}
$fp=$object->getFP();
$name=$object->getName();
?>
<form id="login" name="login" method="POST" action="?page=object">
<?php
te("Jméno: ");
input_text("name",$name,100,8);form_sb();
form_a('?');
te("FP: ");
input_text("fp",$fp,100,8);form_sb();
//----------------------------------------


$edit_hold= $object->hold->vals2list();
$edit_hold=x2xx(xxx2conf($edit_hold));
?>

<form id="login" name="login" method="POST" action="?page=object&amp;hold_send=1">
<b>Suroviny:</b><br>
<?php input_textarea("edit_hold",$edit_hold,55,4); ?><br>
<input type="submit" value="OK" />
</form>
<?php
//----------------------------------------
$funcs= $object->func->vals2list();
foreach($funcs as $name=>$func){
    $class=$func["class"];
    $params=$func["params"];
    $profile=$func["profile"];
    $edit_params=x2xx(xxx2conf($params));
    $edit_profile=x2xx(xxx2conf($profile));
    //$name2=str_replace($class,"",$name);
    //if($name2){$name2="$class#$name2";}else{$name2=$class;}
    //r($GLOBALS['ss']["use_object"]->func->func($name));
    echo("<b>$name ($class)</b>&nbsp;&nbsp;&nbsp;");
    echo("<a href=\"?edit=1&amp;edit_func=$name&amp;edit_class=$class&amp;edit_params=$edit_params&amp;edit_profile=$edit_profile\">Upravit</a>&nbsp;&nbsp;&nbsp;");
    echo("<a href=\"?delete=1&amp;delete_func=$name\">Smazat</a>&nbsp;&nbsp;&nbsp;");
    foreach($params as $param=>$values){
        list($a,$b,$c)=$values;
        if(!$b){$b=1;}//if(!$c){$c=1;}
        $x=$a*$b;
        echo("<br>&nbsp;&nbsp;&nbsp;<b></b>$param = $x ($a,$b)");
    }
    foreach($profile as $param=>$value){
        echo("<br>&nbsp;&nbsp;&nbsp;<b></b>$param: $value");
    }
    echo("<br>");
}
//----------------------------------------
if($get["edit"]){$edit_func=$get["edit_func"];}else{$edit_func=$post["edit_func"];}
if($get["edit"]){$edit_class=$get["edit_class"];}else{$edit_class=$post["edit_class"];}
if($get["edit"]){$edit_params=$get["edit_params"];}else{$edit_params=$post["edit_params"];}
if($get["edit"]){$edit_profile=$get["edit_profile"];}else{$edit_profile=$post["edit_profile"];}
//----------------------------------------
$object->update();
?>
<hr>
<form id="login" name="login" method="POST" action="?func_send=1">
<table>
<tr><td><b>funkce:</b></td><td><?php input_text("edit_func",$edit_func); ?><br></td></tr>
<tr><td><b>třída:</b></td><td><?php input_text("edit_class",$edit_class); ?></td></tr>
<tr><td colspan="2"><?php input_textarea("edit_params",$edit_params,55,4); ?></td></tr>
<tr><td colspan="2"><?php input_textarea("edit_profile",$edit_profile,55,4); ?></td></tr>
<tr><td colspan="2"><input type="submit" value="OK" /></td></tr>
</table>
</form>

