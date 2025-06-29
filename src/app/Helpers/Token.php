<?php
declare(strict_types=1);
namespace App\Helpers;

class Token {
    public static function get(): ?string {
        $headers = getallheaders();
        if (isset($headers['Authorization']) && str_starts_with($headers['Authorization'], 'Bearer ')) {
            return substr($headers['Authorization'], 7);
        }
        return null;
    }
}