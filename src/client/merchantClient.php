<?php

namespace DidiPay\client;

use DidiPay\Consts\Const_Http_Url;
use DidiPay\Http\httpClient;
use DidiPay\Util\SignUtil;
use Exception;

class merchantClient
{

    /** @var string */
    private $appId;
    /** @var string */
    private $merchantId;
    /** @var string */
    private $privateKey;
    /** @var string */
    private $domain;
    /** @var int */
    private $readTimeout;

    /**
     * @param array $defaultOption
     *                app_id the appId string
     *                merchant_id the merchantId string
     *                private_key the private key string
     *                domain the domain name string default https://api.99pay.com.br
     *                read_timeout the read timeout seconds default 10
     * @throws Exception
     */
    public function __construct($defaultOption)
    {
        $this->checkParam($defaultOption);
        $this->appId = $defaultOption['app_id'];
        $this->merchantId = $defaultOption['merchant_id'];
        $this->privateKey = $defaultOption['private_key'];
        $this->domain = isset($defaultOption['domain']) ? $defaultOption['domain'] : Const_Http_Url::DEFAULT_DOMAIN;
        $this->readTimeout = isset($defaultOption['read_timeout']) ? $defaultOption['read_timeout'] : Const_Http_Url::READ_TIME_OUT;
    }

    /**
     * Merchant Places an Order
     * @param $params array the params for request
     * @see https://didipay.didiglobal.com/developer/docs/en/
     */
    public function prePay($params)
    {
        $url = $this->domain . Const_Http_Url::PREPAY_URL;

        return $this->sendRequest($params, $url, $this->readTimeout);
    }

    /**
     * Payment Query
     * @param $params array the params for request
     * @see https://didipay.didiglobal.com/developer/docs/en/
     */
    public function payQuery($params)
    {
        $url = $this->domain . Const_Http_Url::PAY_QUERY_URL;

        return $this->sendRequest($params, $url, $this->readTimeout);
    }

    /**
     * Request Refund
     * @param $params array the params for request
     * @see https://didipay.didiglobal.com/developer/docs/en/
     */
    public function refund($params)
    {
        $url = $this->domain . Const_Http_Url::REFUND_URL;

        return $this->sendRequest($params, $url, $this->readTimeout);
    }

    /**
     * Refund Query
     * @param $params array the params for request
     * @see https://didipay.didiglobal.com/developer/docs/en/
     */
    public function refundQuery($params)
    {
        $url = $this->domain . Const_Http_Url::REFUND_QUERY_URL;

        return $this->sendRequest($params, $url, $this->readTimeout);
    }

    /**
     * Close Trade
     * @param $params array the params for request
     * @see https://didipay.didiglobal.com/developer/docs/en/
     */
    public function closeTrade($params)
    {
        $url = $this->domain . Const_Http_Url::CLOSE_TRADE_URL;

        return $this->sendRequest($params, $url, $this->readTimeout);
    }

    /**
     * @param $params array the params for request
     * @param $url string the request url
     * @param $timeout int the read timeout
     */
    private function sendRequest($params, $url, $timeout)
    {
        $params['app_id'] = $this->appId;
        $params['merchant_id'] = $this->merchantId;
        $params['sign'] = SignUtil::generateSign($params, $this->privateKey);
        $data = json_encode($params);

        return httpClient::sendMessage($url, $timeout, $data);;
    }

    private function checkParam($defaultOption)
    {
        if (!isset($defaultOption['app_id'])) {
            throw new Exception("appId is empty");
        }
        if (!isset($defaultOption['merchant_id'])) {
            throw new Exception("merchantId is empty");
        }
        if (!isset($defaultOption['private_key'])) {
            throw new Exception("privateKey is empty");
        }
    }

}
