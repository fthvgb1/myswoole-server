<?php
/**
 * Created by PhpStorm.
 * User: xing
 * Date: 18-12-8
 * Time: 下午5:39
 */

return [
    'mysql' => '',

    'route' => [
        'map' => [
            '/' => \Apps\Http\Controllers\Controller::class . '@index',
            'aa' => \Apps\Http\Controllers\Controller::class . '@aa',
            '/favicon.ico' => function (\Swoole\Http\Request $request, \Swoole\Http\Response $response, \Apps\Http\Models\Model $model) {
                $response->header('content-type', 'image/jpg');
                $response->sendfile('1.jpg');
            }
        ],
        'regex' => [
            "<controller:\w+>/<id:\d+>" => "<controller>/view",
            "<controller:\w+>/<action:\w+>" => "<controller>/<action>"
        ]
    ]
];