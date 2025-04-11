<?php
namespace App\Models;

class Favorites extends ModelsConnection
{
    public function returnFavorites($id)
    {
        return $this->getAllFavorites($id);
    }

    public function registerFavorites(int $id)
    {
        return $this->postFavorites($id);
    }

    public function deleteFavorites($id)
    {
        return $this->delFavorites($id);
    }
}