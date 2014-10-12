<h3>Import</h3>
<b>Upozornění: </b>Tato funkce může při nesprávném použití poškodit celý svět <?php echo(w); ?> a jeho podsvěty!<br />
<b>Upozornění: </b>Tento proces může trvat i několik minut.<br/>
<?php
/**
 * str_replace_with_sed($search, $replace, $file_in, $file_out=null)
 * 
 * Search for the fixed string `$search` inside the file `$file_in`
 * and replace it with `$replace`. The replace occurs in-place unless
 * `$file_out` is defined: in that case the resulting file is written
 * into `$file_out`
 *
 * Return: sed return status (0 means success, any other integer failure)
 */
function str_replace_with_sed($search, $replace, $file_in, $file_out=null)
{
    $cmd_opts = '';
    if (! $file_out) 
    {
        // replace inline in $file_in
        $cmd_opts .= ' -i';
    }

    // We will use Basic Regular Expressions (BRE). This means that in the 
    // search pattern we must escape
    // $.*[\]^
    //
    // The replacement string must have these characters escaped
    // \ & 
    //
    // In both cases we must escape the separator character too ( usually / )
    // 
    // Since we run the command trough the shell we We must escape the string
    // too (yai!). We're delimiting the string with single quotes (') and we'll
    // escape them with '\'' (close string, write a single quote, reopen string)    

    // Replace all the backslashes as first thing. If we do it in the following
    // batch replace we would end up with bogus results
    $search_pattern = str_replace('\\', '\\\\', $search);

    $search_pattern = str_replace(array('$', '.', '*', '[', ']', '^'),
                                  array('\\$', '\\.', '\\*', '\\[', '\\]', '\\^'),
                                  $search_pattern);

    $replace_string = str_replace(array('\\', '&'),
                                  array('\\\\', '\\&'),
                                  $replace);

    $output_suffix = $file_out ? " > '$file_out' " : '';
    $cmd = sprintf("sed ".$cmd_opts." -e 's/%s/%s/g' \"%s\" ".$output_suffix,
                    str_replace('/','\\/', # escape the regexp separator
                      str_replace("'", "'\''", $search_pattern) // sh string escape
                    ),
                    str_replace('/','\\/', # escape the regexp separator
                      str_replace("'", "'\''", $replace_string) // sh string escape
                    ),
                    $file_in
                  );

    passthru($cmd, $status);

    return $status;
}
//--------------------------------------



error_reporting(E_ALL);

if($_POST['filename']){
//echo('files/'.$_POST['filename'].'.xml');
	if(file_exists(adminroot.'files/'.$_POST['filename'].'.xml')){
		ini_set('memory_limit', '2000M');
		ini_set("max_execution_time","1000");
		//echo(htmlspecialchars(file_get_contents(adminroot.'files/'.$_POST['filename'].'.xml')));
		//echo(adminroot.'files/'.$_POST['filename'].'.xml');
		$xml=/*file_get_contents*/(adminroot.'files/'.$_POST['filename'].'.xml');
		//$xml=str_replace('&nbsp','',$xml);
		str_replace_with_sed('&nbsp','',$xml);
		$xml = json_decode(json_encode((array) simplexml_load_file($xml)), 1);
		print_r($xml);
		//$xml = simplexml_load_file($xml);
		//r($xml);
		$xml=$xml['world'];
		//===============================================CONFIG
		$file=root."world/".w.".txt";
		$content='';//file_get_contents($file).nln;
		foreach($xml['config']['param'] as $row){
			//print_r($row);hr();
			$row=$row['@attributes'];
			if(str_replace($row['key'].'=','',$content)==$content)
			$content.=nln.$row['key'].'='.$row['value'].';';	
		}
		file_put_contents($file,$content);
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
		//===============================================MAP
		foreach($xml['map']['field'] as $row){
			$row=$row['@attributes'];
			sql_query("INSERT INTO `".mpx."map` (`x`, `y`, `ww`, `terrain`, `name`) VALUES ('".($row['x'])."', '".($row['y'])."', '".($row['ww'])."', '".($row['terrain'])."', '".($row['name'])."');");
		}
		//===============================================TEXT
		foreach($xml['text']['row'] as $row){
			$row=$row['@attributes'];
			sql_query("INSERT INTO `".mpx."text` (`id`, `idle`, `type`, `new`, `from`, `to`, `title`, `text`, `time`, `timestop`) VALUES ('".($row['id'])."', '".($row['idle'])."', '".($row['type'])."', '".($row['new'])."', '".($row['from'])."', '".($row['to'])."', '".($row['title'])."', '".($row['text'])."', '".($row['time'])."', '".($row['timestop'])."');");
		}
		//===============================================LOGIN
		foreach($xml['login']['row'] as $row){
			$row=$row['@attributes'];
			sql_query("INSERT INTO `".mpx."login` (`id`, `method`, `key`, `text`, `time_create`, `time_change`, `time_use`) VALUES ('".($row['id'])."', '".($row['method'])."', '".($row['key'])."', '".(addslashes($row['text']))."', '".($row['time_create'])."', '".($row['time_change'])."', '".($row['time_use'])."');");
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
