<?php
/**
 * @author Florian Bender <me@fbender.de>
 * @copyright (c) 2016 Florian Bender
 * @link https://github.com/fjbender/payone-jsonized
 */
namespace Payone\Api;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;

/**
 * Class Service
 * @package Payone\Api
 */
class Service {
    public function __construct() {

    }

    /**
     * @param ServerRequest $request
     * @return array
     */
    public function sendRequest(ServerRequest $request) {
        return Request::send($request);
    }
}
