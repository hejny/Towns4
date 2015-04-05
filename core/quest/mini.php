<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2015
   _____________________________

   core/page/chat.php

   Quest -obal
*/
//==============================
if(!function_exists('questtask')){
function questtask($name,$q){
	if($q){
		imge('quest/quest_finished.png',lr('quest_finished'),17,17);
	}else{
		imge('quest/quest_nonefinished.png',lr('quest_nofinished'),17,17);
		$GLOBALS['questq']=false;
	}
	tee($name,13);
	br();
}
function questbuilding($building,$qq=1,$onlyready=0){
	$building4=str_replace('}','4}',$building);
	$q=building($building,$onlyready)-1+1;
	questtask(lr('quest_build').' '.($qq==1?'':$qq.'x ').$building4.(($qq!=1 and $q>0 and $q<$qq)?'  '.lr('quest_left',"$q;$qq"):''),$q>=$qq);
}
}
//==============================

//window('{title_quest}',NULL,NULL,'chat');


if($GLOBALS['get']['quest'])$GLOBALS['ss']['quest']=$GLOBALS['get']['quest'];
if($GLOBALS['get']['questi'])$GLOBALS['ss']['questi']=$GLOBALS['get']['questi'];

//echo('questi='.$GLOBALS['ss']['questi']);br();
$quests=sql_array("SELECT `quest`,`questi`,`time1`,`time2` FROM [mpx]questt WHERE `id`=".$GLOBALS['ss']['useid']." AND ".($GLOBALS['ss']['questi']?' `quest`='.$GLOBALS['ss']['quest'].' AND `questi`<='.$GLOBALS['ss']['questi']:"!time2")." ORDER BY questi DESC,time1 LIMIT 1");
$quest=$quests[0];
$time2=$quest[3];

