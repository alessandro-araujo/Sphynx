<?php
namespace App\Models;

class Logs extends ModelsConnection
{
    public function returnLogs($pag)
    {
        return $this->getAllLogs($pag);
    }
    public function registerLogs($method, $route, $ip, $user_agent)
    {
        return $this->postLogs($method, $route, $ip, $user_agent);
    }
}