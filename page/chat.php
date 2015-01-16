<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/page/chat.php

   Chat -obal
*/
//==============================



window(lr('title_chat'),NULL,NULL,'chat')

?><div style="width:100%;height:2px;"></div>
<div style="width:100%;height:2px;"></div>

<div style="width:<?php e(!$GLOBALS['mobile']?'449px':'96%') ?>;height:<?php e(!$GLOBALS['mobile']?'117px':'96%') ?>;overflow:hidden;">
<span style="width:100%;height:<?php e(!$GLOBALS['mobile']?'111px':'90%') ?>;overflow: hidden;z-index:1;" id="chatscroll">
<?php eval(subpage("chat_text"));/*subref("chat_text",3);*/ ?>
</span>


<?php
$url="e=content;ee=text-messages;ref=chat;id=".useid;

//ahref(trr(lr('text_toall')),$url);

 /*
<span style="position:absolute;width:100%;"><span style="position:relative;left:45%;top:-77px;">
<?php
eval(subpage("chat_aac"));
 ?>
</span></span>

<?php */?>
<span style="z-index:2;">
<form id="form_chat" name="form_chat" ><!--  method="post"action=""?q=chat [say]-->
    <table border="0" width="100%"><tr><td>
    <input type="text" id="say" name="say" maxlength="160" style="width:<?php e(!$GLOBALS['mobile']?'416px':'96%') ?>;height:22px;color: #cccccc;border: 2px solid #000000; background-color: rgba(0,0,0,1);"/>
    </td><td>
    <input type="submit" value=">" style="width:22px;height:22px;color: #cccccc;border: 2px solid #000000; background-color: #000000"/>
    </td></tr></table>
</form>
</span>
<script>
/*$("#form_chat").submit(function() {
   alert('hovno');
    $.post('?y=<?php e($_GET['y']); ?>&e=chat_text',
        { say: $('#say').val() },
        function(vystup){$('#chat_text').html(vystup);}
    );
    $('#say').val('');
    return(false);
});*/
/*---------CHAT*/
        $('#say').focus();
	$('#say').blur();
	lim=$('#innercontent').css('top');
	$('#chat_text').draggable({ axis:'y'/*,stop: function( event, ui ) { if(ui.position.top<lim) { $('#innercontent').css('top','0px'); } } , distance:<?php e($GLOBALS['dragdistance']); ?>*/ });

        document.chatsubmit=function() {
           //e.preventDefault();
            //alert('hovno');                        
            chating=false;
            
            say=$('#say').val();
            if(say){
                ch=say.substring(0, 1);
                if(ch==":" || ch==";"){
                    q=say.substring(1);
                }else{
                    q='chat [say]&say='+say;
                    /*$('#objectchat<?php  echo(useid); ?>').html(say);*/
                }
                
                htmlplus=$('#chat_new').html();
                /*alert(htmlplus);*/
                htmlplus=htmlplus.split('[text]').join(say);
                $('#chat_text').html($('#chat_text').html()+htmlplus);              
				$("#chatscroll").scrollTop(10000);                
                
                document.nochatref=true;
                $(function(){$.get('?s=<?php e(ssid); ?>e=map_chat&q='+q, function(vystup){document.nochatref=false;/*$('#map_chat').html(vystup);$('#loading').css('visibility','hidden');*/});$('#loading').css('visibility','visible');});

     	    $('#chat_first'+chat_first).html('');
            chat_first=chat_first-1;

            }
            say=$('#say').val('');



            return false;
            
        }
        
        
        $('#form_chat').submit(document.chatsubmit);        




</script>


</div>
