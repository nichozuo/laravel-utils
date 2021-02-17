<?php


namespace Nichozuo\LaravelUtils\Base;


class BaseHelper
{
    private static $instance = null;

    /**
     * @return static|null
     */
    public static function GetInstance()
    {
        if (self::$instance == null) {
            self::$instance = new static();
        }
        return self::$instance;
    }
}