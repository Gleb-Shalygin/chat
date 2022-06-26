<?php
namespace App\Services;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Websocket implements MessageComponentInterface {
    protected $clients; // An array that accepts an object with clients from storage
    protected $rooms; // An array that accepts an array with rooms
    protected $users; // An array that accepts an array with users
    protected $users_name; // An array that accepts an array of usernames

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    /** This method opens connect
     * @param ConnectionInterface $conn
     * @return void
     */
    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    /** This method works with a new message
     * @param ConnectionInterface $from
     * @param $msg
     * @return void
     */
    public function onMessage(ConnectionInterface $from, $msg) {
        $msg = json_decode($msg);
        if($msg->message == 'new room'){
            $this->rooms[$msg->value][$from->resourceId] = $from;
            $this->users[$from->resourceId] = $msg->value;
            $this->users_name[$msg->value][$from->resourceId] = $msg->user;
            $this->checkingForExistence($msg, $from);
            $this->addUsersAndConnect($msg->value);
        } elseif ($msg->message == 'new message') {
            $room = $this->users[$from->resourceId];
            $this->newMessages($room,$from, $msg);
        }
    }

    /** This method clears users and adds new
     * @param ConnectionInterface $conn
     * @return void
     */
    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);
        $room = $this->users[$conn->resourceId];
        $this->unsetArrays($room, $conn);
        $this->addUsersAndConnect($room);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    /** This method outputs errors and close connect
     * @param ConnectionInterface $conn
     * @param \Exception $e
     * @return void
     */
    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    /** This method adds new users
     * @param $value
     * @return void
     */
    private function addUsersAndConnect($value) : void {
        $users = [];
        foreach ($this->users_name[$value] as $user) {
            $users[] = $user;
        }
        $message = ['message' => 'connection', 'users' => $users];
        foreach ($this->rooms[$value] as $client){
            $client->send(json_encode($message));
        }
    }

    /** This method clears the array
     * @param $room
     * @param $conn
     * @return void
     */
    private function unsetArrays($room, $conn) : void {
        unset($this->rooms[$room][$conn->resourceId]);
        unset($this->users[$conn->resourceId]);
        unset($this->users_name[$room][$conn->resourceId]);
    }

    /** This method sends message
     * @param $message
     * @param $client
     * @param $msg
     * @param $user
     * @param $status
     * @return void
     */
    private function sendMessage($message, $client, $msg, $user, $status) : void {
        $message = ['message' => $message, 'value' => $msg->value, 'user' => $user, 'status' => $status];
        $client->send(json_encode($message));
    }

    /** This method sends is new messages
     * @param $room
     * @param $from
     * @param $msg
     * @return void
     */
    private function newMessages($room, $from, $msg) : void{
        foreach ($this->rooms[$room] as $client) {
            if($from !== $client) {
                $user = $this->users_name[$room][$from->resourceId];
                $this->sendMessage("message",$client, $msg, $user, "users");
            } else {
                $this->sendMessage("message",$client, $msg, "Вы", "user");
            }
        }
    }

    /** This method checkings existence users
     * @param $msg
     * @param $from
     * @return void
     */
    private function checkingForExistence($msg, $from) : void {
        $n = 0;
        foreach ($this->users_name[$msg->value] as $user) {
            if($user == $msg->user) {
                $n++;
            }
        }
        if($n > 1) {
            echo "В комнатате два пользователя с одинаковым именем!\n";
            $message = ['message' => 'onclose', 'value' => 'Такой пользователь уже существует!', 'user' => $user];
            $from->send(json_encode($message));
        }
    }
}
