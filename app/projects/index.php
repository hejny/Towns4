<?php
/*
 * only
 *      1= bez Nadpisu
 *      2= Bez Nadpisu a html obalu
 * onlyaac = Pouze aktuální a bez obalu
 * limit = bez obalu
 * nolink bez odkazů
 *
 * */




/*
 * <script>
    jQuery(document).ready(function(){
        jQuery("#projects").load("http://localhost/towns/small/admin/app/projects/?only=1");
        //https://www.towns.cz/app/projects/?only=1
});
</script>
<div id="projects">aa</div>
 *
 * */

require_once('../inc.php');


$alltags=array('text','startx','stopx','start','stop','private','author','inbox');


if(!$_GET['project']){

    header('Access-Control-Allow-Origin: *');

    $only=$_GET['only'];

    if(!$only) {
        page(lr('app_projects'), lr('app_projects_description'), 2015);
    }elseif($only=='2'){
        //nezobrazuje se ani html obal
    }else{
        page();
    }



?>
<style type="text/css">
<!--
    .karta {

        display:inline-block;
        width:262px;
        height:162px;
        overflow-x:hidden;
        overflow-y:hidden;

        border-radius: 5px;
        #border-top-left-radius:5px;
        #border-top-right-radius:5px;
        #border-bottom-right-radius:0px;
        #border-bottom-left-radius:5px;

        border:#000000 0px solid;
        margin: 4px 4px 4px 4px;
        padding: 4px 4px 4px 4px;
        color: #000000;
    }
    .kartaname {
        display:inline-block;
        width:100%;
        font-size:19;
        height:25px;
        overflow-x:hidden;
        overflow-y:hidden;
        text-align:center;
        background-color: rgba(255,255,255,0.5);
        border-radius: 4px;
    }
    .kartadesc {
        text-align: justify;
        padding: 3%;
        font-size:14px;$only=$_GET['only'];

    if(!$only)
        width:94%;
        height:137px;
        overflow-x:hidden;
        overflow-y:hidden;
    }
    .author {
        border:#000000 2px solid;
        border-radius: 22px;
        background-color: #000000;
    }
-->
</style>
<?php


//--------------------------------LOAD projects
$projects=sql_array('SELECT `id`,`name`,`group`,`phase` FROM [mpx]projects WHERE (SELECT 1 FROM [mpx]projects_tags WHERE [mpx]projects_tags.projectid=[mpx]projects.id AND [mpx]projects_tags.tag=\'private\' LIMIT 1) IS NULL');
$i=0;
while($projects[$i]){
$projects[$i]['tags']=sql_array('SELECT `tag`,`value` FROM [mpx]projects_tags WHERE projectid='.$projects[$i]['id'].' ORDER BY pos ');

$i++;
}
//--------------------------------SORT projects
function tags2time($tags){
$time=0;

$lasttime=0;
$lastype='stop';

foreach($tags as $tag){
    list($tag,$value)=$tag;
    $value=strtotime($value);
    if($tag=='startx' or $tag=='start'){
	if($value<=time() and $value>$lasttime){
		$lasttime=$value;
		$lastype='start';
	}
    }
    if($tag=='stopx' or $tag=='stop'){
	if($value<=time() and $value>$lasttime){
		$lasttime=$value;
		$lastype='stop';
	}
    }
}


if($lastype=='start'){
	return($lasttime);
}

//---------------


foreach($tags as $tag){
    list($tag,$value)=$tag;
    //e($tag);
    if($tag=='startx' or $tag=='start'){

        $tmptime=strtotime($value);
        //e("($tmptime<$time or $time==0)");
        if($tmptime>time() and ($tmptime<$time or $time==0)) {
            $time = $tmptime;
        }
    }
}

return($time);

}
function compare2projects($a,$b){
$a=tags2time($a['tags']);
$b=tags2time($b['tags']);
if ($a == $b) {
    return 0;
}
if($a==0)return(1);
if($b==0)return(-1);
return ($a < $b) ? -1 : 1;

}
usort($projects, "compare2projects");

//------------------------------------------------------------------------------------------------Project cards

$timelines=array(
array(  0*30, 'CE7822' ),
array(  1*30, '8888ff' ),
array(  2*30, '999999' ),
array(  3*30, '999999' ),
array(  4*30, '999999' ),
array(  5*30, '999999' ),
array(  6*30, '999999' ),
array(  7*30, '999999' ),
array(  8*30, '999999' ),
array(  9*30, '999999' ),
array( 10*30, '999999' ),
array( 11*30, '999999' ),
array( 12*30, '999999' ),
);
$all=array();//HTML všech karet projektů

$tlc=count($timelines);
$tli=0;$tlil=0;
$timehorizont=0;

foreach($projects as $project) {


$time = tags2time($project['tags']);

while ($time >= $timehorizont and $tli < $tlc) {
    $timehorizont = time() + ($timelines[$tli][0] * 24 * 3600);
    $tli++;
    //echo($tli . "($time>=$timehorizont)");

}

//if($tli>=$tlc)$tli=$tlc-1;

if ($tli != $tlil) {

    $startingindays=$timelines[$tli - 1][0];
    $aacmonth=date('n')-1+1;
    $planmonth=round($aacmonth+($startingindays/30));
    if($planmonth==$aacmonth){
        $planmonth='aac';
    }
    if($planmonth>12){
        $planmonth='long';
    }

    $all['app_projects_month_'.$planmonth]=array();

    /*?>
    <h2><?php ('app_projects_month_'.$planmonth ); ?></h2>
    <?php*/

    $tlil = $tli;
}


ob_start();
?>


    <?php if(!$_GET['nolink']){ ?>
        <a href="#<?= $project['name'] ?>" onclick="karta=window.open('?project=<?= $project['id'] ?>', 'karta', 'width=600, height=500,menubar=no,resizable=no,left=100,top=100');karta.focus(); " style="text-decoration: none;" >
     <?php } ?>


    <div class="karta" style="background-color:#<?= $timelines[$tli-1][1] ?>;">
    <div class="kartaname">

            <?= str_replace(array('&',' '),array(' + ',nbsp),$project['name']); ?>
    </div>


    <div class="kartadesc">
            <?php
            //--------------------------------Description + tags
            $project['texts']=array();
            $project['authors']=array();

            //$project['texts'][]=(date('Y-m-d',$time));

            foreach ($project['tags'] as $tag) {
                list($tag, $value) = $tag;
                if ($tag == 'text') {
                    $project['texts'][]=nl2br(htmlspecialchars($value));
                } elseif ($tag == 'author' and $value) {
                    $project['authors'][]=$value;
                }
            }

            //if(!$_GET['onlyname']) {
                e(implode('<hr/>', $project['texts']));
            //}

            //--------------------------------
            ?>
    </div>

            <?php
            //print_r($authors);
            $left=262-46*count($project['authors']);
            foreach($project['authors'] as $author){

                ?>
                <div style="position:absolute;">
                    <div style="position:relative;left:<?= $left ?>;top:-62px;">

                        <img src="<?php e(str_replace('[world]',$GLOBALS['inc']['app'],$GLOBALS['inc']['url'])); ?>/projects/authors/<?= $author ?>.jpg" alt="<?php le('towns_author_'.$author) ?>" title="<?php le('towns_author_'.$author) ?>" width="40" class="author" />

                    </div>
                </div>

                <?php $left+=46;} ?>

            </span>


    </div>

    <?php
    e(!$_GET['nolink']?'</a>':'');
    ?>



    <?php
    $all['app_projects_month_'.$planmonth][]=ob_get_contents();
    ob_end_clean();


}
//------------------------------------------------------------------------------------------------Months cards - $all
?>
    <style type="text/css">
        <!--
        .allyear {


            width: <?php e($_GET['width']?$_GET['width']:'80%'); ?>;

        }
        .month {
            text-align: center;
            display:inline-block;

            width: 600;
            #height:250px;
            overflow-x:hidden;
            overflow-y:visible;


            border-radius: 5px;
            #border-top-left-radius:5px;
            #border-top-right-radius:5px;
            #border-bottom-right-radius:0px;
            #border-bottom-left-radius:5px;

            border:#000000 0px solid;
            margin: 4px 4px 4px 4px;
            padding: 4px 4px 4px 4px;

        }
        .monthname {
            color: #222222;
            display:inline-block;
            width:100%;
            font-size:25;
            height:35px;
            overflow-x:hidden;
            overflow-y:hidden;
            text-align:center;
            background-color: rgba(255,255,255,0.5);
            border-radius: 4px;
        }
        -->
    </style>
<?php
//---------------------------------------------------

if(!$_GET['onlyaac'] and !$_GET['limit']) {

    e('<div class="allyear">');


    foreach ($all as $name => $month) {
        ?>

        <div class="month" style="background-color:#eeeeee;">
            <div class="monthname">
                <?php e(aacute(lr($name))); ?>
            </div>
            <?php
            foreach ($month as $project) {
                $project = aacute($project);
                e($project);
            }

            $count = count($month);
            $count = (ceil($count / 2) * 2) - $count;
            if ($count) {
                e('<div class="karta"></div>');
            }

            ?>
        </div>



        <?php
        //<br/>
    }
    e('</div>');

}else{

    if($_GET['onlyaac']){
        $limit=9999;
    }elseif($_GET['limit']){
        $limit=$_GET['limit']-1+1;
    }


    foreach ($all as $name => $month) {


        foreach ($month as $project) {
            $project = aacute($project);
            e($project);

            $limit--;
            if($limit<1){
                break(2);
            }
        }


        if($_GET['onlyaac']){
            break;
        }
    }

}

//======================================================================================================================
}else{
//======================================================================================================================Zobrazení karty projektu

    $project=sql_row('SELECT `id`,trelloid,`name`,`group`,`phase` FROM [mpx]projects WHERE id='.sqlx($_GET['project']));
    if($project) {
        //---------------------------------------------
        $project['tags'] = sql_array('SELECT `tag`,`value` FROM [mpx]projects_tags WHERE projectid=' . $project['id']);
        $project['texts'] = array();
        $project['authors'] = array();
        $project['inbox']=false;

        foreach ($project['tags'] as $tag) {
            list($tag, $value) = $tag;
            if ($tag == 'text') {
                $project['texts'][] = nl2br(htmlspecialchars($value));
            } elseif ($tag == 'author' and $value) {
                $project['authors'][] = $value;
            } elseif ($tag == 'inbox' and $value) {
                $project['inbox'] = $value;
            }

        }
        $project['texts'] = implode('<br/>', $project['texts']);

        page($project['name'], $project['texts']);
        //------------------------------------------------------------------------------------------------style
        ?>
                <style type="text/css">
                    <!--
                    .error {
                        color: #CC0000;
                        font-weight: bold;
                    }
                    .success {
                        color: #00cc00;
                        font-weight: bold;
                    }
                    -->
                </style>
        <?php
        //---------------------------------------------------


        foreach ($project['authors'] as $author) {

            ?>
            <div style="display: inline-block;text-align: center;">

                <img src="authors/<?= $author ?>.jpg" alt="<?php le('towns_author_' . $author) ?>"
                     title="<?php le('towns_author_' . $author) ?>" width="100" border="2"/>
                <br/>
                <?php le('towns_author_' . $author) ?>

            </div>

        <?php
        }

        br(2);
        //--------------------------------------------------------------------------------------------------------------Inbox
        if($project['inbox']){
            //----------------------------------------------------------------Projekt má inbox
            $success=false;
            if ($_GET['write']) {
                //--------------------------------Zpracování formuláře
                $text=trim($_POST['text']);
                $email=trim($_POST['email']);

                if (!$text) {
                    e('<span class="error">'.lr('app_projects_write_error_notext').'</span>');
                }elseif(!check_email($email)){
                    e('<span class="error">'.lr('app_projects_write_error_email').'</span>');
                }else{

                    //----------------Malé / Velké Zprávy

                    if (strlen($text) < 140) {
                        $a = $text;
                    } else {
                        $a = substr($text, 0, 140) . '...';

                    }
                    //----------------Kontrola 'TAG injection'
                    foreach($alltags as $tag){
                        if(substr($b,0,strlen($tag))==$tag){
                            $b='-'.$b;
                            break;
                        }
                    }
                    //----------------Přidání kontaktu

                    $b = $text.' ['.$email.']';

                    //----------------Odeslání do Trella

                    $auth = 'key=' . $GLOBALS['inc']['trello_key'] . '&token=' . $GLOBALS['inc']['trello_token'];


                    error_reporting(0);
                    $return = post_request(
                        'https://trello.com/1/cards?' . $auth
                        ,
                        array('idList' => $project['inbox'],
                            'name' => $a,
                            'desc' => $b,
                            //'idLabels' => array('54d9e8fe74d650d56736877d')
                        )

                    );




                    //----------------Úspěch


                    $success=true;

                    if(!$return){
                        mail($GLOBALS['inc']['log_mail'],$a,$b,'From: projects@towns.cz');
                        e('<span class="success">' . lr('app_projects_write_success_mail') . '</span>');
                    }else {
                        e('<span class="success">' . lr('app_projects_write_success') . '</span>');
                    }

                    //----------------
                }
            }
            //--------------------------------Formulář
            ?>


            <form method="post" action="?project=<?= $project['id'] ?>&amp;write=1">
                <textarea name="text" style="width: 60%;" rows="6" placeholder="<?php le('app_projects_write_placeholder'); ?>" ><?php e($success?'':htmlspecialchars($_POST['text'])) ?></textarea>
                <br/>
                <?php le('app_projects_write_email'); ?><input type="text" name="email" value="<?php e(addslashes(htmlspecialchars($_POST['email']))) ?>">
                <br/>
                <input type="submit" value="<?php le('app_projects_write_ok'); ?>">
            </form>


            <?php
            //----------------------------------------------------------------
        }else{
            //----------------------------------------------------------------Projekt nemá inbox
            le('app_projects_write_noinbox');
            //----------------------------------------------------------------
        }

        //--------------------------------------------------------------------------------------------------------------
    }
//======================================================================================================================
}


page_end();


?>


