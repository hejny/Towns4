<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2015
   _____________________________

   core/page/topinfo.php

   Pod odkaz na tutorial
*/

?>
<table height="<?php e($iconsize); ?>" width="100%" border="0" cellpadding="0" cellspacing="0">
<tr><td align="center">
<?php

e('<table style="background: rgba(30,30,30,0.9);border: 2px solid #222222;border-radius: 7px;" cellpadding="0" cellspacing="0" width="200" height="25"><tr><td align="center">');

if($GLOBALS['get']['quest'])$GLOBALS['ss']['quest']=$GLOBALS['get']['quest'];
if($GLOBALS['get']['questi'])$GLOBALS['ss']['questi']=$GLOBALS['get']['questi'];

$quests=sql_array("SELECT `quest`,`questi`,`time1`,`time2` FROM [mpx]questt WHERE `id`=".$GLOBALS['ss']['useid']." AND ".($GLOBALS['ss']['questi']?' `quest`='.$GLOBALS['ss']['quest'].' AND `questi`='.$GLOBALS['ss']['questi']:"!time2")." ORDER BY questi,time1 LIMIT 1");

$quest=$quests[0];
$time2=$quest[3];

if($quest){

	list($quest)=sql_array("SELECT `name`,`quest`,`questi`,`limit`,`cond1`,`cond2`,`description`,`image`,`author`,`reward` FROM [mpx]quest WHERE quest=".$quest['quest']." AND questi=".$quest['questi']);
	list($name,$id,$i,$limit,$cond1,$cond2,$description,$image,$author,$reward)=$quest;

	$url='e=content;ee=quest-mini;';
	ahref(contentlang($name),$url);

	if($id==1 and $i==1 and !$GLOBALS['ss']['qqklicked']){$GLOBALS['ss']['qqklicked']=1;
		click($url);
		//click($url,4);
	}


}else{
?>
<script type="text/javascript">
setTimeout(function(){
    $('#quest-mobile').css('display','none');
},10);
</script>
<?php
}

e('</td></tr></table>');




?>
</td>
</tr>
</table>


