<?php
/* Towns4Admin, www.towns.cz 
   © Pavel Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================
?>

<script>
function copyToClipboard(text) {
  //window.prompt("Copy to clipboard: Ctrl+C, Enter", text);
  var myWindow = window.open("", "myWindow", "width=400, height=200");    // Opens a new window
    myWindow.document.write(text);                  // Text in the new window
    //myWindow.opener.document.write("<p>This is the source window!</p>");  // Text in the window that 
}
</script>
<?php
require2("/func_map.php");
require2("text/func_core.php");


$onrow=6;
$usercolor=false;//'22ff22';
if($_GET['rr']){$rr=$_GET['rr'];}else{$rr=':10';}
if($_GET['dd']){$dd=$_GET['dd'];}else{$dd=1;}


//-------------------------------------------------------------VYTVORIT BUDOVU
if($_POST['origin']){
    if(/*$id=sql_1data('SELECT id FROM [mpx]objects WHERE ww=0 AND own=0 AND origin=\''.$_GET['origin'].'\'')*/ false){
        /*$profile=sql_1data('SELECT profile FROM [mpx]objects WHERE id='.$id);
        $profile=new profile($profile);
        $profile->add($description,$_POST['name']);*/
        /*$object=new object($id);
        $object->name=$_POST['name'];
        $object->profile->add('description',$_POST['description']);
        $object->res=$_POST['res'];
        $object->update();*/
        //e($id.'!rewrite');
    }else{
        $profile=new profile('');
        $profile->add('description',$_POST['description']);
        $profile=$profile->vals2str();
        //e($profile);
        $origin=explode(',',$_POST['origin']);
        sort($origin);
        $origin=implode(',',$origin);
        $sql="INSERT INTO [mpx]objects (`id`,`name`,`profile`,`origin`,`res`,`type`,`own`,`ww`) VALUES ('".nextid()."','".sql($_POST['name'])."','".sql($profile)."','".sql($origin)."','".sql($_POST['res'])."','building',".sql($_POST['author']).",-1)";
        sql_query($sql);
    }
}
//-------------------------------------------------------------

