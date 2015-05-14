<?php
/**
 * Towns API
 *
 * @copyright 2015 Towns.cz
 * @link http://api.towns.cz/
 * @author     Pavol Hejný
 * @version    1.0
 *
 */

//================================================================Object
/*
 * TownsApi
 *
 * Inicializace TownsApi pomocí objektu
 *
 * @param (string) URL
 * @param (string) Token
 * @param (string) Locale
 */
class TownsApi{
    private $url='';
    private $token='';
    private $locale='';

    public function __construct($url,$token,$locale){

        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            trigger_error('Not a valid URL', E_USER_ERROR);
        }
        if(!$token){

            $token = md5(uniqid(mt_rand(), true));

            //$token='none';
            //trigger_error("No token", E_USER_NOTICE);
        }
        if(!$locale){
            trigger_error("No locale", E_USER_ERROR);
        }

        $this->url=$url;
        $this->token=$token;
        $this->locale=$locale;

    }
    /*
     * Dotaz do API
     *
     * @return (array)
     */
    public function q(){

        $url=$this->url.'/api?token='.urlencode($this->token).'&locale='.urlencode($this->locale);

        $params = func_get_args();
        $query='';

        $separator='';
        foreach($params as $param){

            if(is_array($param)){
                $param=array_map(function($param){
                    $param=str_replace('\\','\\\\',$param);
                    $param=str_replace(',','\\,',$param);
                    return($param);
                },$param);
                $param=implode(',',$param);
            }

            $param=str_replace('\\','\\\\',$param);
            $param=str_replace(',','\\,',$param);
            $query.=$separator./*urlencode*/($param);
            $separator=',';
        }

        $postdata=array(
            'q' => $query
        );

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($postdata),
            )
        );
        //print_r($options);
        $context = stream_context_create($options);

        //echo('<br>'.$url.'<br>');
        $result = file_get_contents($url, false, $context);



        //echo('<hr>'.$result.'<hr>');

        $result=json_decode($result,true);

        //echo('<hr>'.print_r($result,true).'<hr>');

        return($result);


    }
    /*
    * Zjištění Tokenu
    *
    * @return (string) Token
    */
    public function getToken(){
        return($this->token);
    }

}

//================================================================Function
/*
 * TownsApiStart
 *
 * Inicializace TownsApi pomocí funkce
 *
 * @param (string) URL
 * @param (string) Token
 * @param (string) Locale
 */
function TownsApiStart($url,$token,$locale){
    $GLOBALS['TownsApi'] = new TownsApi($url,$token,$locale);
}

/*
 * TownsApi
 *
 * Dotaz do API
 *
 * @param string Funkce
 * @param string Parametr1
 * @param string Parametr2
 * @return (array)
 */
function TownsApi(){
    if(is_object($GLOBALS['TownsApi'])){
        return call_user_func_array(array($GLOBALS['TownsApi'], 'q'), func_get_args());
    }else{
        trigger_error("No API", E_USER_ERROR);
    }
}
/*
 * TownsApiGetToken
 *
 * Zjištění Tokenu
 *
 * @return (string) Token
 */
function TownsApiGetToken(){
    return $GLOBALS['TownsApi']->getToken();
}

//================================================================
?>
