
<?php
//htmljscss();

//print_r($GLOBALS['get']);
//----------------------------------------------------------------------------------------------------------------------Name,Text,Position
if(isset($GLOBALS['get']['id'])){

    $id=intval($GLOBALS['get']["id"]);

    list($story_name,$res,$x,$y)=sql_row("SELECT name,res,x,y FROM [mpx]pos_obj WHERE id=".$id." AND `own`=".$GLOBALS['ss']['logid']." AND type='story' AND ".objt());

    $res = explode(':',$res,2);
    $story_text=$res[1];


}else{
    $story_name=trim($_POST['story_name']);
    $story_text=trim($_POST['story_text']);



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
//----------------------------------------------------------------------------------------------------------------------ID

if ($_POST['story_id']) {
    $id = intval($_POST['story_id']);
} else {
    $id = nextid();
}

//----------------------------------------------------------------------------------------------------------------------Text

if($story_text){

    $story_text=remove_javascript($story_text);

    if(strpos($story_text,'<img')) {

        $i=0;
        while($img = substr2($story_text, '<img', '>',$i)){

            //--------------------------------------Nastavení width="100%"
            if(!strpos($img,'width')) {
                $img.=' width="100%" ';
                $story_text=substr2($story_text, '<img', '>',$i,$img);//Uložení
            }
            //--------------------------------------Přesun do userdata/objects
            $src_old = substr2($img, 'src="', '"',0);

            //@todo Může být problematické pokud je nastaven absolutní root
            if(strpos($src_old,'userdata/upload/')){

                $dir_old=root.'userdata/upload';
                $dir_new=root.'userdata/objects/'.$id;

                $src_new=str_replace($dir_old.'/',$dir_new.'/',$src_old);


                $file_old=$dir_old.'/'.html_entity_decode(basename($src_old));
                $file_new=$dir_new.'/'.html_entity_decode(basename($src_new));


                mkdir2(root.'userdata/objects/'.$id);//Vytvoření adresáře objektu
                rename($file_old,$file_new);//Přesun

                $img=substr2($img, 'src="', '"',0,$src_new);//Změna src
                $story_text=substr2($story_text, '<img', '>',$i,$img);//Uložení
                //$story_text.="rename($file_old,$file_new)";

            }


            //--------------------------------------
            $i++;
        }

    }
}
//----------------------------------------------------------------------------------------------------------------------Window Title

if($id or $_POST['story_send']){
    window(lr('title_story_edit'),550,0,'text-storywrite');


}else{
    window(lr('title_story_create'),550,0,'text-storywrite');

}
//----------------------------------------------------------------------------------------------------------------------Saving

//@todo $_POST['story_name']
if($_POST['story_send']){
    //----------------------------------------------------Errors
    if(!trim($story_name)){

        error(lr('story_error_noname'));

    }elseif(!trim($story_text)){

        error(lr('story_error_notext'));

    }else {

        if (ifobject($id,true)) {
            //----------------------------------------------------UPDATE

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
            //----------------------------------------------------

        } else {
            //----------------------------------------------------INSERT NEW

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
            //----------------------------------------------------

        }
        //centerurl()
        click('e=map;noi=1;');
    }
}
//----------------------------------------------------------------------------------------------------------------------Form
form_a('','story_form');

input_hidden('story_send',1);
input_hidden('story_id',$id?$id:0);
input_hidden('story_x',$x);//e("[$x,$y]");
input_hidden('story_y',$y);
input_text('story_name',$story_name,200,'','font-size:22px;width: 100%;border: 2px solid #171717; background-color: #222;color:#fff;',lr('story_name'));

input_tinymce('story_text',$story_text,'100%',400,2);

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