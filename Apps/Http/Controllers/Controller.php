<?php
/**
 * Created by PhpStorm.
 * User: xing
 * Date: 18-12-9
 * Time: 上午11:59
 */

namespace Apps\Http\Controllers;


use Apps\Common\Config;
use Apps\Common\Request;
use Apps\Models\Posts;
use Illuminate\Database\Capsule\Manager as DB;

class Controller
{
    public function index(Config $config)
    {
        $a = DB::table('posts')->select('*')->get();
        (new \Illuminate\Database\Query\Builder);
        $a = Posts::find(1);
        print_r($a);
        return 'hello hi ......';
    }

    public function aa(Request $request)
    {
        $url = $request->getUri();
        return 'want love ' . $url;
        //$request->f
    }

}