<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
   _____________________________

   core/page/test.php

   testování
*/

$from=13756998;
e($from);br();
$from=topobject($from);
e($from);br();
e(id2name($from));br();




//==============================

//OLD USER SYSTEM -> NEW USER SYSTEM
/*foreach(sql_array('SELECT id,fbdata FROM [mpx]users WHERE fbdata!=\'\'') as $row){
	list($id,$fbdata)=$row;
	$fbdata=substr2($fbdata,':"1','"');
	sql_array('UPDATE  [mpx]users SET fbid=1'.$fbdata.' WHERE id='.$id,2);br();
	

}*/



/*//OLD USER SYSTEM -> NEW USER SYSTEM
foreach(sql_array('SELECT id,name,profile FROM [mpx]objects WHERE type=\'user\' AND ww=1 ORDER BY id') as $row1){
	list($id,$name,$profile)=$row1;
	$profile=str2list($profile);
	$email=$profile['mail'];
	if($email=='@')$email='';
	$sendmail=$profile['sendmail']?1:0;
	$password='';
	$fbid='';
	$fbdata='';

	foreach(sql_array('SELECT `method`,`key`,`text` FROM [mpx]login WHERE id='.$id) as $row2){
		list($method,$key,$text)=$row2;
		if($method=='towns'){
			$password=$key;
		
		}
		if($method=='facebook'){
			$fbid=$key;
			$fbdata=$text;
		}
	}

	$userid=sql_1data("SELECT MAX(id) FROM `[mpx]users`")-1+2;

	
	sql_query("INSERT INTO `[mpx]users` (`id`, `aac`, `name`, `password`, `email`, `sendmail`, `fbid`, `fbdata`, `created`)
VALUES ('$userid', '1', '".sql($name)."', '$password', '".sql($email)."', '$sendmail', '$fbid', '".sql($fbdata)."', now());",2);br();
	sql_query('UPDATE [mpx]objects SET userid='.$userid.' WHERE type=\'user\' AND id='.$id);br();

}*/
?>
