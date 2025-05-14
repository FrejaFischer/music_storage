<?php

/**
 * Base controller
 */

 namespace Core;

 use App\Config;
 use App\Helpers\ResponseHelper;

 abstract class Controller
 {
    protected array $routeParams = []; 

    public function __construct(array $routeParams)
    {
        // Check if route requires API key
        $this->requiresApiKey();

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
            ResponseHelper::jsonError("System failed. Contact help");

            throw new \Exception("Method $method not found in controller " . get_class($this), 500);
        }
    }

    /**
     * Method for handling the need for an API key
     */
    private function requiresApiKey(): void
    {
        // Whitelisted public paths (no key required)
        $publicPaths = [Config::$BASE_URL]; // The base of the API

        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (in_array($path, $publicPaths)) {
            return; // Skip API key check
        }

        // Check if API key is given and is valid
        $providedKey = $_GET['api_key'] ?? null;

        if (!in_array($providedKey, Config::$API_KEYS)) {
            ResponseHelper::jsonError('Forbidden: Invalid or missing API key');

            throw new \Exception('Forbidden: Invalid or missing API key', 403);
        }
    }

    /**
     * Method for validating an integer ID
     * @param string|null $id - the id to validate
     * @param string $type - What type of id it is (e.g. artist id)
     * @return int - the id if valid
     */
    protected function validateID(string|null $id, string $type='ID'): int
    {
         // Check if the id is not found (null)
         if (!$id) {
            ResponseHelper::jsonError("Missing $type");

            throw new \Exception("Missing $type", 400);
        }
        
        // Check if the id is not an int or numeric
        if (!filter_var($id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
            ResponseHelper::jsonError("Invalid $type");

            throw new \Exception("Invalid $type", 400);
        }

        // return valid ID
        return (int)$id;
    }

 }