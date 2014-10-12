<?php ob_end_flush();
//error_reporting(E_ALL);
ini_set('memory_limit','10000M');
 ?>
<h3>Copy corex &gt; core</h3>
<b>Upozornění: </b>Tento proces může znefunkčnit celou aplikaci (i Towns4Admin)!<br/>
<a href="?copy=1">Aktualizovat verzi</a>
<!--<a  href="javascript:void(0);" onclick="document.execCommand('SaveAs',true,'files/expor.xml');">Stáhnout</a>-->
<hr/>
<?php



function xcopy($source, $dest, $permissions = 0777)
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
}



set_time_limit(10000);
if($_GET['copy']){
    $core=root.'core';
    $corex=root.'corex';
    $coreold=root.'old/core_'.date('j_n_Y');
    
    echo("<b>'$core' přesouvám do '$coreold', '$corex' zdvojuju do '$core'</b>");
    
    xcopy($core,$coreold);
    xcopy($corex,$core);
    
    
    br();
    echo('<b>hotovo</b>');

    

}



?>
