<?php

class SocketService
{
    private $connections;
    private $worker;

    public function run()
    {
        $this->worker = new \Workerman\Worker('websocket://0.0.0.0:8001');
        $this->worker->count = 4;

        $this->worker->onConnect = function ($connection) {
            $connection->onWebSocketConnect = function ($connection, $header) {
                $this->connections[$_GET['userId']] = $connection;

                dump(array_keys($this->connections));
            };
        };

        $this->worker->onMessage = function ($connection, $data) {
            dump(count($this->connections));
            foreach ($this->connections as $key => $connection) {
                dump('send message to user: ' . $key);
                $connection->send($data);
            }
        };
        \Workerman\Worker::runAll();
    }
}
