<?php
/**
 * Created by PhpStorm.
 * User: xing
 * Date: 18-12-9
 * Time: 上午11:59
 */

namespace Apps\Http\Controllers;


use Apps\Common\Contains;
use Apps\Http\Models\Model;
use Swoole\Http\Request;
use Swoole\Http\Response;

class Controller
{
    public function index(Request $request, Response $response)
    {
        $response->end('hello swoole');
    }

    public function aa(Model $model)
    {
        $s = ob_start();
        print_r($model);
        $f = ob_get_contents();
        Contains::$app->response->end($f);
    }

}