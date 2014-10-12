<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/plus/index.php

   Okno kupování bonusových surovin
*/
//==============================


//require_once(core."text/func_core.php");

window(lr('title_plus'));
//infob('{plus_infob;'.$GLOBALS['ss']['use_object']->name.'}');


$q=submenu(array("content","plus-index"),array(/*"plus_pay",*/'plus_invite','plus_share',"plus_key"/**/),1,'plus');
//$q=$GLOBALS['ss']['submenu'];



//infob('');
//echo($q);

/*if($q==1){contenu_a();
//------------------------------------------------------------------PAY
    $icon=iconr('e=content;ee=hold-change;id='.$GLOBALS['hl'],'f_change',"{f_change}",35);
    ?>
    {plus_info}<?php e($icon); ?>
    <hr/>
    <table border="0" cellpadding="3" cellspacing="0" width="100%">
    <?php
    $wurl=str_replace('[world]',w,url);

    $i=0;foreach($GLOBALS['config']['plus'] as $id=>$row){$i++;
    $title=$row['title'];
    $credit=$row['credit'];
    $amount=$row['amount'];
    ?>

    <tr style="<?php e(($i%2)?'background: rgba(0,0,0,0.4)':'') ?>">
    <td width="100" align="left" valign="center">
    <a href="#" onclick="window.open('<?php e($wurl); ?>?y=<?php e($_GET['y']); ?>&amp;e=plus-paypal_psend&amp;first=<?php e($id); ?>&amp;second=<?php e(useid); ?>', '_blank', 'resizable=yes');">
    <h3><?php e($title); ?></h3>
    </a>
    </td><td align="left" valign="center">
    <a href="#" onclick="window.open('<?php e($wurl); ?>?y=<?php e($_GET['y']); ?>&amp;e=plus-paypal_psend&amp;first=<?php e($id); ?>&amp;second=<?php e(useid); ?>', '_blank', 'resizable=yes');">
    <?php ie($credit); ?> {res_<?php e(plus_res); ?>2} {plus_fromto} $<?php e($amount); ?>
    </a>
    </td>
    </tr>


    <?php } ?>
    </table>
    <?php
//------------------------------------------------------------------
contenu_b();}else*/if($q==1){contenu_a();
//------------------------------------------------------------------SHARE


br();le('plus_invite_info');br(2);


	if($_GET['invite']==1){
		
		
		
		$ids=explode(',',$_GET['invite_data']);
		$a=0;
		$b=0;
		foreach($ids as $id){
		    if(!xlog('fbinvite',$id)){
		        $a++;
		    }else{
		        $b++;
		    }
		}
		   //$a=1;
		
		$gold=(plus_invite-1+1)*$a;
		
		$GLOBALS['ss']['use_object']->hold->add(plus_res,$gold);
		
		js(subjsr('towns'));
		
		
		if($a){
		    success(lr('invite_success',$gold));
		
            if($b){
                blue(lr('invite_xsuccess'));
            }
		}else{
		    error(lr('invite_xerror'));
		}

		
		send_message(logid,$GLOBALS['inc']['write_id'],'Towns invited FB',$_GET['invite_data']);
		
		br();
		
		//$GLOBALS['ss']['use_object']->hold->add(plus_res,plus_share-1+1);
		//$GLOBALS['ss']["use_object"]->set->add("share_time",time());
		//js(subjsr('towns'));
	}


?>

<script>
 //-----------------------------------
 function sendRequest() {
   // Get the list of selected friends
   // Use FB.ui to send the Request(s)
   FB.ui({method: 'apprequests',
     title: '<?php le('invite_title'); ?>',
     message: '<?php le('invite_message'); ?>',
   }, callback);
 }

 function callback(response) {
    if(response.error_code){
        //alert('error');
    }else{
        console.log(response);
        if(typeof(response.request) !== 'undefined'){
        
        
          	 urlpart='?e=plus-index&submenu=1&invite=1&invite_data='+(response.to);
          	 
          	 //alert(123);
        	 $.get(urlpart, function(vystup){
        	    //alert(123);
        	    $('#content').html(vystup);
        	 });
        	 
        	 
        	 
            //alert('shared');
        }else{
            //alert('noselect');
        }
    }
   //console.log(response);
 }
 //-----------------------------------



</script>


<a href="#fbinvite" onclick="sendRequest();"><?php button(lr('invite_button')); ?></a>



<?php 

//------------------------------------------------------------------
contenu_b();}elseif($q==2){contenu_a();
//------------------------------------------------------------------SHARE

?>

<script>



clicktoshare=function(){

/*FB.ui(
   {
     method: 'feed',

     link: 'http://www.towns.cz/'.w.'/?ref=<?php e(logid); ?>'
   },
   function(response) {
     if (response && response.post_id) {

    //alert(1);
	 urlpart='?e=plus-index&shared=1';
    	 $.get(urlpart, function(vystup){$('#content').html(vystup);})


     } else {


     }
   }
 );*/
 FB.ui(
  {
    method: 'share',
    name: 'Towns 4',
    href: 'http://www.towns.cz/<?php e(w); ?>/?ref=<?php e(logid); ?>'
  },
  function(response) {
    if (response && !response.error_code) {
      
      	 urlpart='?e=plus-index&submenu=2&shared=1';
      	 
      	 //alert(123);
    	 $.get(urlpart, function(vystup){
    	    //alert(123);
    	    $('#content').html(vystup);
    	 });
      
    } else {
      
      
    }
  }
);




};

//-------------------------------



//-------------------------------

/*clicktoinvite=function(){

alert(FB.api);
FB.api(
    "/v2.1/me/invitable_friends",
    function (response) {
        console.dir(response)
        //alert(response.error.toSource());
      if (response && !response.error) {
        alert(response);

      }
    }
);*/


/*var friend_ids = "AVl69ekIl0UtRmCIMo8V3UrOC6-LtTHONiXeiray7lh6_QAGwKFmT6RTNan_D7jvfBP2908DIvcyNvlkiyQ5knwjqDYA_t_VcY5v4lfGSlY1sA";

FB.ui({
    method: 'apprequests',
    message: 'Come play Friend Smash with me!',
    to: friend_ids
  }, 
  function(){
        //handle result
  }
);


};*/
    

</script>


<?php

    br();e(lr('plus_share_info'));
    br();br();

//$GLOBALS['ss']["use_object"]->set->add("share_time",time());
$cooldown=-(time()-$GLOBALS['ss']["use_object"]->set->ifnot("share_time",0)-(3600*24*4));
if($cooldown>0){





	error(lr('share_error').' '.timecr(time()+$cooldown),2);


}else{
	if($_GET['shared']==1){
		success(lr('share_success'));
		
		//mail('ph@towns.cz','Towns shared FB','user: '.id2name(logid).nln.'townid: '.useid);
		send_message(logid,$GLOBALS['inc']['write_id'],'Towns shared FB','user: '.id2name(logid).nln.'townid: '.useid);
		
		$GLOBALS['ss']['use_object']->hold->add(plus_res,plus_share-1+1);
		$GLOBALS['ss']["use_object"]->set->add("share_time",time());
		js(subjsr('towns'));
	}


    //e('<div style="width:90%;">'.lr('plus_share_info').'</div>');
    
    br();br();


?>
<a href="#fbshare" onclick="clicktoshare();"><?php button(lr('share_button')); ?></a>
<?php

}

/*br();
?>
<a href="#fbinvite" onclick="clicktoinvite();"><?php button(lr('invite_button')); ?></a>
<?php*/




if(!$GLOBALS['mobile']){

br();
hr();
br();

   if($_POST['url']){
	success(lr('shareo_success'));
	send_message(logid,$GLOBALS['inc']['write_id'],'Towns shared','user: '.id2name(logid).nln.'townid: '.useid.nln.'url: '.$_POST['url']);
	//mail('ph@towns.cz','Towns shared','user: '.id2name(logid).nln.'townid: '.useid.nln.'url: '.$_POST['url']);
    }




    le('plus_shareo_info');
    br();br();




?>
<form id="shareo" name="shareo" method="POST" action="" onsubmit="return false">
<?php input_text("url",/*$_POST["url"],*/NULL,NULL,40); ?>
<br/>
<input type="submit" value="<?php le('shareo_ok'); ?>" />
</form>
<script>
$("#shareo").submit(function() {
    //alert(1);
    $.post('?y=<?php e($_GET['y']); ?>&e=plus-index',
        { url: $('#url').val()},
        function(vystup){/*alert(2);*/$('#content').html(vystup);}
    );
    return(false);
});
</script>
<?php
}
?>





<?php
//------------------------------------------------------------------
contenu_b();}elseif($q==3){contenu_a();
//------------------------------------------------------------------KEY

   $key='';
   //r($_POST);

   if($_POST['key']){

	$key=$_POST['key'];
	//e($key);

	list($keyx)=sql_array("SELECT `key`,`reward`,`id`,`time_create`,`time_used` FROM [mpx]key WHERE `key`='$key'");

	if(!$keyx){
		error(lr('key_error_none'));
	}elseif($keyx['time_used']){
		$key='';
		if($keyx['id']==logid){
			error(lr('key_error_usedx'));
		}else{
			error(lr('key_error_used',id2name($keyx['id'])));
		}
	}else{
		
		$reward=new hold($keyx['reward']);
		$GLOBALS['ss']['use_object']->hold->addhold($reward);
		sql_query("UPDATE [mpx]key SET `id`='".logid."',`time_used`='".time()."' WHERE `key`='$key'");

		//success($reward->textr(2));
		success(lr('key_success'));
		$reward->showimg();
		//mail('ph@towns.cz','Towns key','user: '.id2name(logid).nln.'townid: '.useid.nln.'key: '.$_POST['key']);
        send_message(logid,$GLOBALS['inc']['write_id'],'Towns key','user: '.id2name(logid).nln.'townid: '.useid.nln.'key: '.$_POST['key']);


		$key='';
	}



    }



    //e(rand(1,200));
    le('plus_key_info');
    br();br();

?>




<form id="keyform" name="keyform" method="POST" action="" onsubmit="return false">
<?php input_text("key",$key,NULL,60); ?>
<br/>
<input type="submit" value="<?php le('key_ok'); ?>" />
</form>
<script>
$("#keyform").submit(function() {
    //alert(1);
    $.post('?y=<?php e($_GET['y']); ?>&e=plus-index',
        { key: $('#key').val()},
        function(vystup){/*alert(2);*/$('#content').html(vystup);}
    );
    return(false);
});
</script>






<?php
//------------------------------------------------------------------
contenu_b(true);}    



?>
