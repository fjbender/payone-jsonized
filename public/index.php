<?php
/**
 * @author Florian Bender <me@fbender.de>
 * @copyright (c) 2016 Florian Bender
 * @link https://github.com/fjbender/payone-jsonized
 */
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

// to get Payone API stuff
spl_autoload_register(function ($class) {
    $namespace = explode('\\', $class);
    if ($namespace[0] != "Payone") {
        return false;
    }
    $path = '../classes/' . implode(DIRECTORY_SEPARATOR, $namespace) . '.php';
    if (file_exists($path)) {
        require_once($path);
        return true;
    } else {
        return false;
    }
});

$app = new Slim\App();
$c = $app->getContainer();

/**
 * @param $c
 * @return \Monolog\Logger
 */
$c['logger'] = function($c) {
    $logger = new \Monolog\Logger('payoneJsonized');
    $file_handler = new \Monolog\Handler\StreamHandler("../logs/app.log");
    $logger->pushHandler($file_handler);
    return $logger;
};

/**
 * Error handlers
 */
$c['errorHandler'] = function ($c) {
    return function ($request, $response, Exception $exception) use ($c) {
        $c['logger']->addInfo("Exception: " . $exception->getMessage() . " at " . $exception->getFile() . ":" . $exception->getLine());
        $c['logger']->addInfo("Stacktrace: " . $exception->getTraceAsString());
        return $c['response']->withStatus(500)
            ->withHeader('Content-Type', 'application/json')
            // We send "status": "WRAPPER ERROR" so we can tell this error apart from Payone error messages
            ->write('{"status": "WRAPPER ERROR", "errormessage": "' . $exception->getMessage() . '"}"');
    };
};

$c['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['response']->withStatus(404)
            ->withHeader('Content-Type', 'application/json')
            ->write('{"status": "WRAPPER ERROR", "errormessage": "Resource not found"}');
    };
};

$app->post('/request/', function (Request $request, Response $response) {
    return $response->withJson(\Payone\Api\Request::send($request));
});

$app->run();
