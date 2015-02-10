<?php
/*class FileSessionHandler
{
    private $savePath;

    function open($savePath, $sessionName)
    {
        $this->savePath = 'session';//$savePath;
        if (!is_dir($this->savePath)) {
            mkdir($this->savePath, 0777);
        }

        return true;
    }

    function close()
    {
        return true;
    }

    function read($id)
    {
        return (string)@file_get_contents("$this->savePath/sess_$id");
    }

    function write($id, $data)
    {
        return file_put_contents("$this->savePath/sess_$id", $data) === false ? false : true;
    }

    function destroy($id)
    {
        $file = "$this->savePath/sess_$id";
        if (file_exists($file)) {
            unlink($file);
        }

        return true;
    }

    function gc($maxlifetime)
    {
        foreach (glob("$this->savePath/sess_*") as $file) {
            if (filemtime($file) + $maxlifetime < time() && file_exists($file)) {
                unlink($file);
            }
        }

        return true;
    }
}

$handler = new FileSessionHandler();
session_set_save_handler(
    array($handler, 'open'),
    array($handler, 'close'),
    array($handler, 'read'),
    array($handler, 'write'),
    array($handler, 'destroy'),
    array($handler, 'gc')
    );

// the following prevents unexpected effects when using objects as save handlers
register_shutdown_function('session_write_close');
session_start();*/
//----------------------------------------------------------------------
session_start();
//foreach(glob('session/*') as $file)unlink($file);
//if(!file_exists('session')){mkdir('session');chmod('session',0777);}
$sessionfile='session/'.session_id().'.txt';

if(file_exists($sessionfile))$contents=file_get_contents($sessionfile);
else $contents='';//'a:1:{s:3:"dbs";a:1:{s:6:"server";a:1:{s:21:"isp-slave1.webdum.com";a:1:{s:7:"c17test";a:2:{i:0;s:18:"information_schema";i:1;s:7:"c17test";}}}}}';
$GLOBALS['session']=unserialize($contents);
$_SESSION=$GLOBALS['session'];
//a:1:{s:3:"dbs";a:1:{s:6:"server";a:1:{s:21:"isp-slave1.webdum.com";a:1:{s:7:"c17test";a:2:{i:0;s:18:"information_schema";i:1;s:7:"c17test";}}}}}
function shutdown(){
	chdir(dirname($_SERVER['SCRIPT_FILENAME']));
	$sessionfile='session/'.session_id().'.txt';
	//echo(serialize($_SESSION));
	foreach($GLOBALS['session'] as $key=>$value)if(!$_SESSION[$key])$_SESSION[$key]=$value;
	file_put_contents($sessionfile,serialize($_SESSION));
	chmod($sessionfile,0777);/**/
}

register_shutdown_function('shutdown');

//$_SESSION['aaa']++;
//echo(session_id().' - '.$_SESSION['aaa']);
//----------------------------------------------------------------------
function adminer_object() {
    // required to run any plugin
    include_once "./plugins/plugin.php";
    
    // autoloader
    foreach (glob("plugins/*.php") as $filename) {
        include_once "./$filename";
    }
    
    $plugins = array(
        // specify enabled plugins here
        new AdminerFrames
    );
    
    /* It is possible to combine customization and plugins:
    class AdminerCustomization extends AdminerPlugin {
    }
    return new AdminerCustomization($plugins);
    */
    
    return new AdminerPlugin($plugins);
}

// include original Adminer or Adminer Editor
include "adminer.php";


?>
