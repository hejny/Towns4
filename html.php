<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/html.php

   V tomto souboru je html "obal".
*/
//==============================
//<html lang="<?php if($GLOBALS['ss']['lang']=='cz'){e('cs');}else{e($GLOBALS['ss']['lang']);}?" dir="ltr">
//<meta charset="UTF-8">

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="<?php if($GLOBALS['ss']['lang']=='cz'){e('cs');}else{e($GLOBALS['ss']['lang']);}?>">

<head>


<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Towns 4</title>
<meta name="author" content="hejny" />
<!--<meta name="keywords" content="" />-->
<meta name="description" content="<?php if($GLOBALS['ss']['lang']=='cz'){e('Towns je nový typ strategické hry založené na vytváření vlastního města. Zažijte zbrusu novou atmosféru stavění města na různých terénech v doprovodu s elegantní grafikou. Bojujte a domlouvejte se se spoluhráči, získávejte suroviny, stavějte a dokonce si vytvářejte vlastní budovy!'); }else{ e('Towns is a new type of online strategy game based on building your own town. Experience a brand new atmosphere of your own town on various terrains, accompanied with elegant graphics. Fight and communicate with players, gain resources, build, and create your own buildings!'); } ?> " />


<meta property="og:image" content="<?php e(imageurl('logo/share.png')); ?>" />
<link rel="shortcut icon" href="../favicon.ico">

<link href="favicon.ico" rel="../favicon.ico" type="image/x-icon" />

