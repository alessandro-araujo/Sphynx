<?php
namespace App\Controllers;
use App\Models\Logs;
use App\Services\HttpResponse;

class LogsController {
    private $httpResponse;

    public function __construct() {
        $this->httpResponse = new HttpResponse();
    }

    public function show($pag){

        $logsModel = new Logs();
        $response = $logsModel->returnLogs($pag);

        if($response){
            foreach ($response['clients'] as &$log) {
                $log['date_created'] = date('d/m/Y H:i:s', strtotime($log['date_created']));
            }
            $this->httpResponse->init(200, $response);
        } else {
            $this->httpResponse->init(404, ["message" => "Logs não encontrados"]);
        }
    }

    public function store($request){
        $logsModel = new Logs();
        $response = $logsModel->registerLogs(
            $request['method'],
            $request['route'],
            $request['ip'],
            $request['user_agent'],
        );
        if($response){
            $this->httpResponse->init(201, ["message" => "Cadastrado com Sucesso"]);
        } else {
            $this->httpResponse->init(409, ["message" => "Falha ao cadastrar"]);
        }
    }

 
}