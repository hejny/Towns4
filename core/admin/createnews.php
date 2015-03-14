<?php
/* Towns4Admin, www.towns.cz 
   © Pavel Hejný | 2011-2014
   _____________________________

   admin/...

   Towns4Admin - Nástroje pro správu Towns
*/
//==============================


ob_end_flush();

?>
<h3>CreateNews</h3>
Zjištění a rozeslání vývojových novinek<br/>
<b>Upozornění: </b>Tato funkce rozešle e-maily všem hráčům.<br/>
<br>
<a href="?page=createnews&amp;action=test">Test</a><br>
<a href="?page=createnews&amp;action=send">Vygenerovat příspěvek =&gt; WP =&gt; E-mail</a>
<hr>

<?php
//error_reporting(E_ALL);
ini_set("max_execution_time","100");

//---------------------


if($_GET['action']){
	//---------------------------------------------------Zjištění, zda není něco nového v GITu

    $file=adminfile.'objects/lasttexts_news.txt';
    $lasttexts=unserialize(file_get_contents($file));
	if(!is_array($lasttexts))$lasttexts=array();
	$newtexts=array();

	$commits=shell_exec('git log 2>&1;');
	$commits=explode('commit',$commits);

	foreach($commits as $commit){
		if(strpos($commit,'text:')){
			$commits=explode('text:',$commit);
			$commit=trim($commits[1]);
			$commit=explode(nln,$commit);
			foreach($commit as $message){
				if(!in_array($message,$lasttexts)){

					$newtexts[]=trim($message);
				}
			}
		}
	}
	
	if(count($newtexts)){
		if($_GET['action']!='test'){
			fpc($file,serialize(array_merge($lasttexts,$newtexts)));
		}

		$content=lr('email_news');
		$content.='<ul>';
		foreach($newtexts as $text){
			$content.='<li>';
			$content.=nl2br(htmlspecialchars($text));
			$content.='</li>';
		}
		$content.='</ul>';
		$title=lr('email_news_title',date('j.n.Y'));

		textb($title);
		br();
		e($content);
		hr();

		if($_GET['action']!='test' /*and $GLOBALS['inc']['wp_xmlrpc']*/){
				ebr('WP');
                $result=wppost($title,$content,$GLOBALS['inc']['wp_categories_news']);
                br();
                e($result);
                hr();
				require(adminroot.'/mail.php');
        }

	}

	//print_r($newtexts);

}

?>
