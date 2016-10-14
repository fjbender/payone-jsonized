<?php
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
        $responseArray = array();
        $explode = explode("\n", $rawResponse);
        foreach ($explode as $e) {
            $keyValue = explode("=", $e);
            if (trim($keyValue[0]) != "") {
                if (count($keyValue) == 2) {
                    $responseArray[$keyValue[0]] = trim($keyValue[1]);
                } else {
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