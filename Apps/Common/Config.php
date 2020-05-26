<?php
/**
 * Created by PhpStorm.
 * User: xing
 * Date: 18-12-8
 * Time: 下午5:40
 */

namespace Apps\Common;


use Apps\Helpers\Arr;

class Config
{

    protected array $configs = [];
    protected static array $env = [];

    public static function getEnv($key, $default = ''): string
    {
        return isset(self::$env[$key]) ? self::$env[$key] : self::$env[$key] = $default;
    }


    public static function setEnv(): void
    {
        $path = dirname(dirname(__DIR__)) . '/.env';
        if ($path && file_exists($path)) {
            self::$env = parse_ini_file($path);
        }
    }

    public function __construct($configs)
    {
        $this->set($configs);
        $this->set([
            'ROOTPATH' => dirname(dirname(__DIR__))
        ]);
    }


    /**
     * 获取配置,支持.语法
     * @param string $key
     * @return array|mixed|null
     */
    public function get($key = '')
    {
        return $key ? Arr::get($this->configs, $key) : $this->configs;
    }


    public function set($data)
    {
        $this->configs = array_merge($this->configs, $data);
    }
}