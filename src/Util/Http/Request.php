<?php

namespace Util\Http;
/**
 * @author Sergey Ageev (Cimus <s_ageev@mail.ru>)
 */
class Request
{
    //Базовые заголовки для курла
    public $curlOptions;

    public function __construct($url)
    {
        $this->curlOptions[CURLOPT_URL] = $url;
    }
    
    /**
     * 
     * @param type $referer
     * @return boolean
     */
    public function setReferer($referer)
    {
        $this->curlOptions[CURLOPT_REFERER] = $referer;
        return true;
    }
    /**
     * 
     * @param type $option
     * @param type $value
     * @return boolean
     */
    public function setOption($option, $value)
    {
        $this->curlOptions[$option] = $value;
        return $this;
    }
    
    public function getOption($option)
    {
        if(isset($this->curlOptions[$option]))
                return $this->curlOptions[$option];
        
        return null;
    }
    
    /**
     * 
     * @param type $userAgent
     * @return boolean
     */
    public function setUserAgent($userAgent)
    {
        $this->curlOptions[CURLOPT_USERAGENT] = $userAgent;
        return true;
    }
    
    /**
     * 
     * @param array $header
     * @return boolean
     */
    public function setHeader($header)
    {
        if(is_array($header) AND count($header))
        {
            $this->curlOptions[CURLOPT_HTTPHEADER] = $header;
            return true;
        }
        
        return false;
    }
    
    /**
     * Время ожидания ответа
     * 
     * @param int $sec
     * @return boolean
     */
    public function setTimeOut($sec)
    {
        $this->curlOptions[CURLOPT_TIMEOUT] = (int) $sec;
        
        return true;
    }
}
