<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/login/login.php

   Úvodní přihlašovací obrazovka - obal
*/
//==============================




//window('',0,0,'login');
//r($GLOBALS['get']);
/*if($GLOBALS['get']["login_try"]){
    xquery("login",$post["login_username"],$post["login_password"]);
}*/



$topspace1=intval($GLOBALS['screenheight']*0.2);
$topspace2=$GLOBALS['screenheight']-630;
if($topspace2<0){$topspace2=0;}
$topspace=min(array($topspace1,$topspace2));
$topspace=max(array(1,$topspace));

?>
<table height="<?php e(/*$GLOBALS['screenheight']*/0); ?>" width="100%" border="0" cellpadding="0" cellspacing="0">

<tr>
  <td height="<?php echo($topspace); ?>" align="center" valign="middle"><?php
echo(/*"$topspace1,$topspace2,$topspace"*/'');
?>
</td>
</tr>
<tr><td align="center" valign="middle">

   
            
    <!--======================================================================-->
            <!--==========================-->
            
            <?php /*<span style="display:inline-block;width:350px;height:100px;align:center;valign:middle;padding:5;background: rgba(5,5,5,0.9);border: 2px solid #335599;">
                    <?php
                    eval(subpage('login-loginx'));
                    ?>
            </span>*/ ?>
            
            <!--==========================-->

            <span style="display:inline-block;width:350px;">
                    <?php
                        $i=0;
                        foreach($GLOBALS['inc']['worlds'] as $world){
                            $url=$GLOBALS['inc']['url'];
                            $url=str_replace(array('[world]',w),$world,$url).'#noblank';
                            dockbutton(10+($i*150),-26,($world==w?14:array(14,'888888')),lr('world_'.$world),$url,($world==w?10:1),false,140,false,($world==w?'rgba(5,5,5,0.9)':'rgba(10,10,10,0.9)'),($world==w?/*'#111111'*/'#111111':'#222222'),'relative');
                            $i++;
                        }
                    
                    
                        if($GLOBALS['inc']['forum']){
                            dockbutton(361,10,array(-14,'aaaaaa'),'Více o hře',$GLOBALS['inc']['forum'],1,false,100,false,'rgba(30,10,40,0.9)','#333333','relative');
                        }
                        ?>
            
            
            <span style="position:absolute;display:block;width:350px;align:center;vertical-align: middle;padding:5;background: rgba(5,5,5,0.9);border: 2px solid #335599;z-index: 5;">    
                    <?php
                        eval(subpage('login-loginx'));
                    ?>
            </span>
            </span>
            <?php /*
            <span style="display:inline-block;width:20px;height:140px;align:center;vertical-align: middle;padding:5;background: rgba(5,5,5,0.9);border: 2px solid #335599;border-radius: 2px;">
                    <?php   
                        tee('Komunita', -14);
                    //eval(subpage('login-loginx'));
                    ?>
            </span>
             */ ?>


            <!--==========================-->
                <?php /*
                <table width="350" height="100" border="0" cellpadding="5" cellspacing="0" style="background: rgba(5,5,5,0.9);border: 2px solid #335599;">
                    <tr><td align="center" valign="middle" >
                        <?php
                        //eval(subpage('login-loginx'));
                        ?>

                    </td></tr>
                </table>
                */ ?>
    <!--======================================================================-->




</td></tr>
</table>

