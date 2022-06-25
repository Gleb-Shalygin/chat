<?php
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use App\Services\Websocket;
use Ratchet\WebSocket\WsServer;

require_once __DIR__.'/vendor/autoload.php';

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Websocket()
        )
    ),
    8777
);

$server->run();