<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0"/>
<style type="text/css">
<!--
body {
	background-color: #000000;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
body,td,th {
color: #cccccc;
font-size: 14px;
font-family: "trebuchet ms";
}
h1{
font-size: 25px;
}
h3{
font-size: 15px;
}
hr {
border-color: #cccccc;
height: 0.5px;
}
a{color: #cccccc;text-decoration: none;}
-->
</style>

<?php
function script_($script){
    e('<script type="text/javascript" src="'.rebase(url.base.'/'.$script).'"></script>');
}
function css_($css){
    e('<link rel="stylesheet" href="'.rebase(url.base.'/'.$css).'" type="text/css" />');
}
script_('lib/jquery/js/jquery-1.6.2.min.js');
//script_('lib/jquery/js/jquery-ui-1.8.16.custom.min.js');
//script_('lib/jquery/js/jquery-1.11.1.min.js');
script_('lib/jquery/js/jquery-ui.js');

//OLD//script_('lib/jquery/kemayo-maphilight-4cdc2e2/jquery.maphilight.min.js');
//OLD//script_('lib/jquery/jquery.tinyscrollbar.min.js');
script_('lib/jquery/jquery.fullscreen-min.js');
script_('lib/jquery/jquery.mousewheel.js');
script_('lib/jquery/jquery.scrollbar.js');
script_('lib/jquery/colorpicker/js/colorpicker.js');
//OLD//script_('lib/jquery/colorpicker/js/eye.js');
//OLD//script_('lib/jquery/colorpicker/js/utils.js');
//OLD//script_('lib/jquery/colorpicker/js/layout.js');
css_('lib/jquery/colorpicker/css/colorpicker.css');
script_('lib/jquery/jquery.ui.touch-punch.min.js');



//OLD//script_('lib/jquerymobile/jquery.mobile-1.4.1.js');
?>

 <script type="text/javascript"> fps=30;</script>





<?php
if(defined('analytics')){
?>

<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>



<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?php echo(analytics); ?>']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ?  'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
<?php 
}
//-------------------------------------------------Vlastní statistika
qlog(0,0,0,'html',NULL,NULL);

//-------------------------------
?>

</head>
<body>


<?php if(logged() or 1){ /**/?>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/cs_CZ/all.js#xfbml=1&appId=<?php e(fb_appid); ?>";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>


<?php /**//*
?>

  <script>
      window.fbAsyncInit = function() {
        FB.init({
          appId      : '<?php e(fb_appid); ?>',
          xfbml      : true,
          version    : 'v2.1'
        });
      };

      (function(d, s, id){
         var js, fjs = d.getElementsByTagName(s)[0];
         if (d.getElementById(id)) {return;}
         js = d.createElement(s); js.id = id;
         js.src = "//connect.facebook.net/en_US/sdk.js";
         fjs.parentNode.insertBefore(js, fjs);
       }(document, 'script', 'facebook-jssdk'));
    </script>


<?php */ } ?>


<script type="text/javascript">

    z_index=1000;
    <?php if(!debug){ ?>
    $(document).ready(function(){
       $(document).bind("contextmenu",function(e){
              return false;
       });
    });
    <?php } ?>
    connectfps=4;
    fps=12;
   /*$(document).disableSelection();*/
   //$('*:not(:input)').disableSelection();
   /*setInterval(function() {
         $('*:not(:input)').disableSelection();
   }, 1000);*/

</script>





<div style="width:100%;height:100%;" id="html_fullscreen">
<table width="100%" height="100%"><tr>
<td align="center" valign="center"><?php include(root.core."/page/loading.php"); ?></td>
</tr></table>
</div>

<?php if(edit){ ?>
<iframe src="admin/?page=lang" width="100%" height="100%"></iframe>
<?php } ?>





<?php

if($_GET['ref']){
$GLOBALS['ss']['ref']=$_GET['ref'];
}



//require(root.core.'/html_fullscreen_nologin.php');


/*if ($GLOBALS['mobile']) {
	if(logged()){
		$page='-html_fullscreen';
	}else{
		$page='login-mobile';
	}
}else{
	$page='-html_fullscreen';
}*/
//$page='-html_fullscreen';

?>

<script type="text/javascript">
//------------------------------------------------------------------------------------------------------------------
nacitacihtml=$('#html_fullscreen').html();
logged=false;
first=true;

function reloc(first){
if(first==undefined){first=0;}else{first=1;}
//alert('reloc');
<?php if($_GET['width']){e('ww='.$_GET['width'].';');}else{e('ww=$(window).width();');} ?>
<?php if($_GET['height']){e('hh='.$_GET['height'].';');}else{e('hh=$(window).height();');} ?>

<?php $page='-html_fullscreen'; ?>

/*$(function(){});*/

/*alert('?y=<?php e($_GET['y']); ?>&s=<?php e(ssid); ?>&e=<?php e($page); ?>&width=' + ww + '&height=' + hh + '&mobile=<?php echo($GLOBALS['mobile']?'1':'0'); ?>&j=<?php echo($_GET['j']); ?>&addposition=<?php echo($_GET['addposition']); ?>&'+add);*/
$('#html_fullscreen').html(nacitacihtml);


$.get('?y=<?php e($_GET['y']); ?>&s=<?php e(ssid); ?>&e=<?php e($page); ?>&width=' + ww + '&height=' + hh + '&mobile=<?php echo($GLOBALS['mobile']?($GLOBALS['android']?2:1):0); ?>&j=<?php echo($_GET['j']); ?>&addposition=<?php echo($_GET['addposition']); ?><?php

echo($_POST['write_text']?'&write_text='.urlencode($_POST['write_text']):'');
echo($_GET['unsubscribe']?'&unsubscribe='.urlencode($_GET['unsubscribe']):'');

?>&first='+first, function(vystup){$('#html_fullscreen').html(vystup);});


}
//------------------------------------------------------------------------------------------------------------------
function register(param1,param2){
//alert('register '+param1+','+param2);
<?php if($_GET['width']){e('ww='.$_GET['width'].';');}else{e('ww=$(window).width();');} ?>
<?php if($_GET['height']){e('hh='.$_GET['height'].';');}else{e('hh=$(window).height();');} ?>

<?php $page='-html_fullscreen'; ?>

/*$(function(){});*/

/*alert('?y=<?php e($_GET['y']); ?>&s=<?php e(ssid); ?>&e=<?php e($page); ?>&width=' + ww + '&height=' + hh + '&mobile=<?php echo($GLOBALS['mobile']?'1':'0'); ?>&j=<?php echo($_GET['j']); ?>&addposition=<?php echo($_GET['addposition']); ?>&'+add);*/
<?php
	/*$vi="
	//if(typeof event === 'undefined'){1;}else{
	alert(1);
	\$('#loading').css('display','block');
	\$('#loading').css('left',event.pageX-10);
	\$('#loading').css('top',event.pageY-10);
	//}
	";
        $iv="\$('#loading').css('display','none');";*/


	$key=rand(1111111,9999999);
	$GLOBALS['ss']['register_key']=$key;
?>

$('#html_fullscreen').html(nacitacihtml);


$.get('?y=<?php e($_GET['y']); ?>&s=<?php e(ssid); ?>&e=<?php e($page); ?>&width=' + ww + '&height=' + hh + '&mobile=<?php echo($GLOBALS['mobile']?($GLOBALS['android']?2:1):0); ?>&j=<?php echo($_GET['j']); ?>&addposition=<?php echo($_GET['addposition']); ?>&q=register new,<?php e($key); ?>,0,'+param1+','+param2+'&login_try=1', function(vystup){$('#html_fullscreen').html(vystup);});/**/

}
//------------------------------------------------------------------------------------------------------------------
function logout(){
//alert('logout');
logged=false;
<?php if($_GET['width']){e('ww='.$_GET['width'].';');}else{e('ww=$(window).width();');} ?>
<?php if($_GET['height']){e('hh='.$_GET['height'].';');}else{e('hh=$(window).height();');} ?>

<?php $page='-html_fullscreen'; ?>

/*$(function(){});*/

$('#html_fullscreen').html(nacitacihtml);
/*alert('?y=<?php e($_GET['y']); ?>&q=logout&s=<?php e(ssid); ?>&e=<?php e($page); ?>&width=' + ww + '&height=' + hh + '&mobile=<?php echo($GLOBALS['mobile']?($GLOBALS['android']?2:1):0); ?>&j=<?php echo($_GET['j']); ?>&addposition=<?php echo($_GET['addposition']); ?>');/**/
$.get('?y=<?php e($_GET['y']); ?>&q=logout&s=<?php e(ssid); ?>&e=<?php e($page); ?>&width=' + ww + '&height=' + hh + '&mobile=<?php echo($GLOBALS['mobile']?($GLOBALS['android']?2:1):0); ?>&j=<?php echo($_GET['j']); ?>&addposition=<?php echo($_GET['addposition']); ?>', function(vystup){$('#html_fullscreen').html(vystup);});

}
//------------------------------------------------------------------------------------------------------------------
//function w_close(w_name){ alert(1); }

//function parseMapF(fff){ }







</script>
<?php eval(subpage("javascript")); ?>

<script type="text/javascript">

<?php

		$tdiff=time()-$GLOBALS['ss']["log_object"]->t;
		//e('alert('.$tdiff.');');
		if($tdiff<=60*2){
		    e('reloc();');
		}else{
		    e('reloc(1);');
		}

?>


</script>

</body>
</html>
