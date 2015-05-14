<?php
/**
 * Ukázková Towns API Aplikace - Proměnné
 *
 * @copyright 2015 Towns.cz
 * @link http://api.towns.cz/
 * @author     Pavol Hejný
 * @version    1.0
 *
 */

/*
 * Funkce, proměnné a konstanty předpřipravené pro Towns aplikace:
 *
 * *Funkce*
 *
 * TownsApi             - Základní funkce volání do API
 *
 * **Proměnné**
 *
 * $TownsApp     (array)- Asociované pole hodnot stejné pro každou instanci aplikace.
 * $TownsUser    (array)- Asociované pole hodnot odlišné pro různé uživatele. Pokud není uživatel přihlášen je proměnná nedefinovaná.
 * $TownsUser['delay'] (integer)
                        - Počet sekund za kdy se má (znovu) spustit běh aplikace na pozadí. Defultně je nastavená hodnota false tzn, aplikace nepoběží na pozadí. Pokud je hodnota příliš nízká, bude aplikace znovu spuštěna za nejnižší možnou dobu, která je obvykle 300 (5 minut). Tato hodnota se zachovává a aplikace se spouští ve stejném intervalu, dokud není hodnota $TownsUser['delay'] přestavena.
 *
 * **Konstanty**
 *
 * TownsLogged   (bool) - Je uživatel přihlášen?
 * TownsBackend  (bool) - Běží aplikace právě na pozadí. Pokud ano, nezáleží na HTML výstupu, pouze na volání do API případně změnách $
 */
//----------------------------------------------------------------HTML
?>
<!DOCTYPE html>
<html>
<head>
    <title>Proměnné | Ukázka Towns API</title>
    <meta charset="UTF-8">
    <meta name="description" content="Ukázkový příklad, jakým způsobem si může aplikace ukládat informace." />
    <?php TownsStyle(); ?><!--Zde funkce natvrdo vrátí styl, v Towns se místo toho použije externí .css soubor. -->
</head>
<body>

<?php
//----------------------------------------------------------------------------------Ukázka proměnné $TownsApp

if(!isset($TownsApp['count']))
$TownsApp['count']=1;
else
$TownsApp['count']++;
?>
<h2>Proměnné $TownsApp</h2>
Tato aplikace byla spuštěna dohromady všemi hráči i nepřihlášenými lidmi <?=$TownsApp['count']?>x.


<?php
if($_POST['message_type']=='app')
    $TownsApp['message']=$_POST['message'];
?>
<form method="post">
    <input type="hidden" name="message_type" value="app">
    Veřejný vzkaz pro všechny, kteří používají tuto aplikaci:<br>
    <textarea name="message"><?=htmlspecialchars($TownsApp['message'])?></textarea><br>
    <input type="submit" value="Změnit">
</form>

<?php
//----------------------------------------------------------------------------------Ukázka proměnné $TownsUser
if(TownsLogged) {


    if(!isset($TownsUser['count']))
        $TownsUser['count']=1;
    else
        $TownsUser['count']++;
    ?>
    <h2>Proměnné $TownsUser</h2>
    Tato aplikace byla spuštěna přímo vámi <?= $TownsUser['count'] ?>x.


    <?php
    if($_POST['message_type']=='user')
        $TownsUser['message']=$_POST['message'];
    ?>
    <form method="post">
        <input type="hidden" name="message_type" value="user">
        Vaše soukromá poznámka v této aplikaci:<br>
        <textarea name="message"><?=htmlspecialchars($TownsUser['message'])?></textarea><br>
        <input type="submit" value="Změnit">
    </form>

<?php
}
?>

</body>
</html>






