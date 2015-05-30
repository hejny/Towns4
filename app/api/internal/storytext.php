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
//----------------------------------------------------------------HTML
?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Text příběhů | Ukázka Towns API</title>
        <meta charset="UTF-8">
        <meta name="description" content="Zobrazení příběhů pouze jako text" />
        <?php TownsStyle(); ?><!--Zde funkce natvrdo vrátí styl, v Towns se místo toho použije externí .css soubor. -->


    </head>
<body>

<?php
//----------------------------------------------------------------Příběhy -načtení dat

$result = TownsApi('list', 'id,name,permalink,res,resurl,own_name',array('story',($_GET['author']?"own='".$_GET['author']."'":'')), 'starttime DESC',100,262);
//print_r($result);
$result = $result['objects'];

//----------------------------------------------------------------Příběhy - zobrazení
if($result)
foreach($result as $story) {


    $story['res'] = explode(':', $story['res'], 2);//res u každého příběhu je ve formátu html:text příběhu, proto je potřeba html: useknout.
    $story['res'] = $story['res'][1];

    //echo($story['res']);
    $story['res'] = '-' . $story['res'] . '-';


    foreach (array('img', 'iframe') as $tag) {
        $story['res'] = explode('<' . $tag, $story['res']);
        $i = 0;
        foreach ($story['res'] as &$tmp) {
            if ($i % 2) {
                $tmp = explode('>', $tmp, 2);
                $tmp = $tmp[1];
            }
            $i++;
        }
        $story['res'] = implode('', $story['res']);
    }

    $story['res'] = substr($story['res'], 1, strlen($story['res']) - 2);

    if(trim($story['res'])){
    ?>
            </p>

            <div style="text-align:center;font-weight: 600;">
            <div style="text-align:center;font-weight: 600;font-size: 1.41em;">
            <?= $story['name'] ?>
            </div>
            <?= $_GET['noname'] ? '' : $story['own_name'] ?>
            </div>

            <?= $story['res'] ?>

            </p>

    <?php
    }


}
?>


</body>
</html>


