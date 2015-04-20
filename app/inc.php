<?php

//--------------------------------------------inicializace

//$aacdir=getcwd();
//chdir('../../');
//chdir($aacdir);


define('noinc',true);
require('../../index.php');

define('root', '../../');//todo: PH - je to divné
define('core',$GLOBALS['inc']['core']);//todo: PH - je to divné
//-------------

require_once(root.core."/func_main.php");
require_once(root.core."/func_vals.php");
require_once(root.core."/func_object.php");
require_once(root.core."/memory.php");


//-------------

require_once(root.core."/func.php");
require_once(root.core."/func_core.php");


//-------------

require_once(root.core."/login/func_core.php");
require_once(root.core."/create/func_core.php");
require_once(root.core."/attack/func_core.php");
require_once(root.core."/text/func_core.php");
require_once(root.core."/hold/func_core.php");
require_once(root.core."/quest/func_core.php");

//-------------

require_once(root.core.'/model/func_map.php');

//--------------------------------------------




//===========================================================================================
function app_auth($perm){
    if($GLOBALS['ss']["logged_new"]) {
        $permissions = $GLOBALS['inc']['admin'][$GLOBALS['ss']["logged_new"]]['permissions'];
        if($permissions=='*')return;
        if(in_array('app/'.$perm,$permissions))return;
    }
    //--------------------
    le('app_auth_required');
    br();
    e('<a href="../../admin">'.lr('admin_goto_login').'</a>');
    if($GLOBALS['page_started']){page_end();};
    exit;
    //--------------------
}

//======================================================================================================================Zobrazení aplikace, jako hezké stránky
function aacute($text){
    $from=array('ě','š','č','ř','ž','ý','á','í','é','ú','ů');
    $to=array('&#283;','&scaron;','&#269;','&#345;','&#382;','&yacute;','&aacute;','&iacute;','&eacute;','&uacute;','&#367;');
    $text=str_replace($from,$to,$text);
    $from=array('Ě','Š','Č','Ř','Ž','Ý','Á','Í','É','Ú','Ů');
    $to=array('&#282;','&Scaron;','&#268;','&#344;','&#381;','&Yacute;','&Aacute;','&Iacute;','&Eacute;','&Uacute;','&#366;');
    $text=str_replace($from,$to,$text);
    return($text);
}
//===========================================================================================t_analytics
function t_analytics($code=false){
    if($code){
        ?>

        <script type="text/javascript">

            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', '<?php echo($code); ?>']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ?  'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();

        </script>

    <?php
    }
}
//===========================================================================================t_links
function t_links($links){
    if($links==true){
        $links=array(array('Kunratický les','les.towns.cz'),array('4D Krychle','4d.towns.cz'),array('3D Graf','3d.towns.cz'),array('Kreslení','kresleni.towns.cz'),array('AncWis','ancwis.com'));
    }elseif($links=='E'){
        $links=array(array('Towns editor','editor.towns.cz'),array('Rvscgo','rvscgo.towns.cz'),array('CreateImg','createimg.towns.cz'),array('Tree','tree.towns.cz'));
    }

    $q=false;
    foreach($links as $link){
        list($title,$url)=$link;
        if($q){echo('&nbsp;-&nbsp;');}
        if(strpos($_SERVER['HTTP_HOST'],$url)===false){
            $a=explode('.',$_SERVER['HTTP_HOST']);$a=$a[1];
            $b=explode('.',$url);$b=$b[1];
            echo('<a href="http://'.$url.'" '.(($a!=$b)?'target="_blank"':'').'>'.aacute(htmlspecialchars($title)).'</a>');
        }else{
            echo('<b>'.aacute(htmlspecialchars($title)).'</b>');
        }
        $q=true;
    }


}
//===========================================================================================t_copy
function t_copy($author=false,$links=false,$startyear=false){

    if(!$author){
        $author='<a href="http://towns.cz/" target="_blank">Towns.cz</a>';
    }
    if($author=='PH'){
        $author='Pavel Hejný';
    }



    $year=date('Y');
    if($startyear and $startyear!=$year){
        $year=$startyear.'&nbsp;-&nbsp;'.$year;
    }
    if(!$author){
        $author='&copy;'.$year;
    }else{
        $author='&copy;&nbsp;'.aacute($author).'&nbsp;|&nbsp;'.$year;
    }

    if($links){
        t_links($links);
        echo('&nbsp;|&nbsp;');
    }
    echo($author);
}
//===========================================================================================
/*
 * Začátek HTML stránky aplikace
 * @author PH
 *
 * @param string název
 * @param string popis
 * @param bool jquery (Zatím nefunguje)
 * @param integer Rok vytvoření aplikace
 * @param array barvy array(pozadí, text, link, visited, hoover, active) ffffff
 * @param string Autor
 * @param bool Zobrazovat odkazy dole

 *
 * */
