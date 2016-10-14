<?php
namespace Payone\Api;

use GuzzleHttp\Client;
use Psr\Http\Message\RequestInterface as HttpRequest;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Class Authorization
 * @package Payone\Api
 */
class Request
{
    /**
     * @param HttpRequest $request
     * @param Response $response
     * @return array
     */
    public static function send(HttpRequest $request, Response $response)
    {
        $body = json_decode($request->getBody(), true);

        $client = new Client();
        $payoneResponse = $client->post('https://api.pay1.de/post-gateway/', $body);
        $parsedResponse = \Payone\Api\Response::toArray($payoneResponse->getBody());

        return $parsedResponse;
    }
}
