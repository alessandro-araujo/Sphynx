<?php

namespace App\Controllers;

use App\Models\ExampleModel;

class ExampleController extends Controller
{
    public function index(): void
    {
        $model = new ExampleModel();
        $result = $model->getData();
        $this->response(["message" => $result], 200);
    }

    public function store($data): void
    {
        if (empty($data)) {
            $this->response(["error" => "Dados inválidos"], 400);
        }

        $this->response(["message" => "Exemplo criado com sucesso", "data" => $data], 201);
    }

    public function show($id): void
    {
        if (!$id) {
            $this->response(["error" => "ID não fornecido"], 400);
        }

        $this->response(["message" => "Exibindo detalhes do Exemplo com ID: $id"], 200);
    }

    public function update($data, $id): void
    {
        if (!$id) {
            $this->response(["error" => "ID não fornecido"], 400);
        }
        $nome = $data['name'];
        $this->response(["message" => "Atualizar detalhes do Exemplo com ID: $nome", "id" => $id], 200);
    }
}
