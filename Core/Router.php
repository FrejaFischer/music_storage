<?php

namespace Core;

/**
 * Router class for controlling the routes
 */
class Router
{
    protected array $routes = []; // the routes
    protected array $params = []; // the params for the routes

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
        // Convert {id}'s from endpoint / route to regex group
        $route = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '(?P<\1>[a-zA-Z0-9_-]+)', $route);
        $route = '#^' . $route . '$#';
        
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
        // Loop through all registered routes
        foreach ($this->routes as $pattern => $params) {

            // Check if the current route pattern matches the requested URL
            if (preg_match($pattern, $url, $matches)) {

                // Make sure the HTTP method also matches
                if ($params['method'] === $method) {

                    // Extract named parameters from the regex match (e.g. ['album_id' => '42']) from regex
                    foreach ($matches as $key => $match) {
                        if (is_string($key)) {
                            $params[$key] = $match; // Add to route params
                        }
                    }
                    $this->params = $params;
                    return true;
                }
            }
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

            // Check if the controller class exists
            if (class_exists($controller)) {
                $controllerInstance = new $controller($this->params);
                $action = $this->params['action'];

                // Check if the controllers class is callable
                if (is_callable([$controllerInstance, $action])) {
                    $controllerInstance->$action();
                    return true;
                } else {
                    ResponseHelper::jsonError("System failed. Contact help");
                    throw new \Exception("Method $action in controller $controller not found", 500);
                }
            } else {
                ResponseHelper::jsonError("System failed. Contact help");
                throw new \Exception("Controller class $controller not found", 500);
            }
        } else {
            ResponseHelper::jsonError("URL $url not found");
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