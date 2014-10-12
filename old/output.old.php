<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/output.php

   Tento soubor slouží k finální úpravě souboru před odesláním do prohlížeče, nahrzení {lang} proměnných.
*/
//==============================





function contentantisvin($buffer){
    $kde=strpos($buffer,"<body>")+6;
    $kde2=strpos($buffer,"</body>");
    $zac=(substr($buffer,0,$kde));
    $kon=(substr($buffer,$kde2));
    $buffer=(substr($buffer,$kde,$kde2-$kde));
    //-------------
    $bufferx="";
    foreach(str_split($buffer) as $char){
        if(strtr($char,"ěščřžýáíéúůĚŠČŘŽÝÁÍÉÚŮqwertyuiopasdfghjkl","0000000000000000000000000000000000000000000000000000000000")==$char){
            $char=dechex(ord($char));
            if(strlen($char)==1){ $char=("0".$char); }
            $char="%".$char;
        }
        $bufferx=$bufferx.$char;
    }
    $buffer='<script language="javascript">
    document.write(unescape("'.$bufferx.'"));
    </script>'; 
    //-------------
    $nln="
    ";
    $buffer=$zac.$buffer.$kon;
    $buffer=str_replace($nln,"",$buffer);
    return($buffer);
}
//===============================================================================================================
		$GLOBALS['langdata']=array();
		foreach(sql_array('SELECT `key`,`value` FROM [mpx]lang WHERE lang=\''.$GLOBALS['ss']["lang"].'\'') as $row){
		    list($key,$value)=$row;
		    $GLOBALS['langdata'][$key]=$value;
		}
//------------
function contentlang($buffer,$rec=false){//if(rr())r();
	
    if(1){
	
        /*$file=("data/lang/".$GLOBALS['ss']["lang"].".txt");
        $stream=file_get_contents($file);
        $GLOBALS['ss']["langdata"]=(astream($stream));*/
        $buffer=str_replace(array("{0}","{}"),"",$buffer);
        $buffer=str_replace("{","languageprotectiona",$buffer);
        $buffer=str_replace("}","languageprotectionb",$buffer);
        //if(edit)$addtoend='<table>';
	//if(edit){$addtoend='<iframe src="admin/index.php?page=lang" width="100%" height="100%"></iframe>';}
        //-------------
	for($i=0;$tmp=substr2($buffer,"{","}",$i);$i++){
            if(rr())r($tmp);
	    
      	    /*if(!$langdata){
		$langdata=array();
		foreach(sql_array('SELECT `key`,`value` FROM [mpx]lang WHERE lang=\''.$GLOBALS['ss']["lang"].'\'') as $row){
		    list($key,$value)=$row;
		    $langdata[$key]=$value;
		}
            }*/


            list($key,$params)=explode(";",$tmp,2);
	    $no=0;	
	    if(strpos($key,'"'))$no=1;
	    if(strlen($key)>100)$no=1;
            if($GLOBALS['langdata'][$key] and !$no){
                $text=valsintext($GLOBALS['langdata'][$key],$params);
		if(!$rec)if(strpos($text,'}')!==false){$text=contentlang($text,true);}
                $size=strlen($text);
                $text=$text;
		$text=str_replace(array('{','}'),array('languageprotectiona','languageprotectionb'),$text);

                if(rr())r($text);
                if(rr())r($buffer);
                if(rr())r();
            }elseif(!$no){
                $size=5;
                $text="languageprotectiona".$key.($params?';'.$params:'')."languageprotectionb";
                
		$pdo = new PDO('mysql:host='.mysql_host.';dbname='.mysql_db, mysql_user, mysql_password, array(PDO::ATTR_PERSISTENT => false));
		$pdo->exec("set names utf8");
		$q=("INSERT INTO `world1_lang` (`lang`, `key`, `value`, `font`, `author`, `description`, `time`) VALUES ('".$GLOBALS['ss']["lang"]."', '$key', '{".addslashes($key)."}', '', '', 'new', '".time()."');");
		$response=$pdo->exec($q);
		unset($pdo);

		
                /*$add='//'.$key.'=;';
                if(!strpos($stream,$add) and !strpos($addtoend,$add))$addtoend.=nln.$add;*/
                
            }
            /*if(edit){
		if(strpos($text,nln)){
			$form='<input type="input" name="'.$key.'" value="'.$text.'" size="'.$size.'"/>';
		}else{
			$form='<textarea name="promenna" cols="40" rows="3">'.$text.'</textarea>';	
		}
		
                $addtoend.="<tr><td><b>{$key}</b></td><td>$form</td></tr>";
                //$text='<a href="lem.php" target="_blank">#</a>'.$text;
                //$text='<input type="input" name="move_y" value="'.$text.'" size="'.$size.'"  style="border:  1px solid #333333; background-color: #000000; color: #ffffff;" onBlur="" />';
                //<form id="form" name="form" method="POST" action="http://localhost/4/?w=228720"><input type="input" name="move_y" value="" /></form>
            }*/
            $buffer=substr2($buffer,"{","}",$i,$text);
        }
        $buffer=str_replace(array("{",";}","}"),"",$buffer);
        $buffer=str_replace("languageprotectiona","{",$buffer);
        $buffer=str_replace("languageprotectionb","}",$buffer);
	//if(edit)$addtoend.='</table>';
	//if($GLOBALS['ss']["logged_new"]!=true){};

	//if(edit)$buffer.=$addtoend;
        //if($addtoend)file_put_contents2($file,file_get_contents($file).$addtoend);
    }else{
        //$buffer="contentlang".$buffer;
        $buffer=str_replace("{","{",$buffer);
        $buffer=str_replace("}","}",$buffer);
    }
    return($buffer);
}

/*function contentlang($buffer){
    return(contentlang_(contentlang_($buffer)));
}*/
//===============================================================================================================
function contentzprac($buffer){

    ///if(nob!==true){
        //chdir(dirname($_SERVER['SCRIPT_FILENAME']));
        //$buffer="contentzprac".$buffer;
        //if(strpos("<body>")){
            if(!$_GET["e"]){
                list($start,$buffer,$end)=part3($buffer,"<body>","</body>");
                $buffer=contentlang($buffer);
                $buffer=$start."<body>".$buffer."</body>".$end;
            }else{
                $buffer=contentlang($buffer);
            }
            
        //}
    //}
    return($buffer);
}
//-------------
ob_start("contentzprac");
?>
