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
     * @param array $routes
     */
    public static function setRoutes(array $routes)
    {
        self::$routes = array_merge(self::$routes, $routes);
    }

    /**
     * @param Request $request
     * @return string|array
     * @throws \Exception
     */
    public function Analysis(Request $request)
    {
        $path_info = $request->server['path_info'];
        $route = ltrim($path_info, '\\');
        if (isset(self::$routes[$route])) {
            return self::$routes[$route];
        }

        foreach (self::$routes as $r) {
            if (preg_match("@{$r}@", $route, $matches) !== false) {
                return $matches;
            }
        }
        throw new \Exception('not found', 404);
    }

}