<?php

namespace App\controllers;

use App\models\Room;

class RoomController
{
    private $db; // Gets object with wich need to work

    public function __construct(Room $room)
    {
        $this->db = $room;
    }

    /** It`s method which connects with chat
     * @param $data
     * @return array|mixed|string|string[]|void
     */
    public function connection($data)
    {
        switch ($data['type_connection']) {
            case "store_room":
                $result = $this->db->store($data);
                if($result == "Room created successfully!") {
                    return $data;
                }
                return $result;
            case "connection_room":
                $result = $this->db->verifyUser($data);
                if($result == "No problem connection") {
                    return $data;
                }
                return $result;
        }
    }

}