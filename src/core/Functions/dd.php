<?php

function dd($array): void {
    print('<pre>');;
    print_r($array);
    print('</pre>');
    die;
}