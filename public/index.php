<?php
/**
 * Front controller
 */


/**
 * It requires all class files instead of loading them one by one
 */

spl_autoload_register(function ($class) {
    $root = dirname(__DIR__);
    $file = $root . '/' . str_replace('\\', '/', $class) . '.php';
    if (is_readable($file)) {
        require $file;
    }
});

/**
 * Load and set the database values from .env file once
 */
\App\Config::init();

/**
* Custom error and exception handling
*/
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

/**
 * Set content type in header to expect JSON
 */
header('Content-Type: application/json');

/**
 * Routing
 */
$router = new Core\Router();

//// Root ////
$router->add('',[
    'controller' => 'Indexs',
    'action' => 'index'
]);

//// Artists ////
$router->add('artists',[
    'controller' => 'Artists',
    'action' => 'get'
]);
$router->add('artists/{artist_id}',[
    'controller' => 'Artists',
    'action' => 'find'
]);
$router->add('artists/{artist_id}/albums',[
    'controller' => 'Artists',
    'action' => 'album'
]);


//// Fake routes for testing ////
$router->add('test',[
    'controller' => 'test',
    'action' => 'get'
]);
$router->add('test2',[
    'controller' => 'Artists',
    'action' => 'test'
]);

/**
 * Route dispatch
 */

// Extract the route from the URI, stripping off base path and query string
$requestUri = $_SERVER['REQUEST_URI']; // e.g. /exam/music_storage/public/artists
$scriptName = $_SERVER['SCRIPT_NAME']; // e.g. /exam/music_storage/public/index.php
$basePath   = dirname($scriptName); // e.g. /exam/music_storage/public

// Remove base path from URI - Get the endpoint
$relativeUrl = preg_replace('#^' . preg_quote($basePath) . '/?#', '', $requestUri); // e.g. artists (with query params if present)

// Remove query string
$url = strtok($relativeUrl, '?'); // e.g. artists

$method = $_SERVER['REQUEST_METHOD'];
$router->dispatch($url, $method);

// // DEBUGGING WITH LOGGING ////
// $log = dirname(__DIR__) . '/logs/' . date('Y-m-d') . '.html';
// // Format the log entry (you can customize this)
// $entry = date('H:i:s') . " - " . htmlspecialchars($relativeUrl) . "<br>\n";
// // Append to the log file
// file_put_contents($log, $entry, FILE_APPEND);

// // OLD METHOD ////
// $url = $_SERVER['QUERY_STRING'];
// $method = $_SERVER['REQUEST_METHOD'];
// $router->dispatch($url, $method);