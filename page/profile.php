<?php
/* Towns4, www.towns.cz 
   © Pavel Hejný | 2011-2013
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
    if(!$GLOBALS['ss']["profileid"]){$GLOBALS['ss']["profileid"]=$GLOBALS['ss']["useid"];}
    profile($GLOBALS['ss']["profileid"]);
    
}elseif($q==3){$GLOBALS['stattype']='towns';eval(subpage("stat"));
   //Zatím nezobrazovat profil města//}elseif($q==3){$GLOBALS['stattype']='towns';eval(subpage("stat"));
}elseif($q==2){$GLOBALS['stattype']='users';eval(subpage("stat"));
//}elseif($q==5){$GLOBALS['where']="type='item' AND ww!=0 ";eval(subpage("stat"));
//}elseif($q==6){$GLOBALS['where']='1';eval(subpage("stat"));
}
contenu_b();
//----------------------------------------------------OLDPROFILE
/*$response=xquery("info");
//$profile=new profile($response["profile"]);
$array=$info["profile"]->vals2list();
$array2=$info["set"]->vals2list();
if($array["showmail"]){$array["mail"]=$array2["mail"];}
$array["showmail"]="";
echo("<table width=\"500\"><tr><td><table>");
//-----------
echo("<tr><td colspan=\"2\"><h3>".$response["name"]."<hr/></h3></td></tr>");
echo("<tr><td width=\"150\"><b>".lr("level").": </b></td><td width=\"150\">1</td></tr>");    
echo("<tr><td><b>".lr("útok").": </b></td><td>1</td></tr>");    
//-----------
echo("<tr><td colspan=\"2\"><hr/></td></tr>");
//-----------
foreach($array as $a=>$b){
    if(trim($b)){
        $pa=$a;
        $a=lr($a);
        $b=tr($b);
        //gender,age,mail,showmail,web
        if($pa=="gender"){if($b=="m"){$b=lr("Muž");}if($b=="f"){$b=lr("Žena");}}
        if($pa=="age"){$b=intval((time()-$b)/(3600*24*365.25),0.1);}
        if($pa=="mail"){$b="<a href=\"mailto: $b\">$b</a>";}
        if($pa=="web"){$b="<a href=\"http://$b/\">$b</a>";}
        if($pa!="description"){
            echo("<tr><td ><b>$a: </b></td><td>$b</td></tr>");    
        }else{
            echo("<tr><td colspan=\"2\"><code>$b</code></td></tr>");
        }
    }
}
//-----------
echo("</table></td><td align=\"right\" valign=\"top\">");
echo("obrázek");
echo("</td></tr></table>");
ahref("upravit profil","page=profile_edit",false);
echo("<br/>");
if($GLOBALS['ss']["logid"]==$GLOBALS['ss']["useid"]){
ahref("změnit heslo","page=password_edit",false);
}
//echo($profile->vals2str());
//print_r($profile->vals2list());*/
?>
