<h3>Odeslat jádro systému</h3>
<b>Upozornění: </b>Synchronizace může při nesprávném použití poškodit vzdálený server!<br />




<form id="form1" name="form1" method="post" action="">
<table  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><strong>Master:</strong></td>
    <td><label>
      <input name="master" type="text" id="master" size="73" value="<?php echo(defined('master')?master:'http://user:password@server/'); ?>" />
    </label></td>
  </tr>
    <tr>
    <td colspan="2"><label>
      <input type="submit" name="Submit" value="OK" />
    </label></td>
    </tr>
</table>
</form>




<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_WARNING );
//-------
$push_slave='http://test.towns.cz/';
$push_pass='att31415hoo';
//===========================================================================================================================================
//==================================
function post_request($url, $data, $session='') {
 
    // Convert the data array into URL Parameters like a=b&foo=bar etc.
    $data = http_build_query($data);
 
    // parse the given URL
    $url = parse_url($url);
 
    if ($url['scheme'] != 'http') { 
        die('Error: Only HTTP request are supported !');
    }
 
    // extract host and path:
    $host = $url['host'];
    $path = $url['path'];
 
    // open a socket connection on port 80 - timeout: 30 sec
    $fp = fsockopen($host, 80, $errno, $errstr, 30);
 
    if ($fp){
 
        // send the request headers:
        fputs($fp, "POST $path HTTP/1.1\r\n");
        fputs($fp, "Host: $host\r\n");
        fputs($fp, "User-Agent: Mozilla/5.0 (X11; U; Linux i686; cs-CZ; rv:1.9.2.24) Gecko/20111107 Ubuntu/10.10 (maverick) Firefox/3.6.24\r\n");

 
        if ($referer != '')
            fputs($fp, "Referer: $referer\r\n");
 
        fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
        fputs($fp, "Content-length: ". strlen($data) ."\r\n");
        fputs($fp, "Cookie: PHPSESSID=$session \r\n");
        //Cookie: $Version=1; Skin=new;
        fputs($fp, "Connection: close\r\n\r\n");
        fputs($fp, $data);
 
        $result = ''; 
        while(!feof($fp)) {
            // receive the results of the request
            $result .= fgets($fp, 128);
        }
    }
    else { 
        return array(
            'status' => 'err', 
            'error' => "$errstr ($errno)"
        );
    }
 
    // close the socket connection:
    fclose($fp);
 
    // split the result header from the content
    $result = explode("\r\n\r\n", $result, 2);
 
    $header = isset($result[0]) ? $result[0] : '';
    $content = isset($result[1]) ? $result[1] : '';
 
    // return as structured array:
    return array(
        'status' => 'ok',
        'header' => $header,
        'content' => $content
    );
}
//===========================================================================================================================================

if($_POST['master']){
    //$slave=slave;
	//$pass=substr2($slave,'http://','@');
	//list($tmp,$pass)=split(':',$pass);

	$master=$_POST['master'];
	$pass=substr2($master,'http://','@');
	list($user,$pass)=explode(':',$pass);

	$master=explode('@',$master);
	$master=$master[1];
	$master=explode('/',$master);
	$master=$master[0];
	$push_slave='http://'.$master.'/app/admin/res.php';
	echo($push_slave);    
	br();
	echo('user: '.$user);    
	br();
	echo('pass: '.$pass); ;    
	br();

	foreach(array_merge(glob('core/*'),glob('core/*/*')) as $file){
    $data=array(//'page'=>'res',
		'password'=>$pass,
		'file'=>$file,
                'contents'=>file_get_contents($file));
    $stream=post_request($push_slave,$data);
	echo(print_r($stream));
	br();
	}
}

?>
