<?php ob_end_flush(); ?>
<h3>Lang </h3>
<?php
if(!$GLOBALS['ss']["editlang"]){
   $GLOBALS['ss']["editlang"]='cz';
}
if($_GET["editlang"]){
   $GLOBALS['ss']["editlang"]=$_GET["editlang"];
}

$q=false;foreach(array('cz','en') as $lang){
   if($q){
      echo('&nbsp;-&nbsp;');
   }
   $q=true;
   if($GLOBALS['ss']["editlang"]==$lang){
      echo('<b>'.$lang.'</b>');
   }else{
      echo('<a href="?editlang='.$lang.'">'.$lang.'</a>');
   }
}


if($_POST['contents']){
	echo('<br/><b>změněno</b><br/>');
	$contents=file_put_contents2(root.'data/lang/'.$GLOBALS['ss']["editlang"].'.txt',$_POST['contents']);
}
$contents=file_get_contents(root.'data/lang/'.$GLOBALS['ss']["editlang"].'.txt');
$contents=htmlspecialchars($contents);
?>

<form id="form1" name="form1" method="post" action="">
<textarea name="contents" style="width: 90%;height: 70%;">
<?php echo($contents); ?> 
</textarea><br/>
<input type="submit" name="Submit" value="Změnit" />
</form>
