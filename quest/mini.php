<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
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
function questbuilding($building,$qq=1){
	$building4=str_replace('}','4}',$building);
	$q=building($building)-1+1;
	questtask(lr('quest_build').' '.($qq==1?'':$qq.'x ').$building4.(($qq!=1 and $q>0 and $q<$qq)?'  '.lr('quest_left',"$q;$qq"):''),$q>=$qq);
}
}
//==============================

//window('{title_quest}',NULL,NULL,'chat');


if($GLOBALS['get']['quest'])$GLOBALS['ss']['quest']=$GLOBALS['get']['quest'];
if($GLOBALS['get']['questi'])$GLOBALS['ss']['questi']=$GLOBALS['get']['questi'];

//echo('questi='.$GLOBALS['ss']['questi']);br();
$quests=sql_array("SELECT `quest`,`questi`,`time1`,`time2` FROM [mpx]questt WHERE `id`=".useid." AND ".($GLOBALS['ss']['questi']?' `quest`='.$GLOBALS['ss']['quest'].' AND `questi`='.$GLOBALS['ss']['questi']:"!time2")." ORDER BY questi DESC,time1 LIMIT 1");
$quest=$quests[0];
$time2=$quest[3];

if($quest){
	if($GLOBALS['mobile']){
		e('<table width="100%"><tr><td align="center"><div style="width:95%;">');
		contenu_a();
	}
	
	//print_r($quest);

	list($quest)=sql_array("SELECT `name`,`quest`,`questi`,`limit`,`cond1`,`cond2`,`description`,`image`,`author`,`reward` FROM [mpx]quest WHERE quest=".$quest['quest']." AND questi=".$quest['questi']);

	list($name,$id,$i,$limit,$cond1,$cond2,$description,$image,$author,$reward)=$quest;

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
				$res=sql_1data('SELECT `res` FROM [mpx]objects WHERE id='.$image);
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
			$infob.=ahrefr(textbr(lr('quest_previous')),($GLOBALS['mobile']?'e=content;e':'').'e=quest-mini;quest='.($id).';questi='.($previous).';');
			//e(nbspo);
		}
		if($time2){//e(1);
			$infob.=($infob?nbspo:'').ahrefr(textbr(lr('quest_next')),($GLOBALS['mobile']?'e=content;e':'').'e=quest-mini;quest='.($id).';questi='.($next).';');
		}

		//echo($time2);
		//print_r($quest);
		if($GLOBALS['questq'] and !$time2){
			
			if($next){
				$infob.=($infob?nbspo:'').ahrefr(textbr(lr('quest_next')),($GLOBALS['mobile']?'e=content;e':'').'e=quest-mini;finish=1;');
			}else{
				$infob.=($infob?nbspo:'').ahrefr(textbr(lr('quest_finish')),($GLOBALS['mobile']?'e=content;e':'').'e=quest-mini;finish=1;');
			}


			if($GLOBALS['get']['finish']==1){//e('finite');
				unset($GLOBALS['ss']['quest']);
				unset($GLOBALS['ss']['questi']);
				sql_query('UPDATE [mpx]questt SET time2=\''.time().'\' WHERE id='.useid.' AND quest=\''.$id.'\' and questi=\''.$i.'\'');
				$reward=new hold($reward);
				$GLOBALS['ss']['use_object']->hold->addhold($reward);
				if($next){
					sql_query("INSERT INTO ".mpx."questt (`id`,`quest`,`questi`,`time1`,`time2`) VALUES ('".useid."','".$id."','".$next."','".time()."','')");
					/*?>
					<script>
					setTimeout(function(){
					<?php subjs('quest-mini'); ?>
					},10);
					</script>
					<?php*/
					click('e=quest-mini;quest='.$id.';questi='.$next);
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
