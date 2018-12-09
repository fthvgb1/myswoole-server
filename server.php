<?php
/**
 * Created by PhpStorm.
 * User: xing
 * Date: 18-12-8
 * Time: 下午1:07
 */

include 'vendor/autoload.php';


$server = new swoole_http_server('0.0.0.0', '9999');
$server->on('start', function (swoole_http_server $server) {
    echo 'swoole server started', PHP_EOL,
    'http://127.0.0.1:9999', PHP_EOL;
});

$server->on('request', function (swoole_http_request $request, swoole_http_response $response) {
    //print_r($request);
    //print_r($request->server);
    //print_r($_SERVER);
    $response->end("<h1>hello swoole</h1>");
});

$server->start();