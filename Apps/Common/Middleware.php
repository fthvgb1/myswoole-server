<?php
/**
 * Created by PhpStorm.
 * User: xing
 * Date: 18-12-8
 * Time: 下午5:18
 */

namespace Apps\Common;


class Middleware
{
    public static array $data = [];

    public static function add($name, $value)
    {
        static::$data[$name][] = $value;
    }
}