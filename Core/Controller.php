<?php

/**
 * Base controller
 */

 namespace Core;

 abstract class Controller
 {
    protected array $routeParams = []; 

    public function __construct(array $routeParams)
    {
        header('Content-Type: application/json');

        // When instantiated, sets routes parameters
        $this->routeParams = $routeParams;
    }

    public function __call(string $name, array $args)
    {
        $method = "{$name}Action"; //All controllers methods ends with Action

        // Check if method exists in controller
        if (method_exists($this, $method)) {
            // Calls method from the controller instance, and send arguments along 
            // (or nothing if there is no arguments)
            call_user_func_array([$this, $method], $args); 
        } else {
            throw new \Exception("Method $method not found in controller " . get_class($this));
        }
    }

    protected function jsonResponse(mixed $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        echo json_encode([
            'status' => 'success',
            'data' => $data
        ]);
        exit;
    }

    protected function jsonError(string $message, int $statusCode = 400): void
    {
        http_response_code($statusCode);
        echo json_encode([
            'status' => 'error',
            'message' => $message
        ]);
        exit;
    }
 }