<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

// to get Payone API stuff
function load_payone_api_class($class)
{
    $namespace = explode('\\', $class);
    if ($namespace[0] != "Payone") {
        return false;
    }

    $path = '../classes/' . implode(DIRECTORY_SEPARATOR, $namespace) . '.php';
    if (file_exists($path)) {
        require_once($path);
    } else {
        return false;
    }
}

spl_autoload_register('load_payone_api_class');

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new Slim\App($config);

/**
 * Error handlers
 */

$c = $app->getContainer();
$c['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        return $c['response']->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->write('Internal Server Error');
    };
};

$c['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['response']->withStatus(404)
            ->withHeader('Content-Type', 'text/html')
            ->write('Not found');
    };
};

$app->post('/request/', function (Request $request, Response $response) {
    return $response->withJson(\Payone\Api\Request::send($request, $response));
});

$app->run();
