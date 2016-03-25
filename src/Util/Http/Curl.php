<?php

namespace Util\Http;

use Util\Http\Request;

/**
 * @author Sergey Ageev (Cimus <s_ageev@mail.ru>)
 */
class Curl
{
    //Базовые заголовки для курла
    private $curlOptions;
    


    /**
     * 
     */
    public function __construct()
    {
        $this->headers = [];
        
        $this->curlOptions = [
            CURLOPT_AUTOREFERER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 600,
        ];
    }
    
    
    
    public function run(Request $request, &$info)
    {
        if(!isset($request->curlOptions[CURLOPT_URL]) OR $request->curlOptions[CURLOPT_URL] == '')
            return false;
        
        $request->curlOptions = $request->curlOptions + $this->curlOptions;
        
        
        
        $curl = curl_init();
        curl_setopt_array ($curl, $request->curlOptions);
	$res = curl_exec($curl);
	
	$info=curl_getinfo($curl);
	curl_close($curl);
	
	if($res != '') return $res;
	else return false;
    }
}