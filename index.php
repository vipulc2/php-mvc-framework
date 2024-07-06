<?php

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

spl_autoload_register(function (string $class_name) {

    require "src/" . str_replace("\\", "/", $class_name ) . ".php";
});


$router = new Framework\Router;

/*
The Routes check with the path(url) in the order they are added so we add specific routes first and then add the generic ones
The route {controller}/{action} is the most basic one thus it should be at the last so that the other routes gets checked.
*/

// This route is added with a custom RegEx the \d+ will be checked in pattern checking class
$router->add("{controller}/{id:\d+}/{action}");
// This is the same way the \w is used to matches all characters including hyphens
$router->add("/product/{slug:[\w-]+}", ["controller" => "products", "action" => "show"]);

$router->add("/products", ["controller" => "products", "action" => "index"]);
$router->add("/home/index", ["controller" => "home", "action" => "index"]);
$router->add("/", ["controller" => "home", "action" => "index"]);
$router->add("{controller}/{action}");

$params = $router->match($path);

if ($params === false) {
    exit("no routes matched");
}

$action = $params["action"];
$controller = "App\Controllers\\" . ucwords($params["controller"]);

$controller_object = new $controller;

$controller_object->$action();