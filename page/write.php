<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/page/write.php

   Stránka napište názor
*/
//==============================



window(lr('title_write'),NULL,NULL,'write')



?>
<div style="width:449px;height:155px;overflow:none;">

<?php

//print_r($_GET);hr();
//print_r($_POST);

    if(/*$_GET["write"] or */$_GET["write_text"]){
        $height=117;
        $send=1;
        
        
        //INSERT INTO `world1_text` (`idle`, `type`, `new`, `from`, `to`, `title`, `text`, `time`, `timestop`)VALUES ('0', 1, '1', '', '', '', '', '', '');
        
        
        send_message(logid,$GLOBALS['inc']['write_id'],'write',$_GET["write_text"]);
        backup_text($_GET["write_text"]);
        
        success(lr('write_success'));
        
    }elseif($_POST["write_text"]){
        $height=117;
        $send=1;

        send_message(logid,$GLOBALS['inc']['write_id'],'write',$_POST["write_text"]);
        backup_text($_POST["write_text"]);
        
        success(lr('write_success'));
        
    }else{
        $height=135;
        $send=0;
    }

    //form_a(urlr('write=1'),'writewrite');
    e('<form id="form_writewrite" name="form_writewrite" method="post" action="?y='.$_GET['y'].'" >');
    $GLOBALS['formid']='form_writewrite';

    input_textarea("write_text",'none',44,4,'width:100%;height:'.$height.'px;color: #cccccc;border: 2px solid #000000; background-color: rgba(0,0,0,1);',lr($send?'write_next_description':'write_description'));
    

	form_send(lr('write_send'),'width:100%;height:20px;color: #cccccc;border: 2px solid #555555; background-color: rgba(40,20,40,1);');
	form_b();
	
	form_js('write','?e=write&write=1',array('write_text'));



?>

</div>
