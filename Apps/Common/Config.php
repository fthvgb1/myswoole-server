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

    protected $configs = [];


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