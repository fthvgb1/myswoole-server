<?php
/**
 * Created by PhpStorm.
 * User: xing
 * Date: 18-12-8
 * Time: 下午5:15
 */

namespace Apps\Common;

use Exception;
use Symfony\Component\HttpFoundation\Request;

class Route implements interfaces\Route
{

    protected static array $routes = [];

    protected string $method = 'get';

    protected static array $methods = [
        'get', 'post', 'head', 'options', 'put', 'patch', 'delete'
    ];


    /**
     * @return array
     */
    public static function getRoutes()
    {
        return self::$routes;
    }

    public function __construct($routes)
    {
        $this->init($routes);
    }

    public function init($routes)
    {
        foreach ($routes as $k => $route) {
            if (is_integer($k) && is_array($route)) {
                foreach (self::$methods as $method) {
                    $this->push($method, $route);
                }
            } else {
                $methods = explode('@', $k);
                $middles = [];
                if (count($methods) > 1) {
                    $middles = explode('#', $methods[1]);
                }
                foreach (explode(' ', $methods[0]) as $method) {
                    $this->push($method, $route, $middles);
                }
            }
        }
    }

    public function push($method, $arr = [], $middleWare = [])
    {
        foreach ($arr as $key => $item) {
            $key = trim($key, '/') ?: '/';
            self::$routes[$method][$key] = $item;
            if ($middleWare) {
                Middleware::add($method . '@' . $key, $middleWare);
            }
        }
    }


    public function getCurrentRoute(Request $request = null)
    {
        $url = explode('?', $request->getUri())[0];
        $host = $request->getSchemeAndHttpHost();
        $route = trim(str_replace($host, '', $url), '/');
        return $route ?: '/';
    }


    /**
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function matchRoute($request = null)
    {
        if (!isset($_SERVER['REQUEST_METHOD'])) {
            $this->method = 'cli';
        } else {
            $this->method = strtolower($request->getMethod());
        }
        $route = $this->getCurrentRoute($request);

        if (isset(self::$routes[$this->method][$route])) {
            return self::$routes[$this->method][$route];
        }

        //todo 正则方式的路由

        //todo 注解方式的路由
        throw new Exception('page not found', 404);
    }

}