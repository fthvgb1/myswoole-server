<?php
/**
 * Created by PhpStorm.
 * User: xing
 * Date: 18-12-8
 * Time: 下午5:12
 */

namespace Apps\Common;


/**
 * Class Contains
 * @property Route route;
 * @property Config config
 * @property Middleware middleware
 * @property Dispatch dispatch
 * @package Apps\Common
 */
class Contains
{
    private static $contains = [];

    public function __construct()
    {
        self::$contains['contains'] = $this;
    }

    public function setting($key, $single)
    {
        unset(self::$contains[$key]);
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
        return $this->set($name, $value);
    }

    /**
     * @param $name
     * @param array $param
     * @return mixed|object
     * @throws \ErrorException
     * @throws \ReflectionException
     */
    public function get($name, $param = [])
    {
        if (isset(static::$contains[$name])) {
            return static::$contains[$name];
        }
        return $this->set($name, $param);
    }

    /**
     * @param $concrete
     * @param array $params
     * @param string $alias
     * @return self
     * @throws \ReflectionException|\ErrorException
     */
    public function set($concrete, $params = [], $alias = '')
    {
        if (!$alias) {
            $alias = $concrete;
        }
        if (($concrete instanceof \Closure)) {
            if (!$alias) {
                throw new \ErrorException('closure must need alias');
            }
            static::$contains[$alias] = $concrete;
            return $this;
        }
        if (isset(static::$contains[$alias])) {
            unset(static::$contains[$alias]);
        }

        $instance = $this->build($concrete, $params);
        static::$contains[$alias] = $instance;
        return $this;
    }

    /**
     * @param $class
     * @param array $params
     * @return object
     * @throws \ReflectionException
     */


    public function build($class, $params = [])
    {
        $reflectionClass = new \ReflectionClass($class);
        $constructor = $reflectionClass->getConstructor();

        if (!$constructor) {
            return $reflectionClass->newInstance();
        }
        $param = [];
        foreach ($constructor->getParameters() as $parameter) {
            if (isset($params[$parameter->name])) {
                $arg = $params[$parameter->name];
                if (class_exists($arg)) {
                    $args = $params[$arg] ?? [];
                    $arg = $this->build($arg, $args);
                }
                $param[$parameter->name] = $arg;
            } else {
                if ($parameter->isDefaultValueAvailable()) {
                    $param[$parameter->name] = $parameter->getDefaultValue();
                } elseif ($parameter->allowsNull()) {
                    $param[$parameter->name] = null;
                } else {
                    $param[$parameter->name] = $this->build($parameter->getClass());
                }
            }
        }
        return $reflectionClass->newInstanceArgs($param);

    }
}