<?php

namespace App\Helpers;

final class Result {
    /**
     * @param array<string, mixed> $data
     * @return bool
     */
    public static function status(array $data): bool {
        if ($data['status'] === 'error') {
            return false;
        }
        return true;
    }
}
