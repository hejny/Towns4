<?php

// If you want to ignore the uploaded files, 
// set $demo_mode to true;

$demo_mode = false;


if(isset($_GET['xc']) and $GLOBALS['ss']['logid']){

    //----------------------------------------------------INSERT NEW

    $id=nextid();
    $url = parse_url($GLOBALS['inc']['url']);

    $basename=basename($_FILES['file']['name']);
    list($basename)=explode('.',$basename,2);

    if(!$basename)$basename='-';
    //------------------INSERT objects
    sql_insert('objects', array(
        'id' => $id,
        'name' => $basename,
        'type' => 'story',
        'userid' => '',
        'origin' => '',
        'fp' => '',
        'func' => '',
        'hold' => '',
        'res' => 'html:' . '<img src="'.$url['path'].'userdata/objects/'.$id.'/'.$_FILES['file']['name'].'" width="100%" >',
        'profile' => '',
        'set' => '',
        'own' => $GLOBALS['ss']['logid'],
        't' => '',
        'pt' => ''
    ));

    //------------------INSERT positions
    sql_insert('positions', array(
        'id' => $id,
        'ww' => $GLOBALS['ss']["ww"],
        'x' => floatval($_GET['xc']),
        'y' => floatval($_GET['yc']),
        'traceid' => '',
        'starttime' => time(),
        'readytime' => 0,
        'stoptime' => 0
    ));
    //----------------------------------------------------

    $tmpobject=new object($id);
    $tmpobject->update(true);
    unset($tmpobject);


}else {

    $id = intval($_GET['id']);

}



$upload_dir = 'userdata/objects/'.$id;
mkdir($upload_dir,0777);

$upload_dir.='/';
fpc($upload_dir.'.htaccess','

Deny from all

<FilesMatch "\.(jpg|jpeg|png|gif|bmp|wbmp|JPG|JPEG|PNG|GIF|BMP|WBMP)$">

 Order deny,allow
 Allow from all
 satisfy all

</FilesMatch>

');



$allowed_ext = array(/*'jpg','jpeg','png','gif'*/'htaccess');


if(strtolower($_SERVER['REQUEST_METHOD']) != 'post'){
	exit_status('Error! Wrong HTTP method!');
}


if(array_key_exists('file',$_FILES) && $_FILES['file']['error'] == 0 ) {

    $file = $_FILES['file'];

    if (in_array(get_extension($file['name']), $allowed_ext)) {
        exit_status(' ' . implode(',', $allowed_ext) . ' files are not allowed!');
    }

    if ($demo_mode) {

        // File uploads are ignored. We only log them.

        $line = implode('		', array(date('r'), $_SERVER['REMOTE_ADDR'], $file['size'], $file['name']));
        file_put_contents('log.txt', $line . PHP_EOL, FILE_APPEND);

        exit_status('Uploads are ignored in demo mode.');
    }


    // Move the uploaded file from the temporary
    // directory to the uploads folder:

    $name = $upload_dir . $file['name'];

    if (file_exists($name)){

        list($name,$ext)=explode('.',$name,2);
        $i = 1;
        while (file_exists($name.'('.$i.').'.$ext)) $i++;

        $name=$name.'('.$i.').'.$ext;
    }

	if(move_uploaded_file($file['tmp_name'], $name)){
        chmod($name,0777);
		exit_status('File was uploaded successfuly!');
	}
	
}

exit_status('Something went wrong with your upload!');


// Helper functions

function exit_status($str){
	echo json_encode(array('status'=>$str));
	exit;
}

function get_extension($file_name){
	$ext = explode('.', $file_name);
	$ext = array_pop($ext);
	return strtolower($ext);
}
?>