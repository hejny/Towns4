<?php
/* Towns4Admin, www.towns.cz 
   © Pavel Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================

ob_end_flush();
//error_reporting(E_ALL);
ini_set('memory_limit','10000M');
 ?>
<h3>Export</h3>
Tato funkce provede Mysql Dump...<br/>
<b>Upozornění: </b>Tento proces může trvat i několik minut.<br/>
<a href="?export=1">Vygenerovat Vše</a>
<!--<a  href="javascript:void(0);" onclick="document.execCommand('SaveAs',true,'files/expor.xml');">Stáhnout</a>-->
<hr/>
<?php

/*function GetTableDef($table, $crlf)
{
	// MySQL >= 3.23.20
	$schema_create = "";
	$schema_create .= "DROP TABLE IF EXISTS $table;$crlf";
	$result = mysql_query("SHOW CREATE TABLE $table");
	$data=mysql_fetch_array($result);
	$schema_create .= $data[1] . ";$crlf$crlf";
	mysql_free_result($result);
	return $schema_create;
}
echo(GetTableDef('world1_users','-'));*/


set_time_limit(10000);
if($_GET['export']){

    mkdir(adminfile.'files/backup');
    chmod(adminfile.'files/backup',0777);

    /*if($_GET['export']==1){
        if(!$_GET['notime']){
            $nejm=w.'_'.time().'_'.date('j_n_Y');
        }else{
            $nejm=w;
        }
    }else{*/

    if(!$_GET['notime']){
        $nejm='backup_'.time().'_'.date('j_n_Y');
    }else{
        $nejm='backup';
    }


    $file_name=adminfile."files/backup/".$nejm.".sql";
    $file_zip=adminfile."files/backup/".$nejm.".zip";








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
    terminal('mysqldump --user '.$GLOBALS['inc']['mysql_user'].' --password='.$GLOBALS['inc']['mysql_password'].' '.$GLOBALS['inc']['mysql_db'].' > '.$file_name);
    //br();
    $contents=ob_get_contents();
    ob_end_clean();

    ?>
    <div style="color: #ffffff; background-color: #000000; width: 800px;height: 600px;overflow-x: hidden;overflow-y: auto;">
        <?=$contents ?>
    </div>
    <?php



    $zip = new ZipArchive();
    $zip->open($file_zip,ZIPARCHIVE::OVERWRITE);

    $zip->addFile($file_name,basename($file_name));


    /*foreach(glob(root.'userdata/*') as $file) {

        $file_=explode('userdata/',$file,2);
        $file_=$file_[1];
        $file_='userdata/'.$file_;

        $zip->addFile($file,$file_);
    }*/

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(root.'userdata'),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $name => $file) {
        // Skip directories (they would be added automatically)
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            if(!is_dir($filePath)){

                $file_ = explode('userdata/', $filePath, 2);
                $file_ = $file_[1];
                $file_ = 'userdata/' . $file_;

                $zip->addFile($filePath, $file_);
            }
        }
    }


    $zip->close();
    unlink($file_name);

    chmod($file_zip,0777);


    echo('<b>uloženo do <a href="https://www.towns.cz/app/admin/files/backup/'.$nejm.'.zip">files/backup/'.$nejm.'.zip</a></b>');
}



?>
