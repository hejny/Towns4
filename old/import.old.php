<h3>Import</h3>
<b>Upozornění: </b>Tato funkce může při nesprávném použití poškodit celý svět <?php echo(w); ?> a jeho podsvěty!<br />
<b>Upozornění: </b>Tento proces může trvat i několik minut.<br/>
<?php

function xml_line($xmlline,$wtf='param'){
$xmlline='<?xml version="1.0" encoding="UTF-8" ?>
<world>
'.$xmlline.'
</world>';
$xmlline = json_decode(json_encode((array) simplexml_load_string($xmlline)), 1);
//print_r($xmlline);
$xmlline=$xmlline[$wtf];
return($xmlline);
}


error_reporting(E_ALL);

if($_POST['filename']){
//echo('files/'.$_POST['filename'].'.xml');
	if(file_exists(adminroot.'files/'.$_POST['filename'].'.xml')){
		ini_set('memory_limit', '2000M');
		ini_set("max_execution_time","1000");

		$xml=/*file_get_contents*/(adminroot.'files/'.$_POST['filename'].'.xml');
		//$xml=str_replace('&nbsp','',$xml);
		$mod='none';
		$buffer='';
		$limit=-1;

		//===============================================CLEAR
		sql_query('DROP TABLE `'.mpx.'map`;');
		sql_query('DROP TABLE `'.mpx.'text`;');
		sql_query('DROP TABLE `'.mpx.'objects`;');
		//sql_query('DROP TABLE `'.mpx.'memory`;');
		sql_query('DROP TABLE `'.mpx.'login`;');
		emptydir(root.cache);
		//===============================================TABLES
		$sql=file_get_contents(adminroot.'sql/create_map.sql');
		$sql=str_replace('[mpx]',mpx,$sql);
		sql_query($sql);
		$sql=file_get_contents(adminroot.'sql/create_objects.sql');
		$sql=str_replace('[mpx]',mpx,$sql);
		sql_query($sql);
		$sql=file_get_contents(adminroot.'sql/create_text.sql');
		$sql=str_replace('[mpx]',mpx,$sql);
		sql_query($sql);
		$sql=file_get_contents(adminroot.'sql/create_memory.sql');
		$sql=str_replace('[mpx]',mpx,$sql);
		sql_query($sql);
		$sql=file_get_contents(adminroot.'sql/create_login.sql');
		$sql=str_replace('[mpx]',mpx,$sql);
		sql_query($sql);
		//===============================================


		//e($xml);

		$xml = fopen($xml, 'r') ;  


		// while there is another line to read in the file
		while(!feof($xml) and $limit!=0){$limit--;
			// Get the current line that the file is reading
			$currentLine = trim(fgets($xml));
			

			//e(htmlspecialchars($currentLine));br();


			//UNSETMOD - OUTPUT
			if($currentLine=='</config>'){

				$file=root."world/".w.".txt";
				file_put_contents($file,$buffer);
				

				//die($buffer);
				$mod='none';$buffer='';
			}
			if($currentLine=='</config>'){$mod='none';$buffer='';}
			if($currentLine=='</map>'){$mod='none';$buffer='';}
			if($currentLine=='</text>'){$mod='none';$buffer='';}
			if($currentLine=='</login>'){$mod='none';$buffer='';}
			if($currentLine=='</object>'){
				$buffer.=$currentLine;	
				//e(nl2br(htmlspecialchars($buffer)));br();
				//exit;
				$row = xml_line($buffer,'object');
				//print_r($row);
				$id=$row['@attributes']['id'];
				$keys='`id`';
				$values="'$id'";
				foreach($row['param'] as $param){
					//if($param['@attributes']['key']=='t')$joint='';
					$keys.=",`".$param['@attributes']['key']."`";
					$values.=",'".$param['@attributes']['value']."'";
				}
				sql_query("INSERT INTO `[mpx]objects` ($keys) VALUES ($values);",2);

				//die();
				$mod='none';$buffer='';
			}

			//APPLYMOD
			//---------------------------config
			if($mod=='config'){
				$row = xml_line($currentLine);
				//print_r($row);
				$row=$row['@attributes'];
				if(str_replace($row['key'].'=','',$buffer)==$buffer)
				$buffer.=nln.$row['key'].'='.$row['value'].';';

			}
			//---------------------------map
			if($mod=='map'){
				$row = xml_line($currentLine,'field');
				$row=$row['@attributes'];
				sql_query("INSERT INTO `[mpx]map` (`x`, `y`, `ww`, `terrain`, `name`) VALUES ('".($row['x'])."', '".($row['y'])."', '".($row['ww'])."', '".($row['terrain'])."', '".($row['name'])."');");

			}
			//---------------------------text
			if($mod=='text'){
				$row = xml_line($currentLine,'row');
				$row=$row['@attributes'];
			sql_query("INSERT INTO `[mpx]text` (`id`, `idle`, `type`, `new`, `from`, `to`, `title`, `text`, `time`, `timestop`) VALUES ('".($row['id'])."', '".($row['idle'])."', '".($row['type'])."', '".($row['new'])."', '".($row['from'])."', '".($row['to'])."', '".($row['title'])."', '".($row['text'])."', '".($row['time'])."', '".($row['timestop'])."');");
			}
			//---------------------------login
			if($mod=='login'){
				$row = xml_line($currentLine,'row');
				$row=$row['@attributes'];
			sql_query("INSERT INTO `[mpx]login` (`id`, `method`, `key`, `text`, `time_create`, `time_change`, `time_use`) VALUES ('".($row['id'])."', '".($row['method'])."', '".($row['key'])."', '".(addslashes($row['text']))."', '".($row['time_create'])."', '".($row['time_change'])."', '".($row['time_use'])."');");
			}
			//---------------------------object
			if($mod=='object'){
				//e(htmlspecialchars($currentLine));br();
				$buffer.=$currentLine;	
			}
			//---------------------------


			//SETMOD
			if($currentLine=='<config>'){
				$mod='config';
			}elseif($currentLine=='<map>'){
				$mod='map';
			}elseif($currentLine=='<text>'){
				$mod='text';
			}elseif($currentLine=='<login>'){
				$mod='login';
			}elseif(substr($currentLine,0,7)=='<object'){
				//e(htmlspecialchars($currentLine));br();
				$buffer.=$currentLine;	
				$mod='object';
			}

			//------





		}   

		fclose($xml) ;




		
		//===============================================
		echo('OK');
		br();
	}else{
		echo('Soubor neexistuje!');
		br();
	}
}
?>

