<?php
/* Towns4Admin, www.towns.cz 
   © Pavol Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================


error_reporting(E_ALL ^ E_NOTICE);
ini_set("max_execution_time","1000");
ini_set('memory_limit','500M');
//session_start();
 ?>
<h3>Import</h3>
Tato funkce provede import z MySQL Dump...<br/>
<b>Upozornění: </b>Tato funkce by se měla provádět s prázdnou databází.<br/>
<b>Upozornění: </b>Tento proces může trvat i několik mintut.<br/>
<hr/>
<?php

if($_GET['filename']){
	$tmp=adminfile.'files/backup/'.$_GET['filename'];
	if(file_exists($tmp)){

		if(substr($tmp,-4)=='.zip'){
			
			$tmpsql=substr($tmp,0,strlen($tmp)-3).'sql';
            //echo($tmpsql);

			if(!file_exists($tmpsql)){
				extract_zip($tmp,adminfile.'files/backup/',basename($tmpsql));
			}
			
			$tmp=$tmpsql;
			//e($tmp);
			if(file_exists($tmp)){
				chmod($tmp,0777);
				$_SESSION['filename']=$tmp;
			}else{
				echo('Chyba .zip souboru!');
			}
		}else{
			$_SESSION['filename']=$tmp;
		}
	}else{
		$_SESSION['filename']=false;
		echo('Soubor neexistuje!');
	}
}


if(!$_GET['filename'] or !$_SESSION['filename']){
?>

<form id="form1" name="form1" method="get" action="?page=sqlx">
<table  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><strong>Soubor:</strong></td>
    <td><label>
      <?php e(adminfile.'files/backup/'); ?><input name="filename" type="text" id="filename" value="<?php echo($_GET['filename']?$_GET['filename']:w); ?>" />
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

}else{



    if(!function_exists('terminal')) {
        function terminal($command, $show = false)
        {

            if (!$show) $show = $command;
            e('<span style="color: #aaffaa;">');
            e(nl2br(htmlspecialchars($show . nln)));
            e('</span>');

            $response = shell_exec($command . ' 2>&1;');
            e(nl2br(htmlspecialchars($response . nln)));
        }
    }


    ob_start();
    terminal('mysql --user '.$GLOBALS['inc']['mysql_user'].' --password='.$GLOBALS['inc']['mysql_password'].' '.$GLOBALS['inc']['mysql_db'].' < '.$_SESSION['filename']);
    //br();
    $contents=ob_get_contents();
    ob_end_clean();

    ?>
    <div style="color: #ffffff; background-color: #000000; width: 800px;height: 600px;overflow-x: hidden;overflow-y: auto;">
        <?=$contents ?>
    </div>


<?php

}

?>
