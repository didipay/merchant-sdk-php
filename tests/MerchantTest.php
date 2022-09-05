<?php

namespace Didipay\Tests;

require_once('src/util/SignUtil.php');

use DidiPay\Util\SignUtil;
use PHPUnit\Framework\TestCase;

class MerchantTest extends TestCase
{

    public function test_generate_sign()
    {
        $params = ['merchant_order_id' => '123',
            'pay_order_id' => '123456'];
        $privateKeyFile = "/home/runner/work/merchant-sdk-php/merchant-sdk-php/app_private_key.pem";
        $privateKeyContent = $this->readFile($privateKeyFile);
        echo $privateKeyContent;
        $sign = SignUtil::generateSign($params, $privateKeyContent);
        $publicKeyFile = "/home/runner/work/merchant-sdk-php/merchant-sdk-php/app_public_key.pem";
        $publicKeyContent = $this->readFile($publicKeyFile);
        $isVerify = SignUtil::verifySign($params,$publicKeyContent,$sign);
        $this->assertSame($isVerify,1);
    }

    private function readFile($filePath)
    {

        if (file_exists($filePath)) {
            $fp = fopen($filePath, "r");
            $content = "";
            while (!feof($fp)) {
                $content .= fgets($fp);//逐行读取。如果fgets不写length参数，默认是读取1k。
            }
            fclose($fp);
            return $content;
        }

        return "";
    }
}