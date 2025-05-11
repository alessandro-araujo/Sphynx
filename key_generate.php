<?php

$env_path = __DIR__ . '/.env';
!file_exists($env_path) && exit("Arquivo .env não encontrado.\n");
$env_content = file_get_contents($env_path);
$new_key = base64_encode(random_bytes(64));

if (preg_match('/^JWT_SECRET_KEY=.*$/m', $env_content)) {
    $env_content = preg_replace('/^JWT_SECRET_KEY=.*$/m', "JWT_SECRET_KEY=$new_key", $env_content);
} else {
    $env_content .= "\nJWT_SECRET_KEY=$new_key\n";
}

file_put_contents($env_path, $env_content);
echo "JWT_SECRET_KEY atualizado com sucesso.\n";