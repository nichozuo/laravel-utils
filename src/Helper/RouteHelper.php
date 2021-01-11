<?php


namespace Nichozuo\LaravelUtils\Helper;


use Illuminate\Routing\Router;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionException;

class RouteHelper
{
    /**
     * @param Router $router
     * @param string $controller
     * @param array $actions
     * @param string $prefix
     * @throws ReflectionException
     */
    public static function New(Router $router, string $controller, array $actions = [], string $prefix = '')
    {
        $actions = self::getActions($controller, $actions);
        $controller = Str::start($controller, '\\');
        $prefix = self::getPrefix($prefix, $controller);

        foreach ($actions as $item) {
            if ($item == '__construct')
                continue;
            $temp = explode(':', $item);
            $method = (count($temp) >= 2) ? [$temp[0]] : ['post'];
            $action = (count($temp) >= 2) ? $temp[1] : $temp[0];
            $url = ($prefix == '') ? Str::snake($action) : $prefix . '/' . Str::snake($action);
            $router->match($method, $url, $controller . '@' . $action)->name("{$prefix}.{$action}");
        }
    }

    /**
     * 计算prefix
     *
     * @param string $prefix
     * @param string $controller
     * @return string
     */
    private static function getPrefix(string $prefix, string $controller): string
    {
        if ($prefix == "") {
            $t1 = explode("\\", $controller);
            $t2 = $t1[count($t1) - 1];
            $prefix = Str::snake(str_replace("Controller", "", $t2));
        } elseif ($prefix == "-") {
            $prefix = "";
        } else {
            $prefix = Str::snake($prefix);
        }
        return $prefix;
    }

    /**
     * @param string $controller
     * @param array $actions
     * @return array
     * @throws ReflectionException
     */
    private static function getActions(string $controller, array $actions): array
    {
        if (count($actions) > 0)
            return $actions;

        $newActions = [];
        $ref = new ReflectionClass($controller);
        foreach ($ref->getMethods() as $method) {
            if ($method->getDeclaringClass()->getName() == $controller) {
                $newActions[] = $method->getName();
            } else {
                break;
            }
        }
        return $newActions;
    }
}
