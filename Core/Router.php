<?php

namespace Core;

/**
 * Router class for controlling the routes
 */
class Router
{
    protected array $routes = []; // the route
    protected array $params = []; // the params for 

    // Is this nessesary?
    // public function __get(string $property): array|false
    // {
    //     switch ($property) {
    //         case 'routes':
    //         case 'params':
    //             return $this->$property;
    //         default:
    //             return false;
    //     }
    // }

    /**
     * Method for adding a new route
     * @param string $route, the route / endpoint
     * @param array $params, controller + action params (+ method if given)
     */
    public function add(string $route, array $params): void
    {
        // Check if method is given - else set to GET
        if (!isset($params['method'])) {
            $params['method'] = 'GET';
        }
        $this->routes[$route] = $params;
    }

    /**
     * Method for checkin if there is a match in URL and method in routing table
     * @param string $url, the URL path
     * @param string $method, the method of the request
     * @return bool, true if there is a match
     */
    public function match(string $url, string $method): bool
    {
        // // If the URL ends in an ID, it is extracted
        // $parts = explode('/', $url);
        // $lastPart = end($parts);
        // if(is_numeric($lastPart)) {
        //     $id = $lastPart;
        //     $url = substr($url, 0, strlen($url) - (strlen($lastPart) + 1));
        // } else {
        //     $id = 0;
        // }

        if(isset($this->routes[$url]) && $this->routes[$url]['method'] === $method) {
            $this->params = $this->routes[$url];
            // if($id!==0) {
            //     $this->params['id'] = $id;
            // }
            return true;
        }
        return false;
    }

    /**
     * Method for calling the controller corresponding to the URL it receives
     * @param string $url, the URL to dispatch
     * @param string $method, the method of the request
     */
    public function dispatch(string $url, string $method): bool|string
    {
        // $url = $this->removeQueryStringVariables($url);

        // Check if there is a match
        if ($this->match($url, $method)) {
            // Set the controller
            $controller = $this->params['controller'];
            $controller = "App\Controllers\\$controller";

            // Check if the controllers class exists
            if (class_exists($controller)) {
                $controllerInstance = new $controller($this->params);
                $action = $this->params['action'];

                // Check if the controller instance is callable
                if (is_callable([$controllerInstance, $action])) {
                    $controllerInstance->$action();
                    return true;
                } else {
                    throw new \Exception("Method $action in controller $controller not found");
                }
            } else {
                throw new \Exception("Controller class $controller not found");
            }
        } else {
            throw new \Exception("URL $url not found", 404);
        }
    }

    // protected function removeQueryStringVariables(string $url): string
    // {
    //     // Notice that PHP replaces the "?" with "&" when it receives the URL via $_SERVER['QUERY_STRING']
    //     $parts = explode('&', $url, 2);
    //     $url = strpos($parts[0], '=') ? '' : $parts[0];
    //     return $url;
    // }
}