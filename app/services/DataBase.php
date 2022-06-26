<?php

namespace App\Services;

use App\Services\Interfaces\DBInterfaces;
use Krugozor\Database\Mysql;

class DataBase implements DBInterfaces {
    private $config;


    public function __construct()
    {
        $this->config = require_once "config/db.php";
    }

    /** This method connects with database
     * @return Mysql|void
     * @throws \Krugozor\Database\MySqlException
     */
    public function connection()
    {
        if($this->config["enable"]){
            return Mysql::create($this->config["host"], $this->config["username"], $this->config["password"])->setDatabaseName($this->config["db"])->setCharset("utf8");
        }
    }
}