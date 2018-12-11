<?php
/**
 * Created by PhpStorm.
 * User: xing
 * Date: 18-12-8
 * Time: 下午5:12
 */

namespace Apps\Common;


use Swoole\Http\Request;
use Swoole\Http\Response;

/**
 * Class Contains
 * @property Route route;
 * @property Config config
 * @property Middleware middleware
 * @property Dispatch dispatch
 * @property Response response
 * @property Request request
 * @package Apps\Common
 */
class Contains
{
    private static $contains = [];

    private static $reflects = [];

    private $alias = [];

    /**
     * @param $name
     * @return \ReflectionClass
     */
    public function getReflect($name)
    {
        return self::$reflects[$name];
    }

    /**
     * @param $name
     * @param \ReflectionClass $reflects
     */
    public static function setReflects($name, \ReflectionClass $reflects)
    {
        self::$reflects[$name] = $reflects;
    }

    public function __construct()
    {
        self::$contains['contains'] = $this;
    }

    public function setting($key, $single, $alias = '')
    {
        unset(self::$contains[$key]);
        if ($alias) {
            $this->alias[$alias] = $key;
        }
        self::$contains[$key] = $single;
    }

    /**
     * @param $name
     * @return mixed|object
     * @throws \ErrorException
     * @throws \ReflectionException
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * @param $name
     * @param $value
     * @return object
     * @throws \ErrorException
     * @throws \ReflectionException
     */
    public function __set($name, $value)
    {
        return $this->bindings($name, $value);
    }

    /**
     * @param $name
     * @param string $alias
     * @return mixed|object
     * @throws \ErrorException
     */
    public function get($name, $alias = '')
    {
        if (isset(static::$contains[$name])) {
            return static::$contains[$name];
        }
        if (isset($this->alias[$name]) && isset(static::$contains[$this->alias[$name]])) {
            return static::$contains[$this->alias[$name]];
        }

        throw new \ErrorException($name . ' not exists', 500);
    }

    /**
     * @param $concrete
     * @param array $params
     * @param string $alias
     * @return object
     * @throws \ReflectionException|\ErrorException
     */
    public function bindings($concrete, $params = [], $alias = '')
    {
        if ($alias) {
            $this->alias[$alias] = $concrete;
        }
        if (($concrete instanceof \Closure)) {
            if (!$alias) {
                throw new \ErrorException('closure must need alias');
            }
            return static::$contains[$alias] = $concrete;
        }
        if (isset(static::$contains[$concrete])) {
            unset(static::$contains[$concrete]);
        }


        $instance = $this->build($concrete, $params);
        return static::$contains[$concrete] = $instance;

    }


    /**
     * @param $class
     * @param array $params
     * @return object
     * @throws \ErrorException
     * @throws \ReflectionException
     */
    public function build($class, $params = [])
    {
        if ($this->has($class)) {
            return $this->get($class);
        }

        $reflectionClass = new \ReflectionClass($class);
        self::setReflects($class, $reflectionClass);
        $constructor = $reflectionClass->getConstructor();
        if (!$constructor) {
            return $reflectionClass->newInstance();
        }
        $param = $this->resolveParms($reflectionClass->getConstructor(), $params);
        return $reflectionClass->newInstanceArgs($param);

    }


    /**
     * @param \ReflectionMethod $constructor
     * @param array $params
     * @return array
     * @throws \ErrorException
     * @throws \ReflectionException
     */
    public function resolveParms(\ReflectionMethod $constructor, $params = [])
    {

        $param = [];
        foreach ($constructor->getParameters() as $parameter) {

            if (isset($params[$parameter->name])) {
                $arg = $params[$parameter->name];
                if (is_string($arg) && class_exists($arg)) {
                    $args = $params[$arg] ?? [];
                    $arg = $this->build($arg, $args);
                }
                $param[$parameter->name] = $arg;
            } else {

                if ($this->has($parameter->name)) {
                    $param[$parameter->name] = $this->get($parameter->name);
                } elseif ($parameter->isDefaultValueAvailable()) {
                    $param[$parameter->name] = $parameter->getDefaultValue();
                } elseif ($parameter->allowsNull()) {
                    $param[$parameter->name] = null;
                } else {
                    $param[$parameter->name] = $this->build($parameter->getClass());
                }
            }
        }
        return $param;
    }

    /**
     * @param $name
     * @return bool
     */
    public function has($name)
    {
        return isset(self::$contains[$name]) || isset($this->alias[$name]);
    }
}