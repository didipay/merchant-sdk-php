<?php

namespace DidiPay\client;

use DidiPay\Consts\Const_Http_Url;
use DidiPay\Http\httpClient;
use DidiPay\Util\SignUtil;
use DidiPay\Exceptions\DidipayException;

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
    /** @var bool */
    private $isDebug;

    /**
     * @param array $defaultOption
     *                app_id the appId string
     *                merchant_id the merchantId string
     *                private_key the private key string
     *                domain the domain name string default https://api.99pay.com.br
     *                read_timeout the read timeout seconds default 10
     * @throws DidipayException
     */
    public function __construct($defaultOption)
    {
        $this->checkBasicParam($defaultOption);
        $this->appId = $defaultOption['app_id'];
        $this->merchantId = $defaultOption['merchant_id'];
        $this->privateKey = $defaultOption['private_key'];
        $this->domain = !empty($defaultOption['domain']) && is_string($defaultOption['domain']) ? $defaultOption['domain'] : Const_Http_Url::BR_ONLINE_DOMAIN;
        $this->readTimeout = !empty($defaultOption['read_timeout']) && is_int($defaultOption['read_timeout']) ? $defaultOption['read_timeout'] : Const_Http_Url::READ_TIME_OUT;
        $this->isDebug = !empty($defaultOption['is_debug']) && is_bool($defaultOption['is_debug']) && $defaultOption['is_debug'];
    }

    /**
     * Merchant Places an Order
     * @param $params array the params for request
     * @throws DidipayException
     * @see https://didipay.didiglobal.com/developer/docs/en/
     */
    public function prePay($params)
    {
        $this->checkParam($params);
        $url = $this->domain . Const_Http_Url::PREPAY_URL;

        return $this->sendRequest($params, $url, $this->readTimeout);
    }

    /**
     * Payment Query
     * @param $params array the params for request
     * @throws DidipayException
     * @see https://didipay.didiglobal.com/developer/docs/en/
     */
    public function payQuery($params)
    {
        $this->checkPayQuery($params);
        $url = $this->domain . Const_Http_Url::PAY_QUERY_URL;

        return $this->sendRequest($params, $url, $this->readTimeout);
    }

    /**
     * Request Refund
     * @param $params array the params for request
     * @throws DidipayException
     * @see https://didipay.didiglobal.com/developer/docs/en/
     */
    public function refund($params)
    {
        $this->checkRefund($params);
        $url = $this->domain . Const_Http_Url::REFUND_URL;

        return $this->sendRequest($params, $url, $this->readTimeout);
    }

    /**
     * Refund Query
     * @param $params array the params for request
     * @throws DidipayException
     * @see https://didipay.didiglobal.com/developer/docs/en/
     */
    public function refundQuery($params)
    {
        $this->checkPayQuery($params);
        $url = $this->domain . Const_Http_Url::REFUND_QUERY_URL;

        return $this->sendRequest($params, $url, $this->readTimeout);
    }

    /**
     * Close Trade
     * @param $params array the params for request
     * @throws DidipayException
     * @see https://didipay.didiglobal.com/developer/docs/en/
     */
    public function closeTrade($params)
    {
        $this->checkMerchantOrderId($params);
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

        return httpClient::sendMessage($url, "POST", $timeout, $data, $this->isDebug);
    }

    /**
     * @throws DidipayException
     */
    private function checkParam($params)
    {
        $this->checkMerchantOrderId($params);

        if (!is_string($params['currency']) || empty(trim($params['currency']))) {
            throw new DidipayException("currency is empty or format error");
        }

        if (!is_string($params['total_amount']) || empty(trim($params['total_amount']))) {
            throw new DidipayException("totalAmount is empty or format error");
        }

        if(strpos($params['total_amount'],'.') !== false){
            throw new DidipayException("totalAmount contains .");
        }

    }

    /**
     * @throws DidipayException
     */
    private function checkPayQuery($params)
    {

        $this->checkMerchantOrderId($params);

        if (!is_string($params['pay_order_id']) || empty(trim($params['pay_order_id']))) {
            throw new DidipayException("payOrderId is empty or format error");
        }
    }

    /**
     * @throws DidipayException
     */
    private function checkRefund($params)
    {

        $this->checkMerchantOrderId($params);

        if (!is_string($params['pay_order_id']) || empty(trim($params['pay_order_id']))) {
            throw new DidipayException("payOrderId is empty or format error");
        }

        if (!is_string($params['merchant_refund_id']) || empty(trim($params['merchant_refund_id']))) {
            throw new DidipayException("merchantRefundId is empty or format error");
        }

        if (!is_string($params['amount']) || empty(trim($params['amount']))) {
            throw new DidipayException("amount is empty or format error");
        }

        if(strpos($params['amount'],'.') !== false){
            throw new DidipayException("amount contains .");
        }

    }

    /**
     * @throws DidipayException
     */
    private function checkMerchantOrderId($params)
    {
        if (!is_string($params['merchant_order_id']) || empty(trim($params['merchant_order_id']))) {
            throw new DidipayException("merchantOrderId is empty or format error");
        }
    }

    /**
     * @throws DidipayException
     */
    private function checkBasicParam($defaultOption)
    {
        if (!is_string($defaultOption['app_id']) || empty(trim($defaultOption['app_id']))) {
            throw new DidipayException("appId is empty or format error");
        }
        if (!is_string($defaultOption['merchant_id']) || empty(trim($defaultOption['merchant_id']))) {
            throw new DidipayException("merchantId is empty or format error");
        }
        if (!is_string($defaultOption['private_key']) || empty(trim($defaultOption['private_key']))) {
            throw new DidipayException("privateKey is empty or format error");
        }
    }

}
