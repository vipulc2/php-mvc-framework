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

    // This function matches the Path(URL) with our defined Routes
    public function match(string $path): array|bool {
        // Trims the path(url) so that we don't have to worry about path and route slashes
        $path = trim($path, "/");

        foreach ($this->routes as $route) {
            // Getting the variable pattern
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

    private function getpatternFromRoutePath(string $route_path): string {

        $route_path = trim($route_path, "/");
        $segments = explode("/", $route_path);

        $segments = array_map(function(string $segment): string{

            // This RegEx matches the Router variables that we created not the path(url)
            if (preg_match("#^\{([a-z][a-z0-9]*)\}$#", $segment, $matches)) {
                /*
                This converts the router variables into RegEx to convert into segments
                We added [^/]* so that it can match anything in here except the forward slash so that the segments can remain separate
                */
                return "(?<" . $matches[1] . ">[^/]*)";
            }
            /*
            This is to check if the router varaible also has a RegEx in it for example the variables {id:\d} Here the \d is for checking the number [0-9] and .+ makes sure that the \d or any other regular expression passes to the pattern and matches so that it can be handled in this if statement. same goes for \w
            */
            if (preg_match("#^\{([a-z][a-z0-9]*):(.+)\}$#", $segment, $matches)) {

                return "(?<" . $matches[1] . ">" . $matches[2] . ")";
            }

            return $segment;

        }, $segments);

        /*
        These segments are then returned so that it can be mathced with the path(URL) converting the segments into a whole RegEx for pattern matching. The "i" indicates to check for case insenitive pattern. "u" helps to match any unicode character
        */
        return "#^" . implode("/", $segments) . "$#iu";

    }


}