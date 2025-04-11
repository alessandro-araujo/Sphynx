<?php
namespace App\Models;

class Comment extends ModelsConnection
{
    public function returnLogs($id)
    {
        return $this->getAllComments($id);
    }
    public function registerComment($name, $id_film, $comment)
    {
        return $this->postComment($name, $id_film, $comment);
    }
}

