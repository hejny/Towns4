<h3>Vytvo&#345;en&iacute; nov&eacute;ho sv&#283;ta </h3>
<?php
if($_POST['name']){
	if(!file_exists(root.'world/'.$_POST['name'].'.txt')){

		file_put_contents(root.'world/'.$_POST['name'].'.txt',
($_POST['mysql_server']?"url=".str_replace("//","/\\/",$_POST['url']).";":'')."
".($_POST['mysql_server']?"cache=".$_POST['cache'].";":'')."
".($_POST['mysql_server']?"mysql_server=".$_POST['mysql_server'].";":'')."
".($_POST['mysql_user']?"mysql_user=".$_POST['mysql_user'].";":'')."
".($_POST['mysql_password']?"mysql_password=".$_POST['mysql_password'].";":'')."
".($_POST['mysql_db']?"mysql_db=".$_POST['mysql_db'].";":'')."
".($_POST['mysql_prefix']?"mysql_prefix=".$_POST['mysql_prefix'].";":'')."
"
		);
		chmod(root.'world/'.$_POST['name'].'.txt',0777);
		echo('OK');
		//br();
	}else{
		echo('Tento svět už existuje!');
		//br();
	}
}
?>

<form id="form1" name="form1" method="post" action="?page=createworld">
<table width="384" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="188"><strong>Jm&eacute;no:</strong></td>
    <td width="196"><label>
      <input name="name" type="text" id="name" value="<?php echo($_POST['name']); ?>" />
    </label></td>
  </tr>
  <tr>
    <td><strong>URL:</strong></td>
    <td><input name="url" type="text" id="url" value="<?php echo($_POST['url']); ?>" /></td>
  </tr>
  <tr>
    <td colspan="2">url aplikace nap&#345;.: http://localhost/towns4/</td>
  </tr>
  <tr>
    <td><strong>cache:</strong></td>
    <td><input name="cache" type="text" id="cache" value="<?php echo($_POST['cache']); ?>" /></td>
  </tr>
  <tr>
    <td colspan="2">cesta k pomocn&eacute;mu adres&aacute;&#345;i nap&#345;.: tmp/world2</td>
  </tr>
  <tr>
    <td><strong>mysql_server:</strong></td>
    <td><input name="mysql_server" type="text" id="mysql_server" value="<?php echo($_POST['mysql_server']); ?>" /></td>
  </tr>
  <tr>
    <td><strong>mysql_user:</strong></td>
    <td><input name="mysql_user" type="text" id="mysql_user" value="<?php echo($_POST['mysql_user']); ?>" /></td>
  </tr>
  <tr>
    <td><strong>mysql_password:</strong></td>
    <td><input name="mysql_password" type="text" id="mysql_password" value="<?php echo($_POST['mysql_password']); ?>" /></td>
  </tr>
  <tr>
    <td><strong>mysql_db:</strong></td>
    <td><input name="mysql_db" type="text" id="mysql_db" value="<?php echo($_POST['mysql_db']); ?>" /></td>
  </tr>
  <tr>
    <td><strong>mysql_prefix:</strong></td>
    <td><input name="mysql_prefix" type="text" id="mysql_prefix" value="<?php echo($_POST['mysql_prefix']); ?>" /></td>
  </tr>
  <tr>
    <td colspan="2">&quot;p&#345;edpona&quot; tabulek v datab&aacute;zi nap&#345;.: world2_</td>
  </tr>
  <tr>
    <td colspan="2"><label>
      <input type="submit" name="Submit" value="Vytvo&#345;it" />
    </label></td>
    </tr>
</table>
</form>
