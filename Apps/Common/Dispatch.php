<?php
/**
 * Created by PhpStorm.
 * User: xing
 * Date: 18-12-8
 * Time: 下午5:21
 */

namespace Apps\Common;


use Swoole\Http\Request;
use Swoole\Http\Response;

/**
 * Class Dispatch
 * @package Apps\Common
 */
class Dispatch
{
    /**
     * @var  Contains $Contains
     */
    public $contains;


    /**
     * Dispatch constructor.
     * @param array $configs
     * @throws \ErrorException
     * @throws \ReflectionException
     */
    public function __construct($configs = [])
    {
        $this->contains = new Contains();
        $this->contains->bindings(Config::class, $configs, 'config');
        $this->contains->setting(self::class, $this, 'dispatch');
    }

    public function run(Request $request, Response $response)
    {
        try {

            $this->bootstrap();

            $this->setQQ($request, $response);


            $this->running();

            $this->response();

        } catch (\ErrorException $exception) {

            $re = $exception->getMessage() . PHP_EOL . $exception->getFile() . PHP_EOL .
                $exception->getLine() . PHP_EOL;
            $this->contains->response->end("<h1>$re</h1>");
        }
    }

    /**
     * @throws \ErrorException
     * @throws \ReflectionException
     */
    public function bootstrap()
    {
        $routeHandle = $this->contains->config->get('route_handle');
        //print_r($this->contains->config->get('route'));
        $this->contains->bindings($routeHandle ? $routeHandle : Route::class, $this->contains->config->get('route'), 'route');
        $this->contains->bindings(Middleware::class, $this->contains->config->get('middleware'), 'middleware');
    }

    public function running()
    {
        $action = $this->contains->route->Analysis($this->contains->request);
        if (is_callable($action)) {
            return call_user_func_array($action, [$this->contains->request, $this->contains->response]);
        } elseif (is_array($action)) {

        }
        $arr = explode('@', $action);
        if (class_exists($arr[0])) {
            return $this->action($arr[0], $arr[1]);
        }
        throw new \ErrorException('page not find', 404);
    }

    /**
     * @param $class
     * @param $method
     * @return mixed
     * @throws \ErrorException
     * @throws \ReflectionException
     */
    public function action($class, $method)
    {
        $object = $this->contains->build($class);
        $reflectionClass = $this->contains->getReflect($class);
        $action = $reflectionClass->getMethod($method);
        $params = $this->contains->resolveParms($action);
        return call_user_func_array([$object, $method], $params);
    }


    public function setQQ(Request $request, Response $response)
    {
        $this->contains->setting(Request::class, $request, 'request');
        $this->contains->setting(Response::class, $response, 'response');
    }

    public function response()
    {

    }

}