<?php





if($GLOBALS['get']["id"]){$GLOBALS['ss']["storyid"]=$GLOBALS['get']["id"];}



list($name,$res,$own)=sql_row("SELECT name,res,own FROM [mpx]pos_obj WHERE id=".sqlx($GLOBALS['ss']["storyid"])." AND type='story' AND ".objt());

$res = explode(':',$res,2);
$html=$res[1];

window($name);

contenu_a();

e($html);



e('<div style="width: 430px;border: solid 2px #333;background-color: #111111;padding: 5px;">');

textb(lr('story_author').': ');
e(id2name($own));
br();

if($own==$GLOBALS['ss']['logid']) {


    ahref(lr('story_edit'), 'e=text-storywrite;id=' . $GLOBALS['ss']["storyid"].';'.js2('w_close(\'content\');'));
    br();

    ahrefp(lr('f_dismantle_story_prompt'),lr('story_delete'), 'e=map;noi=1;q=dismantle ' . $GLOBALS['ss']["storyid"].';'.js2('w_close(\'content\');'));

}

e('</div>');

contenu_b();
?>