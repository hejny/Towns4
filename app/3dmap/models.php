<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_WARNING );
define("nln","
");
$models=$_POST["models"];
$params=$_POST["params"];
//=============================================================
if(!$models){
?>
<form action="" method="POST">
<textarea name="models" style="width:100%;height:90%;"></textarea>
<input type="text" name="params" value="rot=0&mode=1&sun=1&destruct=0" style="width:90%;">
<input type="submit" value="OK">
</form>
<?php
}else{
	//print_r(split(nln,$models));
	echo("<div style=\"width:90%;\">");
	foreach(split(nln,$models) as $tmp){
		if($tmp){
			if(substr($tmp,0,1)=="["){
				$res=$tmp;
			}else{
				$name=$tmp;
				$name=str_replace(":","",$name);
			}
		}
		if($res){
			?>
			<nobr>
			<!--<span style="position:absolute;"><div style="position:relative;left:0px;top:256px;width:140px;text-align:center;"><b><?php echo($name); ?></b></div></span>-->
			<img src="model.php?res=<?php echo($res); ?>&s=0.7&<?php echo($params); ?>" width="140" height="266"/>
			<span style="position:absolute;"><div style="position:relative;left:-140px;top:266px;width:140px;text-align:center;white-space:normal;"><b><?php echo($name); ?></b></div></span>
			</nobr>
			<?php
			$res="";
		}
	}/**/
	echo("</div>");
}
?>

