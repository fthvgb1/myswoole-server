<?php
/**
 * Created by PhpStorm.
 * User: xing
 * Date: 18-12-8
 * Time: 下午11:38
 */

use Apps\Common\Config;

if (!function_exists('envv')) {
    /**
     * @param $key
     * @param string $default
     * @return string
     */
    function envv($key, $default = ''): string
    {
        return Config::getEnv($key, $default);
    }
}