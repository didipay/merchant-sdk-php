<?php

namespace Didipay\Tests;

require_once('src/util/SignUtil.php');
require_once('src/consts/Const_Http_Url.php');
require_once('src/client/merchantClient.php');
require_once('src/exceptions/DidipayException.php');
require_once('src/http/httpClient.php');

use DidiPay\client\merchantClient;
use DidiPay\Exceptions\DidipayException;
use DidiPay\Util\SignUtil;
use PHPUnit\Framework\TestCase;

class MerchantTest extends TestCase
{

    const PRI_KEY_PATH = "/home/runner/work/merchant-sdk-php/merchant-sdk-php/app_private_key.pem";
    const PUB_KEY_PATH = "/home/runner/work/merchant-sdk-php/merchant-sdk-php/app_public_key.pem";

    public function test_generate_sign()
    {
        $params = ['merchant_order_id' => '123',
            'pay_order_id' => '123456'];
        $privateKeyFile = self::PRI_KEY_PATH;
        $privateKeyContent = $this->readFile($privateKeyFile);
        echo $privateKeyContent;
        $sign = SignUtil::generateSign($params, $privateKeyContent);
        $publicKeyFile = self::PUB_KEY_PATH;
        $publicKeyContent = $this->readFile($publicKeyFile);
        $isVerify = SignUtil::verifySign($params,$publicKeyContent,$sign);
        $this->assertSame($isVerify,1);
    }

    /**
     * @throws DidipayException
     */
    public function test_pay_query(){

        $params = ['merchant_order_id' => 'merchantOrderId',
            'pay_order_id' => 'payOrderId'];

        $privateKeyFile = self::PRI_KEY_PATH;
        $privateKeyContent = $this->readFile($privateKeyFile);
        $domain = "https://merchant-server-merchant-sdk-bmtfqitgrw.cn-hangzhou.fcapp.run";
        $defaultOption = ['app_id' => 'appId', 'merchant_id' => 'merchantId', 'private_key' => $privateKeyContent, 'domain' => $domain];

        $client = new merchantClient($defaultOption);
        $ret = $client->payQuery($params);
        $response = json_decode($ret,null,512,1);
        echo $ret;
        $this->assertSame('200', $response['returnNo']);
        $this->assertSame('success', $response['returnMsg']);

    }

    /**
     * @throws DidipayException
     */
    public function test_pre_pay()
    {
        $params = ['merchant_order_id' => 'merchantOrderId',
            'pay_order_id' => 'payOrderId','currency'=>'BRL','total_amount'=>'1200'];

        $privateKeyFile = self::PRI_KEY_PATH;
        $privateKeyContent = $this->readFile($privateKeyFile);
        $domain = "https://merchant-server-merchant-sdk-bmtfqitgrw.cn-hangzhou.fcapp.run";
        $defaultOption = ['app_id' => 'appId', 'merchant_id' => 'merchantId', 'private_key' => $privateKeyContent, 'domain' => $domain];

        $client = new merchantClient($defaultOption);
        $ret = $client->prePay($params);
        $response = json_decode($ret,null,512,1);
        $this->assertSame('200', $response['returnNo']);
        $this->assertSame('success', $response['returnMsg']);
    }

    /**
     * @throws DidipayException
     */
    public function test_refund()
    {
        // 设置入参$params和秘钥$pkey
        $params = ['merchant_order_id'=>'merchantOrderId',
            'pay_order_id'=>'payOrderId','merchant_refund_id'=>'merchantRefundId','amount'=>'1200'];


        $privateKeyFile = self::PRI_KEY_PATH;
        $privateKeyContent = $this->readFile($privateKeyFile);
        $domain = "https://merchant-server-merchant-sdk-bmtfqitgrw.cn-hangzhou.fcapp.run";
        $defaultOption = ['app_id' => 'appId', 'merchant_id' => 'merchantId', 'private_key' => $privateKeyContent, 'domain' => $domain];

        $client = new merchantClient($defaultOption);
        $ret = $client->refund($params);
        $response = json_decode($ret,null,512,1);
        $this->assertSame('200', $response['returnNo']);
        $this->assertSame('success', $response['returnMsg']);
    }

    /**
     * @throws DidipayException
     */
    public function test_refund_query()
    {
        $params = ['merchant_order_id' => 'merchantOrderId',
            'pay_order_id' => 'payOrderId'];

        $privateKeyFile = self::PRI_KEY_PATH;
        $privateKeyContent = $this->readFile($privateKeyFile);
        $domain = "https://merchant-server-merchant-sdk-bmtfqitgrw.cn-hangzhou.fcapp.run";
        $defaultOption = ['app_id' => 'appId', 'merchant_id' => 'merchantId', 'private_key' => $privateKeyContent, 'domain' => $domain];

        $client = new merchantClient($defaultOption);
        $ret = $client->refundQuery($params);
        $response = json_decode($ret,null,512,1);
        $this->assertSame('200', $response['returnNo']);
        $this->assertSame('success', $response['returnMsg']);
    }

    /**
     * @throws DidipayException
     */
    public function test_close_trade()
    {
        $params = ['merchant_order_id'=>'merchantOrderId'];

        $privateKeyFile = self::PRI_KEY_PATH;
        $privateKeyContent = $this->readFile($privateKeyFile);
        $domain = "https://merchant-server-merchant-sdk-bmtfqitgrw.cn-hangzhou.fcapp.run";
        $defaultOption = ['app_id' => 'appId', 'merchant_id' => 'merchantId', 'private_key' => $privateKeyContent, 'domain' => $domain];

        $client = new merchantClient($defaultOption);
        $ret = $client->closeTrade($params);
        $response = json_decode($ret,null,512,1);
        $this->assertSame('200', $response['returnNo']);
        $this->assertSame('success', $response['returnMsg']);
    }

    private function readFile($filePath)
    {

        if (file_exists($filePath)) {
            $fp = fopen($filePath, "r");
            $content = "";
            while (!feof($fp)) {
                $content .= fgets($fp);
            }
            fclose($fp);
            return $content;
        }

        return "";
    }
}