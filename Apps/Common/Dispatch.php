<?php
/**
 * Created by PhpStorm.
 * User: xing
 * Date: 18-12-8
 * Time: 下午5:21
 */

namespace Apps\Common;


use ErrorException;
use ReflectionException;

/**
 * Class Dispatch
 * @package Apps\Common
 */
class Dispatch
{
    /**
     * @var  Contains $Contains
     */
    protected Contains $contains;

    protected string $response;

    /**
     * Dispatch constructor.
     * @param array $configs
     * @throws ErrorException
     * @throws ReflectionException
     */
    public function __construct($configs = [])
    {
        $this->contains = new Contains();
        $this->contains->bindings(Config::class, $configs, 'config');
        $this->contains->setting(self::class, $this, 'dispatch');
    }

    public function run()
    {
        try {

            $this->bootstrap();

            $action = $this->parseRoute();

            is_array($action) ? list($action, $params) = $action : $params = null;


            $this->response = $this->running($action, $params);

            $this->response();

        } catch (ErrorException $exception) {

            $re = $exception->getMessage() . PHP_EOL . $exception->getFile() . PHP_EOL .
                $exception->getLine() . PHP_EOL;
            $this->contains->response->setContent("<h1>$re</h1>")->sendContent();
        }
    }

    /**
     * @throws ErrorException
     * @throws ReflectionException
     */
    public function bootstrap()
    {
        $components = $this->contains->config->get('components');

        foreach ($components as $name => $component) {

            is_array($component) ? $this->contains->bindings($component[0], $component[1], $name) :
                $this->contains->bindings($component, [], $name);
            if (isset($component[2]) && is_callable($component[2])) {
                $component[2]($this->contains->get($name));
            }
        }
        $routeHandle = $this->contains->config->get('route_handle');
        $this->contains->bindings($routeHandle ? $routeHandle : Route::class, ['routes' => $this->contains->config->get('route')], 'route');
        //$this->contains->bindings(Middleware::class, $this->contains->config->get('middleware'), 'middleware');
        $this->contains->bindings(Request::class, [$_GET,
            $_POST,
            [],
            $_COOKIE,
            $_FILES,
            $_SERVER], 'request');
        $this->contains->bindings(Response::class, [], 'response');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function parseRoute()
    {
        return $this->contains->route->matchRoute($this->contains->request);
    }


    /**
     * @param $action
     * @param $params
     * @return array|mixed
     * @throws ErrorException
     * @throws ReflectionException
     */
    public function running($action, $params)
    {
        if ($action instanceof \Closure) {
            return $this->closure($action, $params);
        } elseif (is_array($action)) {
            return $this->action($action[0], $action[1], $params);
        }
        $arr = explode('@', $action);
        if (class_exists($arr[0])) {
            return $this->action($arr[0], $arr[1], $params);
        }
        throw new ErrorException('page not find', 404);
    }

    /**
     * @param \Closure $closure
     * @param $params
     * @return mixed
     * @throws ErrorException
     * @throws ReflectionException
     */
    public function closure(\Closure $closure, $params)
    {
        $function = new \ReflectionFunction($closure);
        $params = $params ?: $this->contains->resolveParams($function);
        return call_user_func_array($closure, $params);
    }


    /**
     * @param $class
     * @param $method
     * @param $params
     * @return mixed
     * @throws ErrorException
     * @throws ReflectionException
     */
    public function action($class, $method, $params)
    {
        $object = $this->contains->build($class);
        $reflectionClass = $this->contains->getReflect($class);
        $action = $reflectionClass->getMethod($method);
        $params = $params ?: $this->contains->resolveParams($action);
        return call_user_func_array([$object, $method], $params);
    }


    public function response()
    {
        return $this->contains->response->response($this->response);
    }


}