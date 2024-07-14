<?php

namespace Framework;

/*
We are using ReflextionMethod to get the parameter name of the methods in a certain class. Here it is the Products controller class from wherewe can get all the parameter names and then we can use that to get the values from router array and use it when we are called $action in the controller. This way the parameters are used automatically when the $action is called 
*/
use ReflectionMethod;

class Dispatcher {

    public function __construct(private Router $router) {

    }

    public function handle(string $path) {

        $params = $this->router->match($path);

        if ($params === false) {
            exit("no routes matched");
        }

        $action = $params["action"];
        $controller = "App\Controllers\\" . ucwords($params["controller"]);

        $controller_object = new $controller;

        //With this function's return value we get the list of parameter names of the action that we need to execute and it also has the values associated with the params from the URL. Then we can also give the values to the action method and handle the method according to the URL
        $args = $this->getActionArguments($controller, $action, $params);

        //Here we are using the ... to unpach the $args array because each action method may or may not be expecting an array and this sends the values inside the array as a list of individual arguments. Like we would usually do with a comma like $a, $b etc. The number of arguments and everything is handled automatically by getActionArguments() method
        $controller_object->$action(...$args);
    }

    //Basically we can tell which parameter is being called when a certain action method is supposed to be executed from within a controller
    private function getActionArguments( string $controller, string $action, array $params): array {

        $args = [];

        $method = new ReflectionMethod($controller, $action);

        foreach ($method->getParameters() as $parameter) {

            $name = $parameter->getName();

            $args[$name]= $params[$name];
        }
        return $args;
    }
}