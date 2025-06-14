<?php
declare(strict_types=1);
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

    /**
     * @param string $password
     * @param string $login_password
     * @return bool
     */
    public static function password(string $password, string $login_password): bool {
        if (empty($login_password)) return false;
        if (!password_verify($password, $login_password)) return false;
        return true;
    }
}
