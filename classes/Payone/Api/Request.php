<?php
/**
 * @author Florian Bender <me@fbender.de>
 * @copyright (c) 2016 Florian Bender
 * @link https://github.com/fjbender/payone-jsonized
 */
namespace Payone\Api;

use GuzzleHttp\Client;
use Psr\Http\Message\RequestInterface as HttpRequest;
use Fbender\Jsonized\Exceptions\InvalidJsonException as InvalidJsonException;

/**
 * Class Request
 * @package Payone\Api
 */
class Request
{
    /**
     * @param HttpRequest $request
     * @return array
     * @throws \Exception
     */
    public static function send(HttpRequest $request)
    {
        $body = json_decode($request->getBody()->getContents(), true);
        if ($body === null) {
            throw new InvalidJsonException("JSON Error: " . json_last_error_msg());
        }
        // Tell the people it's us
        $body['sdk_name'] = 'fjbender/payone-jsonized';
        $body['sdk_version'] = '0.1';
        // For good measure
        ksort($body);
        $client = new Client();
        $payoneResponse = $client->post('https://api.pay1.de/post-gateway/', ['form_params' => $body]);
        $parsedResponse = Response::toArray($payoneResponse->getBody());

        return $parsedResponse;
    }
}
