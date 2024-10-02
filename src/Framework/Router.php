<?php

namespace Framework;

class Router {

    public array $routes = [];

    public function add(string $path, array $params = []): void {
        $this->routes[] = [
            "path" => $path,
            "params" => $params
        ];
    }

    // This function matches the Path(URL) with our defined Routes using the pattern that we created. This pattern is created using the Routing table variables
    public function match(string $path): array|bool {
        // Trims the path(url) so that we don't have to worry about path and route slashes
        $path = trim($path, "/");

        foreach ($this->routes as $route) {
            // Getting the variable pattern using the routing table
           $pattern = $this->getPatternFromRoutePath($route["path"]);

            // Finally using that pattern to match with the path(url)
            if (preg_match($pattern, $path, $matches)) {
                // Using the matches to trigger the Controller and Action classes by returning this
                $matches = array_filter($matches, "is_string", ARRAY_FILTER_USE_KEY);
                // There are some routes that we define with their own params (controller and action) and these are not variables but we are not using those so with this we can use those as well to initiate the Controller and Action classes
                $params = array_merge($matches, $route["params"]);

                return $params;
            }
        }

        return false;
    }

   /* Purpose of this function is to match the Routing table with RegEx and also to convert it into RegEx so the routing table 
   varialble like "controller" are first fetched and then used to create a pattern which is then used to compare the URL path. First the variable needs to be fetched from the routing table
    and we use specific RegEx Pattern to match the varialble name as the variable name should start with a letter and then it can have number and then after this we take out the variable name from that routing table between 
    the curly braces and then we use that variable name to be input in our pattern this pattern is then used to compare our 
    URL path and then we can use the varialbles to initiate the classes and methods baed on the variable and the value assocaited with that variable that we get from the actual URL path
   */
    private function getPatternFromRoutePath(string $route_path): string {

        $route_path = trim($route_path, "/");
        $segments = explode("/", $route_path);

        $segments = array_map(function(string $segment): string{

            /* This RegEx matches the Router variables that we created not the path(url)
                The If statement converts the segment into RegEx if there is a variable in the routing table
                In case there is no variable it will not go inside this if statement and then it will try to match the literal string that we get from the routing table
                For exampel we get "product" in our routing table this literal string then 
                it will not go inside this if statement and then it will automatically get returned
            */
            if (preg_match("#^\{([a-z][a-z0-9]*)\}$#", $segment, $matches)) {
                /*
                This converts the router variables into RegEx to convert into segments
                We added [^/]* so that it can match anything in here except the forward slash so that the segments can remain separate
                */
                return "(?<" . $matches[1] . ">[^/]*)";
            }
            /*
            This is to check if the router varaible also has a RegEx in it for example the variables {id:\d} Here the \d is for checking the number [0-9] and .+ makes sure that the \d or any other
            regular expression passes to the pattern and matches so that it can be handled in this if statement. same goes for \w
            */
            if (preg_match("#^\{([a-z][a-z0-9]*):(.+)\}$#", $segment, $matches)) {

                return "(?<" . $matches[1] . ">" . $matches[2] . ")";
            }

            return $segment;

        }, $segments);

        /*
        These segments are then returned so that it can be mathced with the path(URL) converting the segments into a whole RegEx for pattern matching.
        The "i" indicates to check for case insenitive pattern. "u" helps to match any unicode character
        */
        return "#^" . implode("/", $segments) . "$#iu";

    }


}