<?php


namespace Nichozuo\LaravelUtils\Helper;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SQLHelper
{
    public static function logSql()
    {
        DB::listen(function ($query) {
            $tmp = str_replace('?', '"' . '%s' . '"', $query->sql);
            $qBindings = [];
            foreach ($query->bindings as $key => $value) {
                if (is_numeric($key)) {
                    $qBindings[] = $value;
                } else {
                    $tmp = str_replace(':' . $key, '"' . $value . '"', $tmp);
                }
            }
            $tmp = vsprintf($tmp, $qBindings);
            $tmp = str_replace("\\", "", $tmp);
            Log::info(' execution time: ' . $query->time . 'ms; ' . $tmp . "\n\n\t");
        });
    }
}
