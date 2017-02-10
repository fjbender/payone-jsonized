<?php
/**
 * @author Florian Bender <me@fbender.de>
 * @copyright (c) 2016 Florian Bender
 * @link https://github.com/fjbender/payone-jsonized
 */
use Fbender\Jsonized\Exceptions\JsonizedException as JsonizedException;
use Fbender\Jsonized\Exceptions\PayoneErrorException as PayoneErrorException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require '../vendor/autoload.php';

// to get Payone API stuff
spl_autoload_register(function ($class) {
    $namespace = explode('\\', $class);
    if ($namespace[0] != "Payone" && $namespace[0] != "Fbender") {
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
$c['logger'] = function ($c) {
    $logger = new \Monolog\Logger('payoneJsonized');
    $file_handler = new \Monolog\Handler\StreamHandler("../logs/app.log");
    $logger->pushHandler($file_handler);
    return $logger;
};
/**
 * @param $c
 * @return \Payone\Api\Service
 */
$c['payone'] = function ($c) {
    $payoneService = new \Payone\Api\Service();
    return $payoneService;
};

/**
 * Error handlers
 */
$c['errorHandler'] = function ($c) {
    return function ($request, $response, Exception $exception) use ($c) {
        $c['logger']->addInfo("Exception: " . $exception->getMessage() . " at " . $exception->getFile() . ":" . $exception->getLine());
        $c['logger']->addInfo("Stacktrace: " . $exception->getTraceAsString());
        if ($exception instanceof JsonizedException) {
            $status = $exception::HTTP_ERRORCODE;
            if ($exception instanceof PayoneErrorException) {
                return $c['response']->withStatus($status)
                    ->withHeader('Content-Type', 'application/json')
                    // Here the exception message is a JSON object itself
                    ->write('{"status": "PAYONE ERROR", "errormessage": ' . $exception->getMessage() . '}');
            }
        } else {
            $status = 500;
        }
        return $c['response']->withStatus($status)
            ->withHeader('Content-Type', 'application/json')
            // We send "status": "WRAPPER ERROR" so we can tell this error apart from Payone error messages
            ->write('{"status": "WRAPPER ERROR", "errormessage": "' . $exception->getMessage() . '"}');
    };
};

$c['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['response']->withStatus(404)
            ->withHeader('Content-Type', 'application/json')
            ->write('{"status": "WRAPPER ERROR", "errormessage": "Resource not found"}');
    };
};

$app->post('/request/', function (Request $request, Response $response) use ($c) {
    $payoneResponse = $c['payone']->sendRequest($request);
    if ($payoneResponse['status'] == "ERROR") {
        throw new PayoneErrorException(json_encode($payoneResponse));
    }
    return $response->withJson($payoneResponse);
});

$app->run();
