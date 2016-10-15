<?php
namespace Payone\Api;

use GuzzleHttp\Client;
use Psr\Http\Message\RequestInterface as HttpRequest;

/**
 * Class Authorization
 * @package Payone\Api
 */
class Request
{
    /**
     * @param HttpRequest $request
     * @return array
     */
    public static function send(HttpRequest $request)
    {
        $body = json_decode($request->getBody(), true);

        $client = new Client();
        $payoneResponse = $client->post('https://api.pay1.de/post-gateway/', ['form_params' => $body]);
        $parsedResponse = \Payone\Api\Response::toArray($payoneResponse->getBody());

        return $parsedResponse;
    }
}