<form id="form1" name="form1" method="post" action="">
<table  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><strong>Soubor:</strong></td>
    <td><label>
      <?php echo(adminroot); ?>files/<input name="filename" type="text" id="filename" value="<?php echo(w); ?>" />.xml
    </label></td>
  </tr>
    <tr>
    <td colspan="2"><label>
      <input type="submit" name="Submit" value="OK" />
    </label></td>
    </tr>
</table>
</form>
<?php
/*$xml='<?xml version="1.0" encoding="UTF-8" ?>
<world>
<config>
<param key="lang" value="cz"/>
<param key="mapsize" value="150"/>
</config>
<map>
<field x="0" y="0" ww="1" terrain="t1" hard="1.000" name=""/>
</map>
<text>
</text>
<object id="10000">
<param key="name" value="{tree} [133,4]"/>
<param key="type" value="tree"/>
<param key="dev" value="N"/>
<param key="fs" value="25"/>
<param key="fp" value="25"/>
<param key="fr" value="2013"/>
<param key="fx" value="2038"/>
<param key="fc" value="fp=0;iron=17;fuel=9"/>
<param key="func" value="defence=class[5]defence[3]params[5]defence[7]5[10]5[7]2[10]1[3]0[5]profile"/>
<param key="hold" value="energy=656;wood=1357"/>
<param key="res" value="[resource]"/>
<param key="profile" value=""/>
<param key="set" value=""/>
<param key="hard" value="0.150"/>
<param key="own" value="0"/>
<param key="in" value="0"/>
<param key="ww" value="1"/>
<param key="x" value="132.540"/>
<param key="y" value="4.380"/>
<param key="t" value="1357509811"/>
</object>
</world>';
$xml = json_decode(json_encode((array) simplexml_load_string($xml)), 1);
print_r($xml);*/
?>
