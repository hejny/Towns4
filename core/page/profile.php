<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2015
   _____________________________

   core/page/profile.php

   Okno profilu / statistik
*/
//==============================




window(lr('title_profile')/*,520,500*/);
$GLOBALS['ss']["profileid"]=0;
//r(imageurl('id_1'));

$q=submenu(array("content","profile"),array("stat_profile","stat_users"/*,"stat_buildings"*/,"stat_towns"),1);

if($GLOBALS['get']["id"]){$GLOBALS['ss']["profileid"]=$GLOBALS['get']["id"];$q=1;}

//print_r($GLOBALS['get']["id"]);


contenu_a();
if($q==1){
    if(!$GLOBALS['ss']["profileid"]){$GLOBALS['ss']["profileid"]=$GLOBALS['ss']['useid'];}
    profile($GLOBALS['ss']["profileid"]);
    
}elseif($q==3){$GLOBALS['stattype']='towns';eval(subpage("stat"));
   //Zatím nezobrazovat profil města//}elseif($q==3){$GLOBALS['stattype']='towns';eval(subpage("stat"));
}elseif($q==2){$GLOBALS['stattype']='users';eval(subpage("stat"));

}
contenu_b();

?>
