<?php
/**
 * @author Florian Bender <me@fbender.de>
 * @author Timo Kuchel <timo.kuchel@payone.de>
 * @copyright (c) 2016 Florian Bender
 * @link https://github.com/fjbender/payone-jsonized
 */
namespace Payone\Api;

/**
 * Class Response
 * @package Payone\Api
 */
class Response
{
    /**
     * @param string $rawResponse
     * @return array
     */
    public static function toArray($rawResponse)
    {
        // Breaks up the Payone format and puts it into an array
        $responseArray = array();
        $lines = explode("\n", $rawResponse);
        foreach ($lines as $line) {
            $keyValue = explode("=", $line);
            if (trim($keyValue[0]) != "") {
                if (count($keyValue) == 2) {
                    $responseArray[$keyValue[0]] = trim($keyValue[1]);
                } else {
                    // Everything between the first = and the line break is Value, regardless how many = follow
                    $key = $keyValue[0];
                    unset($keyValue[0]);
                    $value = implode("=", $keyValue);
                    $responseArray[$key] = $value;
                }
            }
        }
        return $responseArray;
    }
}
