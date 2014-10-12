<h3>Synchronizace</h3>
<b>Upozornění: </b>Synchronizace může při nesprávném použití poškodit celý svět <?php echo(w); ?> a jeho podsvěty na tomto serveru!<br />
<b>Upozornění: </b>Synchronizace může trvat i několik minut.<br/>
<?php
if($_POST['master']){
//===============================================


//echo('files/'.$_POST['filename'].'.xml');
	$master=$_POST['master'];
	$pass=substr2($master,'http://','@');
	list($tmp,$pass)=split(':',$pass);
	$master=$master.'?e=-export&password='.$pass;
	//echo($master);
	$contents=file_get_contents($master);
	if($contents){
		error_reporting(E_ALL);
		$xml=simplexml_load_string($contents);
		//print_r($xml);
		//exit;		

		sql_query('DROP TABLE `'.mpx.'map`;');
		sql_query('DROP TABLE `'.mpx.'text`;');
		sql_query('DROP TABLE `'.mpx.'objects`;');
		sql_query('DROP TABLE `'.mpx.'memory`;');
		sql_query('DROP TABLE `'.mpx.'login`;');
		//unlink(root.'world/'.w.'.txt');
		emptydir(root.cache);


		ini_set('memory_limit', '-1');
		ini_set("max_execution_time","1000");
		//die($contents);
		$xml = json_decode(json_encode((array) simplexml_load_string($contents)), 1);
		r($xml);
		//$xml=$xml['world'];
		//===============================================CONFIG
		$file=root."world/".w.".txt";
		$content=file_get_contents($file).nln;
		foreach($xml['config']['param'] as $row){
			//print_r($row);hr();
			$row=$row['@attributes'];
			if(str_replace($row['key'].'=','',$content)==$content)
			$content.=nln.$row['key'].'='.$row['value'].';';	
		}
		file_put_contents($file,$content);
		//print_r($xml['config']);
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
		//===============================================MAP
		foreach($xml['map']['field'] as $row){
			$row=$row['@attributes'];
			sql_query("INSERT INTO `".mpx."map` (`x`, `y`, `ww`, `terrain`, `hard`, `name`) VALUES ('".($row['x'])."', '".($row['y'])."', '".($row['ww'])."', '".($row['terrain'])."', '".($row['hard'])."', '".($row['name'])."');");
		}
		//===============================================TEXT
		foreach($xml['text']['row'] as $row){
			$row=$row['@attributes'];
			sql_query("INSERT INTO `".mpx."text` (`id`, `idle`, `type`, `new`, `from`, `to`, `title`, `text`, `time`, `timestop`) VALUES ('".($row['id'])."', '".($row['idle'])."', '".($row['type'])."', '".($row['new'])."', '".($row['from'])."', '".($row['to'])."', '".($row['title'])."', '".($row['text'])."', '".($row['time'])."', '".($row['timestop'])."');");
		}
		//===============================================LOGIN
		foreach($xml['login']['row'] as $row){
			$row=$row['@attributes'];
			sql_query("INSERT INTO `".mpx."login` (`id`, `method`, `key`, `text`, `time_create`, `time_change`, `time_use`) VALUES ('".($row['id'])."', '".($row['method'])."', '".($row['key'])."', '".(addslashes($row['text']))."', '".($row['time_create']).", '".($row['time_change'])."', '".($row['time_use'])."'');");
		}
		//===============================================OBJECTS
		foreach($xml['object'] as $row){
			$id=$row['@attributes']['id'];
			$keys='`id`';
			$values="'$id'";
			foreach($row['param'] as $param){
				//if($param['@attributes']['key']=='t')$joint='';
				$keys.=",`".$param['@attributes']['key']."`";
				$values.=",'".$param['@attributes']['value']."'";
			}
			sql_query("INSERT INTO `".mpx."objects` ($keys) VALUES ($values);");

			//INSERT INTO `towns`.`world2_objects` (`id`, `name`, `type`, `dev`, `fs`, `fp`, `fr`, `fx`, `fc`, `func`, `hold`, `res`, `profile`, `set`, `hard`, `own`, `in`, `ww`, `x`, `y`, `t`) VALUES ('1', 'PH', 'user', 'T', '17', '100', '389432', '389532', 'fp=0;iron=11;fuel=6', '', '', '', '', '', '0.000', '0', '0', '1', '13.416', '34.634', '1357519033');
		}
		//===============================================
		echo('OK');
		echo('<script language="javascript">
    window.location.replace("?world='.w.'&page=createtmp&start=1");
    </script>');
		br();
	}else{
		echo('Chyba!');
		br();
	}
}
?>

<form id="form1" name="form1" method="post" action="">
<table  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><strong>Master:</strong></td>
    <td><label>
      <input name="master" type="text" id="master" size="73" value="<?php echo(defined('master')?master:'http://user:password@server/'); ?>" />
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
