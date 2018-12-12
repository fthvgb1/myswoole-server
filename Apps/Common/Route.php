<?php
/**
 * Created by PhpStorm.
 * User: xing
 * Date: 18-12-8
 * Time: 下午5:15
 */

namespace Apps\Common;

use Swoole\Http\Request;

class Route
{

    static protected $routes = [];

    /**
     * @return array
     */
    public static function getRoutes()
    {
        return self::$routes;
    }


    /**
     * @param $field
     * @param array $routes
     */
    public static function setRoutes($field, array $routes)
    {
        self::$routes[$field] = $routes;
    }

    public function __construct($map, $regex)
    {
        $keys = array_map(function ($item) {
            return $route = trim($item, '/') ?: '/';
        }, array_keys($map));
        self::setRoutes('map', array_combine($keys, array_values($map)));
        self::setRoutes('regex', $regex);
    }


    /**
     * @param Request $request
     * @return string|array
     * @throws \Exception
     */
    public function Analysis(Request $request)
    {
        $path_info = $request->server['path_info'];
        $route = ltrim($path_info, '\\/');
        $route = $route ?: '/';
        if (isset(self::$routes['map'][$route])) {
            return self::$routes['map'][$route];
        }

        foreach (self::$routes['preg'] as $r) {
            if (preg_match("@{$r}@", $route, $matches) !== false) {
                return $matches;
            }
        }
        throw new \Exception('page not found', 404);
    }

}