<?php

namespace DidiPay;

/**
 * http url的常量
 *
 */
class Const_Http_Url
{

    // 支付查询接口
    const PAY_QUERY_URL = '/gateway/api/outer/v1/transaction/query';
    // 发单接口
    const PREPAY_URL = '/gateway/api/outer/v1/transaction/prePay';
    // 关单接口
    const CLOSE_TRADE_URL = '/gateway/api/outer/v1/transaction/close';
    // 退款接口
    const REFUND_URL = '/gateway/api/outer/v1/transaction/refund';
    // 退款查询接口
    const REFUND_QUERY_URL = '/gateway/api/outer/v1/transaction/refund/query';
    // 超时时间，默认是10s
    const READ_TIME_OUT = 10;

}

?>