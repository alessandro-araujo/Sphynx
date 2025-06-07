<?php

use JetBrains\PhpStorm\NoReturn;

#[NoReturn] function dd(mixed ...$vars): void {
    $convert = function ($data) use (&$convert) {
        if (is_object($data)) {
            $encoded = json_decode((string) json_encode($data), true);

            if (empty($encoded)) {
                return [
                    '__raw_dump' => explode("\n", rtrim(print_r($data, true)))
                ];
            }
            return $convert($encoded);
        }
        if (is_array($data)) {
            return array_map(function ($value) use ($convert) {
                return $convert($value);
            }, $data);
        }
        return $data;
    };
    $output = array_map($convert, $vars);
    echo json_encode(['debug' => $output], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

