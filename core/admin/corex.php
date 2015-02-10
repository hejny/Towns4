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
<h3>Push CORE</h3>
<b>Upozornění: </b>Tento proces může znefunkčnit celou aplikaci na hlavním serveru (i Towns4Admin)!<br/>

<!--<a  href="javascript:void(0);" onclick="document.execCommand('SaveAs',true,'files/expor.xml');">Stáhnout</a>-->
<hr/>
<?php



/*function xcopy($source, $dest, $permissions = 0777)
{
    // Check for symlinks
    if (is_link($source)) {
        return symlink(readlink($source), $dest);
    }

    // Simple copy for a file
    if (is_file($source)) {
        unlink($dest);
        return copy($source, $dest);
    }

    // Make destination directory
    if (!is_dir($dest)) {
        mkdir($dest, $permissions);
    }

    // Loop through the folder
    $dir = dir($source);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }

        // Deep copy directories
        xcopy("$source/$entry", "$dest/$entry");
    }
}*/
//--------------------------------------------------------------------------
//ftp_get ( resource $ftp_stream , string $local_file , string $remote_file , int $mode [, int $resumepos = 0 ] )
function ftp_get_dir($conn_id,$local_dir,$remote_dir,$mode){
	mkdir($local_dir);
	chmod($local_dir,0777);

	$files = ftp_nlist($conn_id,$remote_dir);
	//print_r($files);die();
	foreach($files as $file){
		$file_local=$local_dir.'/'.basename($file);
		if(ftp_size($conn_id,$file)>1){
			e('<tr><td>'.$file.'</td><td width=50><b>&gt;</b></td><td>'.$file_local.'</td>');
	
			
			if(ftp_get($conn_id,$file_local,$file,$mode)){e('<td>OK</td>');}else{e('<td>Fail</td>');}
			if(chmod($file_local,0777)){e('<td>OK</td>');}else{e('<td>Fail</td>');}

			e('</tr>');


		}else{
			ftp_get_dir($conn_id,$file_local,$file,$mode);
		}
		
	}
}
//--------------------------------------------------------------------------
//ftp_put ( resource $ftp_stream , string $remote_file , string $local_file , int $mode [, int $startpos = 0 ] )
function ftp_put_dir($conn_id,$remote_dir,$local_dir,$mode){
	ftp_mkdir($conn_id,$remote_dir);
	ftp_chmod($conn_id,0777,$remote_dir);

	$files = glob($local_dir.'/*');
	//print_r($files);die();
	foreach($files as $file){
		$file_remote=$remote_dir.'/'.basename($file);
		//e($file);br();
		if(!is_dir($file)){
			e('<tr><td>'.$file.'</td><td width=50><b>&gt;</b></td><td>'.$file_remote.'</td>');
	
			
			if(ftp_put($conn_id,$file_remote,$file,$mode)){e('<td>OK</td>');}else{e('<td>Fail</td>');}
			if(ftp_chmod($conn_id,0777,$file_remote)){e('<td>OK</td>');}else{e('<td>Fail</td>');}
	
			e('</tr>');

		}else{
			ftp_put_dir($conn_id,$file_remote,$file,$mode);
		}
		
	}
}
//--------------------------------------------------------------------------

if($GLOBALS['inc']['ftp_host']){

	e('<a href="?copy=1">Aktualizovat verzi</a>');
	set_time_limit(10000);
	if($_GET['copy']){
	    br(2);
	    $dest=$GLOBALS['inc']['ftp_path'];
	    $src=root.'core';
	    $backup=root.'backup/core_'.time().'_'.date('j_n_Y');
	    
	    //echo("<b>'$core' přesouvám do '$coreold', '$corex' zdvojuju do '$core'</b>");
	    
	    /*xcopy($dest,$backup);
	    xcopy($src,$dest);*/
	   
	   $conn_id = ftp_connect($GLOBALS['inc']['ftp_host']);//ftp_ssl_connect
	   $login_result = ftp_login($conn_id,$GLOBALS['inc']['ftp_user'],$GLOBALS['inc']['ftp_password']);

	   //$contents = ftp_nlist($conn_id, $dest);
	   //var_dump($contents);


	   e('<table border=0>');
	   e('<tr><td colspan=3>&nbsp;&nbsp;&nbsp;&nbsp;<b>Záloha</b></td></tr>');
	   ftp_get_dir($conn_id, $backup, $dest, FTP_ASCII);
	   e('<tr><td colspan=3>&nbsp;&nbsp;&nbsp;&nbsp;<b>Nahrávání</b></td></tr>');
	   ftp_put_dir($conn_id, $dest, $src, FTP_ASCII);
	   e('</table>');

	   /*if (ftp_put($conn_id, $dest, $backup, FTP_ASCII)) {
		echo "successfully uploaded $file\n";
	   }else{
		echo "There was a problem while uploading $file\n";
	   }*/
	    
	    ftp_close($conn_id);
	    echo('<b>hotovo</b>');

	    

	}

}else{
	e('Není nastaven FTP cíl.');
}


?>
