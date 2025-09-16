<?php
// core/Router.php
class Router {
    protected $routes = [];

    public function add($route, $params = []) {
        $this->routes[$route] = $params;
    }

    public function dispatch($url) {
        $url = $this->removeQueryStringVariables($url);
        if (array_key_exists($url, $this->routes)) {
            $params = $this->routes[$url];
            $controller = $this->convertToStudlyCaps($params['controller']);
            $controller = "app\\controllers\\$controller";
            if (class_exists($controller)) {
                $controller_object = new $controller;
                $action = $this->convertToCamelCase($params['action']);
                if (is_callable([$controller_object, $action])) {
                    $controller_object->$action();
                } else {
                    echo "Method $action not found";
                }
            } else {
                echo "Controller $controller not found";
            }
        } else {
            echo "No route matched.";
        }
    }

    protected function convertToStudlyCaps($string) {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    protected function convertToCamelCase($string) {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    protected function removeQueryStringVariables($url) {
        if ($url != '') {
            $parts = explode('&', $url, 2);
            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }
        return $url;
    }
}

