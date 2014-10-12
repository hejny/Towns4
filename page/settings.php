<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/page/settings.php


   Nastavení - spojení profile_edit a password_edit
*/
//==============================




window(lr('title_settings'));

$q=submenu(array("content","settings"),array("settings_town","settings_user"),1);


contenu_a();
if($q==1){
//===============================================================================TOWN
	//if($GLOBALS['get']['id'])$GLOBALS['ss']['profile_edit_id']=$GLOBALS['get']['id'];
	//if(!$GLOBALS['ss']['profile_edit_id'])$GLOBALS['ss']['profile_edit_id']=useid;
	//infob('{town_profile_info}');
	$id=useid;//$GLOBALS['ss']['profile_edit_id'];

	    $info=array();
	    $tmpinfo=xquery("info",$id);
	    $info["profile"]=new profile($tmpinfo["profile"]);
	    $info["name"]=$tmpinfo["name"];
	    $p=$info["profile"]->vals2list();
	    if(!$p["color"])$p["color"]='000000';

		if($_GET["profile_edit"]){
			//xreport();
			//$response=xquery("move",$_POST["move_x"],$_POST["move_y"]);  
			if($_POST["name"] and $info["name"]!=$_POST["name"]){
			xquery("profile_edit",$id,"name",$_POST["name"]);
			//print_r($GLOBALS['ss']["xresponse"]);
			xreport();
			$info["name"]=$_POST["name"];
	    }
	    //if($post["realname"]){xquery("profile_edit","realname",$post["realname"]);}
	    //if($post["gender"]){xquery("profile_edit","gender",$post["gender"]);}
	    //if($post["showmail"]){xquery("profile_edit","showmail",$post["showmail"]);}
	    //if($post["web"]){xquery("profile_edit","web",$post["web"]);}
	    //if($post["image"]){xquery("profile_edit","image",$post["image"]);}
	    if($_POST["description"] and $p["description"]!=$_POST["description"]){
			xquery("profile_edit",$id,"description",$_POST["description"]);
			xreport();
			$p["description"]=$_POST["description"];
		}
	    if($_POST["color"] and $p["color"]!=$_POST["color"]){
			xquery("profile_edit",$id,"color",$_POST["color"]);
			xreport();
			$p["color"]=$_POST["color"];
		}	    
	    
	}


	
	//realname,gender,age,showmail,web,description

	//print_r($array);
	?>

	<?php

	infob(lr('settings_town_info'));

	form_a(urlr('profile_edit=1'),'profile_edit');
	//<form id="login" name="login" method="POST" action="">
	?>

                


	<table>


	<tr><td><b><?php le('name'); ?>:</b></td><td><?php input_text("name",(!is_numeric($info["name"])?$info["name"]:'')); ?></td></tr>

	
	<?php /*
<tr><td><b>{color}:</b></td><td><?php input_color("color",$info["color"]); ?></td></tr>

 ?>
	<tr><td><b><?php le("realname"); ?>:</b></td><td><?php input_text("realname",$p["realname"]); ?></td></tr>
	<tr><td><b><?php le("gender"); ?>:</b></td><td><?php input_select("gender",$p["gender"],array(" "=>"---", "male"=>"Muž", "female"=>"Žena")); ?></td></tr>
	<tr><td><b><?php le("showmail"); ?>:</b></td><td><?php input_checkbox("showmail",$p["showmail"]);  le("Mail můžete změnit v nastavení."); ?></td></tr>
	<tr><td><b><?php le("web"); ?>:</b></td><td>http://<?php input_text("web",$p["web"]); ?></td></tr>
	<tr><td><b><?php le("image"); ?>:</b></td><td><?php input_text("image",$p["image"]); ?></td></tr>
	<?php */ ?>
	<tr><td><b><?php le('description'); ?>:</b></td><td><?php input_textarea("description",$p["description"],(!$GLOBALS['mobile']?44:'30'),17); ?></td></tr>


<tr><td><b><?php le('color'); ?>:</b></td><td>

<input type="hidden" name="color" id="color" value="<?php e($p["color"]); ?>" />
<p id="colorpickerHolder"></p>
	<script>
	$('#colorpickerHolder').ColorPicker({
	flat: true,
	color: '#<?php e($p["color"]); ?>',
	onChange: function (hsb, hex, rgb) {
		
		$('#color').val(hex);
	}});
	</script>
</td></tr>



	<tr><td colspan="2"><input type="submit" value="<?php le('submit_town_settings') ?>" /></td>
	</tr></table>

	<?php
	form_b();
	form_js('content','?e=settings&submenu=1&profile_edit=1',array('name','description','color'));
//======================================================================================
}elseif($q==2){
//==============================================================================USER
	if($_POST["name"] AND $GLOBALS['ss']["log_object"]->name!=$_POST["name"]){
		//e($info["name"].'!='.$_POST["name"]);
		$q=name_error($_POST["name"]);
		if(!$q){
		    $GLOBALS['ss']["log_object"]->name=$_POST["name"];
		    $GLOBALS['ss']["log_object"]->update();
		    
			success(lr('profile_username').' '.lr('settings_changed')); 
			if(is_numeric($GLOBALS['ss']["use_object"]->name)){
	
				$GLOBALS['ss']["use_object"]->name=$_POST["name"];
				$GLOBALS['ss']["use_object"]->update();
				success(lr('profile_townname').' '.lr('settings_created')); 
			}
		   	
		}else{
		   error($q); 
		}        

		//xquery("profile_edit",useid,"name",$_POST["name"]);
		//xquery("profile_edit",logid,"name",$_POST["name"]);
		xreport();
	}else{
		if(is_numeric($GLOBALS['ss']["log_object"]->name)){
			$q=true;
		}else{
			$q=false;
		}
	}

	if($_POST["oldpass"] or $_POST["newpass"] or $_POST["newpass"]){
	    if($post["newpass"]){
		//alert("{password_change}",1);
		//echo("<hr>");
		//r('hovno');
		   
		   xreport();
		$backup_use=$GLOBALS['ss']['useid'];
		xquery('login',$GLOBALS['ss']["logid"],'towns',$_POST["oldpass"]?$_POST["oldpass"]:$_POST["newpass"],$_POST["newpass"],$_POST["newpass2"]);
		a_use($backup_use);
		
	/*if(xsuccess() and !$q){
	  ?> 
	<script>
	setTimeout(function(){
	    w_close('content');
	},3000);
	</script>
	<?php
	}*/
		
		//alert("chobot",2);
		xreport();
	    }else{
		alert(lr('password_change_no_error'),2);
	    }
	}
	if($GLOBALS['ss']["logid"]!=$GLOBALS['ss']["useid"]){
	    //alert("{password_change_use_warning;".$info2["name"]."}",3);
	}

	//print_r($_POST);
	if($_POST["mail"]){
		    //e(111);
		    //$GLOBALS['ss']["log_object"]->profile->add('mail',$_POST["mail"]);
		    //$GLOBALS['ss']["log_object"]->profile->add('sendmail',$_POST["sendmail"]);
		    xquery("profile_edit",logid,"mail",$_POST["mail"]);
			xreport();
		    xquery("profile_edit",logid,"sendmail",$_POST["sendmail"]?'1':'0');
		    xquery("profile_edit",logid,"sendmail2",$_POST["sendmail2"]?'1':'0');
		    xquery("profile_edit",logid,"sendmail3",$_POST["sendmail3"]?'1':'0');
		    xquery("profile_edit",logid,"sendmail4",$_POST["sendmail4"]?'1':'0');
		    xquery("profile_edit",logid,"sendmail5",$_POST["sendmail5"]?'1':'0');
		    //$GLOBALS['ss']["log_object"]->update();
		   //success(lr('namecreated')); 
		}      

		//xquery("profile_edit",useid,"name",$_POST["name"]);
		//xquery("profile_edit",logid,"name",$_POST["name"]);
		xreport();
	//realname,gender,age,showmail,web,description
	//print_r($array);
	?>
	<form id="changepass" name="changepass" method="POST" action="" onsubmit="return false">
	<table>


	<?php if(true/*is_numeric($GLOBALS['ss']["log_object"]->name)*/){ ?>
	<tr><td><b><?php e('*');le('name'); ?>:</b></td><td><?php input_text("name",$_POST["name"]?$_POST["name"]:(!is_numeric($GLOBALS['ss']["log_object"]->name)?$GLOBALS['ss']["log_object"]->name:'')); ?></td></tr>
	<?php } ?>
	<tr><td><b><?php le("mail"); ?>:</b></td><td><?php input_text("mail",$GLOBALS['ss']["log_object"]->profile->ifnot('mail','@')); ?></td></tr>
	
	
	<tr><td colspan="2"><?php input_checkbox("sendmail",$GLOBALS['ss']["log_object"]->profile->ifnot('sendmail','1')); ?><b><?php le("sendmail"); ?></b></td></tr>
	<tr><td colspan="2"><?php input_checkbox("sendmail2",$GLOBALS['ss']["log_object"]->profile->ifnot('sendmail2','1')); ?><b><?php le("sendmail2"); ?></b></td></tr>
	<tr><td colspan="2"><?php input_checkbox("sendmail3",$GLOBALS['ss']["log_object"]->profile->ifnot('sendmail3','1')); ?><b><?php le("sendmail3"); ?></b></td></tr>
	
	<?php if(!is_numeric($GLOBALS['ss']["log_object"]->name)){ ?>
	<tr><td colspan="2"><?php input_checkbox("sendmail4",$GLOBALS['ss']["log_object"]->profile->ifnot('sendmail4','1')); ?><b><?php le("sendmail4"); ?></b></td></tr>
	<tr><td colspan="2"><?php input_checkbox("sendmail5",$GLOBALS['ss']["log_object"]->profile->ifnot('sendmail5','1')); ?><b><?php le("sendmail5"); ?></b></td></tr>
	
	
	<tr><td colspan="2"><?php br();info(lr("pass_info")); ?></td></tr>
	
	<?php } ?>
	
	<?php if(!nopass){ ?>
	<tr><td><b><?php e('*');le('oldpass'); ?>:</b></td><td><?php input_pass("oldpass",$_POST["oldpass"]); ?></td></tr>
	<?php } ?>

	
	<tr><td><b><?php e('*');le('newpass'); ?>:</b></td><td><?php input_pass("newpass",$_POST["newpass"]); ?></td></tr>
	<tr><td><b><?php e('*');le('newpass2'); ?>:</b></td><td><?php input_pass("newpass2",$_POST["newpass2"]); ?></td></tr>
	<tr><td colspan="2">*<?php le('required'); ?></td></tr>

	</table>
	<input type="submit" value="<?php le('submit_user_settings') ?>" />
	</form>
	<script type="text/javascript">
	//alert(1);
	
	$("#changepass").submit(function() {
	    //alert(1);
	    $.post('?y=<?php e($_GET['y']); ?>&e=settings&submenu=2',
		{   name: $('#name').val(),
		    mail: $('#mail').val(),
		    sendmail: $('input[name=sendmail]').attr('checked'), 
		    sendmail2: $('input[name=sendmail2]').attr('checked'),
		    sendmail3: $('input[name=sendmail3]').attr('checked'), 
		    <?php if(!is_numeric($GLOBALS['ss']["log_object"]->name)){ ?>
    		    sendmail4: $('input[name=sendmail4]').attr('checked'), 
    		    sendmail5: $('input[name=sendmail5]').attr('checked'), 
		    <?php }else{ ?>
    		    sendmail4: 1, 
    		    sendmail5: 1, 
		    <?php } ?>
		    oldpass: $('#oldpass').val(),
		    newpass: $('#newpass').val(),
		    newpass2: $('#newpass2').val()
		},
		function(vystup){$('#content').html(vystup);}
	    );
	    return(false);
	});
	</script>

	<?php
	if(!is_numeric($GLOBALS['ss']["log_object"]->name)){
    	if($GLOBALS['get']['fb_disconnect']){
    		sql_query("DELETE FROM [mpx]login WHERE method='facebook' AND id='".logid."'");
    		//success(lr('fb_disconnected'));
    	}
    
    
    	if(sql_1data("SELECT id FROM [mpx]login WHERE method='facebook' AND id='".logid."'")){
    		$data=unserialize(sql_1data("SELECT text FROM [mpx]login WHERE method='facebook' AND id='".logid."'"));
    	
    		le('fb_connected',$data['name']);br();
    		ahref(trr(lr('fb_disconnect'),15,3,'style="background: rgba(30,30,30,0.9);border: 2px solid #222222;border-radius: 2px;"'),'e=content;ee=settings;submenu=2;fb_disconnect=1');
    	}else{
    
    		if(defined('fb_appid') and defined('fb_secret'))
    		eval(subpage('login-fb_login'));
    
    	}
	}
	?>


<?php
    if(!is_numeric($GLOBALS['ss']["log_object"]->name)){
    	//------------------------------------------------description
    	hr();
    	    $info=array();
    	    $tmpinfo=xquery("info",logid);
    	    $info["profile"]=new profile($tmpinfo["profile"]);
    	    $info["name"]=$tmpinfo["name"];
    	    $p=$info["profile"]->vals2list();
    
    	if($_GET["profile_edit"]){
    
    	    if($_GET["description"] and $p["description"]!=$_GET["description"]){
    			xquery("profile_edit",logid,"description",$_GET["description"]);xreport();
    			$p["description"]=$_GET["description"];
    		}
    
    	    if($_POST["description"] and $p["description"]!=$_POST["description"]){
    			xquery("profile_edit",logid,"description",$_POST["description"]);xreport();
    			$p["description"]=$_POST["description"];
    		}  
    
    	}
    
    	form_a(urlr('profile_edit=1'),'profile_edit');
    	?>
    
                    
    	<table>
    	<tr><td><b><?php le('description'); ?>:</b></td></tr>
    	<tr><td><?php input_textarea("description",$p["description"],(!$GLOBALS['mobile']?52:'30'),17); ?></td></tr>
    
    	<tr><td colspan="2"><input type="submit" value="<?php le('submit_description') ?>" /></td>
    	</tr></table>
    
    	<?php
    	form_b();
    	form_js('content','?e=settings&submenu=2&profile_edit=1',array('description'));
    }
//======================================================================================
}
contenu_b(true);
?>
