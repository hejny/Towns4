<?php
require_once('../inc.php');
page(lr('app_projects_trelloimporter'),lr('app_projects_trelloimporter_description'));

//Není potřeba autorizace
//app_auth('projects/trelloimport');


//<a onclick="window.open('', '', 'width=600, height=500');">Start</a>
//https://trello.com/1/connect?key=9791836da2739b4a70b8036690080168&name=townsgame&response_type=token&expiration=never&scope=read,write

if($_GET['import']){

$alltags=array('text','startx','stopx','start','stop','private','author','inbox');
$auth='key='.$GLOBALS['inc']['trello_key'].'&token='.$GLOBALS['inc']['trello_token'];

//----------------------------------------------------------------Zpracování všech tabulí
//echo('https://api.trello.com/1/members/me/boards?'.$auth );
$boards=file_get_contents('https://api.trello.com/1/organizations/townsgame/boards/?filter=open&'.$auth);
$boards=json_decode($boards);
//print_r($boards);

foreach($boards as $board){
    $name=$board->name;
    $name=trim($name);
    //echo($name);
    if(substr($name,0,1)=='/' or substr($name,0,2)=='./'){
        //--------------------------------Zpracování tabule - jméno projektu
        $project=false;
        $phase=0;
        $group='';

        $name=explode('/',$name);
        if(count($name)==3) {
            list($aac, $group, $project) = $name;
            $aac=trim($aac);
            $group=trim($group);

            //--------Etapa projektu
            // todo PH naučit se pořádně regulární výrazy + nahrazování a udělat pomocí toho
            //preg_match('\(d\)$',$group)
            $group=explode('(',$group,2);
            if(count($group)==2){
                list($group,$phase)=$group;
                $phase=trim($phase,')');
                $phase=$phase-1+1;
            }else{
                $group=$group[0];
            }
            //--------
            if($aac=='.'){$aac=true;}else{$aac=false;}

        }elseif(count($name)==2){
            list($aac, $project) = $name;
        }


        if($project){
            $project=trim($project);
            e('<b>'.$project.'</b>');
            if($group) {
                br();le('app_projects_group');e(nln);
                e($group);
            }
            if($phase) {
                e(','.nln);le('app_projects_phase');e(nln);
                e($phase);
            }
            br();
            //----------------Zpracování tabule - Vytvoření projektu v DB

            $projectid=sql_1number('SELECT id FROM [mpx]projects WHERE trelloid='.sqlx($board->id));
            if($projectid){
                sql_update('projects',"id='$projectid'",array(
                    'name' => $project,
                    'group' => $group,
                    'phase' => $phase
                ));
            }else {
                $projectid = sql_1number('SELECT max(id) FROM [mpx]projects') + 1;
                sql_insert('projects', array(
                    'id' => $projectid,
                    'trelloid' => $board->id,
                    'name' => $project,
                    'group' => $group,
                    'phase' => $phase
                ));
            }
            //----------------Zpracování tabule - Štítku projektu - Smazání z databáze
            //$projecttags=sql_array('SELECT tag,value FROM [mpx]projects_tags WHERE projectid='.$projectid);
            sql_query('DELETE FROM [mpx]projects_tags WHERE projectid='.$projectid );

            //----------------Zpracování tabule - Štítku projektu - Nové
            //e($board->id);
            $board=file_get_contents('https://trello.com/1/boards/'.$board->id.'?cards=open&'.$auth);//&lists=open
            //echo($board);
            $board=json_decode($board);
            $projecttags=false;

            foreach($board->cards as $card){

                //e(serialize($card));
                $text=$card->name.' '.$card->decs;

                foreach($alltags as $tag){
                    //ebr($tag);
                    if(substr($text,0,strlen($tag))==$tag){

                        $value=substr($text,strlen($tag));
                        $value=trim($value);
                        $value=trim($value,':');
                        $value=trim($value);
                        if($tag=='start' or $tag=='startx' or $tag=='stop' or $tag=='stopx'){

                            $value=trim($value,'.');

                            if(preg_match('/^([0-9]{4})$/',$value)){
                                $value='1.6.'.$value;

                            }
                            if(preg_match('/^([0-9]{1,2})\.([0-9]{4})$/',$value)){
                                $value='15.'.$value;

                            }

                            $value=strtotime($value);
                            $value = date('Y-m-d H:i:s',$value);
                        }

                        if($tag=='inbox'){
                            $value = $card->idList;
                        }



                        //------------------INSERT projects_tags
                        if($tag) {
                            sql_insert('projects_tags', array(
                                'projectid' => $projectid,
                                'tag' => $tag,
                                'value' => $value,
                                'pos' => $card->pos
                            ));
                        }
                        //------------------

                        $projecttags=true;
                        echo("<a title=\"".addslashes(htmlspecialchars($value))."\">[$tag]</a>");

                        break;
                    }
                }



            }

            if($projecttags)echo('<br>');


            //----------------
        }
        //--------------------------------
    }

}

}else{
    e('<a href="?import=1">'.lr('app_projects_trelloimporter_start').'</a>');
}


//----------------------------------------------------------------
/*$return=post_request(
    'https://trello.com/1/cards?key=9791836da2739b4a70b8036690080168&token=6ca15a73ca3536fe6c1fe4fc00263b5b98062624f405269c013ac1a72dce8271'
    ,
    array(  'idList'=>  '54c6268dc5692024fd6da8f9',
            'name'=>    'ahoj',
            'desc'=>    'bhoj'
        )

);


&key=9791836da2739b4a70b8036690080168&token=6ca15a73ca3536fe6c1fe4fc00263b5b98062624f405269c013ac1a72dce8271
https://api.trello.com/1/members/me/boards
https://trello.com/1/boards/54c62682d001a08acc99ae66?cards=open&lists=open

*/




page_end();


?>






