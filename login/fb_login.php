<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný, Přemysl Černý | 2011-2013
   _____________________________

   core/login/fb_login.php

   Přihlašování přes Facebook
*/
//==============================





  // login URL
  $params = array(
    'display' => 'popup',
    'redirect_uri' => url.corexx.('-fblogin')
  );
  //e($params['redirect_uri']);
  $loginUrl = $GLOBALS['facebook']->getLoginUrl($params);
/*https://www.facebook.com/dialog/oauth?client_id=408791555870621&redirect_uri=http%3A%2F%2Flocalhost%2Ftowns%2Fworld2%2F%3Fe%3Dfb-fb_redirect&state=f9d7a50e6c86aa24102f5fb6d3e07d17*/

    //e($loginUrl);


?>
<?php

if(/*$GLOBALS['url_param']!='fbonly' and */false){
?>

<a href="<?php echo($loginUrl) ?>">
<img src="<?php imageurle('design/fb_login.png'); ?>" style="background: rgba(30,30,30,0.9);border: 2px solid #222222;border-radius: 2px;" alt="<?php le('fb_login_button'); ?>" width="93" />

</a>


<?php 
}else{
?>
<a href="#" onclick="window.open('<?php echo($loginUrl) ?>', 'fblogin', 'width=<?php e(500); ?>,height=<?php e(400); ?>,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,left=<?php e(round(($GLOBALS['screenwidth']-500)/2)); ?>,top=<?php e(round(($GLOBALS['screenheight']-400)/2)); ?>')">
<img src="<?php imageurle('design/fb_login.png'); ?>" style="background: rgba(30,30,30,0.9);border: 2px solid #222222;border-radius: 2px;" alt="'<?php le('fb_login_button'); ?>'" width="93" />

</a>
<?php
}
?>
