<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/page/help.php

   Tento soubor slouží k zobrazování help oken.
*/
//==============================




if(!$GLOBALS['nowidth']){
window(lr('title_help'));
?>
<!--<div style="width:400;"></div>-->
<?php
}

if(!$GLOBALS['ss']["helppage"]){$GLOBALS['ss']["helppage"]="tutorial";$GLOBALS['ss']["helpanchor"]=false;}
if($GLOBALS['ss']["helppage"]=="about" and !$GLOBALS['nowidth']){$GLOBALS['ss']["helppage"]="tutorial";}

if($GLOBALS['get']["page"]){
    $GLOBALS['ss']["helppage"]=$GLOBALS['get']["page"];
    list($GLOBALS['ss']["helppage"],$GLOBALS['ss']["helpanchor"])=explode('_',$GLOBALS['ss']["helppage"],2);
}

if(logged()){
if($GLOBALS['ss']["helpanchor"]=='x'){
	$GLOBALS['ss']["helpanchor"]=$GLOBALS['ss']["log_object"]->set->ifnot('help_'.$GLOBALS['ss']["helppage"].'_anchor',1);
}
$GLOBALS['ss']["log_object"]->set->add('help_'.$GLOBALS['ss']["helppage"].'_anchor',$GLOBALS['ss']["helpanchor"]);
}else{
$GLOBALS['ss']["helpanchor"]=1;
}

r($GLOBALS['ss']["helppage"].'_'.$GLOBALS['ss']["helpanchor"]);


        /*$file=("data/lang/".$GLOBALS['ss']["lang"].".txt");
        $stream=file_get_contents($file);
        //$buffer=$stream.$file.$buffer;
        //$buffer=$stream.$file.$buffer;
        $GLOBALS['ss']["langdata"]=(astream($stream));*/
	$contentlang=lr('helphtml_'.$GLOBALS['ss']["helppage"]);


if(!/*$GLOBALS['ss']["langdata"]['helphtml_'.$GLOBALS['ss']["helppage"]]*/$contentlang){
    $GLOBALS['ss']["helppage"]="tutorial";
    $GLOBALS['ss']["helpanchor"]=1;
}

//$stream=file_get_contents(root.'data/help/'.$GLOBALS['ss']["lang"].'/'.$GLOBALS['ss']["helppage"].'.html');
$stream=lr('helphtml_'.$GLOBALS['ss']["helppage"]);
$stream=str_replace('[semicolon]',';',$stream);

if($GLOBALS['ss']["helpanchor"]){
	$stream=explode('<hr>',$stream);
	$count=count($stream);
	$stream=$stream[$GLOBALS['ss']["helpanchor"]-1];
}

$stream=substr2($stream,'<title>','</title>',0,'<script>$("#window_title_content").html("[]");</script>',false);


//$stream=str_replace('src="../image/','src="',$stream);
//$stream=str_replace('src="../../image/','src="../../image/',$stream);
//-----------------------------------------------------------------------------------src
$i=0;
while($tmp=substr2($stream,'src="','"',$i)){
    $stream=substr2($stream,'src="','"',$i,imageurl(/*'../help/image/'.*/$tmp));
    $i++;
}
//-----------------------------------------------------------------------------------href
$stream=str_replace('href="http://','http="',$stream);
$i=0;
while($tmp=substr2($stream,'href="','"',$i)){
    $stream=substr2($stream,'href="','"',$i,urlr('e=content;ee=help;page='.$tmp));
    $i++;
}
$stream=str_replace('http="','target="_blank" href="http://',$stream);
//-----------------------------------------------------------------------------------javacsript

$stream=str_replace('href="javascript:','href="#" onclick="',$stream);
//$stream=smiles($stream);

//-----------------------------------------------------------------------------------AAC

