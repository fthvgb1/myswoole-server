<?php
/**
 * Created by PhpStorm.
 * User: xing
 * Date: 18-12-8
 * Time: 下午5:39
 */

use Apps\Common\Config;
use Apps\Common\Contains;
use Apps\Common\Request;
use Apps\Common\Response;
use Apps\Http\Controllers\Controller;
use Illuminate\Database\Capsule\Manager;

Config::setEnv();

return [
    'db' => [
        'driver' => 'mysql',
        'host' => envv('DB_HOST', '127.0.0.1'),
        'port' => envv('DB_PORT', '3306'),
        'database' => envv('DB_DATABASE', 'forge'),
        'username' => envv('DB_USERNAME', 'forge'),
        'password' => envv('DB_PASSWORD', ''),
        'unix_socket' => envv('DB_SOCKET', ''),
        'prefix' => 'wp_',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'strict' => true,
        'engine' => null,
    ],
    'components' => [
        'request' => [Request::class, [$_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER]],
        'response' => Response::class,
        'db' => [Manager::class, [], function (Manager $manager) {
            $db = Contains::getApp()->get('config')->get('db');
            $manager->addConnection($db);
            $manager->setAsGlobal();
            $manager->bootEloquent();
        }]
    ],

    'route' => [
        [
            '/' => Controller::class . '@index',
            'aa' => Controller::class . '@aa',
            '/favicon.ico' => function () {
                return readfile(__DIR__ . '/../favicon.ico');
            },
        ],
        'get post@auth:user#aa:bb' => [

        ],
        'route regex' => [
            [
                'method' => ['get', 'post'],
                'middleWare' => [],
                'rule' => function ($route) {
                    if (preg_match('@^(?P<controller>\w+)/(?P<id>\d+)$@u', $route, $matches) > 0) {
                        $id = $matches['id'];
                        $controller = $matches['controller'];
                        $model = call_user_func_array(['Apps\Models\\' . ucfirst($controller), 'find'], [$id]);

                        return [['Apps\Http\Controllers\\' . ucfirst($controller), 'view'], [$model]];
                    }
                    return false;
                }
            ],
            [
                'method' => ['get', 'post'],
                'middleWare' => [],
                'rule' => function ($route) {
                    if (preg_match('@^(?P<controller>\w+)/(?P<method>\w+)$@u', $route, $matches) > 0) {
                        $controller = $matches['controller'];
                        $method = $matches['method'];
                        return [['Apps\Http\Controllers\\' . ucfirst($controller), $method], null];
                    }
                    return false;
                }
            ],
        ]

    ]
];