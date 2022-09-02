<?php

namespace Didipay\Tests;

require_once '../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class MerchantTest extends TestCase
{

    public function test_pay_query()
    {
        $appId = "123456";
        $this->assertSame('123456', $appId);
    }

}