$join=$_GET['join'];
if(!$join){
    $jurl='';
    e('<b>Začít budovou: </b>');br();
    
}else{
    $jurl=$join.',';
    $jurlx=$join;
    $join=explode(',',$join);
    //-----------------------------------------------------
        //-----------------------------------------------------VYTVOENI AUTOMATICKE KOMBINACE
        $first=true;$i=0;
        foreach($join as $id){

            $row=sql_array('SELECT id,name,type,origin,profile,res,func FROM '.mpx.'objects WHERE id=\''.$id.'\' ');
            list($id,$name,$type,$origin,$profile,$res,$func)=$row[0];
            $profile=str2list($profile);
            $description=($profile['description']);
            if($first){
                $auto_name=$name;
                $auto_res=$res;
                $auto_description=$description;
                $auto_origin=$origin;
                $first=false;
            }else{
                $auto_origin=$auto_origin.','.$origin;
                $auto_origin=explode(',',$auto_origin);
                sort($auto_origin);
                $auto_origin=implode(',',$auto_origin);
                    $reference=sql_1data("SELECT `res` FROM `[mpx]objects` WHERE `ww`=0 AND `origin`='".$auto_origin."' ORDER BY id LIMIT 1");
                    if($reference){
                        if(count($join)!=$i+1/*count($join)==$i+2*/){
                            $auto_res=$reference;
                        }else{
                            e('last=>no reference'.$name);
                            $auto_resx=$reference;
                            $auto_res=model2model($auto_res,$res,$usercolor);
                        }
                    }else{
                        $auto_res=model2model($auto_res,$res,$usercolor);
                    }
                $auto_name=name2name($auto_name,$name);
                $auto_description=$auto_description.nln.$description;
                
            }
            
            $i++;
        }
        if(!$auto_resx)$auto_resx=$auto_res;
        
        /*br();e('a:'.strlen($auto_resx));
        $modelurl=modelx($auto_resx);
        e('<img src="'.$modelurl.'" border="2" />');
        exit;*/
        
        //-----------------------------------------------------SEZNAM BUDOV
        if(count($join)>1){
            $i=0;
            e('<table border="0"><tr>'.((count($join)>$onrow+1)?'<td><br/><br/>&nbsp;<h2>&nbsp;</h2>&nbsp;</td>':''));
            foreach($join as $id){
                e('<td align="center">');
                
                $row=sql_array('SELECT id,name,type,origin,profile,res,func FROM '.mpx.'objects WHERE id=\''.$id.'\' ');
                list($id,$name,$type,$origin,$profile,$res,$func)=$row[0];
                $modelurl=modelx($res.$rr,$dd,$usercolor);
                
                //$jxurl=explode($id,$_GET['join'],3);
                $tmp=$join;
                array_splice($tmp,$i,1);
                $tmp=implode(',',$tmp);
                
            	e('<a href="?page=unique&join='.$tmp.'&amp;rr='.$rr.'&amp;dd='.$dd.'">');
            	e('<img src="'.$modelurl.'" border="2" />');
            	br();
            	e(contentlang($name));
            	e('</a>');
                
                
                e('</td><td>');
                if($i+1==count($join)){
                    e('<br/><br/>&nbsp;<h2>=</h2>&nbsp;');
                    e('</td><td>');
                    
                    $modelurl=modelx($auto_resx.$rr,$dd,$usercolor);
                    e('<img src="'.$modelurl.'" border="2" />');
                    br();
                    e(nbsp);
                    
                }else{e('<br/><br/>&nbsp;<h2>+</h2>&nbsp;');}
                e('</td>');
                
                if($i!=0 and $i/$onrow==floor($i/$onrow) and $i!=count($join)-1){e('</tr><tr><td><br/><br/>&nbsp;<h2>+</h2>&nbsp;</td>');};
                $i++;
            }
            e('</tr></table>');
            hr();br();
        }
        //-----------------------------------------------------ZOBRAZENI
            //-----------------------------------------------------AUTO
            if(count($join)>1){
                alert('Automatická kombinace:','99ccff');
                e('<table border="0"><tr><td>');
                $modelurl=modelx($auto_res.$rr,$dd,$usercolor);
        	    e('<img src="'.$modelurl.'" border="0" />');
                e('</td></tr><tr><td>');
                e('<b>'.contentlang($auto_name).'</b><br/>'.nl2br(contentlang($auto_description)));
                br();e('<i><a onclick="copyToClipboard(\''.$auto_res.'\');">[RES]</a></i>');
                e('</td></tr></table>');
            }
            //-----------------------------------------------------DEFINED
            $auto_origin=explode(',',$auto_origin);
            sort($auto_origin);
            $auto_origin=implode(',',$auto_origin);
            $reference=sql_array("SELECT id,name,type,origin,profile,res,func,own,ww FROM `[mpx]objects` WHERE (`ww`=0 OR `ww`=-1) AND `origin`='".$auto_origin."'"/*."' ORDER BY RAND()"*/);
            foreach($reference as $row){
                list($id,$name,$type,$origin,$profile,$res,$func,$own,$ww)=$row;
                $profile=str2list($profile);
                $description=($profile['description']);
                if($ww==0){
                    if($own/* and $own!=$id*/){
                        alert('Kombinace od uživatele '.id2name($own).':','99ccff');
                       //e('<h2>Kombinace od uživatele '.id2name($id).':</h2>');
                    }else{
                        alert('Systémová kombinace:','99ccff');
                        //e('<h2>Systémová kombinace:</h2>');
                        //$warning='Bude přepsána systémová kombinace!';
                        $auto_name=$name;
                        $auto_res=$res;
                        $auto_description=$description;
                    }
                }elseif($ww==-1){
                        alert('Zatím neschválená kombinace od uživatele '.id2name($own).':','99ccff');
                        //e('<h2>Neschválená kombinace od uživatele '.id2name($id).':</h2>');   
                }elseif($ww==-2){
                        alert('Zamítnutá kombinace od uživatele '.id2name($own).':','99ccff');
                        //e('<h2>Zamítnutá kombinace od uživatele '.id2name($id).':</h2>');  
                }else{
                    alert('Neznámá kombinace:','99ccff');
                    //e('<h2>Neznámá kombinace:</h2>');
                }
                e('<table border="0"><tr><td>');
                $modelurl=modelx($res.$rr,$dd,$usercolor);
        	    e('<img src="'.$modelurl.'" />');
                e('</td></tr><tr><td>');
                e('<b>'.contentlang(xx2x($name)).'</b><br/>'.nl2br(contentlang(xx2x($description))));
                br();e('<i><a onclick="copyToClipboard(\''.$res.'\');">[RES]</a></i>');
                e('</td></tr></table>');
            }
            //-----------------------------------------------------CREATE

            if(logged()){
                br();br();
                alert('Vytvořit novou kombinaci:','ccccff');
                //if($warning)e($warning);
                e('Jako autor bude uveden '.id2name($GLOBALS['ss']['logid']).' (přihlášený uživatel na Towns).');
                e('<form method="POST" action="?page=unique&amp;join='./*substr($jurl,strlen($jurl)-1)*/$jurlx.'&amp;rr='.$rr.'&amp;dd='.$dd.'"><table border="0"><tr><td>');
                e('<input type="hidden" name="origin" value="'.$auto_origin.'" >');
                e('<input type="hidden" name="author" value="'.$GLOBALS['ss']['logid'].'" >');
                e('<b>Jméno: </b>');
                e('</td><td>');
                    //---
                    e('<input type="text" name="name" style="width:400px;" value="'.contentlang($auto_name).'" >');
                e('</td></tr><tr><td>');
                e('<b>Popis: </b>');
                e('</td><td>');
                    //---
                    e('<textarea name="description" style="width:400px;" rows="4">'.$auto_description.'</textarea>');
                    
                e('</td></tr><tr><td>');
                e('<b>[RES]: </b>');
                e('</td><td>');
                    //---
                    e('<textarea name="res" style="width:400px;" rows="4">'.$auto_res.'</textarea>');
                    br();
                    e('<a href="http://editor.towns.cz/" target="_blank">Towns Editor (nová záložka)</a>');
                
                e('</td></tr><tr><td>');
                e('</td><td>');
                e('<input type="submit" value="Vytvořit">');
                e('</td></tr></table></form>');
            }else{
                alert('Vytvářet nové kombinace budete moct až se přihlásíte na Towns...','ccccff');
            }
        
        //-----------------------------------------------------
    hr();
    //-----------------------------------------------------
    e('<b>Přidat budovu: </b>');br();
}

