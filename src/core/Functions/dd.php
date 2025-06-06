<?php

// function dd($array): void {
//     print('<pre>');;
//     print_r($array);
//     print('</pre>');
//     die;
// }
// function dd(...$args): void {
//     echo '<pre>';
//     foreach ($args as $arg) {
//         print_r($arg);
//     }
//     echo '</pre>';
//     die;
// }


// @phpstan-ignore missingType.iterableValue
function dd(array ...$vars): void {
    echo '<pre style="background:#222;color:#0f0;padding:10px;">';
    foreach ($vars as $var) {
        var_dump($var);
        echo "\n";
    }
    echo '</pre>';
    exit;
}
