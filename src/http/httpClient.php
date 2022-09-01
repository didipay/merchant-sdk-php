<?php

namespace DidiPay\Http;

/**
 * http client
 *
 */
class httpClient
{
    public static function sendMessage($url, $timeout, $body)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length:' . strlen($body)));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        $ret = curl_exec($ch);
        curl_close($ch);

        return $ret;
    }

}
