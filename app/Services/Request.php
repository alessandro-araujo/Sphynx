<?php
namespace App\Services;

class Request {
    private $baseUrl;

    public function __construct($baseUrl) {
        $this->baseUrl = $baseUrl;
    }

    public function get($endpoint) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ['code' => $httpCode, 'response' => json_decode($response, true)];
    }
    
    public function getMuilti($result) 
    {
       // Inicializando cURL múltiplo
       $multiHandle = curl_multi_init();
       $handles = [];
       
       // Preparar as requisições
       foreach ($result as $url) {
           if ($url) {
               $ch = curl_init();
               curl_setopt($ch, CURLOPT_URL, $url);
               curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
               curl_multi_add_handle($multiHandle, $ch);
               $handles[] = $ch;
           }
       }

       // Executar as requisições
       $running = null;
       do {
           curl_multi_exec($multiHandle, $running);
       } while ($running);

       $resultData = [];
       foreach ($handles as $ch) {
           $response = json_decode(curl_multi_getcontent($ch), true);
           if ($response && isset($response['name'])) { 
               $resultData[] = $response['name']; 
           }
           curl_multi_remove_handle($multiHandle, $ch);
           curl_close($ch);
       }
       
       curl_multi_close($multiHandle); 
       return $resultData;
    }
}
