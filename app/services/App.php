<?php

namespace App\Services;

use App\Services\Components\DataBase;

class App {
    public function start() {
        DataBase::connection();
    }
}