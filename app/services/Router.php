<?php

namespace App\Services;

class Router {
    private static $list = [];

    /**
     * @param $uri
     * @param $page_name
     * @param $class
     * @param $method
     * Метод роутинга get, заполняющий массив list[] данными
     */
    public static function get($uri, $page_name){
        self::$list[] = [
            "uri" => $uri,
            "page_name" => $page_name
        ];
    }

    /**
     * @param $uri
     * @param $class
     * @param $method
     * Метод роутинга post, заполняющий массив list[] данными
     */
    public static function post($uri, $class, $method) {
        self::$list[] = [
          "uri" => $uri,
          "class" => $class,
          "method" => $method,
          "post" => true
        ];
    }

    public static function enable(){
        $query = '';
        if(isset($_GET['q'])){
            $query = $_GET["q"];
        }
        foreach (self::$list as $route) {
            if($route["uri"] === '/'. $query) {
                if(isset($route['post']) && $route["post"] === true && $_SERVER['REQUEST_METHOD'] === "POST") {
                    $action = new $route["class"];
                    $method = $route["method"];
                    $action->$method($_POST);
                    die();
                } else {
                    require_once "client/views/".$route["page_name"].".php";
                    die();
                }
            }
        }

        self::not_found_page();

    }

    private static function not_found_page()
    {
        require_once "client/errors/404.php";
    }


}