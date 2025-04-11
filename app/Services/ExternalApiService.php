<?php
namespace App\Services;
use App\Services\Request;

class ExternalApiService {
    private $request;
    private $endpoint;

    public function __construct(string $endpoint) {
        $this->request = new Request($_ENV['BASE_URL']);
        $this->endpoint = $endpoint;
    }
    public function index() {
        return $this->request->get($this->endpoint);
    }

    public function MultiCurl($array){
        return $this->request->getMuilti($array);
    }
}
