<?php
/**
 * Created by PhpStorm.
 * User: xing
 * Date: 18-12-8
 * Time: 下午1:07
 */


use Apps\Common\Dispatch;

ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'vendor/autoload.php';

$configs = require 'configs/config.php';


$app = new Dispatch(['configs' => $configs]);
$app->run();