<script>

    try{
        tinyMCE.remove()
    }catch(e){
    }


    tinyMCE.baseURL = '<?=url?>lib/tinymce/';
    tinyMCE.init(
        {
            mode: 'textareas',
            selector: '#story_text',
            skin_url: '<?=url?>lib/tinymce/skins/custom',
            content_css : '<?=url?>/lib/tinymce/skins/custom/content.css',
            language: 'cs',
            theme: 'modern',
            height : 550,
            statusbar:false,
            //pagebreak_separator: '<!-- my page break -->',
            setup: function (ed) {
                ed.on('init', function(args) {
                    setInterval(function() {
                        $('div').scrollTop(0);
                    },200);
                });
            },
            menubar: 'edit insert view format table tools jbimages',
            plugins: 'advlist autolink lists link image charmap print preview hr anchor searchreplace wordcount visualblocks visualchars code fullscreen media nonbreaking table contextmenu emoticons template paste textcolor colorpicker textpattern jbimages',
            toolbar: " bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link |  jbimages  ",//pagebreak
            relative_urls: false
        }
    );





</script>
<style>
    body.mceContentBody {

    }
</style>

<?php

//print_r($GLOBALS['get']);

if(isset($GLOBALS['get']['id'])){

    $id=intval($GLOBALS['get']["id"]);

    list($story_name,$res,$x,$y)=sql_row("SELECT name,res,x,y FROM [mpx]pos_obj WHERE id=".$id." AND `own`=".$GLOBALS['ss']['logid']." AND type='story' AND ".objt());

    $res = explode(':',$res,2);
    $story_text=$res[1];


}else{
    $story_name=$_POST['story_name'];
    $story_text=$_POST['story_text'];



    if(isset($_POST['story_x'])) {

        $x = $_POST['story_x'];
        $y = $_POST['story_y'];

    }elseif(isset($GLOBALS['get']['x'])) {

        $x = $GLOBALS['get']['x'];
        $y = $GLOBALS['get']['y'];

    }else{

        click(js2("w_close('text-storywrite');"));
    }
}


if($id or $_POST['story_send']){
    window(lr('title_story_edit'),550,0,'text-storywrite');


}else{
    window(lr('title_story_create'),550,0,'text-storywrite');

}


//@todo $_POST['story_name']
if($_POST['story_send']){
    if(!trim($story_name)){

        error(lr('story_error_noname'));

    }elseif(!trim($story_text)){

        error(lr('story_error_notext'));

    }else {

        if ($_POST['story_id']) {

            $id = intval($_POST['story_id']);

            trackobject($id);

            //------------------UPDATE objects
            sql_update('objects', "id='$id'", array(
                'id' => $id,
                'name' => $story_name,
                'type' => 'story',
                'userid' => '',
                'origin' => '',
                'fp' => '',
                'func' => '',
                'hold' => '',
                'res' => 'html:' . $story_text,//@todo Takhle?
                'profile' => '',
                'set' => '',
                'own' => $GLOBALS['ss']['logid'],//@todo Takhle?
                't' => '',
                'pt' => ''
            ));
            //------------------UPDATE positions
            sql_update('positions', "id='$id'", array(
                'id' => $id,
                'ww' => $GLOBALS['ss']["ww"],
                'x' => $x,
                'y' => $y,
                'traceid' => '',
                'starttime' => time(),
                'readytime' => 0,
                'stoptime' => 0
            ));
            //------------------

        } else {

            $id = nextid();

            //------------------INSERT objects
            sql_insert('objects', array(
                'id' => $id,
                'name' => $story_name,
                'type' => 'story',
                'userid' => '',
                'origin' => '',
                'fp' => '',
                'func' => '',
                'hold' => '',
                'res' => 'html:' . $story_text,//@todo Takhle?
                'profile' => '',
                'set' => '',
                'own' => $GLOBALS['ss']['logid'],//@todo Takhle?
                't' => '',
                'pt' => ''
            ));

            //------------------INSERT positions
            sql_insert('positions', array(
                'id' => $id,
                'ww' => $GLOBALS['ss']["ww"],
                'x' => $x,
                'y' => $y,
                'traceid' => '',
                'starttime' => time(),
                'readytime' => 0,
                'stoptime' => 0
            ));
            //--rlx----------------
        }
        //centerurl()
        click('e=map;noi=1;');
    }
}

form_a('','story_form');

input_hidden('story_send',1);
input_hidden('story_id',$id?$id:0);
input_hidden('story_x',$x);//e("[$x,$y]");
input_hidden('story_y',$y);
input_text('story_name',$story_name,200,'','font-size:22px;width: 100%;border: 2px solid #171717; background-color: #222;color:#fff;',lr('story_name'));

input_textarea('story_text',$story_text);

/*
?>
  <textarea id="story_text" name="text" style="width:800;height:200px;"><?=htmlspecialchars($story_text)?></textarea>
<?php
*/

form_send(lr('story_send'),'font-size:18px;width:100%;color: #cccccc;border: 2px solid #555555; background-color: rgba(40,20,40,1);');

form_b();
form_js('text-storywrite','?e=text-storywrite',array('story_name','story_text','story_send','story_id','story_x','story_y'));



//$worldmap = TownsApi('worldmap');
//e('<img src="'.$worldmap['url'].'">');




?>
</form>