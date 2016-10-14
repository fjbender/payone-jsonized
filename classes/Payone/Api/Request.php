<?php
namespace Payone\Api;

use GuzzleHttp\Client;
//use Psr\Http\Message\RequestInterface as HttpRequest;
//use Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\Response as Response;
use Slim\Http\Request as HttpRequest;


/**
 * Class Authorization
 * @package Payone\Api
 */
class Request
{
    /**
     * Request constructor.
     * @param HttpRequest $request
     * @param Response $response
     */
    function __construct(HttpRequest $request, Response $response)
    {
        $body = json_decode($request->getBody(), true);

        $client = new Client();
        $payoneResponse = $client->post('https://api.pay1.de/post-gateway/', $body);
        $jsonResponse = json_encode(\Payone\Api\Response::toArray($payoneResponse->getBody()));

        return $response->withJson($jsonResponse);
    }
}
