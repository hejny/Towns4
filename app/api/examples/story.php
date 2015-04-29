<?php
/**
 * Ukázková Towns API Aplikace - Odkazy na budovy, terény
 *
 * @copyright 2015 Towns.cz
 * @link http://api.towns.cz/
 * @author     Pavol Hejný
 * @version    1.0
 *
 */

if(!isset($_GET['onlypage']))
echo '<div class="pageDescription">Zobrazení příběhů a odkazů na ně</div>';


//----------------------------------------------------------------CSS
?>
<style type="text/css">
    .karta {

        display:inline-block;
        width:262px;
        height:162px;
        overflow:hidden;

        border-radius: 5px;

        border:#000000 0px solid;
        margin: 4px 4px 4px 4px;
        padding: 4px 4px 4px 4px;
        color: #000000;
    }
    .kartaname {
        display:inline-block;
        width:100%;
        font-size:1.2em;
        height:25px;
        overflow-x:hidden;
        overflow-y:hidden;
        text-align:center;
        background-color: rgba(255,255,255,0.5);
        border-radius: 4px;
    }
    .kartadesc {
        text-align: justify;
        padding: 3%;
        font-size: 14px;

        height:162px;
        overflow:hidden;
    }

    .author {
    border:#000000 2px solid;
    border-radius: 22px;
        background-color: #000000;
    }
</style>

<?php
//----------------------------------------------------------------Funkce na zkrácení slov
function truncate($text, $length) {
    $length = abs((int)$length);
    if(strlen($text) > $length) {
        $text = preg_replace("/^(.{1,$length})(\s.*|$)/s", '\\1...', $text);
    }
    return($text);
}
//----------------------------------------------------------------Příběhy -načtení dat

/* Funkce list vypíše:
 * ID, jméno, text příběhu, první obrázek v příběhu, jméno autora, url na avatar autora
 * U všech příběhů
 * Výsledek seřadí sestupně podle času vzniku
 * Omezí počet výsledků na 100
 * A všechny obrázky (URL na ně) budou mít 262 pixelů na šířku
 * Výsledek vrátí v asociovaném poli pod klíčem 'objects'
 * */
$result = TownsApi('list', 'id,name,res,resurl,own_name,own_avatar','story', 'starttime DESC',100,262);
//print_r($result);
$result = $result['objects'];

//----------------------------------------------------------------Příběhy - zobrazení
if($result)
foreach($result as $story){



    $story['res'] = explode(':',$story['res'],2);//res u každého příběhu je ve formátu html:text příběhu, proto je potřeba html: useknout.
    $story['res']=$story['res'][1];
    $story['res']=strip_tags($story['res']);//Zobrazení pouze textu, ne html tagy
    $story['res']=truncate($story['res'],140);//Zobrazení pouze 140 znaků

    if($story['resurl'])
        $story['res']='<img src="'.$story['resurl'].'" width="100%" /><br>'.$story['res'];



?>
<a href="<?=$_SESSION['townsapi_url'].'/'.$story['id']?>" target="_blank"
    ><div class="karta" style="background-color:#cccccc;">

            <div style="position:absolute;">
                <div style="position:relative;left:216px;top:132px;">

                    <img src="<?=$story['own_avatar']?>" alt="<?=htmlentities($story['own_name'])?>" alt="<?=htmlentities($story['own_name'])?>" width="40" class="author" />

                </div>
            </div>

            <div class="kartaname"><?=$story['name']?></div>

            <div class="kartadesc"><?=$story['res']?></div>

</div></a><?php


}




