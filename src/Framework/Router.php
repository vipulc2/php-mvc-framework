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

    public function match(string $path): array|bool {

        foreach ($this->routes as $route) {

            $pattern = "#^/(?<controller>[a-z]+)/(?<action>[a-z]+)$#";

            $this->getPatternFromRoutePath($route["path"]);
    
            if (preg_match($pattern, $path, $matches)) {
    
                $matches = array_filter($matches, "is_string", ARRAY_FILTER_USE_KEY);
            }
    
            return $matches;

        }

        return false;
    }


}