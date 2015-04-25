<?php





if($GLOBALS['get']["id"]){$GLOBALS['ss']["storyid"]=$GLOBALS['get']["id"];}



list($name,$res,$own)=sql_row("SELECT name,res,own FROM [mpx]pos_obj WHERE id=".sqlx($GLOBALS['ss']["storyid"])." AND type='story' AND ".objt());

$res = explode(':',$res,2);
$html=$res[1];

window($name);

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


e($html);



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
ahref(tabler('100%',array('left','middle')),'e=content;ee=profile;page=profile;id='.$own);

e('</div>');

if($own==$GLOBALS['ss']['logid']) {

    br();
    $td=ahrefr(lr('story_edit'), 'e=text-storywrite;id=' . $GLOBALS['ss']["storyid"].';'.js2('w_close(\'content\');'));
    $td.=' - ';
    $td.=ahrefpr(lr('f_dismantle_story_prompt'),lr('story_delete'), 'e=map;noi=1;q=dismantle ' . $GLOBALS['ss']["storyid"].';'.js2('w_close(\'content\');'));
    td($td);
    tr();
    table('100%',array('center','middle'));

}





contenu_b();
?>