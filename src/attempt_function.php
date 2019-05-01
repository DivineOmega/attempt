<?php

use DivineOmega\Attempt\AttemptHandler;

if (!function_exists('attempt')) {

    function attempt(callable $function)
    {
        return new AttemptHandler($function);
    }

}