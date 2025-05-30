<?php

namespace Core;

use App\Helpers\ResponseHelper;

/**
 * Router class for controlling the routes
 */
class Router
{
    protected array $routes = []; // the routes
    protected array $params = []; // the params for the route

    // For exposing protected properties for the outside
    public function __get(string $property): array|false
    {
        switch ($property) {
            case 'routes':
            case 'params':
                return $this->$property;
            default:
                return false;
        }
    }

    /**
     * Method for adding a new route
     * @param string $route, the route / endpoint
     * @param array $params, controller + action params (+ method if given)
     */
    public function add(string $route, array $params): void
    {
        // Convert {id} from endpoints to regex group - e.g. #^/artists/(?P<id>[a-zA-Z0-9_-]+)$#
        $route = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '(?P<\1>[a-zA-Z0-9_-]+)', $route);
        $route = '#^' . $route . '$#';

        // Check if method is given - else set to GET and store in $method
        $method = $params['method'] ?? 'GET';
        // remove the method from the params (we now use $method)
        unset($params['method']);

        // Store the route + $method in routes (the same endpoints gets multiple method keys with the params as values)
        $this->routes[$route][$method] = $params;

        // e.g:
        // '#^/artists/(?P<id>[a-zA-Z0-9_-]+)$#' => [
        //'GET' => [
        //    'controller' => 'Artists',
        //    'action' => 'find'
        //],
        //'DELETE' => [
        //    'controller' => 'Artists',
        //    'action' => 'delete'
        //]
    }

    /**
     * Method for checkin if there is a match in URL and method in routing table
     * @param string $url, the URL path
     * @param string $method, the method of the request
     * @return bool, true if there is a match
     */
    public function match(string $url, string $method): bool
    {
        // Loop through all registered routes (which is in a regex pattern) and their methods
        foreach ($this->routes as $pattern => $methods) {

            // Check if the current route pattern matches the requested URL
            // If successful, preg_match fills $matches with both numbered and named capture groups: [0 => '/artists/123', 'id' => '123']
            if (preg_match($pattern, $url, $matches)) {

                // Check if the match has matching method as the requested URL
                if (isset($methods[$method])) {
                    // Get the route's handler params (controller and action)
                    $params = $methods[$method];

                    // Find all named params in matched, like 'id'
                    foreach ($matches as $key => $match) {
                        if (is_string($key)) {
                            $params[$key] = $match;
                        }
                    }
                    // save the new params for the route in params array
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
}