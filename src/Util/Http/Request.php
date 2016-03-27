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
     * @return Request
     */
    public function setReferer($referer)
    {
        $this->curlOptions[CURLOPT_REFERER] = $referer;
        return $this;
    }
    /**
     * 
     * @param type $option
     * @param type $value
     * @return Request
     */
    public function setOption($option, $value)
    {
        $this->curlOptions[$option] = $value;
        return $this;
    }
    
    /**
     * 
     * @param int $option
     * @return string|null
     */
    public function getOption($option)
    {
        if(isset($this->curlOptions[$option]))
                return $this->curlOptions[$option];
        
        return null;
    }
    
    /**
     * 
     * @param string $userAgent
     * @return Request
     */
    public function setUserAgent($userAgent)
    {
        $this->curlOptions[CURLOPT_USERAGENT] = $userAgent;
        return $this;
    }
    
    /**
     * 
     * @param array $header
     * @return Request
     */
    public function setHeader($header)
    {
        if(is_array($header) AND $header){
            $this->curlOptions[CURLOPT_HTTPHEADER] = $header;
        }
        
        return $this;
    }
    
    /**
     * Время ожидания ответа
     * 
     * @param int $sec
     * @return Request
     */
    public function setTimeOut($sec)
    {
        $this->curlOptions[CURLOPT_TIMEOUT] = (int) $sec;
        
        return $this;
    }
}
