<h3>CreateMap</h3>
Tyto nástroje slouží k vytvoření mapy z průvodního mapového podkladu.<br/>
<b>Upozornění: </b>Nesprávné použití může poškodit první podsvět!<br />
<b>Upozornění: </b>Některé procesy mohou trvat i několik minut.<br /><br />
<?php
if($_GET["image"])$GLOBALS['ss']["image"]=$_GET["image"];
if($_POST["image"])$GLOBALS['ss']["image"]=$_POST["image"];
if(!$GLOBALS['ss']["image"])$GLOBALS['ss']["image"]="tmpmap";


$imgurl='files/'.$GLOBALS['ss']["image"].'.png';

$world=1;
define("root", "../");


$action=$_POST["submit"];
if($_GET["action"])$action=$_GET["action"];
if($action=="createtmp"){
	die('<script language="javascript">
    window.location.replace("?page=createtmp&start=1");
    </script>');
}
echo($action);
//if($_GET["action"]){
	$actions=array("start","config","tables","map","treeL","tree","rockL","rock","finish","createtmp");
	$q=0;
	foreach($actions as $tmp){
		if($q==1){$newaction=$tmp;break;}
		if($action==$tmp)$q=1;
	}
	//echo("&nbsp;&gt;&gt;&gt;&nbsp;<a href=\"?action=".$newaction."\">$newaction</a><br>");
	echo("&nbsp;&gt;&gt;&gt;&nbsp;<b>$newaction</b><br>");

//}
echo("<br/>");




if($action=="config"){
$im = imagecreatefrompng($imgurl);
$file=root."world/".w.".txt";
$content=file_get_contents($file);
$content.=nln.'mapsize='.imagesx($im).';';
file_put_contents($file,$content);
/*
$im = imagecreatefrompng($GLOBALS['ss']["image"]);
$content="url=http://localhost/towns4/;
cache=tmp/".w.";
mysql_server=localhost;
mysql_user=root;
mysql_password=;
mysql_db=towns;
mysql_prefix=".w."_;
lang=cz;
lem=0;
mapsize=".imagesx($im).";
notmpimg=0;
timeplan=0;";
	$file="../config/w/".w.".txt";
	if(!file_exists($file)){
		file_put_contents($file,$content);
		chmod($file,0777);
	}
imagedestroy($im);*/
}
?>
<img src="files/<?php echo($GLOBALS['ss']["image"]); ?>.png"/>
<form id="form1" name="form1" method="post" action="?">
Image: files/<input name="image" type="text" id="image" value="<?php echo($GLOBALS['ss']["image"]); ?>" />.png<br>
<input type="submit" name="submit" value="ok" /><br>
<a href="?action=start">start</a>&nbsp;&gt;&gt;&gt;&nbsp;config&nbsp;&gt;&gt;&gt;&nbsp;tables&nbsp;&gt;&gt;&gt;&nbsp;map&nbsp;&gt;&gt;&gt;&nbsp;treeL&nbsp;&gt;&gt;&gt;&nbsp;tree&nbsp;&gt;&gt;&gt;&nbsp;rockL&nbsp;&gt;&gt;&gt;&nbsp;rock&nbsp;&gt;&gt;&gt;&nbsp;finish&nbsp;&gt;&gt;&gt;&nbsp;createtmp
<hr>
<input type="submit" name="submit" value="config" />
<input type="submit" name="submit" value="tables" />
</form>
<?php


if($_GET["action"]){
	echo('<script language="javascript">
    window.location.replace("?action='.$newaction.'");
    </script>');
}



//require("../control/func.php");
//-------
set_time_limit(10000);
$post=$_POST;
//-------
if($action=="tables"){
	$sql=file_get_contents('sql/create_map.sql');
	$sql=str_replace('[mpx]',mpx,$sql);
	sql_query($sql);
	$sql=file_get_contents('sql/create_objects.sql');
	$sql=str_replace('[mpx]',mpx,$sql);
	sql_query($sql);
	$sql=file_get_contents('sql/create_text.sql');
	$sql=str_replace('[mpx]',mpx,$sql);
	sql_query($sql);
}

if($action=="treeS"){
    $file="objects/tree.txt";
    echo($file." saved");
    file_put_contents($file,serialize($post));
    chmod($file,0777);
}
if($action=="rockS"){
    $file="objects/rock.txt";
    echo($file." saved");
    file_put_contents($file,serialize($post));
    chmod($file,0777);
}
if($action=="treeL"){
    $file="objects/tree.txt";
    $post=unserialize(file_get_contents($file));
}
if($action=="rockL"){
    $file="objects/rock.txt";
    $post=unserialize(file_get_contents($file));
}



//---------------------------------------------------------------------------------------
function generate($post){
$kmennuholnik=rand($post["kmennuholnik"],$post["kmennuholnikx"]);//7;
$kmenpoceturovni=rand($post["kmenpoceturovni"],$post["kmenpoceturovnix"]);//7;
if(!$kmenpoceturovni)$kmenpoceturovni=1;
$kmensirka=rand($post["kmensirka"],$post["kmensirkax"]);//10;
$kmenvyska=rand($post["kmenvyska"],$post["kmenvyskax"]);//150;
//---------
$a=rand(0,100)/100;$b=1-$a;
$red=(hexdec(substr($post["kmenfarba"],0,2))*$a)+(hexdec(substr($post["kmenfarbax"],0,2))*$b);
$green=(hexdec(substr($post["kmenfarba"],2,2))*$a)+(hexdec(substr($post["kmenfarbax"],2,2))*$b);
$blue=(hexdec(substr($post["kmenfarba"],4,2))*$a)+(hexdec(substr($post["kmenfarbax"],4,2))*$b);
if($red>255){$red=255;}if($red<0){$red=0;}
if($green>255){$green=255;}if($green<0){$green=0;}
if($blue>255){$blue=255;}if($blue<0){$blue=0;}
$kmenfarba=str_pad(dechex((intval($red)*256*256)+(intval($green)*256)+intval($blue)), 6, "0", STR_PAD_LEFT);
//---------
$a=rand(0,100)/100;$b=1-$a;
$red=(hexdec(substr($post["vetvafarba"],0,2))*$a)+(hexdec(substr($post["vetvafarbax"],0,2))*$b);
$green=(hexdec(substr($post["vetvafarba"],2,2))*$a)+(hexdec(substr($post["vetvafarbax"],2,2))*$b);
$blue=(hexdec(substr($post["vetvafarba"],4,2))*$a)+(hexdec(substr($post["vetvafarbax"],4,2))*$b);
if($red>255){$red=255;}if($red<0){$red=0;}
if($green>255){$green=255;}if($green<0){$green=0;}
if($blue>255){$blue=255;}if($blue<0){$blue=0;}
$vetvafarba=str_pad(dechex((intval($red)*256*256)+(intval($green)*256)+intval($blue)), 6, "0", STR_PAD_LEFT);
//---------
$krand=rand($post["krand"],$post["krandx"]);
//rand($post["kmenfarba"],$post["x"]);//"996600";
$vetvanuholnik=rand($post["vetvanuholnik"],$post["vetvanuholnikx"]);//7;
if(!$vetvanuholnik)$vetvanuholnik=$kmennuholnik;
$vetvapoceturovni=rand($post["vetvapoceturovni"],$post["vetvapoceturovnix"]);//7;
$vetvaod=rand($post["vetvaod"],$post["vetvaodx"]);//30;
$vetvasklon=rand($post["vetvasklon"],$post["vetvasklonx"]);//-10;
$vetvadlzka=rand($post["vetvadlzka"],$post["vetvadlzkax"]);//20;
$vetvadlzkaminus=rand($post["vetvadlzkaminus"],$post["vetvadlzkaminusx"]);//3;
$vetvasirka=rand($post["vetvasirka"],$post["vetvasirkax"]);//0;
$vetvavyska=rand($post["vetvavyska"],$post["vetvavyskax"]);//5;
//$vetvafarba=$post["vetvafarba"];//rand($post["vetvafarba"],$post["x"]);//"009900";
$rand=rand($post["rand"],$post["randx"]);//10;
$rand2=rand($post["rand2"],$post["rand2x"]);//10;
$rand3=rand($post["rand3"],$post["rand3x"]);//10;
//--------------------------
$xs=array();
$ys=array();
$zs=array();
$colors=array();
$polygons=array();

//++++++++++++kmen
if($post["kmenfarba"]!="x"){
    //$xs[0]=50;
    //$ys[0]=50;
    //$zs[0]=$kmenvyska;
    $q=0;$i=1;
    while($i!=$kmenpoceturovni+2){//echo($i);
        $ii=1;
        $uhel=0;
        while($ii-1 < $kmennuholnik){
        $p=(($i-1)/$kmenpoceturovni);
        $xs[$q]=50+((cos($uhel/180*pi())*$kmensirka)+rand(-$krand,$krand))*(1-$p);
        $ys[$q]=50+((sin($uhel/180*pi())*$kmensirka)+rand(-$krand,$krand))*(1-$p);
        $zs[$q]=$kmenvyska*$p+rand(-$krand,$krand);
        //--
        if($i!=$kmenpoceturovni+1){
            if($ii==1){$qq=$q+$kmennuholnik-1;}else{$qq=$q-1;}
            $polygons[count($polygons)]=array($q+1,$qq+1,$q+1+$kmennuholnik);
            $polygons[count($polygons)]=array($qq+1,$q+1+$kmennuholnik,$qq+1+$kmennuholnik);
            $colors[count($colors)]=$kmenfarba;
            $colors[count($colors)]=$kmenfarba;
        }
        //--
        $uhel=$uhel+(360/$kmennuholnik);
        $ii=$ii+1;	
        $q=$q+1;	
        }
    $i=$i+1;
    }
}
//++++++++++++vetve
$i=1;
$levelp=($kmenvyska-$vetvaod)/$vetvapoceturovni;
if($vetvapoceturovni==1){$level=-$kmenvyska;}
while($i!=$vetvapoceturovni+1){
$ii=1;
$level=$level+$levelp;
$uhel=0;
while($ii!=$vetvanuholnik+1){
//--
//echo(intval($uhel)."-".$level."(".($vetvanuholnik+$i+$ii-1).")");
$tmp=count($xs)/*$vetvanuholnik+($i*$vetvanuholnik)+($ii*2)-1-*/;
$x1=50+(cos($uhel/180*pi())*$kmensirka*(1-(($level+$vetvaod)/$kmenvyska)));
$y1=50+(sin($uhel/180*pi())*$kmensirka*(1-(($level+$vetvaod)/$kmenvyska)));
$x2=50+(cos(($uhel+(360/$kmennuholnik))/180*pi())*$kmensirka*(1-(($level+$vetvaod)/$kmenvyska)));
$y2=50+(sin(($uhel+(360/$kmennuholnik))/180*pi())*$kmensirka*(1-(($level+$vetvaod)/$kmenvyska)));
$xs[$tmp]=(($x1*$vetvasirka)+($x2*(100-$vetvasirka)))/100;
$ys[$tmp]=(($y1*$vetvasirka)+($y2*(100-$vetvasirka)))/100;
$zs[$tmp]=$vetvaod+$level;
$xs[$tmp+1]=(($x1*(100-$vetvasirka))+($x2*$vetvasirka))/100;
$ys[$tmp+1]=(($y1*(100-$vetvasirka))+($y2*$vetvasirka))/100;
$zs[$tmp+1]=$vetvaod+$level;
//------
$xs[$tmp+2]=/*($x1+$x2)/2*/50+rand(-$rand2,$rand2);
$ys[$tmp+2]=/*($y1+$y2)/2*/50+rand(-$rand2,$rand2);
$zs[$tmp+2]=$vetvaod+$level+$vetvavyska;//+(rand(-$rand2,$rand2)*2);
//------
$xs[$tmp+3]=rand(-$rand,$rand)+50+(cos(($uhel+(180/$vetvanuholnik))/180*pi())*($kmensirka+$vetvadlzka));
$ys[$tmp+3]=rand(-$rand,$rand)+50+(sin(($uhel+(180/$vetvanuholnik))/180*pi())*($kmensirka+$vetvadlzka));
$zs[$tmp+3]=$vetvaod+rand(-$rand,$rand)+$level+$vetvasklon;
//--
$aafabra=$vetvafarba;
$red=hexdec(substr($aafabra,0,2))+rand(-$rand3,$rand3);
$green=hexdec(substr($aafabra,2,2))+rand(-$rand3,$rand3);
$blue=hexdec(substr($aafabra,4,2))+rand(-$rand3,$rand3);
if($red>255){$red=255;}if($red<0){$red=0;}
if($green>255){$green=255;}if($green<0){$green=0;}
if($blue>255){$blue=255;}if($blue<0){$blue=0;}
$aafabra=str_pad(dechex((intval($red)*256*256)+(intval($green)*256)+intval($blue)), 6, "0", STR_PAD_LEFT);
//--
$polygons[count($polygons)]=array($tmp+3+1,$tmp+0+1,$tmp+1+1);
$colors[count($polygons)]=$aafabra;
$polygons[count($polygons)]=array($tmp+3+1,$tmp+1+1,$tmp+2+1);
$colors[count($polygons)]=$aafabra;
$polygons[count($polygons)]=array($tmp+3+1,$tmp+2+1,$tmp+0+1);
$colors[count($polygons)]=$aafabra;
//--
$ii=$ii+1;
$uhel=$uhel+(360/$vetvanuholnik);
}
$vetvadlzka=$vetvadlzka-$vetvadlzkaminus;
$i=$i+1;
}
//--------------------------
$res="";
$i=0;
foreach($xs as $tmp){
$res=$res."[".intval($xs[$i]).",".intval($ys[$i]).",".intval($zs[$i])."]";
$i=$i+1;
}
$res=$res.":";
foreach($polygons as $tmp){
$res=$res."".$tmp[0].",".$tmp[1].",".$tmp[2].";";
}
$res=$res.":";
foreach($colors as $tmp){
$res=$res."".$tmp.",";
}
$res=$res.":".rand(0,360);
return($res);
}
//--------------------------

if($GLOBALS['ss']["gdata"] and !$post)$post=$GLOBALS['ss']["gdata"];
if($post["kmennuholnik"]){
    $GLOBALS['ss']["gdata"]=$post;
    $res=generate($post);
}
//-=====================================================================================
?>

<form id="form1" name="form1" method="post" action="?">
  <table width="800" border="0">
    <tr>
      <th colspan="2"><div align="center">kmen</div></th>
      <td width="239" rowspan="15">
      <img src="<?php $rot=rand(0,360);$GLOBALS['ss']["res"]=$res; echo("model.php?model="."&rotation=".$rot."&sun=1.5&size=1&noln=1"); ?>" />
      </td>
        <td width="239" rowspan="15">
      <img src="<?php echo("model.php?model="."&rotation=".$rot."&sun=1&size=1"); ?>" />
      </td>
    </tr>
    <tr>
      <th width="116"><div align="left">po&#269;et stran: </div></th>
      <td width="188">
        <input name="kmennuholnik" type="text" id="kmennuholnik" size="5" value="<?php echo($post["kmennuholnik"]); ?>" />
        <input name="kmennuholnikx" type="text" id="kmennuholnikx" size="5" value="<?php echo($post["kmennuholnikx"]); ?>" />
      </td>
    </tr>
    <tr>
      <th><div align="left">po&#269;et &uacute;rovn&iacute;:</div></th>
      <td>
      <input name="kmenpoceturovni" type="text" id="kmenpoceturovni" size="5" value="<?php echo($post["kmenpoceturovni"]); ?>" />
      <input name="kmenpoceturovnix" type="text" id="kmenpoceturovnix" size="5" value="<?php echo($post["kmenpoceturovnix"]); ?>" />
      </td>
    </tr>
    <tr>
      <th><div align="left">&scaron;&iacute;&#345;ka:</div></th>
      <td>
      <input name="kmensirka" type="text" id="kmensirka" size="5" value="<?php echo($post["kmensirka"]); ?>" />
      <input name="kmensirkax" type="text" id="kmensirkax" size="5" value="<?php echo($post["kmensirkax"]); ?>" />
      </td>
    </tr>
    <tr>
      <th><div align="left">v&yacute;&scaron;ka:</div></th>
      <td>
      <input name="kmenvyska" type="text" id="kmenvyska"  size="5" value="<?php echo($post["kmenvyska"]); ?>" />
      <input name="kmenvyskax" type="text" id="kmenvyskax"  size="5" value="<?php echo($post["kmenvyskax"]); ?>" />
      </td>
    </tr>
    <tr>
      <th><div align="left">barva:</div></th>
      <td>
      <input name="kmenfarba" type="text" id="kmenfarba" size="5" value="<?php if($post["kmenfarba"]){ echo($post["kmenfarba"]);}else{echo("996600");} ?>" />
      <input name="kmenfarbax" type="text" id="kmenfarbax" size="5" value="<?php if($post["kmenfarbax"]){ echo($post["kmenfarbax"]);}else{echo("996600");} ?>" />
      </td>
    </tr>
        <tr>
      <th><div align="left">nepravidelnosti:</div></th>
      <td>
      <input name="krand" type="text" id="krand" size="5" value="<?php echo($post["krand"]); ?>" />
      <input name="krandx" type="text" id="krandx" size="5" value="<?php echo($post["krandx"]); ?>" />
      </td>
    </tr>
    <tr>
      <th colspan="2"><div align="center">v&#283;tve</div></th>
    </tr>
        <tr>
      <th width="116"><div align="left">po&#269;et stran: </div></th>
      <td width="188">
        <input name="vetvanuholnik" type="text" id="vetvanuholnik" size="5" value="<?php echo($post["vetvanuholnik"]); ?>" />
        <input name="vetvanuholnikx" type="text" id="vetvanuholnikx" size="5" value="<?php echo($post["vetvanuholnikx"]); ?>" />
      </td>
    </tr>
    <tr>
      <th><div align="left">po&#269;et &uacute;rovn&iacute;:</div></th>
      <td>
      <input name="vetvapoceturovni" type="text" id="vetvapoceturovni" size="5" value="<?php echo($post["vetvapoceturovni"]); ?>" />
      <input name="vetvapoceturovnix" type="text" id="vetvapoceturovnix" size="5" value="<?php echo($post["vetvapoceturovnix"]); ?>" />
      </td>
    </tr>
    <tr>
      <th><div align="left">v&#283;tve od:</div></th>
      <td>
      <input name="vetvaod" type="text" id="vetvaod" size="5" value="<?php echo($post["vetvaod"]); ?>" />
      <input name="vetvaodx" type="text" id="vetvaodx" size="5" value="<?php echo($post["vetvaodx"]); ?>" />
      </td>
    </tr>
    <tr>
      <th><div align="left">sklon:</div></th>
      <td>
      <input name="vetvasklon" type="text" id="vetvasklon" size="5" value="<?php echo($post["vetvasklon"]); ?>" />
      <input name="vetvasklonx" type="text" id="vetvasklonx" size="5" value="<?php echo($post["vetvasklonx"]); ?>" />
      </td>
    </tr>
    <tr>
      <th><div align="left">d&eacute;lka:</div></th>
      <td>
      <input name="vetvadlzka" type="text" id="vetvadlzka" size="5" value="<?php echo($post["vetvadlzka"]); ?>" />
      <input name="vetvadlzkax" type="text" id="vetvadlzkax" size="5" value="<?php echo($post["vetvadlzkax"]); ?>" />
      </td>
    </tr>
    <tr>
      <th><div align="left">d&eacute;lka-:</div></th>
      <td>
      <input name="vetvadlzkaminus" type="text" id="vetvadlzkaminus" size="5" value="<?php echo($post["vetvadlzkaminus"]); ?>" />
      <input name="vetvadlzkaminusx" type="text" id="vetvadlzkaminusx" size="5" value="<?php echo($post["vetvadlzkaminusx"]); ?>" />
      </td>
    </tr>
    <tr>
      <th><div align="left">&scaron;&iacute;&#345;ka%:</div></th>
      <td>
      <input name="vetvasirka" type="text" id="vetvasirka" size="5" value="<?php echo($post["vetvasirka"]); ?>" />
      <input name="vetvasirkax" type="text" id="vetvasirkax" size="5" value="<?php echo($post["vetvasirkax"]); ?>" />
      </td>
    </tr>
    <tr>
      <th><div align="left">v&yacute;&scaron;ka:</div></th>
      <td>
      <input name="vetvavyska" type="text" id="vetvavyska" size="5" value="<?php echo($post["vetvavyska"]); ?>" />
      <input name="vetvavyskax" type="text" id="vetvavyskax" size="5" value="<?php echo($post["vetvavyskax"]); ?>" />
      </td>
    </tr>
    <tr>
      <th><div align="left">barva:</div></th>
      <td>
      <input name="vetvafarba" type="text" id="vetvafarba" size="5" value="<?php if($post["vetvafarba"]){ echo($post["vetvafarba"]);}else{echo("006600");} ?>" />
      <input name="vetvafarbax" type="text" id="vetvafarbax" size="5" value="<?php if($post["vetvafarbax"]){ echo($post["vetvafarbax"]);}else{echo("006600");} ?>" />
      </td>
    </tr>
    <tr>
      <th><div align="left">nepravidelnosti:</div></th>
      <td>
      <input name="rand" type="text" id="rand" size="5" value="<?php echo($post["rand"]); ?>" />
      <input name="randx" type="text" id="randx" size="5" value="<?php echo($post["randx"]); ?>" />
      </td>
    </tr>
    <tr>
      <th><div align="left"></div></th>
      <td>
      <input name="rand2" type="text" id="rand2" size="5" value="<?php echo($post["rand2"]); ?>" />
      <input name="rand2x" type="text" id="rand2x" size="5" value="<?php echo($post["rand2x"]); ?>" />
      </td>
    </tr>
    <tr>
      <th><div align="left"></div></th>
      <td>
      <input name="rand3" type="text" id="rand3" size="5" value="<?php echo($post["rand3"]); ?>" />
      <input name="rand3x" type="text" id="rand3x" size="5" value="<?php echo($post["rand3x"]); ?>" />
      </td>
    </tr>
    <tr>
      <td colspan="3"><div align="left"><input type="submit" name="submit" value="ok" /></div></td>
    </tr>
        <tr>
      <td colspan="3"><div align="left"><input type="submit" name="submit" value="treeL" /><input type="submit" name="submit" value="rockL" /></div></td>
    </tr>
    <tr>
      <td colspan="3"><div align="left"><input type="submit" name="submit" value="treeS" /><input type="submit" name="submit" value="rockS" /></div></td>
    </tr>
        <tr>
      <td colspan="3"><div align="left"><input type="submit" name="submit" value="map" /><input type="submit" name="submit" value="tree" /><input type="submit" name="submit" value="rock" /><input type="submit" name="submit" value="finish" /></div></td>
    </tr>
  </table>
<?php echo($res); ?>
</form>


<?php
//-=====================================================================================
if($action=="map" or $action=="tree" or $action=="rock" or $action=="finish"){echo("<br>mapgenerator<br>");
    if($action=="map"){
        sql_query("DELETE FROM `".mpx."map`",1);
    }
    if($action=="tree"){
        sql_query("DELETE FROM `".mpx."objects` WHERE `name` LIKE '%tree%'",1);
    }
    if($action=="rock"){
        sql_query("DELETE FROM `".mpx."objects` WHERE `name` LIKE '%rock%'",1);
    }
    $im = imagecreatefrompng($imgurl);
    $bgs=array(
    
    array("t1",0,0,"5299F9"),//moře //Výbuchy v Blitz Stree
    array("t2",1,1,"545454"),//dlažba
    array("t3",1.1,1.2,"EFF7FB"),//sníh/led
    array("t4",1,1,"F9F98D"),//písek
    array("t5",1,3,"878787"),//kamení
    array("t6",0.8,1,"5A2F00"),//hlína
    array("t7",1.1,1.2,"DCDCAC"),//sůl
    array("t8",1,1,"2A7302"),//tráva(normal)
    array("t9",1,1,"51F311"),//tráva(toxic)
    array("t10",1,1,"535805"),//les
    array("t11",0.5,0.6,"337EFA"),//řeka
    array("t12",1,1,"8ABC02"),//tráva(jaro)
    array("t13",1,1,"8A9002") //tráva(pozim)
    );
    function rgbbr($a){
    $tmprgb2=array();
    $tmprgb=$a;//$row["rgb"];
    $tmprgb2[0]=(substr($tmprgb,0,2));//echo($tmprgb2[0].",");
    $tmprgb2[1]=(substr($tmprgb,2,2));//echo($tmprgb2[1].",");
    $tmprgb2[2]=(substr($tmprgb,4,2));//echo($tmprgb2[2]);
    eval("\$tmprgb2[0]=0x".$tmprgb2[0].";");//echo($tmprgb2[0].",");
    eval("\$tmprgb2[1]=0x".$tmprgb2[1].";");//echo($tmprgb2[1].",");
    eval("\$tmprgb2[2]=0x".$tmprgb2[2].";");//echo($tmprgb2[2]);
    return($tmprgb2);
    }
    function rgbsimilar($a,$b){
    $f=1.2;
    /*echo($a."<br/>");*/$a=rgbbr($a);//print_r($a);
    /*echo($b."<br/>");*/$b=rgbbr($b);//print_r($b);echo("<br/>");
    $tmp=((abs($a[0]-$b[0])+abs($a[1]-$b[1])+abs($a[2]-$b[2]))/3);
    return($tmp);
    }
    function chid($bgs,$rgb){
    $tmp=10000;$id=0;
    foreach($bgs as $row){
    $tmp2=rgbsimilar($row[3],$rgb);
    if($tmp2<$tmp){$tmp=$tmp2;$id=$row;}
    }
    return($id);
    }
    //======================================================================================
    //echo(imagesx($im).','.imagesy($im));
    $chuj=2;
    $y=0;
    while($y<imagesy($im)){
    $x=0;
    while($x<imagesx($im)){
    $q=0;
    while($q<$chuj){
    //-----
    $rgb = imagecolorat($im,$x,$y);
    $rgb = imagecolorsforindex($im,$rgb);
    $r=dechex($rgb["red"]);if(strlen($r)==1){$r="0".$r;}
    $g=dechex($rgb["green"]);if(strlen($g)==1){$g="0".$g;}
    $b=dechex($rgb["blue"]);if(strlen($b)==1){$b="0".$b;}
    $rgb=$r.$g.$b;
    list($terrain,$la,$lb)=chid($bgs,$rgb);
    $lp=100;
    $l=rand($la*$lp,$lb*$lp)/$lp;
    //-----
    $query="INSERT INTO `".mpx."map` (`x`, `y`, `ww`, `terrain`, `name`) VALUES ('$x','$y',1,'$terrain','')";
    if($action=="map" and $q==0){sql_query($query);echo($query.br);}
    //-----
    if($terrain=="t10" and $action=="tree"){
        //$tmp=new object("create");
        $res=generate($post).":".rand(1,360);
        $xx=$x+(rand(-50,50)/100);
        $yy=$y+(rand(-50,50)/100);
        //sql_query("INSERT INTO `".mpx."objects` (`name`,`res`, `x`, `y`, `hard`) VALUES ('tree [$x,$y]','$res','$xx','$yy',0.15)");
	$defence=rand(5,15);$a=rand(0,1000);$b=rand(500,1500);
	sql_query("INSERT INTO `".mpx."objects` (`name`, `type`, `dev`, `fs`, `fp`, `fr`, `fx`, `fc`, `func`, `hold`, `res`, `profile`, `set`, `hard`, `own`, `in`, `ww`, `x`, `y`, `t`) VALUES ('{tree} [$x,$y]', 'tree', 'N', '".pow($defence,2)."', '".pow($defence,2)."', '".($a+$b)."', '".($a+$b+pow($defence,2))."', 'fp=0;iron=".ceil(pow($defence,2)*(2/3)).";fuel=".ceil(pow($defence,2)*(1/3))."', 'defence=class[5]defence[3]params[5]defence[7]5[10]$defence"."[7]2[10]1[3]0[5]profile', 'energy=$a;wood=$b', '$res', '', '', '0.15', '0', '0', '1', '$xx', '$yy', '".time()."')");
	
	//exit;
        $chuj=4;
    }
    //-----
    if($terrain=="t5" and $action=="rock"){
        //$tmp=new object("create");
        $res=generate($post).":".rand(1,360);
        $xx=$x+(rand(-50,50)/100);
        $yy=$y+(rand(-50,50)/100);
        //sql_query("INSERT INTO `".mpx."objects` (`name`,`res`, `x`, `y`, `hard`) VALUES ('rock [$x,$y]','$res','$xx','$yy',1)");
	$defence=rand(20,100);$a=rand(1500,2000);$b=rand(0,2000);
	sql_query("INSERT INTO `".mpx."objects` (`name`, `type`, `dev`, `fs`, `fp`, `fr`, `fx`, `fc`, `func`, `hold`, `res`, `profile`, `set`, `hard`, `own`, `in`, `ww`, `x`, `y`, `t`) VALUES ('{rock} [$x,$y]', 'rock', 'N', '".pow($defence,2)."', '".pow($defence,2)."', '".($a+$b)."', '".($a+$b+pow($defence,2))."', 'fp=0;iron=".ceil(pow($defence,2)*(2/3)).";fuel=".ceil(pow($defence,2)*(1/3))."', 'defence=class[5]defence[3]params[5]defence[7]5[10]$defence"."[7]2[10]1[3]0[5]profile', 'stone=$a;iron=$b', '$res', '', '', '1', '0', '0', '1', '$xx', '$yy', '".time()."')");

        $chuj=3;
    }
    //-----
    $q=$q+1;
    }
    $x=$x+1;
    }
    $y=$y+1;
    }
    //======================================================================================
    ImageDestroy($im);
    if($action=="finish"){//echo('finish');
	$sql=file_get_contents('sql/hard.sql');
	$sql=str_replace('[mpx]',mpx,$sql);
	sql_query($sql);

        //sql_query('');
	//sql_query('UPDATE `world3_map` SET  `hard` = `hard`+IF( `world3_map`.`terrain`=\'t1\' OR  `world3_map`.`terrain`=\'t11\',1,0)');
    }
}
//=====================================================================================
?>
