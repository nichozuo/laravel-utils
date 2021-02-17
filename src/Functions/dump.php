<?php

if (!function_exists('zdump')) {
    function zdump($var, ...$moreVars)
    {
        if (config('app.debug'))
            dump($var, ...$moreVars);
    }
}

if (!function_exists('zdd')) {
    function zdd(...$vars)
    {
        if (config('app.debug'))
            dd(...$vars);
    }
}
