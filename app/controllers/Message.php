<?php

namespace App\controllers;

use WebSocket;

class Message {
    public function message($date) {
        $message = [
            'message' => 'new order',
            'value' => [
                'name' => $date['name'], 'message' => $date['message']
            ]
        ];
        $client = new WebSocket\Client("ws://127.0.0.1:8777");
        $client->text(json_encode(['message' => 'new room', 'value' => 'one']));
        $client->text(json_encode($message));
        $client->close();
    }


    public function register($data) {
        return $data['name'];
    }

    public function connection($data) {

    }
}