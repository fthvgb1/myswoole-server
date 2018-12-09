<?php
/**
 * Created by PhpStorm.
 * User: xing
 * Date: 18-12-8
 * Time: 下午5:21
 */

namespace Apps\Common;


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
        $this->contains->set(Config::class, $configs, 'config');
        $this->contains->setting('dispatch', $this);
    }

    public function run()
    {
        try {

            $this->bootstrap();

            $this->analysisAction();

            $this->running();

            $this->response();

        } catch (\Exception $exception) {

        }
    }

    /**
     * @throws \ErrorException
     * @throws \ReflectionException
     */
    public function bootstrap()
    {
        $routeHandle = $this->contains->config->get('route_handle');
        $this->contains->set($routeHandle ? $routeHandle : Route::class, $this->contains->config->get('route'), 'route');
        $this->contains->set(Middleware::class, $this->contains->config->get('middleware'), 'middleware');
    }

    public function analysisAction()
    {

    }

    public function running()
    {
    }

    public function response()
    {

    }

}