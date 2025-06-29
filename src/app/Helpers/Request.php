<?php
declare(strict_types=1);
namespace App\Helpers;

use App\Language\Lang;

final class Request {
    private static Lang $lang;
    private static Response $http;

    public static function init(): void {
        if (!isset(self::$lang)) self::$lang = new Lang();
        if (!isset(self::$http)) self::$http = new Response();
    }

    /**
     * @param array<string, mixed> $data
     * @param array<int, string> $fields
     * @param string $lang
     * @return bool
     */
    public static function required(array $data, array $fields, string $lang): bool {
        self::init();
        $response = [
            'status' => 400,
            'message' => self::$lang->get("error.not_provided_s.$lang")['error'],
        ];
        foreach ($fields as $field) {
            if (empty($data[$field])) self::$http->response($response, 400);
        }
        return true;
    }

    /**
     * @param int|string $data
     * @param string $resource
     * @return bool
     */
    public static function requiredParam(mixed $data, string $resource): bool {
        self::init();
        $response = [
            'status' => 400,
            'message' => self::$lang->get("error.not_provided.$resource")['error'],
        ];
        if (empty($data)) self::$http->response($response, 400);
        return true;
    }
}
