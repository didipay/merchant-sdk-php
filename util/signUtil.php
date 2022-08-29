<?php

namespace DidiPay;

const PRI_PRE = "-----BEGIN RSA PRIVATE KEY-----\n";
const PRI_TAIL = "\n-----END RSA PRIVATE KEY-----";
const _sort_string = 2;

/**
 * 1. 初始化签名数据
 * @param $aParams
 * @return null|string
 */
function _prepareSignData($aParams)
{
    $aExceptKey = array('sign');
    if (!is_array($aParams) || empty($aParams)) {
        return null;
    }
    ksort($aParams, _sort_string);
    $aSortArray = array();
    foreach ($aParams as $key => $value) {
        if (in_array($key, $aExceptKey)) {
            continue;
        }
        $aSortArray[] = "{$key}={$value}";
    }
    $sSortResult = implode('&', $aSortArray);
    return $sSortResult;
}

/**
 * 2. 进行base64编码等，获取签名
 * @param $sData
 * @return string
 */
function _getSign($sData, $sPem)
{
    $sPem = PRI_PRE . $sPem . PRI_TAIL;
    $sPriKey = openssl_pkey_get_private($sPem);
    $sSign = '';
    openssl_sign($sData, $sSign, $sPriKey, 'sha256WithRSAEncryption');
    openssl_free_key($sPriKey);
    $base64 = base64_encode($sSign);
    return $base64;
}

/**
 * 3. 生成最终需要的签名
 * @param $aParams
 * @return string
 */
function generateSign($aParams, $sPriName = "cashier_private_key.pem")
{
    $sSignData = _prepareSignData($aParams, $sPriName);
    return _getSign($sSignData, $sPriName);
}

?>