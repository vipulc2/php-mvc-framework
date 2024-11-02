<?php

declare(strict_types=1);

namespace Framework;

use ReflectionMethod;
use Framework\Exception\PageNotFoundException;
use UnexpectedValueException;

/*
We are using ReflextionMethod to get the parameter name of the methods in a certain class. Here it is the Products controller class from wherewe can get all the parameter names and then we can use that to get the values from router array and use it when we are called $action in the controller. This way the parameters are used automatically when the $action is called 
*/

class Dispatcher {

    public function __construct(private Router $router, private Container $container) {

    }

    public function handle(Request $request) {

        $path = $this->getPath($request->uri);

        $params = $this->router->match($path, $request->method);

        if ($params === false) {
            throw new PageNotFoundException("No routes matched for '$path' with method '{$request->method}'");
        }

        $action = $this->getActionName($params);
        $controller = $this->getControllerName($params);

        $controller_object = $this->container->get($controller);

        $controller_object->setRequest($request);
        $controller_object->setViewer($this->container->get(TemplateViewerInterface::class));

        //With this function's return value we get the list of parameter names of the action that we need to execute and it also has the values associated with the params from the URL. Then we can also give the values to the action method and handle the method according to the URL
        $args = $this->getActionArguments($controller, $action, $params);

        //Here we are using the ... to unpach the $args array because each action method may or may not be expecting an array and this sends the values inside the array as a list of individual arguments. Like we would usually do with a comma like $a, $b etc. The number of arguments and everything is handled automatically by getActionArguments() method
        $controller_object->$action(...$args);
    }

    //Basically we can tell which parameter is being called when a certain action method is supposed to be executed from within a controller
    private function getActionArguments(string $controller, string $action, array $params): array {

        $args = [];

        $method = new ReflectionMethod($controller, $action);

        foreach ($method->getParameters() as $parameter) {

            $name = $parameter->getName();

            $args[$name]= $params[$name];
        }
        return $args;
    }

    // This method is created to make the controller calling more robust
    private function getControllerName(array $params): string {

        $controller = $params["controller"];

        $controller = str_replace( "-", "", ucwords(strtolower($controller), "-"));
        
        $namespace = "App\Controllers";

        if (array_key_exists("namespace", $params)) {
            
            $namespace .= "\\" . $params["namespace"];
        }
        
        return $namespace . "\\" . $controller;
    }
    
    private function getActionName(array $params): string {
        
        $action = $params["action"];
        
        $action = lcfirst(str_replace( "-", "", ucwords(strtolower($action), "-")));

        return $action;

    }

    private function getPath(string $uri): string {

        $path = parse_url($uri, PHP_URL_PATH);

        if ($path === false) {

            throw new UnexpectedValueException("Malformed URL: '$uri'");
        }

        return $path;
    }


}