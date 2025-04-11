<?php

function loadEnv($path = '../.env') {
    if(!file_exists($path)) {
        throw new Exception("O arquivo .env não foi encontrado em: $path");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach($lines as $line) {
        // Ignora comentários
        if(strpos(trim($line), '#') === 0) {
            continue;
        }

        // Divide a linha em nome e valor
        if(strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            // Remove aspas se existirem
            if(strpos($value, '"') === 0 && strrpos($value, '"') === strlen($value) - 1) {
                $value = substr($value, 1, -1);
            } else if(strpos($value, "'") === 0 && strrpos($value, "'") === strlen($value) - 1) {
                $value = substr($value, 1, -1);
            }

            // Define a variável de ambiente
            putenv("$name=$value");
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

// Uso
loadEnv();

// Acesso às variáveis
// echo getenv('DB_HOST');  // Usando getenv()
// echo $_ENV['DB_HOST'];   // Usando $_ENV
// echo $_SERVER['DB_HOST']; // Usando $_SERVER
?>