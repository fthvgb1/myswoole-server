<?php
/**
 * Created by PhpStorm.
 * User: xing
 * Date: 18-12-9
 * Time: 上午11:59
 */

namespace Apps\Http\Controllers;


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
        print_r($model);
    }

}