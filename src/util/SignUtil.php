<?php

namespace DidiPay\Util;

class SignUtil
{

    const PRI_PRE = "-----BEGIN RSA PRIVATE KEY-----\n";
    const PRI_TAIL = "\n-----END RSA PRIVATE KEY-----";
    const _sort_string = 2;
    /**
     * 1. Initialize signature data
     * @param $aParams
     * @return null|string
     */
    static function prepareSignData($aParams)
    {
        $aExceptKey = array('sign');
        if (!is_array($aParams) || empty($aParams)) {
            return null;
        }
        ksort($aParams, self::_sort_string);
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
     * 2. Perform base64 encoding, to obtain the signature
     * @param $sData
     * @return string
     */
    static function getSign($sData, $sPem)
    {
        $sPem = self::PRI_PRE . $sPem . self::PRI_TAIL;
        $sPriKey = openssl_pkey_get_private($sPem);
        $sSign = '';
        openssl_sign($sData, $sSign, $sPriKey, 'sha256WithRSAEncryption');
        openssl_free_key($sPriKey);
        $base64 = base64_encode($sSign);
        return $base64;
    }

    /**
     * 3. Generate the final required signature
     * @param $aParams
     * @return string
     */
    public static function generateSign($aParams, $sPriName = "cashier_private_key.pem")
    {
        $sSignData = self::prepareSignData($aParams, $sPriName);
        return self::getSign($sSignData, $sPriName);
    }
}
