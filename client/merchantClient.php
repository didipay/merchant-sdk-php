<?php

namespace DidiPay;

include("../http/httpClient.php");
include("../const/Const_Http_Url.php");
include("../util/signUtil.php");

class merchantClient
{

    /**
     * @param $params the params for request
     * @param $key  the private key
     * @param $url the request url
     */
    public function send($params, $key, $url)
    {
        $this->checkParam($params);

        $params['sign'] = generateSign($params, $key);
        $data = json_encode($params);
        $timeout = Const_Http_Url::READ_TIME_OUT;

        $ret = httpClient::sendMessage($url, $timeout, $data);

        return $ret;
    }

    /**
     * @param $params the params for request
     * @param $key  the private key
     * @param $url the request url
     * @param $timeout the read timeout
     */
    public function sendRequest($params, $key, $url, $timeout)
    {
        $this->checkParam($params);

        $params['sign'] = generateSign($params, $key);
        $data = json_encode($params);

        $ret = httpClient::sendMessage($url, $timeout, $data);

        return $ret;
    }

    private function checkParam($params)
    {

        if (!isset($params['app_id'])) {
            throw new Exception("appId is empty");
        }

        if (!isset($params['merchant_id'])) {
            throw new Exception("merchantId is empty");
        }

        if (!isset($params['merchant_order_id'])) {
            throw new Exception("merchantOrderId is empty");
        }

    }

}

?>