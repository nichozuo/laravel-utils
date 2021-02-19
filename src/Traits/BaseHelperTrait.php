<?php


namespace Nichozuo\LaravelUtils\Traits;


trait BaseHelperTrait
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