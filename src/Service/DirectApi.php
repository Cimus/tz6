<?php
namespace Service;

use Util\Http\Curl;
use Util\Http\Request;

/**
 * @author Sergey Ageev (Cimus <s_ageev@mail.ru>)
 */
class DirectApi
{
    private $apiUrl = 'https://api.direct.yandex.ru/v4/json/';
    
    private $login;
    private $appId;
    private $token;
    
    
    
    public function __construct($login = 'rudakova-test-api', $appId = 'b7a173fe63f34291bdc42704375dcc4d', $token = '644f887aa2d04c0d9d73090a98fbb51b')
    {
        $this->login = $login;
        $this->appId = $appId;
        $this->token = $token;
        
        $this->init();
    }
    
    
    private function init()
    {
        
    }
    
    
    public function getBanners($campaignId)
    {
        $request = $this->createRequest('GetBanners', [
            'param' => [
                'CampaignIDS' => [$campaignId], 
//                'GetPhrases' => 'WithPrices'
            ],
        ]);
        
        return $this->sendRequest($request);
    }


    public function getCampaignsList()
    {
        $request = $this->createRequest('GetCampaignsList', [$this->login]);
        
        return $this->sendRequest($request);
        
    }
    
    /**
     * 
     * @param Request $request
     * @return array
     * @throws DirectApiException
     */
    private function sendRequest(Request $request)
    {
        $curl = new Curl();
        $info = [];
        $result = $curl->run($request, $info);
        
        
        if(!$info['http_code']){
            throw new DirectApiException('Превышено время ожидания ответа');
        }
        elseif($info['http_code'] != 200){
            throw new DirectApiException( print_r($info, true), $info['http_code']);
        }
        
        $result = json_decode($result, true);
        
        if(isset($result['error_code'])){
            throw new DirectApiException($result['error_str'] . PHP_EOL . $result['error_detail'], $result['error_code']);
        }
        
        return $result;
    }


    
    
    /**
     * Метод создаёт запрос для курла
     * @param string $method
     * @param array $params
     * @return Request
     */
    private function createRequest($method, array $params)
    {
        $params['token'] = $this->token;
        $params['method'] = $method;
        
        $request = new Request($this->apiUrl);
        $request
                ->setOption(CURLOPT_POST, true)
                ->setOption(CURLOPT_POSTFIELDS, json_encode($params));
        
        return $request;
        
    }
    
    
    
}
