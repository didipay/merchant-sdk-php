<?php

namespace DidiPay\Http;

use DidiPay\Consts\Const_Http_Url;
use Exception;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * http client
 *
 */
class httpClient
{

    public static function sendMessage($url, $method, $timeout, $body, $isDebug = false, $logFile = Const_Http_Url::DEFAULT_LOG_FILE)
    {

        if ($isDebug) {
            $start_time = microtime(true);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        switch ($method) {
            case "POST":
                curl_setopt($ch, CURLOPT_POST, 1);
                break;
            default:
                break;
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length:' . strlen($body)));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        $ret = curl_exec($ch);
        curl_close($ch);

        if ($isDebug) {
            try {
                $end_time = microtime(true);
                $total = $end_time - $start_time;
                echo "[CALL_HTTP_URL][url: " . $url . ")][time: " . ($total * 1000) . " ms]\r\n";
                $log = new Logger('control');
                $log->pushHandler(new StreamHandler($logFile, Logger::NOTICE));
                $log->addNotice("[CALL_HTTP_URL]\r\n", array("url" => $url, "message" => "http url", "time" => $total * 1000));
            } catch (Exception $e) {
                $log = new Logger('error');
                $log->addError("[LOG_HTTP_CLIENT] error", array("error" => $e));
            }
        }

        return $ret;
    }

}
