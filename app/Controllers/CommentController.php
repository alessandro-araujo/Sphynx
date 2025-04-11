<?php
namespace App\Controllers;
use App\Models\Comment;
use App\Services\HttpResponse;

class CommentController {
    private $httpResponse;

    public function __construct() {
        $this->httpResponse = new HttpResponse();
    }

    public function show($id){
        $logsModel = new Comment();
        $response = $logsModel->returnLogs($id);
      
        if($response){
            foreach ($response as &$log) {
                $log['date_created'] = date('d/m/Y H:i:s', strtotime($log['date_created']));
            }
            $this->httpResponse->init(200, $response);
        } else {
            $this->httpResponse->init(404, ["message" => "Comments não encontrados"]);
        }
    }

    public function store($request){
        $logsModel = new Comment();
        $response = $logsModel->registerComment(
            $request['name'],
            $request['id_film'],
            $request['comment'],
        );
        if($response){
            $this->httpResponse->init(201, ["message" => "Cadastrado com Sucesso"]);
        } else {
            $this->httpResponse->init(409, ["message" => "Falha ao cadastrar"]);
        }
    }

 
}