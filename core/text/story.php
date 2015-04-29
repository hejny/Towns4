<?php





if($GLOBALS['get']["id"]){$GLOBALS['ss']["storyid"]=$GLOBALS['get']["id"];}



list($name,$res,$own)=sql_row("SELECT name,res,own FROM [mpx]pos_obj WHERE id=".sqlx($GLOBALS['ss']["storyid"])." AND type='story' AND ".objt());

$res = explode(':',$res,2);
$html=$res[1];

window($name);
permalink($name,$GLOBALS['ss']["storyid"]);

contenu_a();


if($html){

    if(strpos($html,'<img')) {

        $i=0;
        while($img = substr2($html, '<img', '>',$i)){

            $src = substr2($img, 'src="', '"',0);

            $src=imgresizewurl(html_entity_decode($src),450);

            $img=substr2($img, 'src="', '"',0,$src);

            $html=substr2($html, '<img', '>',$i,$img);


            $i++;
        }


    }
}

e('<div style="width: 440px;">');
e($html);
e('</div>');


e('<div style="width: 440px;background-color: #111111;padding: 5px;border-top: 2px solid rgba(80,80,80,0.44);">');




//@todo Z podpisu pouze v příbězích vytvořit funkci
$email=sql_1data("SELECT email FROM `[mpx]users` WHERE id=(SELECT `userid` FROM `[mpx]pos_obj` WHERE id=".$own." AND ".objt()." LIMIT 1) AND aac=1 LIMIT 1");
td('<img src="'.gravatar($email,60).'" border="2" class="author">',$link);

td(nbsp3);


$response=townsapi('list','profile',array('id='.$own,"type='user'"),NULL,1);
$response=$response['objects'][0]['profile'];
$signature=($response['signature']);

if(trim($signature)) {

    $td.=$signature;

}else{

    $td.=lr('story_author');
    $td.=textbr(id2name($own));

}


td($td);
tr();
$table=tabler('100%',array('left','middle'));

if(logged()){
    ahref($table,'e=content;ee=profile;page=profile;id='.$own);
}else{
    e($table);
}


e('</div>');
br();

if($own==$GLOBALS['ss']['logid']) {


    $td=ahrefr(lr('story_edit'), 'e=text-storywrite;id=' . $GLOBALS['ss']["storyid"].';'.js2('w_close(\'content\');'));
    $td.=' - ';
    $td.=ahrefpr(lr('f_dismantle_story_prompt'),lr('story_delete'), 'e=map;noi=1;q=dismantle ' . $GLOBALS['ss']["storyid"].';'.js2('w_close(\'content\');'));
    td($td);
    tr();


}

//----------------------------------------------------------------------------------------------------------------------Share buttons

$url=url.$GLOBALS['ss']["storyid"];
$title=lr('apps_title',$name);

    $share=brr().'

    <style type="text/css">
        #story_share_buttons {
            margin: 0 auto;
            width: 180px;
            filter: grayscale(80%);
            opacity: 0.4;
        }
        #story_share_buttons:hover {
            filter: grayscale(0%);
            opacity: 1;
        }
    </style>

    <!-- AddToAny BEGIN -->
    <div class="a2a_kit a2a_kit_size_32 a2a_default_style" id="story_share_buttons">
        <a class="a2a_dd" href="https://www.addtoany.com/share_save?linkurl='.urlencode($url).'&amp;linkname='.urlencode($title).'"></a>
        <a class="a2a_button_facebook"></a>
        <a class="a2a_button_twitter"></a>
        <a class="a2a_button_google_plus"></a>
    </div>
    <script type="text/javascript">
        var a2a_config = a2a_config || {};
        a2a_config.linkname = "'.addslashes($title).'";
        a2a_config.linkurl = "'.addslashes($url).'";
        a2a_config.locale = "cs";
        a2a_config.color_main = "undefined";
        a2a_config.color_border = "undefined";
        a2a_config.color_link_text = "undefined";
        a2a_config.color_link_text_hover = "undefined";
        a2a_config.color_bg = "undefined";
        a2a_config.color_arrow = "undefined";
        a2a_config.color_arrow_hover = "undefined";
    </script>
    <script type="text/javascript" src="//static.addtoany.com/menu/page.js"></script>
    <!-- AddToAny END -->
    ';
    td($share);
    tr();

//----------------------------------------------------------------------------------------------------------------------Dolní menu

table('100%',array('center','middle'));

//----------------------------------------------------------------------------------------------------------------------Konec

contenu_b();
?>