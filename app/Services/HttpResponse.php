<?php
namespace App\Services;

class HttpResponse {
    public function init ($httpCode, $value){
        if ($value === null){
            http_response_code($httpCode);
        }else {
            http_response_code($httpCode);
            echo json_encode($value);
        }
    }
}