if($quest){
	if($GLOBALS['mobile']){
		e('<table width="100%"><tr><td align="center"><div style="width:95%;">');
		contenu_a();
	}
	
	//print_r($quest);

	list($quest)=sql_array("SELECT `name`,`quest`,`questi`,`limit`,`cond1`,`cond2`,`description`,`helpids`,`image`,`author`,`reward` FROM [mpx]quest WHERE quest=".$quest['quest']." AND questi=".$quest['questi']);

	list($name,$id,$i,$limit,$cond1,$cond2,$description,$helpids,$image,$author,$reward)=$quest;




	$GLOBALS['questq']=true;


	if(!$GLOBALS['mobile'])e('<div style="width:449px;height:150px;overflow:hidden;">');
	?>
	

	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
	
	<td rowspan="3" width="<?php if($image and false)e(70); else e(0);?>" height="137"  valign="middle">
	<?php
		if($image){


			if(is_numeric($image)){
				if(!defined("func_map"))require(root.core."/func_map.php");
				$res=sql_1data('SELECT `res` FROM `[mpx]pos_obj` WHERE id='.$image);
				//e(modelx($res));
				?><img src="<?php e(modelx($res)); ?>" height="122" alt="<?php e($name); ?>" /><?php /**/
				//iprofile($image);
			}else{
				list($width, $height, $type, $attr) = getimagesize(imageurl($image));
				//e($image);
				if(strpos($image,'icons')!==false){
					if($width>35){$width=35;}else{$width=false;}//e(1);
				}else{
					if($width>122){$width=122;}else{$width=false;}//e(2);
				}
				imge($image,$name,$width);
			}


			//textb('{quest_reward}');
			if(!$time2){
				if($id!=1)showhold($reward,false);
			}
		}
		 ?>
	</td>
	<td align="left" valign="top"><?php
		//<b><?php echo($name); </b><br/>
		window(contentlang($name),NULL,NULL,'quest-mini');

		e(contentlang($description));
		//e(rand(1,999));

?>
</td>
</tr>
<tr>
<td align="left" valign="bottom" height="10">
<?php
		
			if(!$time2 and !$image){
				if($id!=1)showhold($reward);
			}


		if(substr($cond2,0,1)=='{'){
			$cond2x=explode(',',$cond2);
			$cond2='';
			foreach($cond2x as $building){
				$building4=str_replace('}','4}',$building);
				$cond2.="\$q=building('$building');
				questtask('".lr('quest_build')." $building4',\$q);";
			}
		}

		//echo($cond2);
		eval($cond2);//echo(1);
		
		$infob='';

		$next=sql_1data('SELECT questi FROM [mpx]quest WHERE quest=\''.$id.'\' and questi>=\''.($i+1).'\' ORDER BY questi LIMIT 1');
		$previous=sql_1data('SELECT questi FROM [mpx]quest WHERE quest=\''.$id.'\' and questi<=\''.($i-1).'\' ORDER BY questi DESC LIMIT 1');


		if($previous){
			$infob.=ahrefr(textbr(lr('quest_previous')),($GLOBALS['mobile']?'e=content;e':'').'e=quest-mini;quest='.($id).';questi='.($previous).';'.js2('removeallhelp()'));
			//e(nbspo);
		}
		if($time2){//e(1);
			$infob.=($infob?nbspo:'').ahrefr(textbr(lr('quest_next')),($GLOBALS['mobile']?'e=content;e':'').'e=quest-mini;quest='.($id).';questi='.($next).';'.js2('removeallhelp()'),NULL,NULL,'quest_finish');
		}

		//echo($time2);
		//print_r($quest);
		if($GLOBALS['questq'] and !$time2){
			
			if($next){
				$infob.=($infob?nbspo:'').ahrefr(textbr(lr('quest_next')),($GLOBALS['mobile']?'e=content;e':'').'e=quest-mini;finish=1;'.js2('removeallhelp()'),NULL,NULL,'quest_finish');
			}else{
				$infob.=($infob?nbspo:'').ahrefr(textbr(lr('quest_finish')),($GLOBALS['mobile']?'e=content;e':'').'e=quest-mini;finish=1;'.js2('removeallhelp()'),NULL,NULL,'quest_finish');
			}


			if($GLOBALS['get']['finish']==1){//e('finite');
				unset($GLOBALS['ss']['quest']);
				unset($GLOBALS['ss']['questi']);
				sql_query('UPDATE [mpx]questt SET time2=\''.time().'\' WHERE id='.$GLOBALS['ss']['useid'].' AND quest=\''.$id.'\' and questi=\''.$i.'\'');
				$reward=new hold($reward);
				$GLOBALS['ss']['use_object']->hold->addhold($reward);
				if($next){
					sql_query("INSERT INTO [mpx]questt (`id`,`quest`,`questi`,`time1`,`time2`) VALUES ('".$GLOBALS['ss']['useid']."','".$id."','".$next."','".time()."','')");
					/*?>
					<script>
					setTimeout(function(){
					<?php subjs('quest-mini'); ?>
					},10);
					</script>
					<?php*/
					click('e=quest-mini;quest='.$id.';questi='.$next.';'.js2('removeallhelp()'));
				}
			}

		}
?>
</td>
</tr>
<tr>
<td align="left" valign="bottom">
<?php
		if($infob){
			infob($infob/*.("($previous /[$i]/ $next)")*/);
		}

		?>
	</td>
	<tr>
	</tr>
	</table>




	

<?php



	//---------------------------------------------------------------------------------------
	?>
	<style type="text/css">
	<!--
	.helpnumber {
		
		width:30px;
		height:30px;
		border:#ff0000 2px solid;
		border-radius: 22px;
		background-color: #000000;
		padding: 4px 4px 4px 4px;
		text-align:center;
	}
	-->
	</style>
	<?php
	$helpidsp=$helpids;
	$helpids=explode(',',$helpids);	
	$offset_number=0;

	if($time2 or $GLOBALS['questq']){
		$snumber=count($helpids)+1;
		$helpids=array();
	}else{
		$snumber=1;
		if($helpid){//$helpid může být definované v cond2
			$offset_number=$helpid-1;	
		}
	}
	
	if($helpidsp!==''){
		$helpids=array_merge($helpids,array('quest_finish'));
	}

	//print_r($helpids);
	//-----------
	e('<script>
		alerthelp=false		

		if(typeof removeallhelp != "undefined")removeallhelp();
		setTimeout(function() {');
	
	//-------------removeallhelp
	e('removeallhelp = function(){');
	e('if(alerthelp)alert("remove");');
	$number=$snumber;
	foreach($helpids as $helpid){$helpid=trim($helpid);if($helpid){
		e('clearInterval(interval_'.$number.');');
		e('$(\'#helpnumber_'.$number.'\').remove();');
		e('$(\''.($helpid).'\').unbind(\'mousedown\');');
	$number++;}}
	e('
	document.aachelp='.($snumber+$offset_number).';
	document.aachelpp=0;
	}
	removeallhelp();');
	//-------------

 	$rand=rand(1,999);
	//e('alert("create");');
	$number=$snumber;
	foreach($helpids as $helpid){if($helpid){
	
		//var position = $('#<?php e($helpid); ? >').position();
		//alert(position.top);
		//$('#helpnumber_<?php e($helpid); ? >').css('left',position.left);
		//$('#helpnumber_<?php e($helpid); ? >').css('top',position.top);
		?>

		html='<div id="helpnumber_<?php e($number); ?>" baseid="<?php e($helpid); ?>" class="helpnumber" style="position:absolute;left:100px;top:100px;z-index:<?php e(100000-$number); ?>" lr="-1" dragging="2" lasttime="0" ><?php /*tee($number,30);*/imge('icons/click.png',lr('click_here'),40); ?></div>';
		$('#windows').append(html);



		$('#helpnumber_<?php e($number); ?>').draggable(
			{
				start: function() {
					$('#helpnumber_<?php e($number); ?>').attr('dragging','1');
				},
				stop: function() {
					$('#helpnumber_<?php e($number); ?>').attr('dragging','2');
				}
			}
		);
		var interval_<?php e($number); ?>=setInterval(function() {
			
			if($('#helpnumber_<?php e($number); ?>').attr('dragging')=='2'){


				baseid=$('#helpnumber_<?php e($number); ?>').attr('baseid');

				if(<?php e($number); ?>==document.aachelp){
					
					

					if($('#'+baseid).length==0){


							if(<?php e($number); ?>==document.aachelp)
							if($('#'+baseid).length==0){
							    	if(alerthelp)alert(baseid+' not exist '+document.aachelp);
								if(document.aachelp==<?php e($number); ?> && document.aachelpp==document.aachelp){
									document.aachelp--;
									if(alerthelp)alert(document.aachelp+'--');
								}
							}else{
							    	if(alerthelp)alert(baseid+' už exists!');
							}


					}else{


						if(document.aachelpp!=<?php e($number); ?>){
							if(alerthelp)alert('set p <?php e($number); ?>');
							document.aachelpp=<?php e($number); ?>;
						}
						
						var position = $('#'+baseid).offset();



						//------------------------------------------------------------------------------Click Bind----


						if($('#<?php e($helpid); ?>').length>0){
						if($('#<?php e($helpid); ?>').attr('binded')!=1){


							if(alerthelp)alert('binding to <?php e($number); ?> <?php e($helpid); ?>');

							if(alerthelp)
							$('#<?php e($helpid); ?>').append('click');


							$('#<?php e($helpid); ?>').bind('mousedown', function() {

								if(alerthelp)alert('click'+document.aachelp+'==<?php e($number); ?>');
								if(document.aachelp==<?php e($number); ?>){
									document.aachelp++;
									if(alerthelp)alert(document.aachelp+'++');
								}else{
									if(alerthelp)alert(document.aachelp+' - teď ne');
								}


							});



							$('#<?php e($helpid); ?>').attr('binded',1);
						}}

						//-------------------------------------------------------------------------------

						lasttime=parseFloat($('#helpnumber_<?php e($number); ?>').attr('lasttime'));

						aactime = new Date();
						aactime = aactime.getTime();

						if(lasttime+(1000/fps_quick)<aactime){
							ax=position.left-30;
							ay=position.top-30;

							if(ax==-30 && ay==-30){
								
							}else{
				

								x=parseFloat($('#helpnumber_<?php e($number); ?>').css('left'))-ax;
								y=parseFloat($('#helpnumber_<?php e($number); ?>').css('top'))-ay;
								lr=parseFloat($('#helpnumber_<?php e($number); ?>').attr('lr'));


								//q=0.985+(Math.sqrt(Math.abs(x)+Math.abs(y))/200);
								//k=8/(q*q*q);

                                q=1.1+(Math.sqrt(Math.abs(x)+Math.abs(y))/200);
								k=8/(q*q*q);

								x=(x/q)+((lr*y)/k);
								y=(y/q)+((-lr*x)/k);

								x=x+ax;
								y=y+ay;

								b=20;

								if(x<0){x=0;lr=-lr;}
								if(y<0){y=0;lr=-lr;}
								if(x><?= $GLOBALS['ss']['screenwidth'] ?>-b){x=<?= $GLOBALS['ss']['screenwidth'] ?>-b;lr=-lr;}
								if(y><?= $GLOBALS['ss']['screenheight'] ?>-b){y=<?= $GLOBALS['ss']['screenheight'] ?>-b;lr=-lr;}

								$('#helpnumber_<?php e($number); ?>').css('left',(x)+'px');
								$('#helpnumber_<?php e($number); ?>').css('top',(y)+'px');
								$('#helpnumber_<?php e($number); ?>').attr('lr',lr);
							}

							$('#helpnumber_<?php e($number); ?>').attr('lasttime',aactime);
						}


					}
				}else{
					$('#helpnumber_<?php e($number); ?>').css('left',-100);
					$('#helpnumber_<?php e($number); ?>').css('top',500);
					$('#helpnumber_<?php e($number); ?>').attr('not',1);
				}

			}
		},10);
        /**/

		<?php
	
		$number++;

	}}



	e('	},200);
	</script>');

	//---------------------------------------------------------------------------------------










	if(!$GLOBALS['mobile'])e('</div>');

	if($GLOBALS['mobile']){
		e('</div></td></tr></table>');
		contenu_b();
	}

}else{
?>
<script>
setTimeout(function(){
    w_close('quest-mini');
    w_close('window_quest-mini');
    $('#dockbutton_tutorial').css('display','none');
},10);
</script>
<?php
}
?>