$groups=array('master','main'/*,'wall','bridge','path','terrain'*/);

$groups=implode("%' OR func LIKE '%group=class[5]group[3]1[5]profile[3]profile[5]group[7]5[10]$group",$groups);
$groups="( func LIKE '%group=class[5]group[3]1[5]profile[3]profile[5]group[7]5[10]".$groups."%' )";

$i=0;
e('<table border="0"><tr>');
foreach(sql_array('SELECT id,name,type,origin,profile,res,func FROM '.mpx.'objects WHERE ww=0 AND `type`=\'building\' AND '.$groups.' ORDER BY id') as $row){
    e('<td align="center">');
    
    list($id,$name,$type,$origin,$profile,$res,$func)=$row;
    
    $modelurl=modelx($res.$rr,$dd,$usercolor);
	e('<a href="?page=unique&amp;join='.$jurl.$id.'&amp;rr='.$rr.'&amp;dd='.$dd.'">');
	e('<img src="'.$modelurl.'"  border="2" />');
	br();
	e(short(contentlang($name),22));
	e('</a>');
	//if($name!=contentlang($name)){br();e('='.contentlang($name));}
	//$profile=str2list($profile);
    //e(contentlang($profile['description']));
    
    e('</td>');
    
    if(/*$i!=0 and */($i+1)/$onrow==floor(($i+1)/$onrow)){e('</tr><tr>');};
    $i++;
    
}
e('</tr></table>');

br();hr();

e('<b>Poškození:&nbsp;</b>');
$i=0.1;while($i<1){if($i!=0.1){e('&nbsp;-&nbsp;');}/*e("$dd!=$i");*/if($dd-1+1==$i){e('<b>'.($i*100).'%</b>');}else{e('<a href="?page=unique&amp;join='.$jurlx.'&amp;rr='.$rr.'&amp;dd='.$i.'">'.($i*100).'%</a>');}$i+=0.1;}

br();

e('<b>Rotace:&nbsp;</b>');
$i=0;while($i<360){if($i!=0){e('&nbsp;-&nbsp;');}if($rr!=':'.$i){e('<a href="?page=unique&amp;join='.$jurlx.'&amp;rr=:'.$i.'&amp;dd='.$dd.'">'.$i.'</a>');}else{e('<b>'.$i.'</b>');}$i+=10+$i;}



if($_GET['join']){
    br();br();
    e('<a href="?page=unique">Znovu...</a>');
}

?>
