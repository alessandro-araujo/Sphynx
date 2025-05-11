<?php

function writeLog(string $message, string $file = '/../../storage/logs/app.log'): void
{
    $date = date('Y-m-d H:i:s');
    $logMessage = "[$date] $message" . PHP_EOL;

    if (!file_exists(dirname($file))) {
        mkdir(dirname($file), 0777, true);
    }

    file_put_contents($file, $logMessage, FILE_APPEND);
}
