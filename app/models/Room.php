<?php

namespace App\models;

use App\Services\DataBase;
use App\Services\Interfaces\RoomInterfaces;
use Krugozor\Database\MySqlException;

class Room implements RoomInterfaces
{
    private $table = 'rooms';
    private $db;

    public function __construct()
    {
        $this->db = new DataBase();
    }

    /** This method wich create room.
     * @param $data - gets an array with data.
     * @return mixed|string Returns success or mistake.
     */
    public function store($data) {
        $checkRoom = $this->checkRoom($data);
        if(isset($checkRoom['error'])){
            return ['error' => $checkRoom['error'], 'type' => 'store_room'];
        }
        try {
            return $this->addPassword($data);
        } catch (MySqlException $e) {
            return "<b>Выбрашено исключение:</b> ".$e->getMessage()."\n";
        }
    }

    /** This method add password.
     * @param $data - gets an array with data.
     * @return string Returns success or mistake.
     * @throws MySqlException Returns an error if there is an error.
     */
    private function addPassword($data) {
        if(isset($data['password'])){
            $this->db->connection()->query('INSERT INTO `'.$this->table.'` SET ?As', [
                'name_room' => $data['room'],
                'founder' => $data['name'],
                'password' => password_hash($data['password'], PASSWORD_DEFAULT)
            ]);
            return "Room created successfully!";
        } elseif (empty($data['password'])) {
            $this->db->connection()->query('INSERT INTO `'.$this->table.'` SET ?As', [
                'name_room' => $data['room'],
                'founder' => $data['name']
            ]);
            return "Room created successfully!";
        } else {
            throw new MySqlException();
        }
    }

    /** This method check a room.
     * @param $data - gets an array with data.
     * @return array|string Returns array.
     */
    private function checkRoom($data) {
        $room = $this->db->connection()->query("SELECT * FROM `rooms` WHERE `name_room` = '?s'", $data['room'])->fetchAssoc();
        try {
            if(isset($room['name_room']) != $data['room']) {
                return "Success";
            } else {
                throw new MySqlException('Такая комната уже существует!');
            }
        }catch (MySqlException $e){
            return ['error' => $e->getMessage()];
        }
    }

    /** This method verifies the user.
     * @param $data - gets an array with data.
     * @return mixed|string|string[] - Returns message about verify.
     */
    public function verifyUser($data) {
        $room = $this->checkRoom($data);
        $password = $this->checkPassword($data);

        if(empty($room['error'])) {
            return ['error' => 'Комната не найдена!', 'type' => 'connection_room'];
        } elseif (isset($password['error'])) {
            return ['error' => $password['error'], 'type' => 'connection_room'];
        }
        return "No problem connection";

    }

    /** This method check a password.
     * @param $data - gets an array with data.
     * @return array|string - Returns message about checked password.
     */
    private function checkPassword($data) {
        // Find password in DB.
        $password = $this->db->connection()->query("SELECT password FROM `rooms` WHERE `name_room` = '?s'", $data['room'])->fetchAssoc();
        if(empty($password['password'])) {
            return "Success";
        } else {
            try{
                return $this->passwordVerify($data, $password);
            } catch (\Exception $e) {
                return ['error' => $e->getMessage()];
            }
        }
    }

    /** This method verifies the password.
     * @param $data - gets an array with data.
     * @param $password - gets password.
     * @return string - Returns message about verify.
     * @throws \Exception - Returns an error if there is an error.
     */
    private function passwordVerify($data, $password) {
        if(empty($data['password'])) {
            throw new \Exception('Введите пароль от комнаты!');
        } elseif (password_verify($data['password'], $password['password'])){
            return "Success";
        } else {
            throw new \Exception('Пароль не верный!');
        }
    }
}