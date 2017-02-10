<?php
/**
 * @author Florian Bender <me@fbender.de>
 * @copyright (c) 2017 Florian Bender
 * @link https://github.com/fjbender/payone-jsonized
 */

namespace Fbender\Jsonized\Exceptions;

use Exception;

class InvalidJsonException extends JsonizedException
{
    const HTTP_ERRORCODE = 400;

    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}