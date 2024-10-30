<?php

declare(strict_types=1);

set_error_handler(function(int $errno, string $errstr, string $errfile, int $errline): bool {

    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);


});

set_exception_handler(function (Throwable $exception) {

    if ($exception instanceof Framework\Exceptions\PageNotFoundException) {

        http_response_code(404);

        $template = "404.php";
    } else {

        http_response_code(500);

        $template = "500.php";
    }

    
    $show_errors = false;
    
    if ($show_errors) {
        
        ini_set("display_errors", "1");
    } else {
        
        ini_set("display_errors", "0");
    
        ini_set("log_errors", "1");
    
        require "views/$template";
    }

    throw $exception;

});


$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($path === false) {

    throw new UnexpectedValueException("Malformed URL: '{$_SERVER["REQUEST_URI"]}'");
}

spl_autoload_register(function (string $class_name) {

    require "src/" . str_replace("\\", "/", $class_name ) . ".php";
});


$router = new Framework\Router;

/*
The Routes check with the path(url) in the order they are added so we add specific routes first and then add the generic ones
The route {controller}/{action} is the most basic one thus it should be at the last so that the other routes gets checked.
*/


$router->add("/admin/{controller}/{action}", ["namespace" => "Admin"]);
// For this router we have to take care of the name here like title, id, and page have the same named parameter as the method in Products controller called showPage() action method
$router->add("/{title}/{id:\d+}/{page:\d+}", ["controller" => "products", "action" => "showPage"]);
// This is the same way the \w is used to matches all characters including hyphens
$router->add("/product/{slug:[\w-]+}", ["controller" => "products", "action" => "show"]);
// This route is added with a custom RegEx the \d+ will be checked in pattern checking class
$router->add("{controller}/{id:\d+}/{action}");

$router->add("/products", ["controller" => "products", "action" => "index"]);
$router->add("/home/index", ["controller" => "home", "action" => "index"]);
$router->add("/", ["controller" => "home", "action" => "index"]);
$router->add("{controller}/{action}");

$container = new Framework\Container;

$container->set(App\Database::class, function(){

    return new App\Database("localhost", "product_db", "product_db_user", "secret");

});

$dispatcher = new Framework\Dispatcher($router, $container);

$dispatcher->handle($path);