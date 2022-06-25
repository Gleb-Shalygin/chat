<?php

namespace App\exceptions;

use Krugozor\Database\MySqlException;

class RoomException extends MySqlException
{
    private const MESSAGE = 'This is message about error!';
    private const CODE = 500;

    public function __construct()
    {
        parent::__construct(self::MESSAGE, self::CODE);
    }

    public function storeMessage() {
        return 'Room not created!';
    }

    public function checkRoomMessage(){
        return 'Комната уже существует!';
    }
}