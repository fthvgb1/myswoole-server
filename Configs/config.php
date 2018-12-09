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
        'map' => [],
        'preg' => [
            "<controller:\w+>/<id:\d+>" => "<controller>/view",
            "<controller:\w+>/<action:\w+>" => "<controller>/<action>"
        ]
    ]
];