function page($title='',$description='',$startyear=false,$jquery=false,$author=false,$links=false,$colors=false){
    $GLOBALS['page_started']=  true;
    $GLOBALS['page_author']=   $author;
    $GLOBALS['page_links']=    $links;
    $GLOBALS['page_startyear']=$startyear;

    $code = $GLOBALS['inc']['analytics'];


    if (!$colors) $colors = array('FFFFFF', '050505', '000000', '000000', '004499', '000000');

    if (substr($description, 0, 4) == 'none' or (!$description and !$title)) {
        $showdescription = false;
        $description = substr($description, 5);
    } else {
        $showdescription = true;
    }

    ?>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="description" content="<?php echo(aacute($description)); ?>"/>
        <meta name="keywords" content="<?php echo(aacute($description)); ?>"/>
        <meta name="author" content="<?php echo(aacute($author)); ?>"/>
        <link rel="image_src" href="facebook.png"/>
        <link rel="shortcut icon" href="favicon.ico">
        <?php
        if($jquery) {
            // @todo Zprovoznit JQuery u projektů
            //e('<script type="text/javascript" src="../../lib/jquery/js/jquery-1.6.2.min.js"></script>');
            //e('<script type="text/javascript" src="../../lib/jquery/js/jquery-ui.js"></script>');
        }
        ?>


        <title><?php echo(aacute($title)); ?></title>
        <style type="text/css">
            body {
                background-color: #<?php echo($colors[0]); ?>;

            }

            body, td, th {
                color: #<?php echo($colors[1]); ?>;
                font-size: 14px;

                font-family: "trebuchet ms";
            }


            a:link {
                color: #<?php echo($colors[2]); ?>;
            }

            a:visited {
                color: #<?php echo($colors[3]); ?>;
            }

            a:hover {
                color: #<?php echo($colors[4]); ?>;
            }

            a:active {
                color: #<?php echo($colors[5]); ?>;
            }

        </style>
        <?php t_analytics($code); ?>
    </head>
    <body>

    <!--url's used in the movie-->
    <!--text used in the movie-->
    <!-- saved from url=(0013)about:internet -->
    <table width="100%" height="100%" border="0">
    <tr>
    <td align="center" valign="middle">
    <?php if ($showdescription) { ?>

        <?php if ($title) { ?>
            <span style="font-size: 20px;font-weight: bold;"><?php echo(aacute($title)); ?></span><br/>
        <?php } ?>
        <?php if($description) { ?>
            <span style="font-size: 18px;"><?php echo(aacute($description)); ?></span><br/>
        <?php } ?>
        <br/>

<?php }
}
//---------------------------------------------

function page_end(){
    if( $GLOBALS['page_started']){
        $GLOBALS['page_started']=false;
    }else{
        return;
    }

    $author=$GLOBALS['page_author'];
    $links=$GLOBALS['page_links'];
    $startyear=$GLOBALS['page_startyear'];


                ?>
                <br/><br/>
                <?php t_copy($author,$links,$startyear) ?><br>

            </td>
        </tr>
    </table>

    </body>
    </html>
<?php
}

?>
