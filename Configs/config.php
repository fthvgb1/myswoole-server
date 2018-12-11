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
            'aa' => \Apps\Http\Controllers\Controller::class . '@aa'
        ],
        'preg' => [
            "<controller:\w+>/<id:\d+>" => "<controller>/view",
            "<controller:\w+>/<action:\w+>" => "<controller>/<action>"
        ]
    ]
];