if(strpos($stream,'<!--aac-->')){
	/*$aac='<table width="100%" border="0">';

	$array=sql_array("SELECT post_title,guid,post_date FROM `wp_posts` WHERE `post_type` = 'post' AND `post_status` = 'publish' AND `post_password` = '' ORDER BY `ID` DESC LIMIT 2");
	foreach($array as $row){
		
		list($post_title,$guid,$post_date)=$row;
		$post_date=date('d.m.Y', strtotime($post_date));
		$aac.=('<tr><td width="30"><a href="'.$guid.'" target="_blank">'.$post_date.'</a></td><td align="right"><a href="'.$guid.'" target="_blank">'.$post_title.'</a></td></tr>');
	}
	$aac.='</table>';*/


	$array=sql_array("SELECT post_title,guid,post_date FROM `".sql($GLOBALS['inc']['wp_posts'])."` WHERE `post_type` = 'post' AND `post_status` = 'publish' AND `post_password` = '' ORDER BY `ID` DESC LIMIT 1");
		list($post_title,$guid,$post_date)=$array[0];
		$post_date=date('d.m', strtotime($post_date));
		$aac=('<a href="'.$guid.'" target="_blank">'.lr('last_aac').' '.$post_title.' ('.$post_date.')</a>');

	$stream=str_replace('<!--aac-->',$aac,$stream);
}
//-----------------------------------------------------------------------------------Projects

    if(strpos($stream,'<!--projects-->')){

        $url=url.'../app/projects/?only=2&limit=2&nolink=1';
        $projects='<a href="'.$GLOBALS['inc']['projects'].'" target="_blank"><span id="app_projects" style="" >'.lr('loading').'</span></a>';
        $projects.=<<<EOF
            <script>

            $.get( "$url", function( data ) {
                $( "#app_projects" ).html( data );
            });

            </script>

EOF;


        $stream=str_replace('<!--projects-->',$projects,$stream);
    }


//-----------------------------------------------------------------------------------External

$i=0;
while($tmp=substr2($stream,'<!--','-->',$i)){
    
    //e(123);
    
    list($page,$div)=explode(',',$tmp);
    
    $pagedata=sql_1data("SELECT `post_content` FROM `".sql($GLOBALS['inc']['wp_posts'])."` WHERE `post_name`='".sql($page)."' ");
    $pagedata='<!--none--><!--/none-->'.$pagedata;
    if($div){
        $as='<!--'.$div.'-->';
        $bs='<!--/'.$div.'-->';
        $a=strpos($pagedata,$as);
        $b=strpos($pagedata,$bs);
        if($a and $b){
            $pagedata=substr($pagedata,$a+strlen($as),$b-$a-strlen($as));
        }
    }
    
    $pagedata=str_replace(array('<!--','-->'),array('replaceprotectiona','replaceprotectionb'),$pagedata);

    
    $stream=substr2($stream,'<!--','-->',$i,$pagedata);
    $i++;

}

$stream=str_replace(array('<!--','-->'),array('',''),$stream);
$stream=str_replace(array('replaceprotectiona','replaceprotectionb'),array('<!--','-->'),$stream);


//$stream.='x';

//-----------------------------------------------------------------------------------
if(!$GLOBALS['nowidth']){
    if($GLOBALS['ss']["helpanchor"]){


infob((($GLOBALS['ss']["helpanchor"]-1)?ahrefr(lr('help_previous'),'e=content;ee=help;page='.$GLOBALS['ss']["helppage"].'_'.($GLOBALS['ss']["helpanchor"]-1)).nbspo:'').(($GLOBALS['ss']["helpanchor"]-1+2<=$count)?ahrefr(lr('help_next'),'e=content;ee=help;page='.$GLOBALS['ss']["helppage"].'_'.($GLOBALS['ss']["helpanchor"]-1+2)).nbspo:''));


    }else{
	//infob(ahrefr('{help_list}','e=content;ee=help;page=list'));
	infob();

    }
    contenu_a();
    e($stream);
    contenu_b();
}else{
    e($stream);
//exit;
}

?>
