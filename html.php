<?php/* Towns4, www.towns.cz    © Pavel Hejný | 2011-2013   _____________________________   core/html.php   V tomto souboru je html "obal".*///==============================//<html lang="<?php if($GLOBALS['ss']['lang']=='cz'){e('cs');}else{e($GLOBALS['ss']['lang']);}?" dir="ltr">//<meta charset="UTF-8">?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="<?php if($GLOBALS['ss']['lang']=='cz'){e('cs');}else{e($GLOBALS['ss']['lang']);}?>"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>Towns 4</title><meta name="author" content="hejny" /><!--<meta name="keywords" content="" />--><meta name="description" content="<?php if($GLOBALS['ss']['lang']=='cz'){e('Towns je nový typ strategické hry založené na vytváření vlastního města. Zažijte zbrusu novou atmosféru stavění města na různých terénech v doprovodu s elegantní grafikou. Bojujte a domlouvejte se se spoluhráči, získávejte suroviny, stavějte a dokonce si vytvářejte vlastní budovy!'); }else{ e('Towns is a new type of online strategy game based on building your own town. Experience a brand new atmosphere of your own town on various terrains, accompanied with elegant graphics. Fight and communicate with players, gain resources, build, and create your own buildings!'); } ?> " /><meta property="og:image" content="<?php e(imageurl('logo/share.png')); ?>" /><link rel="shortcut icon" href="../favicon.ico"><link href="favicon.ico" rel="../favicon.ico" type="image/x-icon" /><meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0"/><style type="text/css"><!--body {	background-color: #000000;	margin-left: 0px;	margin-top: 0px;	margin-right: 0px;	margin-bottom: 0px;}body,td,th {color: #cccccc;font-size: 14px;font-family: "trebuchet ms";}h1{font-size: 25px;}h3{font-size: 15px;}hr {border-color: #cccccc;height: 0.5px;}a{color: #cccccc;text-decoration: none;}--></style><?phpfunction script_($script){   // e('<script type="text/javascript" src="'.rebase(url.base.'/'.$script).'"></script>');   e('<script type="text/javascript">');   readfile($script);   e('</script>');}function css_($css){    //e('<link rel="stylesheet" href="'.rebase(url.base.'/'.$css).'" type="text/css" />');   e('<style>');   readfile($css);   e('</style>');}script_('lib/jquery/js/jquery-1.6.2.min.js');//script_('lib/jquery/js/jquery-ui-1.8.16.custom.min.js');//script_('lib/jquery/js/jquery-1.11.1.min.js');script_('lib/jquery/js/jquery-ui.js');//OLD//script_('lib/jquery/kemayo-maphilight-4cdc2e2/jquery.maphilight.min.js');//OLD//script_('lib/jquery/jquery.tinyscrollbar.min.js');script_('lib/jquery/jquery.fullscreen-min.js');script_('lib/jquery/jquery.mousewheel.js');script_('lib/jquery/jquery.scrollbar.js');script_('lib/jquery/colorpicker/js/colorpicker.js');//OLD//script_('lib/jquery/colorpicker/js/eye.js');//OLD//script_('lib/jquery/colorpicker/js/utils.js');//OLD//script_('lib/jquery/colorpicker/js/layout.js');css_('lib/jquery/colorpicker/css/colorpicker.css');script_('lib/jquery/jquery.ui.touch-punch.min.js');//OLD//script_('lib/jquerymobile/jquery.mobile-1.4.1.js');?> <script type="text/javascript"> fps=30;</script><?phpif(defined('analytics')){?><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script><script type="text/javascript">  var _gaq = _gaq || [];  _gaq.push(['_setAccount', '<?php echo(analytics); ?>']);  _gaq.push(['_trackPageview']);  (function() {    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;    ga.src = ('https:' == document.location.protocol ?  'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);  })();</script><?php }//-------------------------------------------------Vlastní statistikaqlog('html');//-------------------------------?></head><body><!--<a href="http://katalog.nene.cz/">Katalog internetových stránek</a>--><?php if($GLOBALS['inc']['google_page']){ ?><a href="https://plus.google.com/<?php e($GLOBALS['inc']['google_page']); ?>" rel="publisher"></a><?php } ?><?php if(logged() or 1){ /**/?><div id="fb-root"></div><script>(function(d, s, id) {  var js, fjs = d.getElementsByTagName(s)[0];  if (d.getElementById(id)) return;  js = d.createElement(s); js.id = id;  js.src = "//connect.facebook.net/cs_CZ/all.js#xfbml=1&appId=<?php e(fb_appid); ?>";  fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'facebook-jssdk'));</script><?php /**//*?>  <script>      window.fbAsyncInit = function() {        FB.init({          appId      : '<?php e(fb_appid); ?>',          xfbml      : true,          version    : 'v2.1'        });      };      (function(d, s, id){         var js, fjs = d.getElementsByTagName(s)[0];         if (d.getElementById(id)) {return;}         js = d.createElement(s); js.id = id;         js.src = "//connect.facebook.net/en_US/sdk.js";         fjs.parentNode.insertBefore(js, fjs);       }(document, 'script', 'facebook-jssdk'));    </script><?php */ } ?><script type="text/javascript">    z_index=1000;    <?php if(!debug){ ?>    $(document).ready(function(){       $(document).bind("contextmenu",function(e){              return false;       });    });    <?php } ?>    connectfps=4;    fps=12;   /*$(document).disableSelection();*/   //$('*:not(:input)').disableSelection();   /*setInterval(function() {         $('*:not(:input)').disableSelection();   }, 1000);*/</script><div style="width:100%;height:100%;" id="html_fullscreen"><table width="100%" height="100%"><tr><td align="center" valign="center"><?php include(root.core."/page/loading.php"); ?></td></tr></table></div><?php if(edit){ ?><iframe src="admin/?page=lang" width="100%" height="100%"></iframe><?php } ?><?phpif($_GET['ref']){$GLOBALS['ss']['ref']=$_GET['ref'];}//require(root.core.'/html_fullscreen_nologin.php');/*if ($GLOBALS['mobile']) {	if(logged()){		$page='-html_fullscreen';	}else{		$page='login-mobile';	}}else{	$page='-html_fullscreen';}*///$page='-html_fullscreen';$baseurl='?y='.$_GET['y']        .'&s='.ssid        .'&e=-html_fullscreen'        .'&width=\' + ww + \'&height=\' + hh + \''        .'&mobile='.($GLOBALS['mobile']?($GLOBALS['android']?2:1):0)        .($_GET['j']?'&j='.$_GET['j']:'')        .($_GET['addposition']?'&addposition='.$_GET['addposition']:'')        .($GLOBALS['mapgtx']?'&mapgtx='.$GLOBALS['mapgtx']:'')        .($GLOBALS['mapgty']?'&mapgty='.$GLOBALS['mapgty']:'')        ;?><script type="text/javascript">//------------------------------------------------------------------------------------------------------------------apptime=<?php e(filemtime(core.'/page/aac.php')); ?>;nacitacihtml=$('#html_fullscreen').html();logged=false;first=true;function reloc(first){if(first==undefined){first=0;}else{first=1;}//alert('reloc');<?php if($_GET['width']){e('ww='.$_GET['width'].';');}else{e('ww=$(window).width();');} ?><?php if($_GET['height']){e('hh='.$_GET['height'].';');}else{e('hh=$(window).height();');} ?>/*$(function(){});*/$('#html_fullscreen').html(nacitacihtml);$.get('<?php e($baseurl); ?><?phpecho($_POST['write_text']?'&write_text='.urlencode($_POST['write_text']):'');echo($_GET['unsubscribe']?'&unsubscribe='.urlencode($_GET['unsubscribe']):'');?>&first='+first, function(vystup){$('#html_fullscreen').html(vystup);});}//------------------------------------------------------------------------------------------------------------------//var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9\+\/\=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/\r\n/g,"\n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}x2xx = function(text){<?php    $i=0;    foreach($GLOBALS['ss']["vals_a"] as $val_a){        if($val_a!=nln and $val_a!='[' and $val_a!=']'){            e("text=text.split('".addslashes($val_a)."').join('".$GLOBALS['ss']["vals_bb"][$i]."');");        }        $i++;    }?>return(text);}//-------------------------------------------------function register(username,password,email,sendmail){//alert(username+','+password+','+email+','+sendmail);//alert(encodeBase64(username));<?php if($_GET['width']){e('ww='.$_GET['width'].';');}else{e('ww=$(window).width();');} ?><?php if($_GET['height']){e('hh='.$_GET['height'].';');}else{e('hh=$(window).height();');} ?>register_username=$('#register_username').val();register_password=$('#register_password').val();register_email=$('#register_email').val();register_sendmail=$("#register_sendmail").is(':checked') ? true : false ;$('#html_fullscreen').html(nacitacihtml);username=x2xx(username);password=x2xx(password);email=x2xx(email);/*username=username?Base64.encode(username):'';password=password?Base64.encode(password):'';email=email?Base64.encode(email):'';*/$.get('<?php e($baseurl); ?>&q=register,'+(username)+','+(password)+','+(email)+','+(sendmail)+'&register_try=1', function(vystup){    $('#html_fullscreen').html(vystup);        $('#register_username').val(register_username);    $('#register_password').val(register_password);    $('#register_email').val(register_email);    $("#register_sendmail").attr('checked', register_sendmail);    //alert(register_sendmail);    });/**/}//------------------------------------------------------------------------------------------------------------------function logout(){//alert('logout');logged=false;<?php if($_GET['width']){e('ww='.$_GET['width'].';');}else{e('ww=$(window).width();');} ?><?php if($_GET['height']){e('hh='.$_GET['height'].';');}else{e('hh=$(window).height();');} ?>/*$(function(){});*/$('#html_fullscreen').html(nacitacihtml);$.get('<?php e($baseurl); ?>&q=logout', function(vystup){$('#html_fullscreen').html(vystup);});}//------------------------------------------------------------------------------------------------------------------//function w_close(w_name){ alert(1); }//function parseMapF(fff){ }</script><?php eval(subpage("javascript")); ?><script type="text/javascript"><?php		$tdiff=time()-$GLOBALS['ss']["log_object"]->t;		//e('alert('.$tdiff.');');		//if($tdiff<=60*2){		//    e('reloc();');		//}else{		    e('reloc(1);');		//}?></script></body></html>