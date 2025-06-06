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
 * Logging all requests
 */
use Core\Logger;

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri    = $_SERVER['REQUEST_URI'];
$queryString   = $_SERVER['QUERY_STRING'] ?? '';
$clientIp      = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$timestamp = date("Y-m-d H:i:s A");

$requestInfo = <<<INFO
$timestamp <br>
---------------------------------------------<br>
---------------------------------------------
<section>
    <p>Request method: $requestMethod</p>
    <p>Request Uri: $requestUri</p>
    <p>Query Strings (if any): $queryString</p>
    <p>Client Ip (if found): $clientIp</p>
</section>
---------------------------------------------<br>
---------------------------------------------
INFO;

Logger::LogRequest($requestInfo);


/**
 * Set headers for API response
 */
header('X-Content-Type-Options: nosniff'); // Ensures the content-type header should be respected and not changed. So that it doesn't think its html and execute it -> XSS
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
$router->add('artists/{artist_id}',[
    'controller' => 'Artists',
    'action' => 'delete',
    'method' => 'DELETE'
]);
$router->add('artists',[
    'controller' => 'Artists',
    'action' => 'create',
    'method' => 'POST'
]);

//// Albums ////
$router->add('albums',[
    'controller' => 'Albums',
    'action' => 'get'
]);
$router->add('albums/{album_id}',[
    'controller' => 'Albums',
    'action' => 'find'
]);
$router->add('albums/{album_id}/tracks',[
    'controller' => 'Albums',
    'action' => 'track'
]);
$router->add('albums/{album_id}',[
    'controller' => 'Albums',
    'action' => 'delete',
    'method' => 'DELETE'
]);
$router->add('albums',[
    'controller' => 'Albums',
    'action' => 'create',
    'method' => 'POST'
]);
$router->add('albums/{album_id}',[
    'controller' => 'Albums',
    'action' => 'update',
    'method' => 'POST'
]);

//// Tracks ////
$router->add('tracks',[
    'controller' => 'Tracks',
    'action' => 'get'
]);
$router->add('tracks/{track_id}',[
    'controller' => 'Tracks',
    'action' => 'find'
]);
$router->add('tracks',[
    'controller' => 'Tracks',
    'action' => 'create',
    'method' => 'POST'
]);
$router->add('tracks/{track_id}',[
    'controller' => 'Tracks',
    'action' => 'update',
    'method' => 'POST'
]);
$router->add('tracks/{track_id}',[
    'controller' => 'Tracks',
    'action' => 'delete',
    'method' => 'DELETE'
]);

//// Media Types ////
$router->add('media-types',[
    'controller' => 'MediaTypes',
    'action' => 'get'
]);

//// Genres ////
$router->add('genres',[
    'controller' => 'Genres',
    'action' => 'get'
]);

//// Playlists ////
$router->add('playlists',[
    'controller' => 'Playlists',
    'action' => 'get'
]);
$router->add('playlists/{playlist_id}',[
    'controller' => 'Playlists',
    'action' => 'find'
]);
$router->add('playlists',[
    'controller' => 'Playlists',
    'action' => 'create',
    'method' => 'POST'
]);
$router->add('playlists/{playlist_id}/tracks',[
    'controller' => 'Playlists',
    'action' => 'trackAdd',
    'method' => 'POST'
]);
$router->add('playlists/{playlist_id}/tracks/{track_id}',[
    'controller' => 'Playlists',
    'action' => 'trackRemove',
    'method' => 'DELETE'
]);
$router->add('playlists/{playlist_id}',[
    'controller' => 'Playlists',
    'action' => 'delete',
    'method' => 'DELETE'
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

$router->dispatch($url, $requestMethod);