<?php

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
// $router->add("{controller}/{id:\d+}/{action}");

$router->add("/{controller}/{id:\d+}/show", ["action" => "show"]);
$router->add("/{controller}/{id:\d+}/edit", ["action" => "edit"]);
$router->add("/{controller}/{id:\d+}/update", ["action" => "update"]);
$router->add("/{controller}/{id:\d+}/delete", ["action" => "delete"]);
$router->add("/{controller}/{id:\d+}/destroy", ["action" => "destroy", "method" => "post"]);


$router->add("/home/index", ["controller" => "home", "action" => "index"]);
$router->add("/products", ["controller" => "products", "action" => "index"]);
$router->add("/", ["controller" => "home", "action" => "index"]);
$router->add("{controller}/{action}");

return $router;