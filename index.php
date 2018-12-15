<?php

require __DIR__ . '/vendor/autoload.php';

$worker = new \Workerman\Worker('websocket://0.0.0.0:8001');
$worker->count = 4;

$worker->onConnect = function ($connection) {
    $connection->send('message');

    \Workerman\Lib\Timer::add(1, function () use ($connection) {
        $connection->send('Hello from server');
    });
};

$worker->onMessage = function ($connection, $data) {
    $connection->send($data);
};

\Workerman\Worker::runAll();
