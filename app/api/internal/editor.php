<?php
/**
 * Ukázková Towns API Aplikace - Editor
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
    <title>Editor budov | Ukázka Towns API</title>
    <meta charset="UTF-8">
    <meta name="description" content="Ukázkový příklad, jakým způsobem pracuje editor budov." />
    <?php TownsStyle(); ?><!--Zde funkce natvrdo vrátí styl, v Towns se místo toho použije externí .css soubor. -->
</head>
<body>


<?php
//----------------------------------------------------------------Kontrola přihlášení

//Při umístění aplikace na server Towns si můžete nastavit, že bude přístupná pouze po přihlášení. Každá aplikace má své pevné URL, které je dostupné každému a proto je dobré odchytit případ nepřihlášeného hráče.
if (!TownsLogged) {
    die('<div class="error">Tato aplikace vyžaduje přihlášení.</div></body></html>');
}

//----------------------------------------------------------------Základní info


    if(!$_GET['id']) {

        ?>
        Jakmile aplikaci umístíte do Towns, editor vždy dostane v GET Parametru id objektu, který má upravovat.<br>
        V testovacím prostředí to tak není, proto musíte id zadat ručně:

        <form method="post">
            <input type="number" value="<?= $_GET['id'] ?>">
            <input type="submit" value="Spustit editor s tímto ID">
        </form>


<?php

//----------------------------------------------------------------Zobrazení seznamu náhodných budov

        echo('Některé Vaše náhodné budovy:<br>');

        $buildings = TownsApi('list', 'id,_name', array('building', 'mybuildings'), 'rand', 10);
        $buildings = $buildings['objects'];

        foreach ($buildings as $building) {
            echo('<a href="?id=' . $building['id'] . '">' . $building['_name'] . ' (' . $building['id'] . ')</a><br>');

        }

//----------------------------------------------------------------
}else{
//----------------------------------------------------------------Změna budovy
    if($_POST['edit']) {

        TownsApi($_GET['id'].'.edit', 'name', $_POST['name']);
        TownsApi($_GET['id'].'.edit', 'res', $_POST['res']);
        TownsApi($_GET['id'].'.edit_set', 'editor_data', $_POST['editor_data']);

    }

//----------------------------------------------------------------Formulář



    $editBuilding = TownsApi('list', 'id,name,res,set','id='.$_GET['id']);
    //print_r($editBuilding);
    $editBuilding = $editBuilding['objects'][0];

    ?>

        Smyslupnlný editor budov by měl umět pracovat s budovou i jinak než pouze textově. Tato aplikace pouze ukazuje, jak se dá zdroják budovy načíst a uložit.<br>
        <a href="http://forum.towns.cz/wiki/439-2/api/modely-budov/" target="_blank">Více o modelech budov</a><br><br>


    <form method="post" action="?id=<?=$_GET['id']?>">
        <input type="hidden" name="edit" value="1">
        <b>Jméno:</b><br>
        <input type="text" name="name" value="<?=addslashes($editBuilding['name'])?>"><br><br>

        <b>Zdrojový kód budovy:</b><br>
        Z něj se vykresluje samotná budova na mapě.<br>
        <textarea name="res" style="width:100%;height: 60px;"><?=htmlspecialchars($editBuilding['res'])?></textarea><br><br>

        <b>Dodatečná data editoru:</b><br>
        <textarea name="editor_data" style="width:100%;height: 60px;"><?=htmlspecialchars($editBuilding['set']['editor_data'])?></textarea><br><br>


        <input type="submit" value="Změnit">
    </form>


    <?php

}

//----------------------------------------------------------------Konec
?>




</body>
</html>






