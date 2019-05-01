<?php

require_once __DIR__.'/../vendor/autoload.php';

$generateLowNumber = function() {
    $x = rand(1, 1000);
    echo $x.PHP_EOL;
    if ($x > 50) {
        throw new Exception('Number is too high');
    }
    return $x;
};

$result = attempt($generateLowNumber)
    ->at(new DateTime('+5 seconds'));

var_dump($result);