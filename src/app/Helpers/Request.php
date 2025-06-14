<?php
declare(strict_types=1);
namespace App\Helpers;

final class Request {
    /**
     * @param array<string, mixed> $data
     * @param array<int, string> $fields
     * @return bool
     */
    public static function required(array $data, array $fields): bool {
        foreach ($fields as $field) {
            if (empty($data[$field])) return false;
        }
        return true;
    }
}
