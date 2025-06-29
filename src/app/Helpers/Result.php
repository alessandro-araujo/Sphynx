<?php
declare(strict_types=1);
namespace App\Helpers;
use App\Language\Lang;

final class Result {
    private static Lang $lang;
    private static Response $http;

    public static function init(): void {
        if (!isset(self::$lang)) self::$lang = new Lang();
        if (!isset(self::$http)) self::$http = new Response();
    }

    /**
     * @param array{status: string, message?: string} $entity
     * @param string $resource
     * @param int $http_code
     */
    public static function status(array $entity, string $resource, int $http_code): void {
        if ($entity['status'] === 'error') {
            self::init();
            $message = (string) ($entity['message'] ?? 'not_found');
            $response = [
                'status' => $http_code,
                'message' => self::$lang->get("error.$message.$resource")['error'],
            ];
            self::$http->response($response, $http_code);
        }
    }

    /**
     * @param string $password
     * @param array<string, array<string, int|string>|string> $user
     * @return bool
     */
    public static function password(string $password, array $user): bool {
        self::init();
        $response = [
            'status' => 401,
            'message' => self::$lang->get("error.invalid.username_password")['error'],
        ];
        assert(isset($user['result']['password']));
        $login_password = $user['result']['password'];
        if (empty($login_password)) self::$http->response($response, 401);
        if (!password_verify($password, (string)$login_password)) self::$http->response($response, 401);
        return true;
    }

    /**
     * @param array<string, array<string, int|string>|string> $user
     * @param string $lang
     * @param int $http_code
     * @return bool
    */
    public static function empty(array $user, string $lang, int $http_code) : bool {
        self::init();
        $response = [
            'status' => $http_code,
            'message' => self::$lang->get("error.$lang")['error'],
        ];
        if (empty($user['result'])) self::$http->response($response, $http_code);
        return false;
    }
}
