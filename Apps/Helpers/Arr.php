<?php
/**
 * Created by PhpStorm.
 * User: xing
 * Date: 18-12-8
 * Time: 下午11:46
 */

namespace Apps\Helpers;


class Arr
{
    /**
     * 数据单个字段排序
     * @param $data
     * @param $field
     * @param int $sort
     */
    public static function keySort(&$data, $field, $sort = SORT_ASC)
    {
        $tmp = [];
        foreach ($data as $v) {
            $tmp[] = $v[$field];
        }
        array_multisort($tmp, $sort, $data);
    }


    /**
     * 以数组的某列为key
     * @param $data
     * @param string|array $index
     * @return array
     */
    public static function indexBy($data, $index = 'id')
    {
        return is_array($index) ? static::indexByArr($data, $index) : static::indexByKey($data, $index);
    }

    /**
     * 设置数据Key
     * @param array $data
     * @param array $index 'field',['key'=>'val'],['key'=>['key1','key2']]
     * @return array
     */
    public static function indexByArr($data, $index)
    {
        $key = key($index);
        $val = current($index);
        if (is_string($val)) {
            return array_column($data, $val, $key);
        }
        $arr = [];
        foreach ($data as $datum) {
            foreach ($val as $item) {
                $arr[$datum[$key]][$item] = $datum[$item];
            }
        }
        return $arr;
    }

    public static function indexByKey($data, $index)
    {
        $arr = [];
        foreach ($data as $datum) {
            $arr[$datum[$index]] = $datum;
        }
        return $arr;
    }

    /**
     * 获取数组的值，支持 . 语法
     * @param $arr array
     * @param $key string
     * @return null|array
     */
    public static function get($arr, $key)
    {
        $keys = explode('.', $key);
        foreach ($keys as $key) {
            if (!isset($arr[$key])) {
                return null;
            }
            $val = $arr[$key];
            $arr = $val;
        }
        return $arr;
    }

    public static function del(&$arr, $keys)
    {
        if (is_string($keys)) {
            $fields = explode(',', $keys);
        } else {
            $fields = $keys;
        }
        foreach ($fields as $item) {
            unset($arr[$item]);
        }
    }


    /**
     * 获取数组纵列数据
     * @param $arr array
     * @param $keys string|array
     * @return array
     */
    public function columns($arr, $keys)
    {
        if (is_string($keys)) {
            return array_column($arr, $keys);
        }
        $res = [];
        foreach ($keys as $key => $item) {
            $res[$key] = array_column($arr, $item);
        }
        return $res;
    }
}