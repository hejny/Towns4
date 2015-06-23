<?php
/* Towns4, www.towns.cz 
   © Pavol Hejný | 2011-2015
   _____________________________

   core/page/chat_text.php

   Chat -obsah
*/
//==============================


$q=submenu('terminal',array("terminal_terminal","terminal_token"),1);


if($q==1) {
//======================================================================================================================Terminál
    window(lr('title_terminal'), 100, 100, 'terminal');

    ?>


    <div style="width: 620px;height: 480px;overflow: hidden;" onclick="terminalclick();" class="scroll">
    <div id="terminal_scroll"
         style="color: #ffffff; background-color: #000011; width: 640px;height: 480px;overflow-x: hidden;overflow-y: auto;">
    <?php

    if ($GLOBALS['ss']['get']['clean']) {
        $GLOBALS['ss']['terminal'] = array();
        $GLOBALS['ss']['get']['clean'] = false;
    }
    if ($_POST['terminal_text']) {

        xquery($_POST['terminal_text']);
        //$GLOBALS['ss']['terminal'].=$_POST['terminal_text'].nln;
    }

    //print_r($GLOBALS['ss']['terminal']);
    $i = 0;
    foreach ($GLOBALS['ss']['terminal'] as $command) {
        $i++;
        list($query, $response) = $command;

        ahref(textcolorr($i . '&gt; ', '333333'), js2('$(\'#terminal_text\').val(\'' . addslashes($query) . '\')'));
        e(nl2br(htmlspecialchars($query)));
        br();

        if ($response['error']) {
            if (is_string($response['error'])) {
                ebr('<span style="color:#ff0000;">' . $response['error'] . '</span>');
            } else {
                foreach ($response['error'] as $error) {
                    ebr('<span style="color:#ff0000;">' . $error . '</span>');
                }

            }
            unset($response['error']);
        }
        if ($response['1'] == 1) {
            ebr('<span style="color:#229977;">' . lr('townsfunction_success') . '</span>');
            unset($response['1']);
        }

        if ($response) {
            $response = json_encode($response, JSON_PRETTY_PRINT);
            e('<pre style="color: #ffffcc;">');
            e($response);
            e('</pre>');
            br();
            //xr($response,NULL,'111122');

        }

    }

    e(nl2br(htmlspecialchars()));

    e('</div></div>');


    e('<form id="form_terminal" name="form_terminal" method="post" action="?token=' . $_GET['token'] . '"  style="display:inline;" >');
    $GLOBALS['formid'] = 'form_terminal';
    input_text("terminal_text", '', NULL, NULL, 'width:100%;color:#cccccc;border:0px;background-color:#000011;');

    form_b();

    form_js('terminal', '?e=terminal', array('terminal_text'));

    moveby(ahrefr(imgr('icons/fx_plus.png', lr('terminal_refresh'), 22), 'e=terminal;'), -22, -478/*550,-540-514,*/);
    moveby(ahrefr(imgr('icons/fx_automine.png', lr('terminal_clean'), 22), 'e=terminal;clean=1'), -22, -454/*550,-540-514,*/);

    ?>
    <script type="text/javascript">

        function terminalclick() {
            //alert(444);
            //$('#terminal_text').val(getSelectionText());
            if (!getSelectionText()) {
                $('#terminal_text').focus();
            }
        }


        function getSelectionText() {
            var text = "";
            if (window.getSelection) {
                text = window.getSelection().toString();
            } else if (document.selection && document.selection.type != "Control") {
                text = document.selection.createRange().text;
            }
            return text;
        }

        window.setTimeout(function () {
            var elem = document.getElementById('terminal_scroll');
            elem.scrollTop = elem.scrollHeight;
        }, 10);

        $('#terminal_text').blur();
        $('#terminal_text').focus();
    </script>


<?php
}elseif($q==2){
//======================================================================================================================Token
    e('<div style="width: 620px;height: 499px;overflow: hidden;">');

    //print_r($_COOKIE);
    br();
    textb(lr('api_url').':');br();
    ebr(url);
    br();
    textb(lr('api_token').':');br();
    e(ssid);

    e('</div>');

//======================================================================================================================
}
?>