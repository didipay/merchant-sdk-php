<?php

namespace DidiPay\Consts;

/**
 * http url constants
 *
 */
class Const_Http_Url
{

    const PAY_QUERY_URL = '/gateway/api/outer/v1/transaction/query';
    const PREPAY_URL = '/gateway/api/outer/v1/transaction/prePay';
    const CLOSE_TRADE_URL = '/gateway/api/outer/v1/transaction/close';
    const REFUND_URL = '/gateway/api/outer/v1/transaction/refund';
    const REFUND_QUERY_URL = '/gateway/api/outer/v1/transaction/refund/query';
    const BR_ONLINE_DOMAIN = 'https://api.99pay.com.br';
    const MAXICO_ONLINE_DOMAIN = "https://api.didipay.didiglobal.com";
    const DEFAULT_LOG_FILE = "didipay.log";
    const READ_TIME_OUT = 10;

}
